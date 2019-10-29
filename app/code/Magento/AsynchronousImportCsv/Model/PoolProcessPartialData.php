<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\AsynchronousImportCsv\Model;

use Magento\AsynchronousImportSourceDataRetrievingApi\Api\Data\SourceInterface;
use Magento\AsynchronousImportCsvApi\Model\PartialResponseInterface;
use Magento\AsynchronousImportCsvApi\Api\PartialResponseException;
use Magento\AsynchronousImportCsvApi\Api\PoolProcessPartialDataInterface;
use Magento\AsynchronousImportSourceDataRetrievingApi\Model\PartialSourceInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

class PoolProcessPartialData implements PoolProcessPartialDataInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var PartialSourceInterface
     */
    protected $partialMetaData;

    /**
     * @var PartialResponseInterface
     */
    protected $partialResponse;

    /**
     * PoolProcessPartialData constructor.
     * @param Filesystem $filesystem
     * @param PartialResponseInterface $partialResponse
     */
    public function __construct(
        Filesystem $filesystem,
        PartialResponseInterface $partialResponse
    ) {
        $this->filesystem = $filesystem;
        $this->partialResponse = $partialResponse;
    }

    /**
     * Generate file name with source type
     *
     * @param $dirName
     * @param $fileName
     * @return string
     */
    private function generateFilePath($dirName, $fileName): string
    {
        return PoolProcessPartialDataInterface::IMPORT_SOURCE_FILE_PATH . $dirName . DIRECTORY_SEPARATOR . $fileName;
    }

    /**
     * Check current piece is last
     *
     * @return bool
     */
    public function isFinalPiece(): bool
    {
        /** @var Magento\Framework\Filesystem\Directory\Read $var */
        $var = $this->filesystem->getDirectoryRead(DirectoryList::VAR_DIR);

        /** @var int[] $missingPieces */
        $missingPieces = [];
        for ($piecesNumber = 1; $piecesNumber <= $this->partialMetaData->getPiecesCount(); $piecesNumber++) {
            /** @var string $contentFilePath */
            $contentFilePath =  $this->generateFilePath($this->partialMetaData->getPiecesHash(), $piecesNumber);
            /** check piece file exist */
            if (!$var->isExist($contentFilePath)) {
                $missingPieces[] = $piecesNumber;
            }
        }

        if (count($missingPieces) == 0 ||
            (
                count($missingPieces) == 1 &&
             current($missingPieces) == $this->partialMetaData->getPiecesNumber()
            )
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Merge contents's pieces
     *
     * @param SourceInterface $source
     * @return SourceInterface
     * @throws PartialResponseException
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function merge(SourceInterface $source): SourceInterface
    {
        /** @var Magento\Framework\Filesystem\Directory\Read $var */
        $var = $this->filesystem->getDirectoryRead(DirectoryList::VAR_DIR);
        $contents = [];
        for ($piecesNumber = 1; $piecesNumber <= $this->partialMetaData->getPiecesCount(); $piecesNumber++) {
            try {
                /** @var string $contentFilePath */
                $contentFilePath =  $this->generateFilePath($this->partialMetaData->getPiecesHash(), $piecesNumber);
                /** read content and remove end carriage return and new line words */
                $contents[] = rtrim($var->readFile($contentFilePath), "\r\n");
            } catch (\Exception $e) {
                /** missing piece from partial import error */
                throw new PartialResponseException(
                    __('The content from partial piece: %1 can\'t be read', $piecesNumber)
                );
            }
        }
        /** @var Magento\Framework\Filesystem\Directory\Write $var */
        $var = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        /** delete partial piece directory to complete the process */
        $var->delete(PoolProcessPartialDataInterface::IMPORT_SOURCE_FILE_PATH . $this->partialMetaData->getPiecesHash());
        $content = implode("\r\n", $contents);

        $source->setSourceDefinition($content);

        return $source;
    }

    /**
     * Save partial source in directory
     *
     * @param SourceInterface $source
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function save(SourceInterface $source): SourceInterface
    {
        /** @var string $contentFilePath */
        $contentFilePath =  $this->generateFilePath(
                                $this->partialMetaData->getPiecesHash(),
                                $this->partialMetaData->getPiecesNumber()
                            );

        /** @var Magento\Framework\Filesystem\Directory\Write $var */
        $var = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        if (!$var->writeFile($contentFilePath, $source->getSourceDefinition())) {
            /** @var array $lastError */
            $lastError = error_get_last();
            /** @var string $errorMessage */
            $errorMessage = isset($lastError['message']) ? $lastError['message'] : '';
            throw new PartialResponseException(
                __('Cannot create file with given source: %1', $errorMessage)
            );
        }

        $this->partialResponse->setStatus(PartialResponseInterface::STATUS_UPLOADED);

        return $source;
    }

    /**
     * @param SourceInterface $source
     * @param PartialSourceInterface $partialMetaData
     * @return SourceInterface
     * @throws PartialResponseException
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function execute(SourceInterface $source, PartialSourceInterface $partialMetaData): SourceInterface
    {
        $this->partialMetaData = $partialMetaData;
        $this->save($source);

        if ($this->isFinalPiece()) {
            $this->partialResponse->setPieceIsLast(true);

            return $this->merge($source);
        } else {
            $this->partialResponse->setPieceIsLast(false);

            return $source;
        }
    }
}

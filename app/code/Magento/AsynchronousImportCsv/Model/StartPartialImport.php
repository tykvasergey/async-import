<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\AsynchronousImportCsv\Model;

use Magento\AsynchronousImportCsvApi\Api\StartPartialImportInterface;
use Magento\AsynchronousImportDataConvertingApi\Api\ApplyConvertingRulesInterface;
use Magento\AsynchronousImportCsvApi\Api\Data\CsvFormatInterface;
use Magento\AsynchronousImportCsvApi\Model\DataParserInterface;
use Magento\AsynchronousImportDataExchangingApi\Api\Data\ImportInterface;
use Magento\AsynchronousImportDataExchangingApi\Api\ExchangeImportDataInterface;
use Magento\AsynchronousImportSourceDataRetrievingApi\Api\Data\SourceInterface;
use Magento\AsynchronousImportSourceDataRetrievingApi\Model\PartialSourceInterface;
use Magento\AsynchronousImportSourceDataRetrievingApi\Api\RetrieveSourceDataInterface;
use Magento\AsynchronousImportSourceDataRetrievingApi\Model\PartialSourceDataValidatorInterface;
use Magento\AsynchronousImportSourceDataRetrievingApi\Api\Data\RetrievingResultInterfaceFactory;
use Magento\AsynchronousImportSourceDataRetrievingApi\Api\Data\RetrievingResultInterface;
use Magento\AsynchronousImportCsvApi\Model\PartialResponseInterface;
use Magento\AsynchronousImportCsvApi\Api\PoolProcessPartialDataInterface;
use Magento\AsynchronousImportSourceDataRetrievingApi\Api\SourceDataRetrievingException;
use Magento\AsynchronousImportCsvApi\Api\StartImportInterface;


/**
 * @inheritdoc
 */
class StartPartialImport implements StartPartialImportInterface
{
    /**
     * @var RetrieveSourceDataInterface
     */
    private $retrieveSourceData;

    /**
     * @var DataParserInterface
     */
    private $dataParser;

    /**
     * @var ApplyConvertingRulesInterface
     */
    private $applyConvertingRules;

    /**
     * @var ExchangeImportDataInterface
     */
    private $exchangeImportData;

    /**
     * @var PartialSourceDataValidatorInterface
     */
    private $partialSourceDataValidator;

    /**
     * @var RetrievingResultInterfaceFactory
     */
    private $retrievingResultFactory;

    /**
     * @var PartialResponseInterface
     */
    private $partialResponse;

    /**
     * @var PoolProcessPartialDataInterface
     */
    private $poolProcessPartialData;

    /**
     * @var StartImportInterface
     */
    private $startImport;

    /**
     * StartPartialImport constructor.
     *
     * @param RetrieveSourceDataInterface $retrieveSourceData
     * @param DataParserInterface $dataParser
     * @param ApplyConvertingRulesInterface $applyConvertingRules
     * @param ExchangeImportDataInterface $exchangeImportData
     * @param PartialSourceDataValidatorInterface $partialSourceDataValidator
     * @param RetrievingResultInterfaceFactory $retrievingResultFactory
     * @param PartialResponseInterface $partialResponse
     * @param PoolProcessPartialDataInterface $poolProcessPartialData
     * @param StartImportInterface $startImport
     */
    public function __construct(
        RetrieveSourceDataInterface $retrieveSourceData,
        DataParserInterface $dataParser,
        ApplyConvertingRulesInterface $applyConvertingRules,
        ExchangeImportDataInterface $exchangeImportData,
        PartialSourceDataValidatorInterface $partialSourceDataValidator,
        RetrievingResultInterfaceFactory $retrievingResultFactory,
        PartialResponseInterface $partialResponse,
        PoolProcessPartialDataInterface $poolProcessPartialData,
        StartImportInterface $startImport
    ) {
        $this->retrieveSourceData = $retrieveSourceData;
        $this->dataParser = $dataParser;
        $this->applyConvertingRules = $applyConvertingRules;
        $this->exchangeImportData = $exchangeImportData;
        $this->partialSourceDataValidator = $partialSourceDataValidator;
        $this->retrievingResultFactory = $retrievingResultFactory;
        $this->partialResponse = $partialResponse;
        $this->poolProcessPartialData = $poolProcessPartialData;
        $this->startImport = $startImport;
    }

    /**
     * @inheritdoc
     */
    public function execute(
        SourceInterface $source,
        ImportInterface $import,
        CsvFormatInterface $format = null,
        PartialSourceInterface $partialMetaData,
        array $convertingRules = []
    ): string {

        $validationPartialResult = $this->partialSourceDataValidator->validate($partialMetaData);
        if (!$validationPartialResult->isValid()) {
            $partialErrors = $validationPartialResult->getErrors();
            $dataPartialValidation = [
                RetrievingResultInterface::STATUS => RetrievingResultInterface::STATUS_FAILED,
                RetrievingResultInterface::FILE => null,
                RetrievingResultInterface::ERRORS => $partialErrors
            ];

            $retrievingPartialResult = $this->retrievingResultFactory->create($dataPartialValidation);
            if ($retrievingPartialResult->getStatus() === RetrievingResultInterface::STATUS_FAILED) {
                throw new SourceDataRetrievingException(__('Source retrieving was failed'));
            }
        }

        $source = $this->poolProcessPartialData->execute($source, $partialMetaData);

        if ($source &&
            $this->partialResponse->getPieceIsLast() === true) {

            $importResult = $this->startImport->execute($source, $import, $format = null, $convertingRules);
            if($importResult) {
                return
                    PartialSourceInterface::PIECES_NUMBER . " : " .
                    $partialMetaData->getPiecesNumber() . " : " . PartialResponseInterface::IS_PIECE_LAST . " , " .
                    PartialResponseInterface::STATUS . " : " . $this->partialResponse->getStatus() . " , " .
                    PartialResponseInterface::STATUS . " : " . PartialResponseInterface::COMPLETED_ALL_PARTS;
            }
        } else {
            return
                PartialSourceInterface::PIECES_NUMBER . " : " . $partialMetaData->getPiecesNumber() . " , " .
                PartialResponseInterface::STATUS . " : " . $this->partialResponse->getStatus();
        }
    }
}

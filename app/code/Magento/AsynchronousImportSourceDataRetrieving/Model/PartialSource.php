<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\AsynchronousImportSourceDataRetrieving\Model;

use Magento\AsynchronousImportSourceDataRetrievingApi\Model\PartialSourceInterface;

/**
 * @inheritdoc
 */
class PartialSource implements PartialSourceInterface
{
    /**
     * @var string
     */
    private $piecesCount;

    /**
     * @var string
     */
    private $piecesNumber;

    /**
     * @var string
     */
    private $piecesHash;

    /**
     * PartialSourceData constructor.
     *
     * @param string $piecesCount
     * @param string $piecesNumber
     * @param string $piecesHash
     */
    public function __construct(
        string $piecesCount,
        string $piecesNumber,
        string $piecesHash

    ) {
        $this->piecesCount  = $piecesCount;
        $this->piecesNumber = $piecesNumber;
        $this->piecesHash   = $piecesHash;
    }

    /**
     * Get pieces count
     *
     * @return string
     */
    public function getPiecesCount(): string
    {
        return $this->piecesCount;
    }

    /**
     * Get pieces number
     *
     * @return string
     */
    public function getPiecesNumber(): string
    {
        return $this->piecesNumber;
    }

    /**
     * Get pieces hash
     *
     * @return string
     */
    public function getPiecesHash(): string
    {
        return $this->piecesHash;
    }
}

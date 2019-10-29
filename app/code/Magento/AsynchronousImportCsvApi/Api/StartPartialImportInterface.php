<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\AsynchronousImportCsvApi\Api;

use Magento\AsynchronousImportCsvApi\Api\Data\CsvFormatInterface;
use Magento\AsynchronousImportDataExchangingApi\Api\Data\ImportInterface;
use Magento\AsynchronousImportSourceDataRetrievingApi\Api\Data\SourceInterface;
use Magento\AsynchronousImportSourceDataRetrievingApi\Model\PartialSourceInterface;

/**
 * Start partial import operation
 *
 * @api
 */
interface StartPartialImportInterface
{

    /**
     * Start partial import operation
     *
     * @param SourceInterface $source
     * @param ImportInterface $import
     * @param CsvFormatInterface|null $format
     * @param PartialSourceInterface $partialMetaData
     * @param array $convertingRules
     * @return string
     */
    public function execute(
        SourceInterface $source,
        ImportInterface $import,
        CsvFormatInterface $format = null,
        PartialSourceInterface $partialMetaData,
        array $convertingRules = []
    ): string;
}

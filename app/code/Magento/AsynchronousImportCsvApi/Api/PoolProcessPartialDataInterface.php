<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\AsynchronousImportCsvApi\Api;

use Magento\AsynchronousImportSourceDataRetrievingApi\Api\Data\SourceInterface;
use Magento\AsynchronousImportSourceDataRetrievingApi\Model\PartialSourceInterface;

/**
 * Response of partial source import operation
 *
 * @api
 */
interface PoolProcessPartialDataInterface
{
    public const IMPORT_SOURCE_FILE_PATH = 'import/';

    /**
     * @param SourceInterface $source
     * @param PartialSourceInterface $partialMetaData
     * @return SourceInterface
     * @throws PartialResponseException
     */
    public function execute(
        SourceInterface $source,
        PartialSourceInterface $partialMetaData
    ): SourceInterface;
}

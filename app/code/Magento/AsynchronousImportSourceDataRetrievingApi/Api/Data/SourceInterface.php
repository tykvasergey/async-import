<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\AsynchronousImportSourceDataRetrievingApi\Api\Data;

/**
 * Describes how to retrieve data from data source
 *
 * @api
 */
interface SourceInterface
{
    public const SOURCE_TYPE = 'source_type';
    public const SOURCE_DEFINITION = 'source_definition';
    public const SOURCE_DATA_FORMAT = 'source_data_format';

    /**
     * Get source type
     *
     * @return string
     */
    public function getSourceType(): string;

    /**
     * Get source definition
     *
     * @return string
     */
    public function getSourceDefinition(): string;

    /**
     * Set source definition
     *
     * @return string
     */
    public function setSourceDefinition(string $sourceDefinition): void;

    /**
     * Get source data format
     *
     * @return string
     */
    public function getSourceDataFormat(): string;
}

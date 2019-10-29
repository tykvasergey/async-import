<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\AsynchronousImportSourceDataRetrieving\Model;

use Magento\AsynchronousImportSourceDataRetrievingApi\Api\Data\SourceInterface;

/**
 * @inheritdoc
 */
class Source implements SourceInterface
{
    /**
     * @var string
     */
    private $sourceType;

    /**
     * @var string
     */
    private $sourceDefinition;

    /**
     * @var string
     */
    private $sourceDataFormat;

    /**
     * @param string $sourceType
     * @param string $sourceDefinition
     * @param string $sourceDataFormat
     */
    public function __construct(
        string $sourceType,
        string $sourceDefinition,
        string $sourceDataFormat
    ) {
        $this->sourceType = $sourceType;
        $this->sourceDefinition = $sourceDefinition;
        $this->sourceDataFormat = $sourceDataFormat;
    }

    /**
     * @inheritdoc
     */
    public function getSourceType(): string
    {
        return $this->sourceType;
    }

    /**
     * @inheritdoc
     */
    public function getSourceDefinition(): string
    {
        return $this->sourceDefinition;
    }

    /**
     * @inheritdoc
     */
    public function setSourceDefinition(string $sourceDefinition): void
    {
        $this->sourceDefinition = $sourceDefinition;
    }

    /**
     * @inheritdoc
     */
    public function getSourceDataFormat(): string
    {
        return $this->sourceDataFormat;
    }
}

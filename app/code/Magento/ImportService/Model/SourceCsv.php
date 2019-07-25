<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\ImportService\Model;

use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\ImportServiceApi\Api\Data\SourceCsvExtensionInterface;
use Magento\ImportServiceApi\Api\Data\SourceCsvInterface;
use Magento\ImportService\Model\ResourceModel\Source as SourceResource;
use Magento\ImportServiceApi\Api\Data\SourceCsvFormatInterface;
use Magento\ImportService\Model\SourceCsvFormatFactory as FormatFactory;

/**
 * Class Source
 */
class SourceCsv extends AbstractExtensibleModel implements SourceCsvInterface
{
    const CACHE_TAG = 'magento_import_service_source';

    /**
     * Source format factory
     *
     * @var FormatFactory
     */
    private $formatFactory;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $customAttributeFactory
     * @param FormatFactory $formatFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        FormatFactory $formatFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->formatFactory = $formatFactory;
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $resource, $resourceCollection, $data);
    }

    /**
     * Source constructor
     */
    protected function _construct()
    {
        $this->_init(SourceResource::class);
    }

    /**
     * Get unique page cache identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @inheritDoc
     */
    public function getUuid(): ?string
    {
        return $this->getData(self::UUID);
    }

    /**
     * @inheritDoc
     */
    public function setUuid(string $uuid): SourceCsvInterface
    {
        return $this->setData(self::UUID, $uuid);
    }

    /**
     * @inheritDoc
     */
    public function getSourceType(): string
    {
        return $this->getData(self::SOURCE_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setSourceType(string $sourceType): SourceCsvInterface
    {
        return $this->setData(self::SOURCE_TYPE, $sourceType);
    }

    /**
     * @inheritDoc
     */
    public function getImportType(): string
    {
        return $this->getData(self::IMPORT_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setImportType(string $importType): SourceCsvInterface
    {
        return $this->setData(self::IMPORT_TYPE, $importType);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): string
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(string $status): SourceCsvInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     */
    public function getImportData(): string
    {
        return $this->getData(self::IMPORT_DATA);
    }

    /**
     * @inheritDoc
     */
    public function setImportData(string $importData): SourceCsvInterface
    {
        return $this->setData(self::IMPORT_DATA, $importData);
    }

    /**
     * @inheritDoc
     */
    public function getFormat(): ?SourceCsvFormatInterface
    {
        return $this->getData(self::FORMAT);
    }

    /**
     * @inheritDoc
     */
    public function setFormat(SourceCsvFormatInterface $format): SourceCsvInterface
    {
        return $this->setData(self::FORMAT, $format);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt(string $date): SourceCsvInterface
    {
        return $this->setData(self::CREATED_AT, $date);
    }

    /**
     * @inheritdoc
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(SourceCsvExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * {@inheritdoc}
     */
    public function afterLoad()
    {
        $this->decorate();
        parent::afterLoad();
    }

    /**
     * {@inheritdoc}
     */
    public function decorate()
    {
        $formatJson = $this->getData('format');

        if(isset($formatJson)) {

            /** get format json string and decode */
            $formatJson = json_decode($formatJson, true);

            /** set decoded json string and object to formatted source */
            $format = $this->formatFactory->create()->setData($formatJson);
            $this->setData('format', $format);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave()
    {
        /** get format object */
        $format = $this->getFormat();

        if(!isset($format)) {
            $data = [
                SourceCsvFormatInterface::CSV_SEPARATOR => SourceCsvFormatInterface::DEFAULT_CSV_SEPARATOR,
                SourceCsvFormatInterface::CSV_ENCLOSURE => SourceCsvFormatInterface::DEFAULT_CSV_ENCLOSURE,
                SourceCsvFormatInterface::CSV_DELIMITER => SourceCsvFormatInterface::DEFAULT_CSV_DELIMITER,
                SourceCsvFormatInterface::MULTIPLE_VALUE_SEPARATOR => SourceCsvFormatInterface::DEFAULT_MULTIPLE_VALUE_SEPARATOR
            ];
            /** create format object and set default values */
            $format = $this->formatFactory->create()->setData($data);
        }

        /** set format json string to format field */
        $this->setData('format', $format->toJson());

        parent::beforeSave();
    }
}

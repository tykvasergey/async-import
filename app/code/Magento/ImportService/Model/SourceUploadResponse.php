<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\ImportService\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\ImportService\Api\Data\SourceUploadResponseInterface;

class SourceUploadResponse extends AbstractModel implements SourceUploadResponseInterface
{

    /**
     * Get file ID
     *
     * @return int
     */
    public function getSourceId()
    {
        return $this->getData(self::SOURCE_ID);
    }

    /**
     * Get file status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Get error
     * @return string
     */
    public function getError()
    {
        return $this->getData(self::ERROR);
    }

    /**
     * @param $sourceId
     * @return SourceUploadResponse|mixed
     */
    public function setSourceId($sourceId)
    {
        return $this->setData(self::SOURCE_ID, $sourceId);
    }

    /**
     * @param $status
     * @return SourceUploadResponse|mixed
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @param $error
     * @return SourceUploadResponse|mixed
     */
    public function setError($error)
    {
        return $this->setData(self::ERROR, $error);
    }
}
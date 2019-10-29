<?php

namespace Magento\AsynchronousImportCsvApi\Model;

use Magento\Framework\Model\AbstractModel;

class PartialResponse extends AbstractModel implements PartialResponseInterface
{
    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @param string $status
     * @return \Magento\AsynchronousImportCsvApi\Model\PartialResponseInterface
     */
    public function setStatus(string $status): PartialResponseInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @param bool $isPieceLast
     * @return PartialResponseInterface
     */
    public function setPieceIsLast(bool $isPieceLast): PartialResponseInterface
    {
        return $this->setData(self::IS_PIECE_LAST, $isPieceLast);
    }

    /**
     * @return bool
     */
    public function getPieceIsLast(): bool
    {
        return $this->getData(self::IS_PIECE_LAST);
    }
}

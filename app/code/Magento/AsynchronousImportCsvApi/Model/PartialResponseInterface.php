<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\AsynchronousImportCsvApi\Model;

/**
 * Represents the result of saving part of a csv document
 *
 * @api
 */
interface PartialResponseInterface
{
    public const STATUS              = 'status';
    public const IS_PIECE_LAST       = 'is_piece_last';

    public const STATUS_UPLOADED     = 'uploaded';
    public const STATUS_FAILED       = 'failed';
    public const COMPLETED_ALL_PARTS = 'completed all parts';

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus(): string;

    /**
     * Set status
     *
     * @param string $status
     * @return PartialResponseInterface
     */
    public function setStatus(string $status): PartialResponseInterface;

    /**
     * @return bool
     */
    public function setPieceIsLast(bool $isPieceLast): PartialResponseInterface;

    /**
     * @return bool
     */
    public function getPieceIsLast(): bool;
}

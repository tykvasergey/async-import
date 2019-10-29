<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\AsynchronousImportSourceDataRetrievingApi\Model;

/**
 * Describes how to retrieve data from partial data source
 *
 * @api
 */
interface PartialSourceInterface
{

    public const PIECES_COUNT  = 'pieces_count';
    public const PIECES_NUMBER = 'pieces_number';
    public const PIECES_HASH   = 'pieces_hash';

    /**
     * Get pieces count
     *
     * @return string
     */
    public function getPiecesCount(): string;

    /**
     * Get pieces number
     *
     * @return string
     */
    public function getPiecesNumber(): string;

    /**
     * Get pieces hash
     *
     * @return string
     */
    public function getPiecesHash(): string;
}

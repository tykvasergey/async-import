<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\AsynchronousImportCsvApi\Api;

use Magento\Framework\Exception\LocalizedException;

/**
 * Response Partial Source Exception
 *
 * @api
 */
class PartialResponseException extends LocalizedException
{
}
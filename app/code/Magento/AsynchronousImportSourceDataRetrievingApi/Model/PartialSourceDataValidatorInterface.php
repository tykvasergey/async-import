<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\AsynchronousImportSourceDataRetrievingApi\Model;

use Magento\Framework\Validation\ValidationResult;

/**
 * Extension point for validation of new partial source data types via di configuration
 *
 * @api
 */
interface PartialSourceDataValidatorInterface
{

    /**
     * @param PartialSourceInterface $partialSource
     * @return ValidationResult
     */
    public function validate(PartialSourceInterface $partialSource): ValidationResult;
}

<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\AsynchronousImportSourceDataRetrieving\Model\SourceValidator;

use Magento\AsynchronousImportSourceDataRetrievingApi\Model\PartialSourceInterface;
use Magento\AsynchronousImportSourceDataRetrievingApi\Model\PartialSourceDataValidatorInterface;
use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;


/**
 * Class PartialSourceDataValidator
 *
 * @package Magento\AsynchronousImportRetrievingSource\Model\SourceDataValidator
 */
class PartialSourceDataValidator implements PartialSourceDataValidatorInterface
{

    /**
     * Regular expression pattern for matching a valid sha256.
     */
    const VALID_PATTERN = '^[0-9A-Fa-f]{64}$';

    /**
     * @var ValidationResultFactory
     */
    private $validationResultFactory;

    /**
     * PartialSourceMetaDataValidator constructor.
     *
     * @param ValidationResultFactory $validationResultFactory
     */
    public function __construct(ValidationResultFactory $validationResultFactory)
    {
        $this->validationResultFactory = $validationResultFactory;
    }

    /**
     * Validate partial source meta data
     *
     * @param PartialSourceMetaDataInterface $partialSource
     * @return ValidationResult
     */
    public function validate(PartialSourceInterface $partialSource): ValidationResult
    {
        $errors = [];

        if (!$partialSource->getPiecesHash()) {
            $errors[] = __('%1 cannot be empty.', PartialSourceInterface::PIECES_HASH);
        } elseif (!preg_match('/' . self::VALID_PATTERN . '/D', $partialSource->getPiecesHash())) {
            $errors[] = __('%1" cannot be hash type sha256.', PartialSourceInterface::PIECES_HASH);
        }

        if (!filter_var($partialSource->getPiecesCount(), FILTER_VALIDATE_INT)) {
            $errors[] = __('%1" can be integer and not empty.', PartialSourceInterface::PIECES_COUNT);
        } else {
            $piecesCount = (int) $partialSource->getPiecesCount();
        }

        if (!filter_var($partialSource->getPiecesNumber(), FILTER_VALIDATE_INT)) {
            $errors[] = __('%1 can be integer and not empty.', PartialSourceInterface::PIECES_NUMBER);
        } else {
            $piecesNumber = (int) $partialSource->getPiecesNumber();
        }

        if($piecesCount && $piecesNumber && $piecesNumber > $piecesCount ) {
            $errors[] = __('%1 must be less than %2.',
                PartialSourceInterface::PIECES_NUMBER,
                PartialSourceInterface::PIECES_COUNT
            );
        }

        return $this->validationResultFactory->create(['errors' => $errors]);
    }
}

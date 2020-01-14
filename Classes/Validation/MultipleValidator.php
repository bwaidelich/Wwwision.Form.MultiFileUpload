<?php
declare(strict_types=1);
namespace Wwwision\MultiFileUpload\Validation;

use Neos\Error\Messages\Result;
use Neos\Flow\Validation\Error as ValidationError;
use Neos\Flow\Validation\Validator\ValidatorInterface;

/**
 * A Validator that applies a given validator to all elements of an iterable object.
 * Usage:
 * $validator = MultipleValidator::for(new StringLengthValidator(['minimum' => 5]));
 * $validator->validate($stringArray); // will apply the configured StringLengthValidator to all array elements and return the merged result
 *
 * Used in @see MultiFileUpload
 */
final class MultipleValidator implements ValidatorInterface
{

    /**
     * @var ValidatorInterface
     */
    private $wrappedValidator;

    protected function __construct(ValidatorInterface $wrappedValidator)
    {
        $this->wrappedValidator = $wrappedValidator;
    }

    public static function for(ValidatorInterface $validator): self
    {
        return new static($validator);
    }

    public function validate($values): Result
    {
        $result = new Result();
        if (!is_iterable($values)) {
            $result->addError(new ValidationError('The given argument is not iterable but an %s', 1579011228, [is_object($values) ? get_class($values) : gettype($values)]));
        }
        foreach ($values as $value) {
            $result->merge($this->wrappedValidator->validate($value));
        }
        return $result;
    }

    public function getOptions(): array
    {
        return [];
    }
}

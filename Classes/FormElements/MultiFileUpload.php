<?php
declare(strict_types=1);
namespace Wwwision\MultiFileUpload\FormElements;

use Neos\Flow\Property\PropertyMappingConfiguration;
use Neos\Flow\ResourceManagement\PersistentResource;
use Neos\Flow\Validation\Exception\InvalidValidationOptionsException;
use Neos\Form\Core\Model\AbstractFormElement;
use Neos\Form\Core\Runtime\FormRuntime;
use Neos\Form\Exception\FormDefinitionConsistencyException;
use Neos\Form\Validation\FileTypeValidator;
use Wwwision\MultiFileUpload\Validation\MultipleValidator;

/**
 * A Neos.Form Element that represents a multiple file upload
 * The resulting form value is an array of PersistentResource instances
 */
final class MultiFileUpload extends AbstractFormElement
{

    /**
     * @return void
     */
    public function initializeFormElement()
    {
        $this->setDataType('array<' . PersistentResource::class . '>');
    }

    /**
     * @param FormRuntime $formRuntime
     * @param mixed $elementValue
     * @return void
     * @throws FormDefinitionConsistencyException | InvalidValidationOptionsException
     */
    public function onSubmit(FormRuntime $formRuntime, &$elementValue)
    {
        if (!is_array($elementValue)) {
            return;
        }

        $processingRule = $this->getRootForm()->getProcessingRule($this->getIdentifier());

        $fileTypeValidator = new FileTypeValidator(['allowedExtensions' => $this->properties['allowedExtensions']]);
        $processingRule->addValidator(MultipleValidator::for($fileTypeValidator));

        /** @var PropertyMappingConfiguration $propertyMappingConfiguration */
        $propertyMappingConfiguration = $processingRule->getPropertyMappingConfiguration();
        foreach ($elementValue as $index => $resource) {
            $propertyMappingConfiguration->allowProperties((string)$index);
        }
    }

}

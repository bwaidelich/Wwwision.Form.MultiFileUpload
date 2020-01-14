<?php
declare(strict_types=1);
namespace Wwwision\MultiFileUpload\ViewHelpers;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Property\PropertyMapper;
use Neos\FluidAdaptor\ViewHelpers\Form\AbstractFormFieldViewHelper;
use Neos\FluidAdaptor\ViewHelpers\FormViewHelper;

/**
 * Custom Fluid Form ViewHelper representing a multiple file upload
 */
final class MultiFileUploadViewHelper extends AbstractFormFieldViewHelper
{
    /**
     * @Flow\Inject
     * @var PropertyMapper
     */
    protected $propertyMapper;

    /**
     * @var string
     */
    protected $tagName = 'input';

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerTagAttribute('disabled', 'boolean', 'Specifies that the input element should be disabled when the page loads', false, false);
        $this->registerArgument('errorClass', 'string', 'CSS class to set if there are errors for this view helper', false, 'f3-form-error');
        $this->registerUniversalTagAttributes();
    }

    public function render(): string
    {
        $nameAttribute = $this->getName() . '[]';
        $this->tag->addAttribute('type', 'file');
        $this->tag->addAttribute('multiple', true);
        $this->tag->addAttribute('name', $nameAttribute);

        $this->viewHelperVariableContainer->addOrUpdate(FormViewHelper::class, 'required-enctype', 'multipart/form-data');
        $this->addAdditionalIdentityPropertiesIfNeeded();
        $this->setErrorClassAttribute();

        return $this->tag->render();
    }
}

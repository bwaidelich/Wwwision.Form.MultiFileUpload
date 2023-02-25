<?php
declare(strict_types=1);
namespace Wwwision\Form\MultiFileUpload\ViewHelpers;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Property\PropertyMapper;
use Neos\Flow\ResourceManagement\PersistentResource;
use Neos\FluidAdaptor\ViewHelpers\Form\AbstractFormFieldViewHelper;

/**
 * Custom Fluid Form ViewHelper that can be used to render previously uploaded resources:
 *
 * <x:uploadedResources property="files">
 *   <f:for each="{resources}" as="resource">
 *     {resource.fileName}
 *   </f:for>
 * </x:uploadedResources>
 */
final class UploadedResourcesViewHelper extends AbstractFormFieldViewHelper
{
    /**
     * @Flow\Inject
     * @var PropertyMapper
     */
    protected $propertyMapper;
    
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerTagAttribute('as','string','');
    }

    public function render(string $as = 'resources'): string
    {
        $this->templateVariableContainer->add($as, $this->getUploadedResources());
        $output = $this->renderChildren();
        $this->templateVariableContainer->remove($as);

        return $output;
    }

    /**
     * Returns previously uploaded resources.
     * Errors during property mapping are ignored
     *
     * @return PersistentResource[]
     */
    protected function getUploadedResources(): array
    {
        if ($this->getMappingResultsForProperty()->hasErrors()) {
            return [];
        }
        $resources = null;
        if ($this->hasMappingErrorOccurred()) {
            $resources = $this->getLastSubmittedFormData();
        } elseif ($this->hasArgument('value')) {
            $resources = $this->arguments['value'];
        } elseif ($this->isObjectAccessorMode()) {
            $resources = $this->getPropertyValue();
        }
        if (!is_array($resources) || empty($resources)) {
            return [];
        }
        return array_map(function($resource) {
            if ($resource instanceof PersistentResource) {
                return $resource;
            }
            return $this->propertyMapper->convert($resource, PersistentResource::class);
        }, $resources);
    }
}

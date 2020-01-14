# Wwwision.Form.MultiFileUpload

Example package providing a simple MultiFileUpload element for the [Neos.Form](https://github.com/neos/form) framework.

## DISCLAIMER

This package is not meant to be used in production but rather serves as basis implementation.
Feel free to copy (parts of the) implementation and adjust it to your needs.

## Usage

As mentioned above, this package is meant as inspiration and source for copy/paste.
If you want to test it out, you can however install it as-is of course:

1. Install package via `composer require wwwision/multifileupload`
2. Configure your form preset to include a definition for the new element:

```yaml
Neos:
  Form:
    presets:
      'default':
        formElementTypes:
          'Wwwision.Form.MultiFileUpload:MultiFileUpload':
            superTypes:
              'Neos.Form:FormElement': true
            implementationClassName: Wwwision\Form\MultiFileUpload\FormElements\MultiFileUpload
            properties:
              allowedExtensions:
                - pdf
                - doc

```

3a. Include the element in a form factory:

```php
    public function build(array $factorySpecificConfiguration, $presetName) {
        $formConfiguration = $this->getPresetConfiguration($presetName);
        $form = new FormDefinition('someForm', $formConfiguration);

        $page1 = $form->createPage('page1');

        // ...

        $files = $page1->createElement('files', 'Wwwision.Form.MultiFileUpload:MultiFileUpload');
        $files->setLabel('Some Files');

        return $form;
    }
```

3b. Or within a form definition file:

```yaml
type: 'Neos.Form:Form'
identifier: someForm
label: 'Some Form'
renderingOptions:
  submitButtonLabel: 'Send'
renderables:
  - type: 'Neos.Form:Page'
    identifier: page1
    renderables:
      - type: 'Wwwision.Form.MultiFileUpload:MultiFileUpload'
        identifier: files
        label: 'Some Files'
```

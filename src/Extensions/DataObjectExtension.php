<?php

namespace Goldfinch\FocusPointExtra\Extensions;

use ReflectionMethod;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\ValidationResult;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\AssetAdmin\Forms\ImageFormFactory;
use Goldfinch\FocusPointExtra\Forms\ImageCoordsField;

class DataObjectExtension extends DataExtension
{
    public function updateCMSFields(FieldList $fields)
    {
        foreach($fields->flattenFields() as $field)
        {
            if (get_class($field) == UploadField::class)
            {
                // if ($this->owner->appCategory() === 'image')
                if ($field->getName())
                {
                    $fieldName = $field->getName();
                    $relationship = $this->owner->{$fieldName}();

                    if ($relationship == Image::class)
                    {
                        $image = $relationship;

                        $r = new ReflectionMethod(ImageFormFactory::class, 'getSpecsMarkup');
                        $r->setAccessible(true);
                        $imageSpecs = $r->invoke(new ImageFormFactory(), $image);

                        $fields->insertAfter($field->getName(),
                            ToggleCompositeField::create(
                                $fieldName.'_ImageSettings',
                                'Image Settings',
                                [
                                    ImageCoordsField::create(
                                    'Focus Point',
                                    $fieldName.'-_1_-FocusPointX',
                                    $fieldName.'-_1_-FocusPointY',
                                    'filename',
                                    $image,
                                    $image->getWidth(),
                                    $image->getHeight(),
                                    false,
                                    true
                                    ),
                                    TextField::create($fieldName.'-_1_-Title', 'Alt / Title'),
                                    TextField::create($fieldName.'-_1_-Name', 'Filename'),
                                    LiteralField::create($fieldName.'ImageInfo', '<div class="form__fieldgroup form__field-holder field"><p><a href="'.$image->Link().'" target="_blank">Original image</a></p>' . $imageSpecs . '</div>'),
                                ]
                            )
                        );
                    }
                }
            }
        }
    }
}

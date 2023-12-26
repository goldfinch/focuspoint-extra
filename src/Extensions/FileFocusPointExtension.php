<?php

namespace Goldfinch\ImageSettings\Extensions;

use Goldfinch\ImageSettings\Forms\ImageCoordsField;
use SilverStripe\ORM\DataExtension;

class FileFocusPointExtension extends DataExtension
{
    public function updateCMSFields($fields)
    {
        // &$fields?
        if ($this->owner->appCategory() === 'image') {
            $field = ImageCoordsField::create(
                'FocusPoint',
                'FocusPointX',
                'FocusPointY',
                'filename',
                $this->owner,
                $this->owner->getWidth(),
                $this->owner->getHeight(),
            );

            // $field = FocusPointField::create('FocusPoint', $this->owner->fieldLabel('Focus point'), $this->owner);

            $fields->insertAfter('Title', $field);
        }
    }
}

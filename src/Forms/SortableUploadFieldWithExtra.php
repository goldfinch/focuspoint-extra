<?php

namespace Goldfinch\FocusPointExtra\Forms;

use SilverStripe\Assets\Image;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\ToggleCompositeField;
use Bummzack\SortableFile\Forms\SortableUploadField;
use Goldfinch\FocusPointExtra\Forms\GridField\GridFieldManyManyFocusConfig;

class SortableUploadFieldWithExtra
{
    public static $fields;
    public static $parent;
    public static $name;
    public static $record;

    public function isOneOrMany()
    {
        $parent = self::$parent;
        if (!$parent->exists()) {
            return false;
        }
        switch ($parent->getRelationType(self::$name)) {
            case 'has_one':
            case 'belongs_to':
                return 'one';
            case 'has_many':
            case 'many_many':
            case 'belongs_many_many':
                return 'many';
        }
    }

    public function __construct(...$args)
    {
        $name = $args[0];
        $title = $args[1];
        $fieldList = $args[2];
        $parent = $args[3];

        self::$name = $name;
        self::$parent = $parent;
        self::$record = $parent;

        if (isset($args[4]))
        {
            $field = $args[4];
        }
        else
        {
            $field = SortableUploadField::create($name, $title);
        }

        if ($this->isOneOrMany() == 'many') { // has
            self::$record = $parent->{$name}();
        }

        if (self::$record->dataClass == Image::class && self::$record->count())
        {
            $imagesSettings = ToggleCompositeField::create($name . '_ImageAttributes', $title . ' Settings',
              GridField::create($name . '_ImageAttributes', $title, self::$record, GridFieldManyManyFocusConfig::create())->addExtraClass('focuspoint-extra-attrs-grid')
            );

            if ($imagesSettings)
            {
                self::$fields = [$field, $imagesSettings];
            }
            else
            {
                self::$fields = [$field];
            }
        }
        else
        {
            self::$fields = [$field];
        }

        $fieldList->removeByName([
            $name . '_ImageAttributes',
            $name
        ]);
    }

    public function getFields()
    {
        return self::$fields;
    }

    public static function create(...$args): static
    {
        return new static(...$args);
    }
}

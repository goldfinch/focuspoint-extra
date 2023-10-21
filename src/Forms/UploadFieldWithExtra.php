<?php

namespace Goldfinch\FocusPointExtra\Forms;

use SilverStripe\Assets\Image;
use SilverStripe\AssetAdmin\Forms\UploadField;

class UploadFieldWithExtra
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
            $field = UploadField::create($name, $title);
        }

        if ($this->isOneOrMany() == 'one') {
            self::$record = $parent->{$name}();
        }

        if (self::$record::class == Image::class && self::$record->exists())
        {
            $imageSettings = $fieldList->flattenFields()->fieldByName($name . '_ImageSettings');

            self::$fields = [$field, $imageSettings];
        }
        else
        {
            self::$fields = [$field];
        }

        $fieldList->removeByName([
            $name . '_ImageSettings',
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

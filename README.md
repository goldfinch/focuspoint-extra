# 🦅 Image Editor for Silverstripe

[![Silverstripe Version](https://img.shields.io/badge/Silverstripe-^5.1-005ae1.svg?labelColor=white&logoColor=ffffff&logo=data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDEuMDkxIDU4LjU1NSIgZmlsbD0iIzAwNWFlMSIgeG1sbnM6dj0iaHR0cHM6Ly92ZWN0YS5pby9uYW5vIj48cGF0aCBkPSJNNTAuMDE1IDUuODU4bC0yMS4yODMgMTQuOWE2LjUgNi41IDAgMCAwIDcuNDQ4IDEwLjY1NGwyMS4yODMtMTQuOWM4LjgxMy02LjE3IDIwLjk2LTQuMDI4IDI3LjEzIDQuNzg2czQuMDI4IDIwLjk2LTQuNzg1IDI3LjEzbC02LjY5MSA0LjY3NmM1LjU0MiA5LjQxOCAxOC4wNzggNS40NTUgMjMuNzczLTQuNjU0QTMyLjQ3IDMyLjQ3IDAgMCAwIDUwLjAxNSA1Ljg2MnptMS4wNTggNDYuODI3bDIxLjI4NC0xNC45YTYuNSA2LjUgMCAxIDAtNy40NDktMTAuNjUzTDQzLjYyMyA0Mi4wMjhjLTguODEzIDYuMTctMjAuOTU5IDQuMDI5LTI3LjEyOS00Ljc4NHMtNC4wMjktMjAuOTU5IDQuNzg0LTI3LjEyOWw2LjY5MS00LjY3NkMyMi40My0zLjk3NiA5Ljg5NC0uMDEzIDQuMTk4IDEwLjA5NmEzMi40NyAzMi40NyAwIDAgMCA0Ni44NzUgNDIuNTkyeiIvPjwvc3ZnPg==)](https://packagist.org/packages/goldfinch/image-editor)
[![Package Version](https://img.shields.io/packagist/v/goldfinch/image-editor.svg?labelColor=333&color=F8C630&label=Version)](https://packagist.org/packages/goldfinch/image-editor)
[![Total Downloads](https://img.shields.io/packagist/dt/goldfinch/image-editor.svg?labelColor=333&color=F8C630&label=Downloads)](https://packagist.org/packages/goldfinch/image-editor)
[![License](https://img.shields.io/packagist/l/goldfinch/image-editor.svg?labelColor=333&color=F8C630&label=License)](https://packagist.org/packages/goldfinch/image-editor)

Provides a full list of all available images for a quick Alt/Title, Filename and Focus Point update.

Apart from that, it comes with two custom fields: `EditableUploadField`, `EditableSortableUploadField` (based on `UploadField`, `SortableUploadField`) enchanted with an inline editor for quick access and update, without leaving the page or making too many clicks. To more other additions: `ImageCoordsField` and `GridFieldManyManyFocusConfig`.

## Install

```bash
composer require goldfinch/image-editor
```

## Usage

#### Editable UploadField

```php
use SilverStripe\Assets\Image;
use Goldfinch\ImageEditor\Forms\EditableUploadField;

private static $has_one = [
    'Image' => Image::class,
];

private static $owns = [
    'Image',
];

public function getCMSFields()
{
    $fields = parent::getCMSFields();

    $fields->addFieldsToTab(
        'Root.Main',
        [
            // [
            //     TextField::create('Title')
            //     Other fields ...
            // ],
            ...EditableUploadField::create('Image', 'Image', $fields, $this)->getFields(),
            // [
            //     Other fields ...
            // ],
        ]
    );
    
    return $fields;
}
```

#### Editable SortableUploadField

```php
use SilverStripe\Assets\Image;
use Goldfinch\ImageEditor\Forms\EditableSortableUploadField;

private static $many_many = [
    'Images' => Image::class,
];

private static $many_many_extraFields = [
    'Images' => [
        'SortExtra' => 'Int',
    ]
];

private static $owns = [
    'Images',
];

public function getCMSFields()
{
    $fields = parent::getCMSFields();

    $fields->addFieldsToTab(
        'Root.Main',
        [
            // [
            //     TextField::create('Title')
            //     Other fields ...
            // ],
            ...EditableSortableUploadField::create('Images', 'Images', $fields, $this)->getFields(),
            // [
            //     Other fields ...
            // ],
        ]
    );
    
    return $fields;
}
```

#### Editable GridField

```php
use SilverStripe\Assets\Image;
use SilverStripe\Forms\GridField\GridField;
use Goldfinch\ImageEditor\Forms\GridField\GridFieldManyManyFocusConfig;

private static $many_many = [
    'Images' => Image::class,
];

private static $many_many_extraFields = [
    'Images' => [
        'SortOrder' => 'Int',
    ]
];

private static $owns = [
    'Images',
];

public function getCMSFields()
{
    $fields = parent::getCMSFields();

    $fields->addFieldsToTab(
        'Root.Main',
        [
            GridField::create('ImageAttributes', 'Images', $this->Images(), GridFieldManyManyFocusConfig::create()),
        ]
    );

    return $fields;
}
```

#### Editable ImageCoords

```php
use SilverStripe\Assets\Image;
use Goldfinch\ImageEditor\Forms\ImageCoordsField;

private static $has_one = [
    'Image' => Image::class,
];

private static $owns = [
    'Image',
];

public function getCMSFields()
{
    $fields = parent::getCMSFields();

    $fields->addFieldsToTab(
        'Root.Main',
        [
            ImageCoordsField::create($this, 'Image'),
        ]
    );

    return $fields;
}
```
## Previews

#### Image Editor admin
![Image Editor admin](screenshots/admin.jpeg)
#### Image edit page
![Image edit page](screenshots/admin-edit.jpeg)
#### Editable UploadField
![Editable-UploadField](screenshots/editable-UploadField.jpeg)
#### Editable SortableUploadField
![Editable SortableUploadField](screenshots/editable-SortableUploadField.jpeg)
#### Editable GridField
![Editable GridField](screenshots/editable-grid.jpeg)
#### Editable ImageCoords
![Editable ImageCoords](screenshots/image-coords.jpeg)

## References

**Pre-installed packages included in this module**
- [bummzack/sortablefile](https://github.com/bummzack/sortablefile)
- [symbiote/silverstripe-gridfieldextensions](https://github.com/symbiote/silverstripe-gridfieldextensions)
- [stevie-mayhew/hasoneedit](https://github.com/stevie-mayhew/hasoneedit)
- [jonom/focuspoint](https://github.com/jonom/silverstripe-focuspoint)

*and thanks to*

- [jonom/silverstripe-image-coord](https://github.com/jonom/silverstripe-image-coord)
- [seppzzz/silverstripe-image-coord](https://github.com/seppzzz/silverstripe-image-coord)
- [axllent/silverstripe-meta-editor](https://github.com/axllent/silverstripe-meta-editor)

## License

The MIT License (MIT)

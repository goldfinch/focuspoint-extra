<?php

namespace Goldfinch\ImageEditor\Admin;

use SilverStripe\Assets\Image;
use SilverStripe\Admin\ModelAdmin;
use SilverStripe\View\Requirements;
use SilverStripe\Forms\GridField\GridFieldConfig;
use Goldfinch\ImageEditor\Forms\GridField\MetaEditorFocusPointColumn;
use Goldfinch\ImageEditor\Forms\GridField\GridFieldManyManyFocusConfig;

class ImageEditorAdmin extends ModelAdmin
{
    private static $url_segment = 'image-editor';

    private static $menu_title = 'Image settings';

    private static $managed_models = [
        Image::class => [
            'title' => 'Images',
        ],
    ];

    private static $menu_priority = -0.5;

    private static $menu_icon_class = 'font-icon-block-banner';

    public $showImportForm = true;

    public $showSearchForm = true;

    public function init()
    {
        parent::init();
        Requirements::javascript(
            'goldfinch/image-editor:client/dist/resources/assets/image-editor-admin.js',
        );
    }

    public function getList()
    {
        $list = parent::getList();

        // ..

        return $list;
    }

    public function getSearchContext()
    {
        $context = parent::getSearchContext();

        // ..

        return $context;
    }

    protected function getGridFieldConfig(): GridFieldConfig
    {
        $config = GridFieldManyManyFocusConfig::create(
            null,
            'SortOrder',
            [],
            $this->modelClass,
        );
        $config->addComponent(MetaEditorFocusPointColumn::create());

        return $config;
    }

    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);

        $form->addExtraClass('image-editor');

        return $form;
    }
}

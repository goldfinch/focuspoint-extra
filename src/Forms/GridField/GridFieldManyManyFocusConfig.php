<?php

namespace Goldfinch\FocusPointExtra\Forms\GridField;

use SilverStripe\Forms\TextField;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldPaginator;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\GridField\GridField_ActionMenu;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\GridField\GridFieldSortableHeader;
use Symbiote\GridFieldExtensions\GridFieldEditableColumns;
use Symbiote\GridFieldExtensions\GridFieldConfigurablePaginator;

class GridFieldManyManyFocusConfig extends GridFieldConfig
{
    public function __construct($itemsPerPage = null, $sortable = false, $extraFields = [])
    {
        parent::__construct($itemsPerPage);

        $this->removeComponentsByType(GridFieldPaginator::class);

        $this->addComponents(
            GridFieldToolbarHeader::create(),
            GridFieldSortableHeader::create(),
            GridFieldDataColumns::create(),
            GridFieldDetailForm::create(),
            GridFieldDeleteAction::create(),
            GridFieldEditButton::create(),
            GridField_ActionMenu::create(),
            GridFieldEditableColumns::create(),
            GridFieldConfigurablePaginator::create(50, [10, 50, 100, 200, 300]),
        );

        if ($sortable)
        {
            $this->addComponent(GridFieldOrderableRows::create('SortOrder'));
        }

        $dataColumns = $this->getComponentByType(GridFieldDataColumns::class);

        $df = [
            'Title' => 'Alt / Title',
            'Name' => 'Filename',
          ]+(isset($extraFields) && !empty($extraFields) ? $extraFields : [])+[
            'FocusPointX'=> 'FocusPointX',
            'FocusPointY'=> 'FocusPointY',
            'FocusPoint' => ['title' => 'Focus Point', 'field' => LiteralField::class, 'callback' => function($record, $columnName, $gridField) {

              if (in_array('ss-gridfield-editable', array_values($gridField->extraClasses)))
              {
                  $gridPrefix = $gridField->getName() . '[GridFieldEditableColumns]['.$record->ID.']';

                  return $record->getFocusPointGridEditableColumn($columnName, $gridPrefix);
              }
          }],
        ];

        $dataColumns->setDisplayFields($df);

        $dataEditableColumns = $this->getComponentByType(GridFieldEditableColumns::class);

        $extraKeysFields = isset($extraFields) && !empty($extraFields) ? array_combine(array_keys($extraFields), array_keys($extraFields)) : [];

        $ec = [
            // 'Title'  => function($record, $column, $grid) {
            //   return TextField::create($column);
            // },
            // 'Filename'  => function($record, $column, $grid) {
            //   return TextField::create($column);
            // },
            'Title' => 'Title',
            'Name' => 'Name',
            ]+(isset($extraKeysFields) && !empty($extraKeysFields) ? $extraKeysFields : [])+[
            'FocusPointX' => function($record, $column, $grid) {
              return HiddenField::create('FocusPointX');
            },
            'FocusPointY'  => function($record, $column, $grid) {
              return HiddenField::create('FocusPointY');
            },
        ];

        $dataEditableColumns->setDisplayFields($ec);

        $this->extend('updateConfig');
    }
}

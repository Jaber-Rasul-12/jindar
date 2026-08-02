<?php

return [
    'plugin' => [
        'name' => 'immovables',
        'description' => '',
        'immovabless_menu' => 'immovabless',
        'realestateds'=> 'Realestateds',
        'countries' => 'Countries',
        'county' => 'Country',

        'log_changes' => 'Log Changes',
        'message_delete' => 'Deletion is not possible due to the presence of records associated with the section.',
        'import' => 'Import',
        'export' => 'Export',
    ],
    'model' => [
        'country' => [
            'id' => 'Id',
            'name' => 'Name',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
        ],
        'realestated' => [
            'id' => 'Id',
            'country' => 'Country',
            'first_team' => 'First team',
            'second_team' => 'Second team',
            'type' => 'Type',
            'detail' => 'Detail',
            'area' => 'Area',
            'syria_price' => 'Syria price',
            'dollar_price' => 'Dollar price',
            'purchase_date' => 'Purchase date',
            'point' => 'Point',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
        ],
    ],
    'controller' => [
        'countries' => [
            'countries' => 'Countries',
        ],
    ],
];

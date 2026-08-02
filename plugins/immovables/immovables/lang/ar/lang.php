<?php

return [
    'plugin' => [
        'name' => 'العقارات',
        'description' => '',
        'immovabless_menu' => 'العقارات',
        'realestateds' => 'العقارات المسجلة',
        'countries' => 'المدن',
        'country' => 'المدن',

        'log_changes' => 'سجل التغييرات',
        'message_delete' => 'لا يمكن الحذف بسبب وجود سجلات مرتبطة بهذا القسم.',
        'import' => 'استيراد',
        'export' => 'تصدير',
    ],
    'model' => [
        'country' => [
            'id' => 'الرقم',
            'name' => 'الاسم',
            'created_at' => 'تاريخ الإنشاء',
            'updated_at' => 'تاريخ التحديث',
        ],
        'realestated' => [
            'id' => 'الرقم',
            'country' => 'الدولة',
            'first_team' => 'الطرف الأول',
            'second_team' => 'الطرف الثاني',
            'type' => 'النوع',
            'detail' => 'التفاصيل',
            'area' => 'المساحة',
            'syria_price' => 'السعر بالليرة السورية',
            'dollar_price' => 'السعر بالدولار',
            'purchase_date' => 'تاريخ الشراء',
            'point' => 'الموقع',
            'created_at' => 'تاريخ الإنشاء',
            'updated_at' => 'تاريخ التحديث',
        ],
    ],
    'controller' => [
        'countries' => [
            'countries' => 'الدول',
        ],
    ],
];
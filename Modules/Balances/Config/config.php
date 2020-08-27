<?php

return [
    'name' => 'Balances',
    'order' => [
        'id' => 'asc',
    ],
    'sidebar' => [
        'weight' => 10,
        'icon' => 'fa fa-file',
    ],
    'th' => ['Start Date', 'End Date', 'Particulars', 'Debit Amount', 'Credit Amount'],
    'columns' => [
        ['data' => 'start_date', 'name' => 'start_date'],
        ['data' => 'end_date', 'name' => 'end_date'],
        ['data' => 'particulars', 'name' => 'particulars'],
        ['data' => 'debit_amount', 'name' => 'debit_amount'],
        ['data' => 'credit_amount', 'name' => 'credit_amount'],
        ['data' => 'action', 'name' => 'action'],
    ],
    'form' => 'Balances\Forms\BalancesForm',
    'company_permissions' => [
        'balances' => [
            'index',
            'create',
            'store',
            'edit',
            'update',
            'destroy',
            'bulk_upload',
        ],
    ]
];

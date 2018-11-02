<?php //-->
return [
    'disable' => '1',
    'singular' => 'Session',
    'plural' => 'Sessions',
    'name' => 'session',
    'icon' => 'fas fa-id-card',
    'detail' => 'Manages 3-legged application sessions',
    'fields' => [
        [
            'disable' => '1',
            'label' => 'Token',
            'name' => 'token',
            'field' => [
                'type' => 'uuid'
            ],
            'list' => [
                'format' => 'none'
            ],
            'detail' => [
                'format' => 'none'
            ],
            'default' => '1',
            'searchable' => '1',
            'filterable' => '1'
        ],
        [
            'disable' => '1',
            'label' => 'Secret',
            'name' => 'secret',
            'field' => [
                'type' => 'uuid'
            ],
            'list' => [
                'format' => 'none'
            ],
            'detail' => [
                'format' => 'none'
            ],
            'default' => '1',
            'searchable' => '1',
            'filterable' => '1'
        ],
        [
            'disable' => '1',
            'label' => 'Status',
            'name' => 'status',
            'field' => [
                'type' => 'select',
                'options' => [
                    [
                        'key' => 'pending',
                        'value' => 'PENDING'
                    ],
                    [
                        'key' => 'access',
                        'value' => 'ACCESS'
                    ]
                ],
            ],
            'validation' => [
                [
                    'method' => 'one',
                    'parameters' => [
                        'pending',
                        'access'
                    ],
                    'message' => 'Should be one of: pending or access'
                ]
            ],
            'list' => [
                'format' => 'upper'
            ],
            'detail' => [
                'format' => 'upper'
            ],
            'default' => 'pending',
            'filterable' => '1',
            'sortable' => '1'
        ],
        [
            'disable' => '1',
            'label' => 'Active',
            'name' => 'active',
            'field' => [
                'type' => 'active'
            ],
            'list' => [
                'format' => 'hide'
            ],
            'detail' => [
                'format' => 'hide'
            ],
            'default' => '1',
            'sortable' => '1'
        ],
        [
            'disable' => '1',
            'label' => 'Created',
            'name' => 'created',
            'field' => [
                'type' => 'created'
            ],
            'list' => [
                'format' => 'none'
            ],
            'detail' => [
                'format' => 'none'
            ],
            'default' => 'NOW()',
            'sortable' => '1'
        ],
        [
            'disable' => '1',
            'label' => 'Updated',
            'name' => 'updated',
            'field' => [
                'type' => 'updated'
            ],
            'list' => [
                'format' => 'none'
            ],
            'detail' => [
                'format' => 'none'
            ],
            'default' => 'NOW()',
            'sortable' => '1'
        ]
    ],
    'relations' => [
        [
            'many' => '1',
            'name' => 'app'
        ],
        [
            'many' => '1',
            'name' => 'profile'
        ],
        [
            'many' => '2',
            'name' => 'scope'
        ]
    ],
    'suggestion' => '{{app_title}} - {{profile_name}} - {{session_token}}'
];

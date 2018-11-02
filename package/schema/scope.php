<?php //-->
return [
    'disable' => '1',
    'singular' => 'Scope',
    'plural' => 'Scopes',
    'name' => 'scope',
    'icon' => 'fas fa-crosshairs',
    'detail' => 'Groups API REST calls and Webhooks in order to swap in and out on the fly with out the developer necessarily updating their app. This is also useful for API versioning.',
    'fields' => [
        [
            'disable' => '1',
            'label' => 'Name',
            'name' => 'name',
            'field' => [
                'type' => 'text'
            ],
            'validation' => [
                [
                    'method' => 'required',
                    'message' => 'Name is required'
                ]
            ],
            'list' => [
                'format' => 'none'
            ],
            'detail' => [
                'format' => 'none'
            ],
            'default' => '',
            'searchable' => '1'
        ],
        [
            'disable' => '1',
            'label' => 'Slug',
            'name' => 'slug',
            'field' => [
                'type' => 'slug',
                'attributes' => [
                    'data-source' => 'input[name=scope_name]',
                    'data-lower' => '1',
                    'data-space' => '_'
                ]
            ],
            'validation' => [
                [
                    'method' => 'required',
                    'message' => 'Slug is required'
                ],
                [
                    'method' => 'unique',
                    'message' => 'Must be unique'
                ]
            ],
            'list' => [
                'format' => 'none'
            ],
            'detail' => [
                'format' => 'none'
            ],
            'default' => '',
            'searchable' => '1'
        ],
        [
            'disable' => '1',
            'label' => 'Type',
            'name' => 'type',
            'field' => [
                'type' => 'select',
                'options' => [
                    [
                        'key' => 'app',
                        'value' => 'App'
                    ],
                    [
                        'key' => 'user',
                        'value' => 'User'
                    ]
                ],
            ],
            'validation' => [
                [
                    'method' => 'one',
                    'parameters' => [
                        'app',
                        'user'
                    ],
                    'message' => 'Should be one of public, app or user'
                ]
            ],
            'list' => [
                'format' => 'lower'
            ],
            'detail' => [
                'format' => 'lower'
            ],
            'default' => 'app',
            'filterable' => '1'
        ],
        [
            'disable' => '1',
            'label' => 'Detail',
            'name' => 'detail',
            'field' => [
                'type' => 'markdown',
                'attributes' => [
                    'rows' => '10',
                    'placeholder' => 'Used for API Documentation'
                ]
            ],
            'list' => [
                'format' => 'hide'
            ],
            'detail' => [
                'format' => 'markdown'
            ],
            'default' => '',
            'searchable' => '1'
        ],
        [
            'disable' => '1',
            'label' => 'Special Approval',
            'name' => 'special_approval',
            'field' => [
                'type' => 'switch'
            ],
            'validation' => [
                [
                    'method' => 'lte',
                    'parameters' => '1',
                    'message' => 'Should be 0 or 1'
                ],
                [
                    'method' => 'gte',
                    'parameters' => '0',
                    'message' => 'Should be 0 or 1'
                ]
            ],
            'list' => [
                'format' => 'yes'
            ],
            'detail' => [
                'format' => 'yes'
            ],
            'default' => '0',
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
            'many' => '2',
            'name' => 'rest'
        ]
    ],
    'suggestion' => '{{scope_name}}'
];

<?php //-->
return [
    'singular' => 'Application',
    'plural' => 'Applications',
    'name' => 'app',
    'icon' => 'fas fa-mobile-alt',
    'detail' => 'Manages Applications',
    'fields' => [
        [
            'label' => 'Title',
            'name' => 'title',
            'field' => [
                'type' => 'text'
            ],
            'validation' => [
                [
                    'method' => 'required',
                    'message' => 'Title is required'
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
            'label' => 'Domain',
            'name' => 'domain',
            'field' => [
                'type' => 'text',
                'attributes' => [
                    'placeholder' => 'ex. foo.bar.com'
                ]
            ],
            'validation' => [
                [
                    'method' => 'required',
                    'message' => 'Domain is required'
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
            'label' => 'Website',
            'name' => 'website',
            'field' => [
                'type' => 'url'
            ],
            'list' => [
                'format' => 'link',
                'parameters' => [ '{{app_website}}', '{{app_website}}'
                ]
            ],
            'detail' => [
                'format' => 'link',
                'parameters' => [ '{{app_website}}', '{{app_website}}'
                ]
            ],
            'default' => '',
            'searchable' => '1'
        ],
        [
            'label' => 'Webhook URL',
            'name' => 'webhook',
            'field' => [
                'type' => 'url'
            ],
            'list' => [
                'format' => 'none'
            ],
            'detail' => [
                'format' => 'none'
            ],
            'default' => ''
        ],
        [
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
            'name' => 'profile'
        ],
        [
            'many' => '2',
            'name' => 'scope'
        ],
        [
            'many' => '2',
            'name' => 'webhook'
        ]
    ],
    'suggestion' => '{{app_title}}'
];

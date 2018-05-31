<?php //-->
return array (
  'redirect_uri' => '/admin/system/schema/search',
  'singular' => 'Webhook',
  'plural' => 'Webhooks',
  'name' => 'webhook',
  'icon' => 'fas fa-rocket',
  'detail' => 'Collection of API Webhooks',
  'fields' =>
  array (
    0 =>
    array (
      'label' => 'Url',
      'name' => 'url',
      'field' =>
      array (
        'type' => 'url',
      ),
      'validation' =>
      array (
        0 =>
        array (
          'method' => 'url',
          'message' => 'Invalid website url',
        ),
        1 =>
        array (
          'method' => 'required',
          'message' => 'Url url is required',
        ),
      ),
      'list' =>
      array (
        'format' => 'link',
        'parameters' =>
        array (
          0 => '{{webhook_url}}',
          1 => '{{webhook_url}}',
        ),
      ),
      'detail' =>
      array (
        'format' => 'link',
        'parameters' =>
        array (
          0 => '{{webhook_url}}',
          1 => '{{webhook_url}}',
        ),
      ),
      'default' => '',
      'disable' => '1',
    ),
    1 =>
    array (
      'label' => 'Secret',
      'name' => 'secret',
      'field' =>
      array (
        'type' => 'text',
      ),
      'validation' =>
      array (
        0 =>
        array (
          'method' => 'required',
          'message' => 'Secret is required',
        ),
      ),
      'list' =>
      array (
        'format' => 'none',
      ),
      'detail' =>
      array (
        'format' => 'none',
      ),
      'default' => '',
      'disable' => '1',
    ),
    2 =>
    array (
      'label' => 'Type',
      'name' => 'type',
      'field' =>
      array (
        'type' => 'text',
      ),
      'list' =>
      array (
        'format' => 'none',
      ),
      'detail' =>
      array (
        'format' => 'none',
      ),
      'default' => '',
      'disable' => '1',
    ),
    3 =>
    array (
        'label' => 'Events',
        'name' => 'events',
        'field' =>
        array (
          'type' => 'rawjson',
        ),
        'list' =>
        array (
          'format' => 'hide',
        ),
        'detail' =>
        array (
          'format' => 'hide',
        ),
        'default' => '',
        'disable' => '1',
    ),
    4 =>
    array (
      'label' => 'Flag',
      'name' => 'flag',
      'field' =>
      array (
        'type' => 'active',
      ),
      'list' =>
      array (
        'format' => '{{#if webhook_flag}}subscribed{{else}}subscription not yet confirmed{{/if}}',
      ),
      'detail' =>
      array (
        'format' => '{{#if webhook_flag}}subscribed{{else}}subscription not yet confirmed{{/if}}',
      ),
      'default' => '0',
      'filterable' => '1',
      'sortable' => '1',
      'disable' => '1',
    ),
    5 =>
    array (
      'label' => 'Active',
      'name' => 'active',
      'field' =>
      array (
        'type' => 'active',
      ),
      'list' =>
      array (
        'format' => 'hide',
      ),
      'detail' =>
      array (
        'format' => 'hide',
      ),
      'default' => '1',
      'filterable' => '1',
      'sortable' => '1',
      'disable' => '1',
    ),
    6 =>
    array (
      'label' => 'Created',
      'name' => 'created',
      'field' =>
      array (
        'type' => 'created',
      ),
      'list' =>
      array (
        'format' => 'date',
        'parameters' => 'Y-m-d H:i:s',
      ),
      'detail' =>
      array (
        'format' => 'date',
        'parameters' => 'Y-m-d H:i:s',
      ),
      'default' => 'NOW()',
      'sortable' => '1',
      'disable' => '1',
    ),
    7 =>
    array (
      'label' => 'Updated',
      'name' => 'updated',
      'field' =>
      array (
        'type' => 'updated',
      ),
      'list' =>
      array (
        'format' => 'date',
        'parameters' => 'Y-m-d H:i:s',
      ),
      'detail' =>
      array (
        'format' => 'date',
        'parameters' => 'Y-m-d H:i:s',
      ),
      'default' => 'NOW()',
      'sortable' => '1',
      'disable' => '1',
    ),
  ),
  'relations' =>
  array (
    // 0 =>
    // array (
    // 'many' => '1',
    // 'name' => 'app',
    // ),
  ),
  'suggestion' => '{{webhook_url}}',
  'disable' => '1',
);

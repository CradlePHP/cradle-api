<?php //-->
return array (
  'redirect_uri' => '/admin/system/schema/search',
  'singular' => 'Application',
  'plural' => 'Applications',
  'name' => 'app',
  'icon' => 'fas fa-rocket',
  'detail' => 'Collection of API Applications',
  'fields' =>
  array (
    0 =>
    array (
      'label' => 'Icon',
      'name' => 'icon',
      'field' =>
      array (
        'type' => 'image',
        'attributes' =>
        array (
          'accept' => 'image/*',
        ),
      ),
      'list' =>
      array (
        'format' => 'image',
        'parameters' =>
        array (
          0 => '100',
          1 => '100',
        ),
      ),
      'detail' =>
      array (
        'format' => 'image',
        'parameters' =>
        array (
          0 => '100',
          1 => '100',
        ),
      ),
      'default' => '',
      'disable' => '1',
    ),
    1 =>
    array (
      'label' => 'Slug',
      'name' => 'slug',
      'field' =>
      array (
        'type' => 'slug',
      ),
      'validation' =>
      array (
        0 =>
        array (
          'method' => 'required',
          'message' => 'Slug is required',
        ),
        1 =>
        array (
          'method' => 'unique',
          'message' => 'Slug already exists',
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
      'searchable' => '1',
      'filterable' => '1',
      'disable' => '1',
    ),
    2 =>
    array (
      'label' => 'Name',
      'name' => 'name',
      'field' =>
      array (
        'type' => 'text',
      ),
      'validation' =>
      array (
        0 =>
        array (
          'method' => 'required',
          'message' => 'Name is required',
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
      'searchable' => '1',
      'disable' => '1',
    ),
    3 =>
    array (
      'label' => 'Description',
      'name' => 'description',
      'field' =>
      array (
        'type' => 'textarea',
      ),
      'validation' =>
      array (
        0 =>
        array (
          'method' => 'char_lte',
          'parameters' => '255',
          'message' => 'Must be less than 255 characters',
        ),
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
      'label' => 'Domain',
      'name' => 'domain',
      'field' =>
      array (
        'type' => 'text',
      ),
      'validation' =>
      array (
        0 =>
        array (
          'method' => 'required',
          'message' => 'Domain is required',
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
      'default' => '*',
      'disable' => '1',
    ),
    5 =>
    array (
      'label' => 'Website',
      'name' => 'website',
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
          'message' => 'Website url is required',
        ),
      ),
      'list' =>
      array (
        'format' => 'link',
        'parameters' =>
        array (
          0 => '{{app_website}}',
          1 => '{{app_website}}',
        ),
      ),
      'detail' =>
      array (
        'format' => 'link',
        'parameters' =>
        array (
          0 => '{{app_website}}',
          1 => '{{app_website}}',
        ),
      ),
      'default' => '',
      'disable' => '1',
    ),
    6 =>
    array (
      'label' => 'Token',
      'name' => 'token',
      'field' =>
      array (
        'type' => 'text',
      ),
      'validation' =>
      array (
        0 =>
        array (
          'method' => 'required',
          'message' => 'Token is required',
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
    7 =>
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
    8 =>
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
    9 =>
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
    10 =>
    array (
      'label' => 'Permissions',
      'name' => 'permissions',
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
    11 =>
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
    12 =>
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
    0 =>
    array (
      'many' => '1',
      'name' => 'auth',
    ),
    1 =>
    array (
    'many' => '1',
    'name' => 'webhook',
    ),
  ),
  'suggestion' => '{{app_name}} - {{app_slug}}',
  'disable' => '1',
);

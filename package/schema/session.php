<?php //-->
return array (
  'redirect_uri' => '/admin/system/schema/search',
  'singular' => 'Session',
  'plural' => 'Sessions',
  'name' => 'session',
  'icon' => 'fas fa-user-secret',
  'detail' => 'Collection of API Sessions',
  'fields' =>
  array (
    0 =>
    array (
      'label' => 'Code',
      'name' => 'code',
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
      'searchable' => '1',
      'disable' => '1',
    ),
    1 =>
    array (
      'label' => 'Token',
      'name' => 'token',
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
      'searchable' => '1',
      'disable' => '1',
    ),
    2 =>
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
    3 =>
    array (
      'label' => 'Status',
      'name' => 'status',
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
      'default' => 'PENDING',
      'searchable' => '1',
      'filterable' => '1',
      'disable' => '1',
    ),
    4 =>
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
      'searchable' => '1',
      'filterable' => '1',
      'disable' => '1',
    ),
    5 =>
    array (
      'label' => 'Expiry Date',
      'name' => 'expiry_date',
      'field' =>
      array (
        'type' => 'datetime',
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
    ),
    6 =>
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
    7 =>
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
    8 =>
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
      'name' => 'app',
    ),
    1 =>
    array (
      'many' => '1',
      'name' => 'auth',
    ),
  ),
  'suggestion' => '',
  'disable' => '1',
);

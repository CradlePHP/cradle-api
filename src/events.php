<?php //-->
/**
 * This file is part of Cradle API Package.
 * (c) 2018 Sterling Technologies.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

use Cradle\Storm\SqlFactory;

 /**
  * OAuth Permission Check
  *
  * @param Request $request
  * @param Request $response
  */
$cradle->on('rest-permitted', function ($request, $response) {
    // check permission type
    if ($request->hasStage('client_id')) {
        $this->trigger('rest-app-permitted', $request, $response);
    } else {
        $this->trigger('rest-session-permitted', $request, $response);
    }

    // if error
    if ($response->isError()) {
        return;
    }

    // if invalid roles
    $permissions = $request->get('source', 'app_permissions');
    if (!$permissions || empty($permissions)) {
        return $response->setError(true, 'Unauthorize Request');
    }

    // get role ids
    $roleIds = [];
    foreach ($permissions as $roleId => $permission) {
        $roleIds[] = '"' . $roleId . '"';
    }

    // get roles based on app_permissions
    $database = SqlFactory::load(cradle('global')->service('sql-main'));
    $roles = $database
        ->search('role')
        ->addFilter('role_id IN (' . implode(',', $roleIds) . ')')
        ->getRows();

    $method = strtolower($request->getMethod());
    $currentPath = $request->get('path', 'string');
    $authorized = false;

    // check if authorized
    foreach ($roles as $role) {
        // get app_permissions
        $appPermissions = $permissions[$role['role_id']];

        // format role_permissions
        if ($role['role_permissions']) {
            $role['role_permissions'] = json_decode($role['role_permissions'], true);
        } else {
            $role['role_permissions'] = [];
        }

        foreach ($role['role_permissions'] as $endpoint) {
            $condition = $currentPath == $endpoint['path'];

            if (strpos($endpoint['path'], '*') !== FALSE) {
                $path = str_replace('/', '\/', $endpoint['path']);
                $condition = preg_match('/' . $path . '/', $currentPath);
            }

            if ($method == $endpoint['method'] && $condition) {
                $authorized = true;
            }
        }
    }

    // if authorized
    if (!$authorized) {
        return $response->setError(true, 'Unauthorize Request');
    }

    return $response->setError(false);
});

/**
 * OAuth App Permission Check
 *
 * @param Request $request
 * @param Request $response
 */
$cradle->on('rest-app-permitted', function ($request, $response) {
    // must have client_id
    if (!$request->hasStage('client_id')) {
        return $response->setError(true, 'Unauthorize Request');
    }

    // get credentials
    $token = $request->getStage('client_id');
    $secret = $request->getStage('client_secret');

    // need secret for POST, PUT and DELETE methods
    if ($request->getMethod() !== 'GET' && !$secret) {
        return $response->setError(true, 'Unauthorize Request');
    }

    // query app
    $database = SqlFactory::load(cradle('global')->service('sql-main'));
    $search = $database
        ->search('app')
        ->setColumns('profile.*', 'app.*')
        ->innerJoinUsing('app_auth', 'app_id')
        ->innerJoinUsing('auth', 'auth_id')
        ->innerJoinUsing('auth_profile', 'auth_id')
        ->innerJoinUsing('profile', 'profile_id')
        ->filterByAppToken($token);

    // if not GET
    if ($secret) {
        $search->filterByAppSecret($secret);
    }

    $row = $search->getRow();

    // no app exists
    if (empty($row)) {
        return $response->setError(true, 'Unauthorize Request');
    }

    // format app_permissions
    if ($row['app_permissions']) {
        $row['app_permissions'] = json_decode($row['app_permissions'], true);
    } else {
        $row['app_permissions'] = [];
    }

    // set source data
    $request->set('source', $row);
    $request->set('source', 'type', 'app');
    $request->set('source', 'token', $token);
    $request->set('source', 'secret', $secret);

    return $response->setError(false);
});

/**
 * OAuth Session Permission Check
 *
 * @param Request $request
 * @param Request $response
 */
$cradle->on('rest-session-permitted', function ($request, $response) {
    // must have access_token
    if (!$request->hasStage('access_token')) {
        return $response->setError(true, 'Unauthorize Request');
    }

    // get credentials
    $token = $request->getStage('access_token');
    $secret = $request->getStage('access_secret');

    // need secret for POST, PUT and DELETE methods
    if ($request->getMethod() !== 'GET' && !$secret) {
        return $response->setError(true, 'Unauthorize Request');
    }

    // query session
    $database = SqlFactory::load(cradle('global')->service('sql-main'));
    $search = $database
        ->search('session')
        ->setColumns('session.*', 'profile.*', 'app.*')
        ->innerJoinUsing('session_app', 'session_id')
        ->innerJoinUsing('app', 'app_id')
        ->innerJoinUsing('session_auth', 'session_id')
        ->innerJoinUsing('auth_profile', 'auth_id')
        ->innerJoinUsing('auth', 'auth_id')
        ->innerJoinUsing('profile', 'profile_id')
        ->filterBySessionToken($token)
        ->filterBySessionStatus('AUTHORIZED');

    // if not GET
    if ($secret) {
        $search->filterBySessionSecret($secret);
    }

    $row = $search->getRow();

    // no session exists
    if (empty($row)) {
        return $response->setError(true, 'Unauthorize Request');
    }

    // format app_permissions
    if ($row['app_permissions']) {
        $row['app_permissions'] = json_decode($row['app_permissions'], true);
    } else {
        $row['app_permissions'] = [];
    }

    // set source data
    $request->set('source', $row);
    $request->set('source', 'type', 'session');
    $request->set('source', 'token', $token);
    $request->set('source', 'secret', $secret);

    return $response->setError(false);
});

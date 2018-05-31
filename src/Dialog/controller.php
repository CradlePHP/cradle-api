<?php //-->
/**
 * This file is part of Cradle API Package.
 * (c) 2018 Sterling Technologies.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

use Cradle\Http\Request;
use Cradle\Http\Response;
use Cradle\Package\System\Model\Service;

/**
 * Render the Signup Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/dialog/signup', function ($request, $response) {
    //redirect
    $query = http_build_query($request->get('get'));
    $redirect = urlencode('/dialog/oauth?' . $query);
    $this->package('global')->redirect('/auth/signup?redirect_uri='.$redirect);
});

/**
 * Render the Login Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/dialog/login', function ($request, $response) {
    //redirect
    $query = http_build_query($request->get('get'));
    $redirect = urlencode('/dialog/oauth?' . $query);
    $this->package('global')->redirect('/auth/login?redirect_uri='.$redirect);
});

/**
 * Render the Account Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/dialog/account', function ($request, $response) {
    //redirect
    $query = http_build_query($request->get('get'));
    $this->package('global')->redirect('/auth/account?'.$query);
});

/**
 * Render the Request Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/dialog/oauth', function ($request, $response) {
    //----------------------------//
    // 1. Route Permissions
    // for logged in
    $this->package('global')->requireLogin();

    // validate parameters
    if (!$request->hasStage('client_id') || !$request->hasStage('redirect_uri')) {
        $response->set('json', 'message', 'Invalid Parameters');
        return $this->routeTo('get', '/dialog/invalid', $request, $response);
    }

    // default response type
    $type = 'code';

    // validate parameters
    if ($request->hasStage('response_type')) {
        $type = $request->getStage('response_type');
    }

    // validate type
    if (!in_array($type, ['code', 'token'])) {
        $response->set('json', 'message', 'Invalid Response Type');
        return $this->routeTo('get', '/dialog/invalid', $request, $response);
    }

    //----------------------------//
    // 2. Prepare Data
    // get the app token
    $token = $request->getStage('client_id');

    // app request
    $appRequest = Request::i()->load();
    // app response
    $appResponse = Response::i()->load();

    // filter by app token
    $appRequest->setStage('filter', 'app_token', $token);
    // should be an active app
    $appRequest->setStage('filter', 'app_active', 1);
    // search the app by token
    $this->trigger('app-search', $appRequest, $appResponse);

    // get the app
    $app = $appResponse->getResults('rows', 0);

    // does app exists?
    if (!$app) {
        $response->set('json', 'message', 'Invalid Application');
        return $this->routeTo('get', '/dialog/invalid', $request, $response);
    }

    // get the app permissions
    $appPermissions = $app['app_permissions'];

    // empty app permissions?
    if (empty($appPermissions)) {
        $response->set('json', 'message', 'Invalid Permissions');
        return $this->routeTo('get', '/dialog/invalid', $request, $response);
    }

    // get the role ids
    $roles = array_keys($appPermissions);

    // get the roles
    $roles = Service::get('sql')
        ->getResource()
        ->search('role')
        ->addFilter('role_id IN %s', $roles)
        ->getRows();

    // if we don't have roles
    if (!$roles) {
        $response->set('json', 'message', 'Invalid Permissions');
        return $this->routeTo('get', '/dialog/invalid', $request, $response);
    }

    // collect permissions
    $permissions = [];

    // get the role permissions
    foreach($roles as $role) {
        // get the permission
        $permission = [];

        // try to parse permissions
        try {
            // get the permissions
            $permission = json_decode($role['role_permissions'], true);
        } catch(\Exception $e) {
            // set empty permissions
            $permission = [];
        }

        // if we have permission
        foreach($permission as $key => $value) {
            // set the role id
            $permission[$key]['role'] = $role['role_id'];

            // figure out the icons?
            // all methods?
            if ($value['method'] == 'all') {
                $permission[$key]['icon'] = 'fas fa-asterisk text-muted';

            // detail?
            } else if($value['method'] == 'get') {
                $permission[$key]['icon'] = 'fas fa-book text-muted';

            // delete?
            } else if($value['method'] == 'delete') {
                $permission[$key]['icon'] = 'fas fa-trash text-muted';

            // create or update?
            } else if($value['method'] == 'post' || $value['method'] == 'put') {
                $permission[$key]['icon'] = 'fas fa-pencil-alt text-muted';

            // unknown?
            } else {
                $permission['icon'] = 'fas fa-question text-muted';
            }

            // set the permission
            $permissions[$value['id']] = $permission[$key];
        }
    }

    // flatten app permissions
    $appPermissions = call_user_func_array('array_merge', $appPermissions);

    // filter out permissions
    foreach($permissions as $key => $value) {
        // not in app permissions?
        if (!in_array($key, $appPermissions)) {
            // remove it
            unset($permissions[$key]);
        }
    }

    //set data
    $data = [
        'permissions' => $permissions,
        'app' => $app
    ];

    //add CSRF
    $this->trigger('csrf-load', $request, $response);
    $data['csrf'] = $response->getResults('csrf');

    if ($response->isError()) {
        $response->setFlash($response->getMessage(), 'error');
        $data['errors'] = $response->getValidation();
    }

    //----------------------------//
    // 3. Render Template
    $class = 'page-dialog-request';
    $title = $this->package('global')->translate('Authorize Application');
    $body = cradle('cradlephp/cradle-api')->template('Dialog', 'oauth', $data);

    // set content
    $response
        ->setPage('title', $title)
        ->setPage('class', $class)
        ->setContent($body);

    // render dialog page
    $this->trigger('rest-render-dialog-page', $request, $response);
});

/**
 * Process the Request Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->post('/dialog/oauth', function ($request, $response) {
    //----------------------------//
    // 1. Route Permissions
    //for logged in
    $this->package('global')->requireLogin();

    //csrf check
    $this->trigger('csrf-validate', $request, $response);

    // csrf error?
    if ($response->isError()) {
        return $this->routeTo('get', '/dialog/invalid', $request, $response);
    }

    // validate parameters
    if (!$request->hasStage('client_id') || !$request->hasStage('redirect_uri')) {
        return $this->routeTo('get', '/dialog/invalid', $request, $response);
    }

    // default response type
    $type = 'code';

    // validate parameters
    if ($request->hasStage('response_type')) {
        $type = $request->getStage('response_type');
    }

    // validate type
    if (!in_array($type, ['code', 'token'])) {
        $response->set('json', 'message', 'Invalid Response Type');
        return $this->routeTo('get', '/dialog/invalid', $request, $response);
    }

    // denied access?
    if ($request->getStage('action') !== 'allow') {
        //redirect
        $url = $request->getStage('redirect_uri');
        $this->getDispatcher()->redirect($url . '?error=deny');
    }

    //----------------------------//
    // 2. Prepare Data
    // get the app token
    $token = $request->getStage('client_id');
    // set the schema
    $request->setStage('schema', 'app');
    // set filter by app token
    $request->setStage('filter', 'app_token', $token);
    // should be an active app
    $request->setStage('filter', 'app_active', 1);

    // search the app
    $this->trigger('app-search', $request, $response);
    // get the results
    $app = $response->getResults('rows', 0);

    // app does not exists?
    if (!$app) {
        $response->set('json', 'message', 'Invalid Application');
        return $this->routeTo('get', '/dialog/invalid', $request, $response);
    }

    // link to the auth id
    $request->setStage('auth_id', $request->getSession('me', 'auth_id'));
    // link to the app id
    $request->setStage('app_id', $app['app_id']);

    // flatten permissions
    $permissions = $request->getStage('session_permissions');

    // encode permissions
    try {
        $permissions = json_encode($permissions);
    } catch(\Exception $e) {
        $permissions = '[]';
    }

    // set the permissions
    $request->setStage('session_permissions', $permissions);

    // compute expiry, 3 hours expiry
    $expiry = date('Y-m-d H:i:s', strtotime('+3 hours', time()));

    // set the expiry date
    $request->setStage('session_expiry_date', $expiry);

    // default redirect
    $redirect = null;
    // generate hash for token / code
    $hash = md5(uniqid() . uniqid());;

    // if request type token
    if ($type == 'token') {
        // set redirect url
        $redirect = sprintf(
            $request->getStage('redirect_uri') . '#%s',
            $hash
        );

        // create the session token
        $request->setStage('session_token', $hash);
        // set session status as authorized
        $request->setStage('session_status', 'AUTHORIZED');
        // set session type as token
        $request->setStage('session_type', 'token');
    } else {
        // set redirect url
        $redirect = sprintf(
            $request->getStage('redirect_uri') . '?code=%s',
            $hash
        );

        // create the session code
        $request->setStage('session_code', $hash);
        // set session status as pending
        $request->setStage('session_status', 'PENDING');
        // set session type as code
        $request->setStage('session_type', 'code');
    }

    //----------------------------//
    // 3. Process Request
    $this->trigger('session-create', $request, $response);

    //----------------------------//
    // 4. Interpret Results
    if ($response->isError()) {
        return $this->routeTo('get', '/dialog/invalid', $request, $response);
    }

    //it was good
    //redirect
    $this->getDispatcher()->redirect($redirect);
});

/**
 * Process the Logout
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/dialog/logout', function ($request, $response) {
    //redirect
    //TODO: Find better way
    $query = http_build_query($request->get('get'));
    $this->package('global')->redirect('/auth/logout?'.$query);
});

/**
 * Render the Invalid Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/dialog/invalid', function ($request, $response) {
    //----------------------------//
    // 1. Route Permissions
    //not needed
    //----------------------------//
    // 2. Prepare Data
    //prepare data
    $data = [];
    if ($response->hasJson()) {
        $data = $response->getJson();
    }

    //----------------------------//
    // 3. Render Template
    $class = 'page-dialog-invalid';
    $title = $this->package('global')->translate('Invalid Request');
    $body = cradle('cradlephp/cradle-api')->template('Dialog', 'invalid', $data);

    // set content
    $response
        ->setPage('title', $title)
        ->setPage('class', $class)
        ->setContent($body);

    // render dialog page
    $this->trigger('rest-render-dialog-page', $request, $response);
});

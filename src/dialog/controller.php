<?php //-->
/**
 * This file is part of a package designed for the CradlePHP Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

/**
 * Render the Request Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/dialog/request', function ($request, $response) {
    $global = $this->package('global');
    //for logged in
    $global->requireLogin();

    if ($response->isError()) {
        $response->setFlash($response->getMessage(), 'error');
        $data['errors'] = $response->getValidation();
    }

    //validate parameters
    if (!$request->hasStage('client_id') || !$request->hasStage('redirect_uri')) {
        return $this->routeTo('get', '/dialog/invalid', $request, $response);
    }

    //get app detail
    $token = $request->getStage('client_id');
    $request->setStage('schema', 'app');
    $request->setStage('app_token', $token);
    $this->trigger('system-model-detail', $request, $response);

    //get the app
    $app = $response->getResults();

    if (empty($app)) {
        return $this->routeTo('get', '/dialog/invalid', $request, $response);
    }

    $permitted = $app['scope'];

    $requested = [];
    if ($request->hasStage('scope')) {
        $requested = explode(',', $request->getStage('scope'));
    }

    //the final permission set
    $permissions = [];
    foreach ($app['scope'] as $scope) {
        //if this is not a user scope
        if ($scope['scope_type'] !== 'user') {
            continue;
        }

        //if this scope is being requested for
        if (!in_array($scope['scope_slug'], $requested)) {
            continue;
        }

        $permissions[$scope['scope_slug']] = $scope;
    }

    //Prepare body
    $data = [
        'permissions' => $permissions,
        'app' => $app
    ];

    //if we only want the raw data
    if ($request->getStage('render') === 'false') {
        return $response->setResults($data);
    }

    //add CSRF
    $this->trigger('csrf-load', $request, $response);
    $data['csrf'] = $response->getResults('csrf');

    //Render body
    $class = 'page-dialog-request page-dialog';
    $title = $global->translate('Request Access');

    $template = __DIR__ . '/template';
    if (is_dir($response->getPage('template_root'))) {
        $template = $response->getPage('template_root');
    }

    $partials = __DIR__ . '/template';
    if (is_dir($response->getPage('partials_root'))) {
        $partials = $response->getPage('partials_root');
    }

    $body = $this
        ->package('cradlephp/cradle-system')
        ->template(
            'request',
            $data,
            [],
            $template,
            $partials
        );

    //Set Content
    $response
        ->setPage('title', $title)
        ->setPage('class', $class)
        ->setContent($body);

    //if we only want the body
    if ($request->getStage('render') === 'body') {
        return;
    }

    //render page
    $this->trigger('www-render-blank', $request, $response);
});

/**
 * Process the Request Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->post('/dialog/request', function ($request, $response) {
    $global = $this->package('global');
    //for logged in
    $global->requireLogin();

    //csrf check
    $this->trigger('csrf-validate', $request, $response);

    if ($response->isError()) {
        return $this->routeTo('get', '/dialog/invalid', $request, $response);
    }

    //validate parameters
    if (!$request->hasStage('client_id') || !$request->hasStage('redirect_uri')) {
        return $this->routeTo('get', '/dialog/invalid', $request, $response);
    }

    if ($request->getStage('action') !== 'allow') {
        //redirect
        $url = $request->getStage('redirect_uri');
        return $global->redirect($url . '?error=deny');
    }

    //get the profile
    $profile = $request->getSession('me');

    //get the app
    $token = $request->getStage('client_id');
    $request->setStage('schema', 'app');
    $request->setStage('app_token', $token);
    $this->trigger('system-model-detail', $request, $response);
    $app = $response->getResults();

    //next we need to get the permissions from the form submission
    $permissions = $request->getStage('permissions');
    if (!is_array($permissions)) {
        $permissions = [];
    }

    //loop through the scopes
    foreach ($app['scope'] as $scope) {
        //if this is not a user scope
        if ($scope['scope_type'] === 'user') {
            continue;
        }

        //even if it wasn't requested for, let's just give the access
        $permissions[] = $scope['scope_id'];
    }

    //then stuff the permission back into stage
    $request->setStage('permissions', $permissions);

    //now call the create job
    $request->setStage('schema', 'session');
    $request->setStage('profile_id', $profile['profile_id']);
    $request->setStage('app_id', $app['app_id']);

    $this->trigger('system-model-create', $request, $response);

    if ($response->isError()) {
        return $this->routeTo('get', '/dialog/invalid', $request, $response);
    }

    //it was good

    //redirect
    $url = $request->getStage('redirect_uri');
    $code = $response->getResults('session_token');
    $global->redirect($url . '?code=' . $code);
});

/**
 * Render the Invalid Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/dialog/invalid', function ($request, $response) {
    //prepare data
    $data = [];
    if ($response->hasJson()) {
        $data = $response->getJson();
    }

    //Render body
    $class = 'page-dialog-invalid page-dialog';
    $title = $this->package('global')->translate('Invalid Request');

    $template = __DIR__ . '/template';
    if (is_dir($response->getPage('template_root'))) {
        $template = $response->getPage('template_root');
    }

    $partials = __DIR__ . '/template';
    if (is_dir($response->getPage('partials_root'))) {
        $partials = $response->getPage('partials_root');
    }

    $body = $this
        ->package('cradlephp/cradle-system')
        ->template(
            'invalid',
            $data,
            [],
            $template,
            $partials
        );

    //Set Content
    $response
        ->setPage('title', $title)
        ->setPage('class', $class)
        ->setContent($body);

    //render page
    $this->trigger('www-render-blank', $request, $response);
});

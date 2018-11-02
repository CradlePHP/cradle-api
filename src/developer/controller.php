<?php //-->
/**
 * This file is part of a package designed for the CradlePHP Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

use Cradle\Package\System\Schema;

/**
 * Render the REST Documentaion Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/developer/docs/calls', function ($request, $response) {
    $request->setStage('schema', 'rest');
    $this->trigger('system-model-search', $request, $response);

    //if no rendering
    if ($request->getStage('render') === 'false') {
        return;
    }

    $redirect = '/';
    if ($request->getStage('redirect_uri')) {
        $redirect = $request->getStage('redirect_uri');
    }

    //if there's an error
    if ($response->isError()) {
        $this->package('global')->flash($response->getMessage(), 'error');
        $this->package('global')->redirect($redirect);
    }

    $data = $response->getResults();
    $data['schema'] = Schema::i('rest')->getAll();

    $class = 'page-developer-doc-calls page-developer';
    $data['title'] = $this->package('global')->translate(
        '%s - API Documentation', $data['schema']['plural']
    );

    $template = __DIR__ . '/template';
    if (is_dir($response->getPage('template_root'))) {
        $template = $response->getPage('template_root');
    }

    $partials = __DIR__ . '/template';
    if (is_dir($response->getPage('partials_root'))) {
        $partials = $response->getPage('partials_root');
    }

    $body = cradle('cradlephp/cradle-system')->template(
        'docs/calls',
        $data,
        [],
        $template,
        $partials
    );

    //Set Content
    $response
        ->setPage('title', $data['title'])
        ->setPage('class', $class)
        ->setContent($body);

    //if we only want the body
    if ($request->getStage('render') === 'body') {
        return;
    }

    //render page
    $this->trigger('www-render-page', $request, $response);
});

/**
 * Render the REST Documentaion Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/developer/docs/calls/:rest_id', function ($request, $response) {
    $request->setStage('schema', 'rest');
    $this->trigger('system-model-detail', $request, $response);

    //if no rendering
    if ($request->getStage('render') === 'false') {
        return;
    }

    $redirect = '/';
    if ($request->getStage('redirect_uri')) {
        $redirect = $request->getStage('redirect_uri');
    }

    //if there's an error
    if ($response->isError()) {
        $this->package('global')->flash($response->getMessage(), 'error');
        $this->package('global')->redirect($redirect);
    }

    $data = $response->getResults();
    $data['schema'] = Schema::i('rest')->getAll();

    $class = 'page-developer-doc-call page-developer';
    $data['title'] = $response->getResults('rest_title');
    $data['title'] = $this->package('global')->translate(
        '%s - API Documentation', $data['title']
    );

    $template = __DIR__ . '/template';
    if (is_dir($response->getPage('template_root'))) {
        $template = $response->getPage('template_root');
    }

    $partials = __DIR__ . '/template';
    if (is_dir($response->getPage('partials_root'))) {
        $partials = $response->getPage('partials_root');
    }

    $body = cradle('cradlephp/cradle-system')->template(
        'docs/call',
        $data,
        [],
        $template,
        $partials
    );

    //Set Content
    $response
        ->setPage('title', $data['title'])
        ->setPage('class', $class)
        ->setContent($body);

    //if we only want the body
    if ($request->getStage('render') === 'body') {
        return;
    }

    //render page
    $this->trigger('www-render-page', $request, $response);
});

/**
 * Render the Scope Documentaion Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/developer/docs/scopes', function ($request, $response) {
    $request->setStage('schema', 'scope');
    $this->trigger('system-model-search', $request, $response);

    //if no rendering
    if ($request->getStage('render') === 'false') {
        return;
    }

    $redirect = '/';
    if ($request->getStage('redirect_uri')) {
        $redirect = $request->getStage('redirect_uri');
    }

    //if there's an error
    if ($response->isError()) {
        $this->package('global')->flash($response->getMessage(), 'error');
        $this->package('global')->redirect($redirect);
    }

    $data = $response->getResults();
    $data['schema'] = Schema::i('scope')->getAll();

    $class = 'page-developer-doc-scopes page-developer';
    $data['title'] = $this->package('global')->translate(
        '%s - API Documentation', $data['schema']['plural']
    );

    $template = __DIR__ . '/template';
    if (is_dir($response->getPage('template_root'))) {
        $template = $response->getPage('template_root');
    }

    $partials = __DIR__ . '/template';
    if (is_dir($response->getPage('partials_root'))) {
        $partials = $response->getPage('partials_root');
    }

    $body = cradle('cradlephp/cradle-system')->template(
        'docs/scopes',
        $data,
        [],
        $template,
        $partials
    );

    //Set Content
    $response
        ->setPage('title', $data['title'])
        ->setPage('class', $class)
        ->setContent($body);

    //if we only want the body
    if ($request->getStage('render') === 'body') {
        return;
    }

    //render page
    $this->trigger('www-render-page', $request, $response);
});

/**
 * Render the Scope Documentaion Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/developer/docs/scopes/:scope_slug', function ($request, $response) {
    $request->setStage('schema', 'scope');
    $this->trigger('system-model-detail', $request, $response);

    //if no rendering
    if ($request->getStage('render') === 'false') {
        return;
    }

    $redirect = '/';
    if ($request->getStage('redirect_uri')) {
        $redirect = $request->getStage('redirect_uri');
    }

    //if there's an error
    if ($response->isError()) {
        $this->package('global')->flash($response->getMessage(), 'error');
        $this->package('global')->redirect($redirect);
    }

    $data = $response->getResults();
    $data['schema'] = Schema::i('scope')->getAll();

    $class = 'page-developer-doc-scope page-developer';
    $data['title'] = $response->getResults('scope_name');
    $data['title'] = $this->package('global')->translate(
        '%s - API Documentation', $data['title']
    );

    $template = __DIR__ . '/template';
    if (is_dir($response->getPage('template_root'))) {
        $template = $response->getPage('template_root');
    }

    $partials = __DIR__ . '/template';
    if (is_dir($response->getPage('partials_root'))) {
        $partials = $response->getPage('partials_root');
    }

    $body = cradle('cradlephp/cradle-system')->template(
        'docs/scope',
        $data,
        [],
        $template,
        $partials
    );

    //Set Content
    $response
        ->setPage('title', $data['title'])
        ->setPage('class', $class)
        ->setContent($body);

    //if we only want the body
    if ($request->getStage('render') === 'body') {
        return;
    }

    //render page
    $this->trigger('www-render-page', $request, $response);
});

/**
 * Render the Webhook Documentaion Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/developer/docs/webhooks', function ($request, $response) {
    $request->setStage('schema', 'webhook');
    $this->trigger('system-model-search', $request, $response);

    //if no rendering
    if ($request->getStage('render') === 'false') {
        return;
    }

    $redirect = '/';
    if ($request->getStage('redirect_uri')) {
        $redirect = $request->getStage('redirect_uri');
    }

    //if there's an error
    if ($response->isError()) {
        $this->package('global')->flash($response->getMessage(), 'error');
        $this->package('global')->redirect($redirect);
    }

    $data = $response->getResults();
    $data['schema'] = Schema::i('webhook')->getAll();

    $class = 'page-developer-doc-webhooks page-developer';
    $data['title'] = $this->package('global')->translate(
        '%s - API Documentation', $data['schema']['plural']
    );

    $template = __DIR__ . '/template';
    if (is_dir($response->getPage('template_root'))) {
        $template = $response->getPage('template_root');
    }

    $partials = __DIR__ . '/template';
    if (is_dir($response->getPage('partials_root'))) {
        $partials = $response->getPage('partials_root');
    }

    $body = cradle('cradlephp/cradle-system')->template(
        'docs/webhooks',
        $data,
        [],
        $template,
        $partials
    );

    //Set Content
    $response
        ->setPage('title', $data['title'])
        ->setPage('class', $class)
        ->setContent($body);

    //if we only want the body
    if ($request->getStage('render') === 'body') {
        return;
    }

    //render page
    $this->trigger('www-render-page', $request, $response);
});

/**
 * Render the Webhook Documentaion Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/developer/docs/webhooks/:webhook_id', function ($request, $response) {
    $request->setStage('schema', 'webhook');
    $this->trigger('system-model-detail', $request, $response);

    //if no rendering
    if ($request->getStage('render') === 'false') {
        return;
    }

    $redirect = '/';
    if ($request->getStage('redirect_uri')) {
        $redirect = $request->getStage('redirect_uri');
    }

    //if there's an error
    if ($response->isError()) {
        $this->package('global')->flash($response->getMessage(), 'error');
        $this->package('global')->redirect($redirect);
    }

    $data = $response->getResults();
    $data['schema'] = Schema::i('webhook')->getAll();

    $class = 'page-developer-doc-webhook page-developer';
    $data['title'] = $response->getResults('webhook_title');
    $data['title'] = $this->package('global')->translate(
        '%s - API Documentation', $data['title']
    );

    $template = __DIR__ . '/template';
    if (is_dir($response->getPage('template_root'))) {
        $template = $response->getPage('template_root');
    }

    $partials = __DIR__ . '/template';
    if (is_dir($response->getPage('partials_root'))) {
        $partials = $response->getPage('partials_root');
    }

    $body = cradle('cradlephp/cradle-system')->template(
        'docs/webhook',
        $data,
        [],
        $template,
        $partials
    );

    //Set Content
    $response
        ->setPage('title', $data['title'])
        ->setPage('class', $class)
        ->setContent($body);

    //if we only want the body
    if ($request->getStage('render') === 'body') {
        return;
    }

    //render page
    $this->trigger('www-render-page', $request, $response);
});

/**
 * Render the App Search Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/developer/app/search', function ($request, $response) {
    $this->package('global')->requireLogin();

    //----------------------------//
    // 1. Prepare Data
    $profile = $request->getSession('me', 'profile_id');
    $render = $request->getStage('render');

    $request
        ->setStage('schema', 'app')
        ->setStage('render', 'body')
        ->setStage('filter', 'profile_id', $profile);

    $response
        ->setPage('template_root', __DIR__ . '/template')
        ->setPage('partials_root', __DIR__ . '/template');

    $this->routeTo('get', '/admin/system/model/app/search', $request, $response);

    //put back the render state
    $request->setStage('render', $render);

    //if we only want the data
    if ($request->getStage('render') === 'false') {
        return;
    }

    //if we only want the body
    if ($request->getStage('render') === 'body') {
        return;
    }

    //render page
    $this->trigger('www-render-page', $request, $response);
});

/**
 * Render the App Create Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/developer/app/create', function ($request, $response) {
    $this->package('global')->requireLogin();

    //----------------------------//
    // 1. Prepare Data
    $render = $request->getStage('render');

    $request
        ->setStage('schema', 'app')
        ->setStage('render', 'body');

    $response
        ->setPage('template_root', __DIR__ . '/template')
        ->setPage('partials_root', __DIR__ . '/template');

    $this->routeTo('get', '/admin/system/model/app/create', $request, $response);

    //put back the render state
    $request->setStage('render', $render);

    //if we only want the data
    if ($request->getStage('render') === 'false') {
        return;
    }

    //if we only want the body
    if ($request->getStage('render') === 'body') {
        return;
    }

    //render page
    $this->trigger('www-render-page', $request, $response);
});

/**
 * Render the App Update Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/developer/app/update/:app_id', function ($request, $response) {
    $this->package('global')->requireLogin();

    //----------------------------//
    // 1. Prepare Data
    $render = $request->getStage('render');

    $request
        ->setStage('schema', 'app')
        ->setStage('render', 'body');

    $response
        ->setPage('template_root', __DIR__ . '/template')
        ->setPage('partials_root', __DIR__ . '/template');

    $route = sprintf(
        '/admin/system/model/app/update/%s',
        $request->getStage('app_id')
    );

    $this->routeTo('get', $route, $request, $response);

    //put back the render state
    $request->setStage('render', $render);

    //if we only want the data
    if ($request->getStage('render') === 'false') {
        return;
    }

    //if we only want the body
    if ($request->getStage('render') === 'body') {
        return;
    }

    //render page
    $this->trigger('www-render-page', $request, $response);
});

/**
 * Process App Remove
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/developer/app/remove/:app_id', function ($request, $response) {
    $request
        ->setStage('schema', 'app')
        ->setStage('redirect_uri', '/developer/app/search');

    $route = sprintf(
        '/admin/system/model/app/remove/%s',
        $request->getStage('app_id')
    );

    $this->routeTo('get', $route, $request, $response);
});

/**
 * Process App Refresh
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/developer/app/refresh/:app_id', function ($request, $response) {
    $request
        ->setStage('schema', 'app')
        ->setStage('redirect_uri', '/developer/app/search');

    $route = sprintf(
        '/admin/system/model/app/refresh/%s',
        $request->getStage('app_id')
    );

    $this->routeTo('get', $route, $request, $response);
});

/**
 * Process the App Create Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->post('/developer/app/create', function ($request, $response) {
    $this->package('global')->requireLogin();

    //----------------------------//
    // 1. Prepare Data
    $profile = $request->getSession('me', 'profile_id');

    $request
        ->setStage('schema', 'app')
        ->setStage('route', '/developer/app/create')
        ->setStage('profile_id', $profile)
        ->setStage('redirect_uri', '/developer/app/search');

    $this->routeTo('post', '/admin/system/model/app/create', $request, $response);
});

/**
 * Process the App Update Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->post('/developer/app/update/:app_id', function ($request, $response) {
    $this->package('global')->requireLogin();

    //----------------------------//
    // 1. Prepare Data
    $route = sprintf(
        '/admin/system/model/app/update/%s',
        $request->getStage('app_id')
    );

    $request
        ->setStage('schema', 'app')
        ->setStage('route', $route)
        ->setStage('redirect_uri', '/developer/app/search')
        ->removeStage('profile_id');

    $this->routeTo('post', $route, $request, $response);
});

<?php //-->
/**
 * This file is part of Cradle API Package.
 * (c) 2018 Sterling Technologies.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

/**
 * Renders a create form
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/admin/webhook/create', function ($request, $response) {
    //----------------------------//
    // 1. Prepare Data
    $data = ['item' => $request->getPost()];

    // if has copy
    if ($request->hasStage('copy')) {
        // set the webhook id
        $request->setStage('webhook_id', $request->getStage('copy'));

        // get webhook detail
        $this->trigger('webhook-detail', $request, $response);

        // get the item
        $data['item'] = $response->getResults();

        // let them copy the default webhooks
        if (isset($data['item']['webhook_flag'])) {
            unset($data['item']['webhook_flag']);
        }
    }

    if ($response->isError()) {
        $response->setFlash($response->getMessage(), 'error');
        $data['errors'] = $response->getValidation();
    }

    $this->trigger('system-schema-search', $request, $response);
    $data['schemas'] = $response->getResults('rows');

    //----------------------------//
    // 2. Render Template
    //Render body
    $class = 'page-webhook-create';
    $data['title'] = $this->package('global')->translate('Webhook Create');
    $data['action'] = 'create';

    $body = $this
        ->package('cradlephp/cradle-webhook')
        ->template('form', $data, [
            'webhook_permission'
        ]);

    //Set Content
    $response
        ->setPage('title', $data['title'])
        ->setPage('class', $class)
        ->setContent($body);

    //if we only want the body
    if ($request->getStage('render') === 'body') {
        return;
    }

    //Render blank page
    $this->trigger('admin-render-page', $request, $response);
});

/**
 * Overrides model webhook create form
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/admin/system/model/webhook/create', function ($request, $response) {
    // do not render
    $request->setStage('render', 'false');

    // re-route to our custom handler
    return $this->routeTo('get', '/admin/webhook/create', $request, $response);
});

/**
 * Renders a create form
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/admin/webhook/detail/:webhook_id', function ($request, $response) {
    //----------------------------//
    //set redirect
    $request->setStage('redirect_uri', '/admin/webhook/search');

    //now let the object detail take over
    $this->routeTo(
        'get',
        sprintf(
            '/admin/system/model/webhook/detail/%s',
            $request->getStage('webhook_id')
        ),
        $request,
        $response
    );
});

/**
 * Removes a webhook
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/admin/webhook/remove/:webhook_id', function ($request, $response) {
    //----------------------------//
    // 2. Prepare Data
    // trigger webhook detail
    $this->trigger('webhook-detail', $request, $response);

    // get webhook details
    $data['item'] = $response->getResults();

    // not removable
    if ($data['item']['webhook_flag'] == 1) {
        //add a flash
        $this->package('global')->flash('Invalid Action', 'error');
        //redirect
        return $this->package('global')->redirect('/admin/webhook/search');
    }

    //----------------------------//
    // 3. Process Request
    $this->trigger('webhook-remove', $request, $response);

    //----------------------------//
    // 4. Interpret Results
    if ($response->isError()) {
        //add a flash
        $this->package('global')->flash($response->getMessage(), 'error');
    } else {
        //add a flash
        $message = cradle('global')->translate('Webhook was Removed');
        $this->package('global')->flash($message, 'success');
    }

    $this->package('global')->redirect('/admin/webhook/search');
});

/**
 * Restores a webhook
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/admin/webhook/restore/:webhook_id', function ($request, $response) {
    // 1. Process Request
    $this->trigger('webhook-restore', $request, $response);

    //----------------------------//
    // 2. Interpret Results
    if ($response->isError()) {
        //add a flash
        $this->trigger('global')->flash($response->getMessage(), 'error');
    } else {
        //add a flash
        $message = cradle('global')->translate('Webhook was Restored');
        $this->package('global')->flash($message, 'success');
    }

    $this->package('global')->redirect('/admin/webhook/search');
});

/**
 * Renders a search page
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/admin/webhook/search', function ($request, $response) {
    //----------------------------//
    // 1. Prepare data
    if (!$request->hasStage('filter')) {
        $request->setStage('filter', 'webhook_active', 1);
    }

    //trigger job
    $this->trigger('webhook-search', $request, $response);

    //if we only want the raw data
    if ($request->getStage('render') === 'false') {
        return;
    }

    $data = array_merge($request->getStage(), $response->getResults());

    //----------------------------//
    // 2. Render Template
    //Render body
    $class = 'page-webhook-search';
    $title = $this->package('global')->translate('Webhook Search');

    $body = $this
        ->package('cradlephp/cradle-webhook')
        ->template('search', $data);

    //Set Content
    $response
        ->setPage('title', $title)
        ->setPage('class', $class)
        ->setContent($body);

    //if we only want the body
    if ($request->getStage('render') === 'body') {
        return;
    }

    //Render blank page
    $this->trigger('admin-render-page', $request, $response);
});

/**
 * Renders an update form
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/admin/webhook/update/:webhook_id', function ($request, $response) {
    //----------------------------//
    // 1. Prepare Data
    // trigger webhook detail
    $this->trigger('webhook-detail', $request, $response);

    //can we update ?
    if ($response->isError()) {
        //redirect
        $redirect = '/admin/webhook/search';

        //this is for flexibility
        if ($request->hasStage('redirect_uri')) {
            $redirect = $request->getStage('redirect_uri');
        }

        //add a flash
        $this->package('global')->flash($response->getMessage(), 'error');
        return $this->package('global')->redirect($redirect);
    }

    // get webhook details
    $data['item'] = $response->getResults();

    if (!empty($request->getPost())) {
        // get post stored as item
        $data['item'] = $request->getPost();

        // get any errors
        $data['errors'] = $response->getValidation();
    }

    $this->trigger('system-schema-search', $request, $response);
    $data['schemas'] = $response->getResults('rows');

    //----------------------------//
    // 2. Render Template
    //Render body
    $class = 'page-webhook-update';
    $data['title'] = $this->package('global')->translate('Webhook Update');

    $body = $this
        ->package('cradlephp/cradle-webhook')
        ->template('form', $data, [
            'webhook_permission'
        ]);

    //Set Content
    $response
        ->setPage('title', $data['title'])
        ->setPage('class', $class)
        ->setContent($body);

    //if we only want the body
    if ($request->getStage('render') === 'body') {
        return;
    }

    //Render blank page
    $this->trigger('admin-render-page', $request, $response);
});

/**
 * Overrides model webhook update form
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/admin/system/model/webhook/update/:webhook_id', function ($request, $response) {
    // do not render
    $request->setStage('render', 'false');

    // re-route to our custom handler
    return $this->routeTo(
        'get',
        sprintf(
            '/admin/webhook/update/%s',
            $request->getStage('webhook_id')
        ),
        $request,
        $response
    );
});

/**
 * Processes a create form
 *
 * @param Request $request
 * @param Response $response
 */
$this->post('/admin/webhook/create', function ($request, $response) {
    //----------------------------//
    // 1. Prepare Data
    // nothing to prepare
    //----------------------------//
    // 2. Process Request
    $this->trigger('webhook-create', $request, $response);

    //----------------------------//
    // 4. Interpret Results
    if ($response->isError()) {
        //add a flash
        $this->package('global')->flash('Invalid Data', 'error');
        return $this->routeTo('get', '/admin/webhook/create', $request, $response);
    }

    //it was good
    //add a flash
    $this->package('global')->flash('Webhook was Created', 'success');

    //redirect
    $this->package('global')->redirect('/admin/webhook/search');
});

/**
 * Processes an update form
 *
 * @param Request $request
 * @param Response $response
 */
$this->post('/admin/webhook/update/:webhook_id', function ($request, $response) {
    //----------------------------//
    // 1. Process Request
    $this->trigger('webhook-update', $request, $response);

    //----------------------------//
    // 2. Interpret Results
    if ($response->isError()) {
        $route = '/admin/webhook/update/' . $request->getStage('webhook_id');
        return $this->routeTo('get', $route, $request, $response);
    }

    //it was good
    //add a flash
    $this->package('global')->flash('Webhook was Updated', 'success');

    //redirect
    $this->package('global')->redirect('/admin/webhook/search');
});

/**
 * Subscription receipt receiver
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/webhook/:app_id/subscription/:hash', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    $data = $request->getStage();
    //----------------------------//
    // 1. Validate Data
    $this->trigger('app-detail', $request, $response);

    if ($response->isError()) {
        return $response;
    }

    $webhook = $response->getResults();
    if ($data['hash'] != md5($webhook['app_updated'])) {
        return $response->setError(true, 'invalid subscription');
    }
    
    //----------------------------//
    // 2. Process Data
    $request->setStage('app_webhook_flag', 1);
    $this->trigger('app-update', $request, $response);
});

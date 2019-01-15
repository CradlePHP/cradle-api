<?php //-->
/**
 * This file is part of Cradle API Package.
 * (c) 2018 Sterling Technologies.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

use Cradle\Package\System\Schema;

/**
 * Render App Search Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/admin/system/model/app/search', function ($request, $response) {
    if (!$response->hasPage('template_root')) {
        $response->setPage('template_root', __DIR__ . '/template');
    }

    if (!$response->hasPage('partials_root')) {
        $response->setPage('partials_root', __DIR__ . '/template');
    }
});

/**
 * Render App Create Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/admin/system/model/app/create', function ($request, $response) {
    //get all the REST calls
    $payload = $this->makePayload();

    $payload['request']
        ->setStage('schema', 'scope')
        ->setStage('range', 0);

    $this->trigger(
        'system-model-search',
        $payload['request'],
        $payload['response']
    );

    $scopes = $payload['response']->getResults();

    //get all the webhooks
    $payload['request']->setStage('schema', 'webhook');

    $this->trigger(
        'system-model-search',
        $payload['request'],
        $payload['response']
    );

    $hooks = $payload['response']->getResults();

    $request
        ->set('partial', 'scopes', $scopes)
        ->set('partial', 'hooks', $hooks);

    if (!$response->hasPage('template_root')) {
        $response->setPage('template_root', __DIR__ . '/template');
    }

    if (!$response->hasPage('partials_root')) {
        $response->setPage('partials_root', __DIR__ . '/template');
    }
}, 10);

/**
 * Render App Create Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/admin/system/model/app/update/:app_id', function ($request, $response) {
    //get all the REST calls
    $payload = $this->makePayload();

    $payload['request']
        ->setStage('schema', 'scope')
        ->setStage('range', 0);

        $this->trigger(
            'system-model-search',
            $payload['request'],
            $payload['response']
        );

    $scopes = $payload['response']->getResults();

    //get all the webhooks
    //get all the webhooks
    $payload['request']->setStage('schema', 'webhook');

    $this->trigger(
        'system-model-search',
        $payload['request'],
        $payload['response']
    );

    $hooks = $payload['response']->getResults();

    $request
        ->set('partial', 'scopes', $scopes)
        ->set('partial', 'hooks', $hooks);

    if (!$response->hasPage('template_root')) {
        $response->setPage('template_root', __DIR__ . '/template');
    }

    if (!$response->hasPage('partials_root')) {
        $response->setPage('partials_root', __DIR__ . '/template');
    }
}, 10);

/**
 * Process App Refresh
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/admin/system/model/app/refresh/:app_id', function ($request, $response) {
    //----------------------------//
    // 1. Prepare Data
    $request
        ->setStage('schema', 'app')
        ->setStage('app_token', md5(uniqid()))
        ->setStage('app_secret', md5(uniqid()));

    //----------------------------//
    // 2. Process Request
    $this->trigger('system-model-update', $request, $response);

    //----------------------------//
    // 3. Interpret Results
    if (!$response->isError()) {
        //record logs
        $this->log(
            sprintf(
                'refreshed App #%s',
                $request->getStage('app_id')
            ),
            $request,
            $response,
            'update',
            'app',
            $request->getStage('app_id')
        );
    }

    //redirect
    $redirect = '/admin/system/model/app/search';

    //if there is a specified redirect
    if ($request->getStage('redirect_uri')) {
        //set the redirect
        $redirect = $request->getStage('redirect_uri');
    }

    //if we dont want to redirect
    if ($redirect === 'false') {
        return;
    }

    if ($response->isError()) {
        //add a flash
        $this->package('global')->flash($response->getMessage(), 'error');
    } else {
        //add a flash
        $this->package('global')->flash('App was Refreshed', 'success');
    }

    $this->package('global')->redirect($redirect);
});

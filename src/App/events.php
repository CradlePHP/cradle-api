<?php //-->
/**
 * This file is part of Cradle API Package.
 * (c) 2018 Sterling Technologies.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

use Cradle\Package\System\Schema;
use Cradle\Package\System\Exception;

use Cradle\Http\Request;
use Cradle\Http\Response;

 use Cradle\Package\Api\Webhook\Validator;

/**
 * Creates an App
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('app-create', function ($request, $response) {
    //do a custom validation if there's a webhook
    //just few validations because we still have the
    //default validation of system model
    $errors = Validator::getWebhookErrors($request->getStage());

    //if there are errors
    if (!empty($errors)) {
        return $response
            ->setError(true, implode(',', array_values($errors)))
            ->set('json', 'validation', $errors);
    }

    //trigger model create
    $this->trigger('webhook-create', $request, $response);

    if (!$response->isError()) {
        $request->setStage('webhook_id', $response->getResults('webhook_id'));
    }

    //set app as schema
    $request->setStage('schema', 'app');

    //trigger model create
    $this->trigger('system-model-create', $request, $response);
});

/**
 * Gets the App
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('app-detail', function ($request, $response) {
    //set app as schema
    $request->setStage('schema', 'app');

    //trigger model detail
    $this->trigger('system-model-detail', $request, $response);
});

/**
 * Removes an App
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('app-remove', function ($request, $response) {
    //set app as schema
    $request->setStage('schema', 'app');

    //trigger model remove
    $this->trigger('system-model-remove', $request, $response);
});

/**
 * Restores an App
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('app-restore', function ($request, $response) {
    //set app as schema
    $request->setStage('schema', 'app');

    //trigger model restore
    $this->trigger('system-model-restore', $request, $response);
});

/**
 * Searches an App
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('app-search', function ($request, $response) {
    //set app as schema
    $request->setStage('schema', 'app');

    //trigger model search
    $this->trigger('system-model-search', $request, $response);
});

/**
 * Updates an App
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('app-update', function ($request, $response) {
    //do a custom validation if there's a webhook
    //just few validations because we still have the
    //default validation of system model
    $errors = Validator::getWebhookErrors($request->getStage());

    //if there are errors
    if (!empty($errors)) {
        return $response
            ->setError(true, implode(',', array_values($errors)))
            ->set('json', 'validation', $errors);
    }

    //set app as schema
    $request->setStage('schema', 'app');

    $this->trigger('system-model-detail', $request, $response);
    $app = $response->getResults();

    $webhookRequest = new Request();
    $webhookResponse =  new Response();

    $webhookRequest->setStage($request->getStage());
    $webhookRequest->setServer($request->getServer());
    $webhookRequest->setStage('webhook_id', $app['webhook_id']);

    // //trigger model create
    $this->trigger('webhook-update', $webhookRequest, $webhookResponse);

    //trigger model update
    $this->trigger('system-model-update', $request, $response);
});

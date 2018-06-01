<?php //-->
/**
 * This file is part of Cradle API Package.
 * (c) 2018 Sterling Technologies.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

use Cradle\Curl\Rest;

/**
 * Creates a webhook
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('webhook-create', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    $data = [];
    if($request->hasStage()) {
        $data = $request->getStage();
    }

    //----------------------------//
    // 2. Prepare Data
    // set webhook as schema
    $request->setStage('schema', 'webhook');

    if (isset($data['webhook_events'])) {
        $request->setStage('webhook_events', json_encode($data['webhook_events']));
    }

    //----------------------------//
    // 3. Process Data
    // trigger model create
    $this->trigger('system-model-create', $request, $response);

    if (!$response->isError()) {
        $results = $response->getResults();

        $hash = md5($results['webhook_updated']);
        $request->setStage('webhook_id', $results['webhook_id']);
        $request->setStage('webhook_updated', $results['webhook_updated']);
        $request->setStage('url', $results['webhook_url']);

        $this->trigger('webhook-subscription', $request, $response);

        $response->setResults($results);
    }
});

/**
 * Creates a webhook
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('webhook-detail', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    // unnecessary move to get data
    //----------------------------//
    // 2. Prepare Data
    // set webhook as schema
    $request->setStage('schema', 'webhook');
    //----------------------------//
    // 3. Process Data
    // trigger model detail
    $this->trigger('system-model-detail', $request, $response);
});

/**
 * Removes a webhook
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('webhook-remove', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    // unnecessary move to get data
    //----------------------------//
    // 2. Prepare Data
    // set webhook as schema
    $request->setStage('schema', 'webhook');
    //----------------------------//
    // 3. Process Data
    // trigger model remove
    $this->trigger('system-model-remove', $request, $response);
});

/**
 * Restores a webhook
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('webhook-restore', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    // unnecessary move to get data
    //----------------------------//
    // 2. Prepare Data
    // set webhook as schema
    $request->setStage('schema', 'webhook');
    //----------------------------//
    // 3. Process Data
    // trigger model restore
    $this->trigger('system-model-restore', $request, $response);
});

/**
 * Searches webhook
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('webhook-search', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    // unnecessary move to get data
    //----------------------------//
    // 2. Prepare Data
    // set webhook as schema
    $request->setStage('schema', 'webhook');
    //----------------------------//
    // 3. Process Data
    // trigger model search
    $this->trigger('system-model-search', $request, $response);
});

/**
 * Updates a webhook
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('webhook-update', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    $data = [];
    if($request->hasStage()) {
        $data = $request->getStage();
    }

    //----------------------------//
    // 2. Prepare Data
    // set webhook as schema
    $request->setStage('schema', 'webhook');

    if (isset($data['webhook_events'])) {
        $request->setStage('webhook_events', json_encode($data['webhook_events']));
    }

    //----------------------------//
    // 3. Process Data
    // trigger model update
    $this->trigger('system-model-update', $request, $response);
});

/**
 * Performs webhook sending of data
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('webhook-send', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    $data = [];
    if($request->hasStage()) {
        $data = $request->getStage();
    }

    //----------------------------//
    // 2. Validate Data
    $errors = [];
    if (!isset($data['url'])
        || empty($data['url'])
        || filter_var($data['url'], FILTER_VALIDATE_URL)) {
        $errors['url'] = 'Invalid url';
    }

    if (!isset($data['event']) || empty($data['event'])) {
        $errors['event'] = 'Please specify event type';
    }

    if ($errors) {
        return $response->setError(true, implode(',', $errors));
    }

    //----------------------------//
    // 3. Prepare Data
    $url = $data['url'];
    $event = strtoupper($data['event']);
    unset($data['url']);
    unset($data['event']);

    //----------------------------//
    // 4. Process Data
    Rest::i($url)
        ->set($data)
        ->setNotificationType('Event')
        ->setEventType($event)
        ->post();

    $response->setError(false);
});

/**
 * Performs sending of subscription notification
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('webhook-subscription', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    $data = [];
    if($request->hasStage()) {
        $data = $request->getStage();
    }

    //----------------------------//
    // 2. Validate Data
    $errors = [];
    if (!isset($data['url'])
        || empty($data['url'])
        || filter_var($data['url'], FILTER_VALIDATE_URL)
    ) {
        $errors['url'] = 'Invalid url';
    }

    if (!isset($data['webhook_id']) || !$data['webhook_id']) {
        $errors['webhook_id'] = 'Missing webhook_id';
    }

    if (!isset($data['webhook_updated']) || !$data['webhook_updated']) {
        $errors['webhook_updated'] = 'Missing webhook_updated';
    }

    //----------------------------//
    // 3. Prepare Data
    $protocol = 'http';
    if ($request->getServer('SERVER_PORT') === 443) {
        $protocol = 'https';
    }

    $host = $protocol . '://' . $request->getServer('HTTP_HOST');
    $host .= '/webhook/' . $data['webhook_id'] . '/subscription/';
    $host .= md5($data['webhook_updated']);

    //----------------------------//
    // 4. Process Data
    Rest::i($data['url'])
        ->setSubscriptionUrl($host)
        ->setNotificationType('SubscriptionConfirmation')
        ->post();

    $response->setError(false);
});

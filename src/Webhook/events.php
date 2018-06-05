<?php //-->
/**
 * This file is part of Cradle API Package.
 * (c) 2018 Sterling Technologies.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

use Cradle\Curl\Rest;
use Cradle\Storm\SqlFactory;

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

        $subscription = [
            'webhook_id' => $results['webhook_id'],
            'webhook_updated' => $results['webhook_updated'],
            'url' => $results['webhook_url']
        ];

        try {
            $this
                ->package('cradlephp/cradle-queue')
                ->queue('webhook-subscription', $subscription);
        } catch (Exception $e) {
        }

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

    $this->trigger('webhook-detail', $request, $response);

    if ($response->isError()) {
        return;
    }

    $old = $response->getResults();

    //----------------------------//
    // 2. Prepare Data
    // set webhook as schema
    $request->setStage('schema', 'webhook');

    if (isset($data['webhook_events'])) {
        $request->setStage('webhook_events', json_encode($data['webhook_events']));
    }

    if ($request->hasStage('webhook_url')
        && $request->getStage('webhook_url') !== $old['webhook_url']
    ) {
        $request->setStage('webhook_flag', 0);
    }

    //----------------------------//
    // 3. Process Data
    // trigger model update
    $this->trigger('system-model-update', $request, $response);

    if (!$response->isError()
        && $request->hasStage('webhook_url')
        && $request->getStage('webhook_url') !== $old['webhook_url']
    ) {
        $results = $response->getResults();

        $subscription = [
            'webhook_id' => $results['webhook_id'],
            'webhook_updated' => $results['webhook_updated'],
            'url' => $results['webhook_url']
        ];

        try {
            $this
                ->package('cradlephp/cradle-queue')
                ->queue('webhook-subscription', $subscription);
        } catch (Exception $e) {
        }

        $response->setResults($results);
    }
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
        || !filter_var($data['url'], FILTER_VALIDATE_URL)) {
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
    // nothing to prepare
    //----------------------------//
    // 4. Process Data
    Rest::i($data['url'])
        ->setData($data['webhook_data'])
        ->setNotificationType('Event')
        ->setEventType($data['event'])
        ->setResponseFormat('raw')
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


/**
 * Checks for webhook distribution
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('webhook-distribution', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    $data = [];
    if ($request->getStage()) {
        $data = $request->getStage();
    }
    // 2. Validate Data
    //----------------------------//
    $errors = [];
    if (!isset($data['uri']) || empty($data['uri'])) {
        $errors['uri'] = 'URI is required';
    }

    if (!isset($data['method']) || empty($data['method'])) {
        $errors['method'] = 'Method is required';
    }

    if (!isset($data['json_data']) || empty($data['json_data'])) {
        $errors['json_data'] = 'JSON data is required';
    }

    if ($errors) {
        return $response
            ->setError(true, 'There are missing data for the webhook to work')
            ->set('json', 'validation', $errors);
    }

    // 3. Prepare Data
    //----------------------------//
    $database = SqlFactory::load(cradle('global')->service('sql-main'));

    // pull all the roles with the given uri
    $roles = $database
        ->search('role')
        // ->addFilter("JSON_CONTAINS(role_permissions->'$[*].path', '\"" . $data['uri'] ."\"') = 1")
        ->addFilter("JSON_SEARCH(role_permissions, 'one', %s) IS NOT NULL", $data['uri'])
        ->getRows();

    // if no roles for that, then there shouldn't be a webhook too
    if (!$roles) {
        return $response->setResults("No webhooks enrolled for this uri");
    }

    $roleIds = [];

    // check roles pulled are of the same method as the given method
    foreach ($roles as $rkey => $role) {
        $role['role_permissions'] = json_decode($role['role_permissions'], true);
        foreach ($role['role_permissions'] as $pkey => $permission) {
            // if path is not the same as the given uri
            // or the method is not the same ignore
            if ($permission['path'] != $data['uri']
                || $permission['method'] != $data['method']
            ) {
                continue;
            }

            $roleIds[$role['role_id']] = [
                'role_id' => $role['role_id'],
                'permission_id' => $permission['id'],
                'event_name' => $permission['label']
            ];
        }
    }

    // pull all webhooks with these routes
    // and it should be a confirmed susbcription
    $webhooks = $database
        ->search('webhook')
        ->addFilter('webhook_flag = 1');

    $where = [];

    foreach ($roleIds as $role) {
        $where[] = sprintf(
            "JSON_SEARCH(webhook_events, 'one', '%s') IS NOT NULL",
            $role['permission_id']
        );
    }

    if ($where) {
        $webhooks->addFilter('(' . implode(' OR ', $where) . ')');
    }

    $webhooks = $webhooks->getRows();
    //----------------------------//
    // 4. Process Data
    // since we now have the webhook urls,
    // we have to send it to them
    foreach ($webhooks as $webhook) {
        $events = json_decode($webhook['webhook_events'] , true);

        $event = 'Unnamed Event';
        foreach ($events as $permission => $role) {
            if (isset($roleIds[$permission])) {
                $event = $roleIds[$permission]['event_name'];
                break;
            }
        }

        // prepare data before sending
        $send = [
            'event' => $event,
            'url' => $webhook['webhook_url'],
            'webhook_data' => $data['json_data']
        ];

        try {
            $this
                ->package('cradlephp/cradle-queue')
                ->queue('webhook-send', $send);
        } catch (Exception $e) {
            $response->setError(true, 'No queue');
        }
    }
});

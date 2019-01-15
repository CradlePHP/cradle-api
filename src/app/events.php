<?php //-->
/**
 * This file is part of a package designed for the CradlePHP Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

use Cradle\Package\System\Schema;

/**
 * System Model Create Job
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('system-model-create', function ($request, $response) {
    //this is only for the app schema
    if ($request->getStage('schema') !== 'app') {
        return;
    }

    //if there's already an error
    if ($response->isError()) {
        //theres nothing to do then
        return;
    }

    //----------------------------//
    // 1. Get Data
    //precurse, we need the ID
    $appId = $response->getResults('app_id');
    $scopes = $response->getResults('scope');
    $webhooks = $response->getResults('webhook');

    //----------------------------//
    // 2. Process Data
    //this/these will be used a lot
    $schema = Schema::i('app');
    $sql = $schema->model()->service('sql');
    $elastic = $schema->model()->service('elastic');

    //we need to link the scopes
    if (is_array($scopes)) {
        foreach($scopes as $scopeId => $enabled) {
            if($enabled) {
                $sql->link('scope', $appId, $scopeId);
                continue;
            }

            $sql->unlink('scope', $appId, $scopeId);
        }
    }

    //we need to link the webhooks
    if (is_array($webhooks)) {
        foreach($webhooks as $webhookId => $enabled) {
            if($enabled) {
                $sql->link('webhook', $appId, $webhookId);
                continue;
            }

            $sql->unlink('webhook', $appId, $webhookId);
        }
    }

    $elastic->update($appId);
}, -10);

/**
 * System Model Update Job
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('system-model-update', function ($request, $response) {
    //this is only for the app schema
    if ($request->getStage('schema') !== 'app') {
        return;
    }

    //if there's already an error
    if ($response->isError()) {
        //theres nothing to do then
        return;
    }

    //so a good use case is when another controller
    //is calling for update like app refresh
    //we should consider other methods calling events

    //if there are no updates to scopes or webhooks
    if (!$response->hasResults('scope')
        && !$response->hasResults('webhook')
    ) {
        return;
    }

    //----------------------------//
    // 1. Get Data
    $appId = $response->getResults('app_id');

    //get the detail (for the existing scopes and hooks)
    $payload = $this->makePayload();

    $payload['request']
        ->setStage('schema', 'app')
        ->setStage('app_id', $appId);

    $this->trigger(
        'system-model-detail',
        $payload['request'],
        $payload['response']
    );

    $current = $payload['response']->getResults();

    //----------------------------//
    // 2. Process Data
    //this/these will be used a lot
    $schema = Schema::i('app');
    $sql = $schema->model()->service('sql');
    $elastic = $schema->model()->service('elastic');

    if ($response->hasResults('scope')) {
        //organize the scopes
        $exists = [];
        foreach($current['scope'] as $scope) {
            $exists[$scope['scope_id']] = true;
        }

        $scopes = $response->getResults('scope');

        //we need to link the scopes
        if (is_array($scopes)) {
            foreach($scopes as $scopeId => $enabled) {
                //if it's enabled
                if($enabled) {
                    //if not already linked
                    if (!isset($exists[$scopeId])) {
                        $sql->link('scope', $appId, $scopeId);
                    }

                    continue;
                }

                $sql->unlink('scope', $appId, $scopeId);
            }
        }
    }

    if ($response->hasResults('webhook')) {
        //organize the hooks
        $exists = [];
        foreach($current['webhook'] as $scope) {
            $exists[$scope['webhook_id']] = true;
        }

        $webhooks = $response->getResults('webhook');

        //we need to link the webhooks
        if (is_array($webhooks)) {
            foreach($webhooks as $webhookId => $enabled) {
                //if it's enabled
                if($enabled) {
                    //if not already linked
                    if (!isset($exists[$webhookId])) {
                        $sql->link('webhook', $appId, $webhookId);
                    }

                    continue;
                }

                $sql->unlink('webhook', $appId, $webhookId);
            }
        }
    }

    $elastic->update($appId);
}, -10);

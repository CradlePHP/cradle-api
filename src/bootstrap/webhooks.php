<?php
/**
 * This file is part of a package designed for the CradlePHP Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

use Cradle\Package\System\Schema;
use Cradle\Package\System\Exception;

return function($request, $response) {
    try { //first test for webhook
        $schema = Schema::i('webhook');
    } catch (Exception $e) {
        //if no webhook schema
        return;
    }

    //before we do any webhook processing,
    $queueAvailable = $this->isPackage('cradlephp/cradle-queue');
    $devMode = !!$this->package('global')->config('settings', 'debug_mode');

    //if queue package is not available
    //and we are not in dev mode
    if (!$queueAvailable && !$devMode) {
        //lets do the responsible thing
        return;
    }

    //get all webhooks
    $payload = $this->makePayload();
    $payload['request']->setStage('schema', 'webhook');

    //WARNING: Too many webhooks will slow down the system
    try {
        $this->trigger(
            'webhook-valid-search',
            $payload['request'],
            $payload['response']
        );
    } catch (Throwable $e) {
        //this can fail if the database has not been setup yet
        //if this is the case, then let's silently exit
        return;
    }

    $webhooks = $payload['response']->getResults('rows');

    //if no webhooks no need to continue
    if (empty($webhooks)) {
        return;
    }

    //need to create a special in_array for multidimensional arrays
    $in = function($list1, $list2) use (&$in) {
        foreach ($list1 as $key => $value) {
            //if its not in list2
            if (!isset($list2[$key])) {
                return false;
            }

            //if value is not an array
            if (!is_array($value)) {
                if ($list2[$key] != $value) {
                    return false;
                }

                continue;
            }

            //value is an array
            if(!$in($list1[$key], $list2[$key])) {
                return false;
            }
        }

        return true;
    };

    foreach ($webhooks as $webhook) {
        $this->on(
            $webhook['webhook_event'],
            function($request, $response) use (
                $webhook,
                &$queueAvailable,
                &$in
            ) {
                //if the parameters dont exist in the stage
                if(!$in($webhook['webhook_parameters'], $request->getStage())) {
                    //stop
                    return;
                }

                $results = $response->getResults();

                //now we need to call the calls
                foreach ($webhook['calls'] as $call) {
                    //is it a user webhook?
                    if ($webhook['webhook_type'] === 'user'
                        && (//and
                            //if there is no profile id
                            !isset($results['profile_id'])
                            //or the profile ids dont match
                            || $results['profile_id'] !== $call['profile']
                        )
                    ) {
                        //dont call the webhook
                        continue;
                    }

                    //setup the payload
                    $data = [
                        'url' => $call['url'],
                        'action' => $webhook['webhook_action'],
                        'method' => $webhook['webhook_method'],
                        'results' => $results
                    ];

                    //try to see if queue is available
                    if ($queueAvailable) {
                        // execute webhook distribution
                        try {
                            $queued = $this
                                ->package('cradlephp/cradle-queue')
                                ->queue('webhook-call', $data);
                        } catch (Exception $e) {
                            $queued = false;
                        }

                        if ($queued) {
                            continue;
                        }
                    }

                    //it was not queued, so trigger the event
                    $payload = $this->makePayload();
                    $payload['request']->setStage($data);
                    $this->trigger(
                        'webhook-call',
                        $payload['request'],
                        $payload['response']
                    );
                }
            }
        );
    }
};

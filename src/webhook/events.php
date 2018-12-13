<?php //-->
/**
 * This file is part of a package designed for the CradlePHP Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

use Cradle\Package\System\Schema;
use Cradle\Curl\CurlHandler;

/**
 * Gets all the rest calls given the source scopes
 *
 * @param Request $request
 * @param Response $response
 */
$cradle->on('webhook-valid-search', function ($request, $response) {
    $results = [];

    //only get the webhooks that are being
    //listened to and have valid webhook URLS
    $rows = Schema::i('webhook')
        ->model()
        ->service('sql')
        ->getResource()
        ->search('app_webhook')
        ->innerJoinUsing('app', 'app_id')
        ->innerJoinUsing('webhook', 'webhook_id')
        ->innerJoinUsing('app_profile', 'app_id')
        ->addFilter('app_webhook IS NOT NULL AND app_webhook !=\'\'')
        ->filterByAppActive(1)
        ->filterByWebhookActive(1)
        ->getRows();

    foreach ($rows as $row) {
        $row['webhook_parameters'] = json_decode(
            $row['webhook_parameters'],
            true
        );

        if (!is_array($row['webhook_parameters'])) {
            $row['webhook_parameters'] = [];
        }

        $id = $row['webhook_id'];

        //add the webhook
        if (!isset($results[$id])) {
            $results[$id] = $row;
            foreach ($results[$id] as $key => $value) {
                if (strpos($key, 'app_') === 0
                    || strpos($key, 'profile_') === 0
                ) {
                    unset($results[$id][$key]);
                }
            }
        }

        //add to app
        $results[$id]['calls'][$row['app_id']] = [
            'url' => $row['app_webhook'],
            'profile' => $row['profile_id']
        ];
    }

    //clean up results
    $results = array_values($results);
    foreach ($results as $i => $webhook) {
        $calls = [];
        //this logic is to reduce the minimum calls performed
        foreach ($results[$i]['calls'] as $call) {
            //by default unique by url
            $id = $call['url'];
            //if this webhook is a user type
            if ($webhook['webhook_type'] === 'user') {
                //unique by url + profile
                $id = $call['profile'] . $call['url'];
            }

            $calls[$id] = $call;
        }

        $results[$i]['calls'] = array_values($calls);
    }

    $response->setResults([
        'rows' => $results,
        'total' => count($results)
    ]);
});

/**
 * Gets all the rest calls given the source scopes
 *
 * @param Request $request
 * @param Response $response
 */
$cradle->on('webhook-call', function ($request, $response) {
    $url = $request->getStage('url');
    $action = $request->getStage('action');
    $method = $request->getStage('method');
    $results = $request->getStage('results');

    $payload = [
        'action' => $action,
        'data' => $results
    ];

    CurlHandler::i()
        ->setUrl($url)
        ->when(
            strpos($url, 'https') === 0,
            function () {
                $this
                    ->verifyPeer(false)
                    ->verifyHost(false);
            }
        )
        ->setCustomRequest(strtoupper($method))
        ->when(
            $method === 'get' || $method === 'delete',
            function () use (&$url, &$payload) {
                $query = http_build_query($payload);
                $separator = '?';
                if (strpos($url, '?') !== false) {
                    $separator = '&';
                }

                $this->setUrl($url . $separator . $query);

            },
            //else (post or put)
            function () use (&$payload) {
                $this->setPostFields(
                    $payload,
                    CurlHandler::ENCODE_JSON
                );
            }
        )
        ->send();
});
<?php //-->
/**
 * This file is part of a package designed for the CradlePHP Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

use Cradle\Package\System\Schema;

/**
 * Session Access Job
 *
 * @param Request $request
 * @param Response $response
 */
$cradle->on('rest-access', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    $data = [];
    if ($request->hasStage()) {
        $data = $request->getStage();
    }

    //----------------------------//
    // 2. Validate Data
    //code Required
    if (!isset($data['code']) || empty($data['code'])) {
        $errors['code'] = 'Cannot be empty';
    }

    //client_id Required
    if (!isset($data['client_id']) || empty($data['client_id'])) {
        $errors['client_id'] = 'Cannot be empty';
    }

    //client_secret Required
    if (!isset($data['client_secret']) || empty($data['client_secret'])) {
        $errors['client_secret'] = 'Cannot be empty';
    }

    //if there are errors
    if (!empty($errors)) {
        return $response
            ->setError(true, 'Invalid Parameters')
            ->set('json', 'validation', $errors);
    }

    //get the session detail
    $request->setStage('schema', 'session');
    $request->setStage('session_token', $data['code']);
    $this->trigger('system-model-detail', $request, $response);

    if ($data['client_id'] !== $response->getResults('app_token')) {
        $response
            ->setError(true, 'Invalid Parameters')
            ->set(
                'json',
                'validation',
                'client_id',
                'Token does not belong with this session'
            );
    }

    if ($data['client_secret'] !== $response->getResults('app_secret')) {
        $response
            ->setError(true, 'Invalid Parameters')
            ->set(
                'json',
                'validation',
                'client_secret',
                'Token does not belong with this session'
            );
    }

    //if there's an error
    if ($response->isError()) {
        return;
    }

    //----------------------------//
    // 3. Process Data
    $current = $response->getResults();

    $request
        ->setStage('session_id', $current['session_id'])
        ->setStage('session_token', md5(uniqid()))
        ->setStage('session_secret', md5(uniqid()))
        ->setStage('session_status', 'access');

    $this->trigger('system-model-update', $request, $response);

    //if there's an error
    if ($response->isError()) {
        return;
    }

    $results = [];

    $results['access_token'] = $response->getResults('session_token');
    $results['access_secret'] = $response->getResults('session_secret');

    foreach ($current as $key => $value) {
        if (strpos($key, 'profile_') === 0) {
            $results[$key] = $value;
            continue;
        }
    }

    unset($results['profile_active'], $results['profile_updated']);

    //return response format
    $response->setResults($results);
});

/**
 * OAuth App Permission Check
 *
 * @param Request $request
 * @param Request $response
 */
$cradle->on('rest-source-app-detail', function ($request, $response) {
    if (!$request->hasStage('client_id')) {
        return $response->setError(true, 'Unauthorize Request');
    }

    $token = $request->getStage('client_id');
    $secret = $request->getStage('client_secret');

    if ($request->getMethod() !== 'GET' && !$secret) {
        return $response->setError(true, 'Unauthorize Request');
    }

    $filters = [];
    $filters['app_token'] = $token;
    $filters['app_active'] = 1;

    if ($secret) {
        $filters['app_secret'] = $secret;
    }

    $sql = Schema::i('app')->model()->service('sql');
    $results = $sql->search(['filter' => $filters]);

    if (!$results['total']) {
        return $response->setError(true, 'Unauthorize Request');
    }

    $row = $sql->get('app_id', $results['rows'][0]['app_id']);

    $response->setResults($row);
    $response->setResults('type', 'app');
    $response->setResults('token', $token);
    $response->setResults('secret', $secret);

    return $response->setError(false);
});

/**
 * OAuth Session Permission Check
 *
 * @param Request $request
 * @param Request $response
 */
$cradle->on('rest-source-session-detail', function ($request, $response) {
    if (!$request->hasStage('access_token')) {
        return $response->setError(true, 'Unauthorize Request');
    }

    $token = $request->getStage('access_token');
    $secret = $request->getStage('access_secret');

    if ($request->getMethod() !== 'GET' && !$secret) {
        return $response->setError(true, 'Unauthorize Request');
    }

    $filters = [];
    $filters['session_token'] = $token;
    $filters['session_status'] = 'access';
    $filters['session_active'] = 1;

    if ($secret) {
        $filters['session_secret'] = $secret;
    }

    $sql = Schema::i('session')->model()->service('sql');
    $results = $sql->search(['filter' => $filters]);

    if (!$results['total']) {
        return $response->setError(true, 'Unauthorize Request');
    }

    $row = $sql->get('session_id', $results['rows'][0]['session_id']);

    $response->setResults($row);
    $response->setResults('type', 'session');
    $response->setResults('token', $token);
    $response->setResults('secret', $secret);

    return $response->setError(false);
});

/**
 * Gets all the rest calls given the source scopes
 *
 * @param Request $request
 * @param Response $response
 */
$cradle->on('rest-route-search', function ($request, $response) {
    $results = [];
    //first get all the public calls
    $rows = Schema::i('rest')
        ->model()
        ->service('sql')
        ->getResource()
        ->search('rest')
        ->filterByRestType('public')
        ->getRows();

    //add it to results
    foreach ($rows as $row) {
        $row['rest_parameters'] = json_decode(
            $row['rest_parameters'],
            true
        );

        if (!is_array($row['rest_parameters'])) {
            $row['rest_parameters'] = [];
        }

        $results[$row['rest_id']] = $row;
    }

    //next get all the
    $scopes = $request->get('source', 'scope');

    if (!is_array($scopes)) {
        $scopes = [];
    }

    //just need the scope ids
    $ids = [];
    foreach ($scopes as $scope) {
        $ids[] = $scope['scope_id'];
    }

    if (!empty($ids)) {
        $rows = Schema::i('rest')
            ->model()
            ->service('sql')
            ->getResource()
            ->search('rest')
            ->innerJoinUsing('scope_rest', 'rest_id')
            ->addFilter('scope_id IN ('.implode(',', $ids).')')
            ->getRows();

        //add it to results
        foreach ($rows as $row) {
            $row['rest_parameters'] = json_decode(
                $row['rest_parameters'],
                true
            );

            if (!is_array($row['rest_parameters'])) {
                $row['rest_parameters'] = [];
            }

            $results[$row['rest_id']] = $row;
        }
    }

    $response->setResults([
        'rows' => array_values($results),
        'total' => count($results)
    ]);
});

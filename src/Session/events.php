<?php //-->
/**
 * This file is part of Cradle API Package.
 * (c) 2018 Sterling Technologies.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */
 use Cradle\Package\Api\Session\Validator;

 /**
  * Session Access Job
  *
  * @param Request $request
  * @param Response $response
  */
$this->on('session-access', function ($request, $response) {
     //----------------------------//
     // 1. Get Data
     $this->trigger('session-detail', $request, $response);

     //if there's an error
     if ($response->isError()) {
         return;
     }

     $data = [];
     if ($request->hasStage()) {
         $data = $request->getStage();
     }

     //----------------------------//
     // 2. Validate Data
     $errors = Validator::getAccessErrors($data);

     //if there are errors
     if (!empty($errors)) {
         return $response
             ->setError(true, implode(',', array_values($errors)))
             ->set('json', 'validation', $errors);
     }

     //----------------------------//
     // 3. Prepare Data
     //set session as schema
     $request->setStage('schema', 'session');
     // set the id to update
     $request->setStage('session_id', $response->getResults('session_id'));
     // set a session token
     $request->setStage('session_token', md5(uniqid()));
     // set status to authorized
     $request->setStage('session_status', 'AUTHORIZED');
     //----------------------------//
     // 4. Process Data
     //save to database
     $this->trigger('system-model-update', $request, $response);
 });

/**
 * Creates a Session
 *
 * @param Request $request
 * @param Response $response
 */
$this->on('session-create', function ($request, $response) {
    //set session as schema
    $request->setStage('schema', 'session');

    //trigger model create
    $this->trigger('system-model-create', $request, $response);
});

/**
* Session Detail Job
*
* @param Request $request
* @param Response $response
*/
$this->on('session-detail', function ($request, $response) {
    //----------------------------//
    // 1. Get Data
    $data = [];
    if ($request->hasStage()) {
        $data = $request->getStage();
    }

    // set session as schema
    $request->setStage('schema', 'session');

    $id = null;
    switch (true) {
        case isset($data['session_id']):
            // trigger model detail
            $this->trigger('system-model-detail', $request, $response);
            break;

        case isset($data['session_code']) || isset($data['code']):
            if (isset($data['session_code'])) {
                $data['code'] = $data['session_code'];
            }

            // if code was given
            if (isset($data['code'])) {
                $request->setStage('filter', ['session_code' => $data['code']]);
            }

        case isset($data['session_token']) || isset($data['token']):
            if (isset($data['session_token'])) {
                $data['token'] = $data['session_token'];
            }

            // if token was given
            if (isset($data['token'])) {
                $request->setStage('filter', ['session_token' => $data['token']]);
            }

            $request->setStage('range', 1);
            $this->trigger('system-model-search', $request, $response);

            if (!$response->isError() && $response->getResults('rows')) {
                // return the first item only
                $response->setResults($response->getResults('rows')[0]);
                break;
            }

            if (!$response->isError()) {
                $response->setResults($response->getResults('rows'));
                break;
            }
            break;

        default:
            $response->setResults([]);
            break;
    }
});

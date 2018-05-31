<?php //-->
/**
 * This file is part of Cradle API Package.
 * (c) 2018 Sterling Technologies.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

// Back End Controllers
use Cradle\Http\Request;
use Cradle\Http\Response;
use Cradle\Package\System\Schema;

/**
 * Routes
 */
$this->post('/session/access', function ($request, $response) {
    //----------------------------//
    // 1. Route Permissions
    // no need for permission as we are
    // requesting for access
    //----------------------------//
    // 2. Prepare Date
    //set the auth id
    $auth = $request->get('source', 'auth_id');
    $request->setStage('permission', $auth);
    //----------------------------//
    // 3. Process Data
    // call the job
    $this->trigger('session-access', $request, $response);
    //----------------------------//
    // 4. Interpret Results
    if ($response->isError()) {
        return $this->routeTo('get', '/dialog/invalid', $request, $response);
    }

    // set redirect url
    $redirect = sprintf(
        $request->getStage('redirect_uri') . '#%s',
        $response->getResults('session_token')
    );

    //it was good
    //redirect
    $this->getDispatcher()->redirect($redirect);
});

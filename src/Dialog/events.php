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
    if ($request->getStage('schema') !== 'session') {
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
    $sessionId = $response->getResults('session_id');
    $permissions = $request->getStage('permissions');

    //----------------------------//
    // 2. Validate Data
    //if no session id
    if (!$sessionId) {
        //theres nothing to do then
        return;
    }
    //if there are no permissions
    if (!is_array($permissions)) {
        //theres nothing to do then
        return;
    }

    //----------------------------//
    // 3. Process Data
    //this/these will be used a lot
    $schema = Schema::i('session');
    $sql = $schema->model()->service('sql');
    $elastic = $schema->model()->service('elastic');

    //we need to link the scopes
    foreach($permissions as $scopeId) {
        $sql->link('scope', $sessionId, $scopeId);
    }

    $elastic->update($sessionId);
});

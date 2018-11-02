<?php //-->
/**
 * This file is part of a package designed for the CradlePHP Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

/**
 * Process REST Access
 *
 * @param Request $request
 * @param Response $response
 */
$this->post('/rest/access', function ($request, $response) {
    //set the profile id
    $profile = $request->get('source', 'profile_id');
    $request->setStage('permission', $profile);

    //call the job
    $this->trigger('rest-access', $request, $response);
});

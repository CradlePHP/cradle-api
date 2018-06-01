<?php //-->
/**
 * This file is part of Cradle API Package.
 * (c) 2018 Sterling Technologies.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

/**
 * Render the Model Search Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/rest/:schema/search', function ($request, $response) {
    //----------------------------//
    // 1. Route Permissions
    $this->trigger('rest-permitted', $request, $response);

    if ($response->isError()) {
        return;
    }

    //----------------------------//
    // 2. Prepare Data

    // render raw data
    $request->setStage('render', 'false');
    // disable redirect
    $request->setStage('redirect', 'false');

    //----------------------------//
    // 3. Render Request
    try {
        $this->routeTo(
            'get',
            sprintf(
                '/admin/system/model/%s/search',
                $request->getStage('schema')
            ),
            $request,
            $response
        );
    } catch (\Exception $e) {
        return $response
            ->setError(true, $e->getMessage());
    }
});

/**
 * Render the Model Search Page Filtered by Relation
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/rest/:schema1/:id/search/:schema2', function ($request, $response) {
    //----------------------------//
    // 1. Route Permissions
    $this->trigger('rest-permitted', $request, $response);

    if ($response->isError()) {
        return;
    }

    //----------------------------//
    // 2. Prepare Data

    // render raw data
    $request->setStage('render', 'false');

    //----------------------------//
    // 3. Render Request
    try {
        $this->routeTo(
            'get',
            sprintf(
                '/admin/system/model/%s/%s/search/%s',
                $request->getStage('schema1'),
                $request->getStage('id'),
                $request->getStage('schema2')
            ),
            $request,
            $response
        );
    } catch (\Exception $e) {
        return $response
            ->setError(true, $e->getMessage());
    }
});

/**
 * Process the Model Search Actions
 *
 * @param Request $request
 * @param Response $response
 */
$this->post('/rest/:schema/search', function ($request, $response) {
    //----------------------------//
    // 1. Route Permissions
    $this->trigger('rest-permitted', $request, $response);

    if ($response->isError()) {
        return;
    }

    //----------------------------//
    // 2. Prepare Data

    // don't redirect
    $request->setStage('redirect_uri', 'false');

    // rest route
    $route = sprintf(
        '/rest/%s/search',
        $request->getStage('schema')
    );

    // set route
    $request->setStage('route', $route);

    //----------------------------//
    // 3. Render Request
    try {
        $this->routeTo(
            'post',
            sprintf(
                '/admin/system/model/%s/search',
                $request->getStage('schema')
            ),
            $request,
            $response
        );
    } catch (\Exception $e) {
        return $response
            ->setError(true, $e->getMessage());
    }
});

/**
 * Process the Model Search Page Filtered by Relation
 *
 * @param Request $request
 * @param Response $response
 */
$this->post('/rest/:schema1/:id/search/:schema2', function ($request, $response) {
    //----------------------------//
    // 1. Route Permissions
    $this->trigger('rest-permitted', $request, $response);

    if ($response->isError()) {
        return;
    }

    //----------------------------//
    // 2. Prepare Data

    // don't redirect
    $request->setStage('redirect_uri', 'false');

    // rest route
    $route = sprintf(
        '/rest/%s/%s/search/%s',
        $request->getStage('schema1'),
        $request->getStage('id'),
        $request->getStage('schema2')
    );

    // set route
    $request->setStage('route', $route);

    //----------------------------//
    // 3. Render Request
    try {
        $this->routeTo(
            'post',
            sprintf(
                '/admin/system/model/%s/%s/search/%s',
                $request->getStage('schema1'),
                $request->getStage('id'),
                $request->getStage('schema2')
            ),
            $request,
            $response
        );
    } catch (\Exception $e) {
        return $response
            ->setError(true, $e->getMessage());
    }
});

/**
 * Process the Model Create Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->post('/rest/:schema/create', function ($request, $response) {
    //----------------------------//
    // 1. Route Permissions
    $this->trigger('rest-permitted', $request, $response);

    if ($response->isError()) {
        return;
    }

    //----------------------------//
    // 2. Prepare Data

    // don't redirect
    $request->setStage('redirect_uri', 'false');

    // rest route
    $route = sprintf(
        '/rest/%s/create',
        $request->getStage('schema')
    );

    // set route
    $request->setStage('route', $route);

    //----------------------------//
    // 3. Render Request
    try {
        $this->routeTo(
            'post',
            sprintf(
                '/admin/system/model/%s/create',
                $request->getStage('schema')
            ),
            $request,
            $response
        );
    } catch (\Exception $e) {
        return $response
            ->setError(true, $e->getMessage());
    }
});

/**
 * Process the Model Create Page Filtered by Relation
 *
 * @param Request $request
 * @param Response $response
 */
$this->post('/rest/:schema1/:id/create/:schema2', function ($request, $response) {
    //----------------------------//
    // 1. Route Permissions
    $this->trigger('rest-permitted', $request, $response);

    if ($response->isError()) {
        return;
    }

    //----------------------------//
    // 2. Prepare Data

    // don't redirect
    $request->setStage('redirect_uri', 'false');

    // rest route
    $route = sprintf(
        '/rest/%s/%s/create/%s',
        $request->getStage('schema1'),
        $request->getStage('id'),
        $request->getStage('schema2')
    );

    // set route
    $request->setStage('route', $route);

    //----------------------------//
    // 3. Render Request
    try {
        $this->routeTo(
            'post',
            sprintf(
                '/admin/system/model/%s/%s/create/%s',
                $request->getStage('schema1'),
                $request->getStage('id'),
                $request->getStage('schema2')
            ),
            $request,
            $response
        );
    } catch (\Exception $e) {
        return $response
            ->setError(true, $e->getMessage());
    }
});

/**
 * Process the Model Update Page
 *
 * @param Request $request
 * @param Response $response
 */
$this->post('/rest/:schema/update/:id', function ($request, $response) {
    //----------------------------//
    // 1. Route Permissions
    $this->trigger('rest-permitted', $request, $response);

    if ($response->isError()) {
        return;
    }

    //----------------------------//
    // 2. Prepare Data

    // don't redirect
    $request->setStage('redirect_uri', 'false');

    // rest route
    $route = sprintf(
        '/rest/%s/update/%s',
        $request->getStage('schema'),
        $request->getStage('id')
    );

    // set route
    $request->setStage('route', $route);

    //----------------------------//
    // 3. Render Request
    try {
        $this->routeTo(
            'post',
            sprintf(
                '/admin/system/model/%s/update/%s',
                $request->getStage('schema'),
                $request->getStage('id')
            ),
            $request,
            $response
        );
    } catch (\Exception $e) {
        return $response
            ->setError(true, $e->getMessage());
    }
});

/**
 * Process the Model Remove
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/rest/:schema/remove/:id', function ($request, $response) {
    //----------------------------//
    // 1. Route Permissions
    $this->trigger('rest-permitted', $request, $response);

    if ($response->isError()) {
        return;
    }

    //----------------------------//
    // 2. Prepare Data

    // don't redirect
    $request->setStage('redirect_uri', 'false');

    //----------------------------//
    // 3. Render Request
    try {
        $this->routeTo(
            'get',
            sprintf(
                '/admin/system/model/%s/remove/%s',
                $request->getStage('schema'),
                $request->getStage('id')
            ),
            $request,
            $response
        );
    } catch(\Exception $e) {
        return $response
            ->setError(true, $e->getMessage());
    }
});

/**
 * Process the Model Restore
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/rest/:schema/restore/:id', function ($request, $response) {
    //----------------------------//
    // 1. Route Permissions
    $this->trigger('rest-permitted', $request, $response);

    if ($response->isError()) {
        return;
    }

    //----------------------------//
    // 2. Prepare Data

    // don't redirect
    $request->setStage('redirect_uri', 'false');

    //----------------------------//
    // 3. Render Request
    try {
        $this->routeTo(
            'get',
            sprintf(
                '/admin/system/model/%s/restore/%s',
                $request->getStage('schema'),
                $request->getStage('id')
            ),
            $request,
            $response
        );
    } catch (\Exception $e) {
        return $response
            ->setError(true, $e->getMessage());
    }
});

/**
 * Process the Model Import
 *
 * @param Request $request
 * @param Response $response
 */
$this->post('/rest/:schema/import', function ($request, $response) {
    //----------------------------//
    // 1. Route Permissions
    $this->trigger('rest-permitted', $request, $response);

    if ($response->isError()) {
        return;
    }

    //----------------------------//
    // 2. Prepare Data

    // don't redirect
    $request->setStage('redirect_uri', 'false');

    //----------------------------//
    // 3. Render Request
    try {
        $this->routeTo(
            'post',
            sprintf(
                '/admin/system/model/%s/import',
                $request->getStage('schema')
            ),
            $request,
            $response
        );
    } catch (\Exception $e) {
        return $response
            ->setError(true, $e->getMessage());
    }
});

/**
 * Process the Model Export
 *
 * @param Request $request
 * @param Response $response
 */
$this->get('/rest/:schema/export/:type', function ($request, $response) {
    //----------------------------//
    // 1. Route Permissions
    $this->trigger('rest-permitted', $request, $response);

    if ($response->isError()) {
        return;
    }
    
    //----------------------------//
    // 2. Prepare Data


    //----------------------------//
    // 3. Render Request
    try {
        $this->routeTo(
            'get',
            sprintf(
                '/admin/system/model/%s/export/%s',
                $request->getStage('schema'),
                $request->getStage('type')
            ),
            $request,
            $response
        );
    } catch (\Exception $e) {
        return $response
            ->setError(true, $e->getMessage());
    }
});

<?php //-->

cradle(function() {
    //setup result counters
    $errors = [];
    $processed = [];

    //scan through each file
    foreach (scandir(__DIR__ . '/../schema') as $file) {
        //if it's not a php file
        if (substr($file, -4) !== '.php') {
            //skip
            continue;
        }

        //get the schema data
        $data = include sprintf('%s/../schema/%s', __DIR__, $file);

        //if no name
        if (!isset($data['name'])) {
            //skip
            continue;
        }

        //NOTE: Special way to redo the entire package setup
        //in this version there are major database changes
        $schemaPath = $this->package('global')->path('schema');
        if (file_exists($schemaPath . '/' . $file)) {
            //setup a new RnR
            $payload = $this->makePayload();
            //set the data
            $payload['request']->setStage('schema', $data['name']);
            //this will permanently remove the file and table
            $payload['request']->setStage('mode', 'permanent');

            //remove the schema
            $this->trigger(
                'system-schema-remove',
                $payload['request'],
                $payload['response']
            );

            //clear cache
            $this->package('global')->schema($data['name'], false);
        }

        //setup a new RnR
        $payload = $this->makePayload();

        //set the data
        $payload['request']->setStage($data);

        //----------------------------//
        // 1. Prepare Data
        //if detail has no value make it null
        if ($payload['request']->hasStage('detail')
            && !$payload['request']->getStage('detail')
        ) {
            $payload['request']->setStage('detail', null);
        }

        //if fields has no value make it an array
        if ($payload['request']->hasStage('fields')
            && !$payload['request']->getStage('fields')
        ) {
            $payload['request']->setStage('fields', []);
        }

        //if validation has no value make it an array
        if ($payload['request']->hasStage('validation')
            && !$payload['request']->getStage('validation')
        ) {
            $payload['request']->setStage('validation', []);
        }

        //----------------------------//
        // 2. Process Request
        //now trigger
        $this->trigger(
            'system-schema-create',
            $payload['request'],
            $payload['response']
        );

        //----------------------------//
        // 3. Interpret Results
        //if the event returned an error
        if ($payload['response']->isError()) {
            //collect all the errors
            $errors[$data['name']] = $payload['response']->getValidation();
            continue;
        }

        $processed[] = $data['name'];
    }

    if ($this->isPackage('cradlephp/cradle-role')) {
        $payload = $this->makePayload();
        $payload['request']
            ->setStage('schema', 'role')
            ->setStage('role_slug', 'developer');

        $this->trigger(
            'system-model-detail',
            $payload['request'],
            $payload['response']
        );

        if (!$payload['response']->isError()
            && $payload['response']->hasResults('role_admin_menu')
        ) {
            $id = $payload['response']->getResults('role_id');
            $menu = $payload['response']->getResults('role_admin_menu');

            $menu[] = [
                'icon' => 'fas fa-code',
                'path' => '#menu-api',
                'label' => 'API',
                'children' => [
                    [
                        'icon' => 'fas fa-mobile-alt',
                        'path' => '/admin/system/model/app/search',
                        'label' => 'Applications'
                    ],
                    [
                        'icon' => 'fas fa-address-card',
                        'path' => '/admin/system/model/session/search',
                        'label' => 'Sessions'
                    ],
                    [
                        'icon' => 'fas fa-phone',
                        'path' => '/admin/system/model/rest/search',
                        'label' => 'REST Calls'
                    ],
                    [
                        'icon' => 'fas fa-comments',
                        'path' => '/admin/system/model/webhook/search',
                        'label' => 'Webhooks'
                    ]
                ]
            ];

            $payload['request']
                ->setStage('role_id', $id)
                ->setStage('role_admin_menu', $menu);

            $this->trigger(
                'system-model-update',
                $payload['request'],
                $payload['response']
            );
        }
    }

    if (!empty($errors)) {
        $this->getResponse()->set('json', 'validation', $errors);
    }

    $this->getResponse()->setResults('schemas', $processed);
});

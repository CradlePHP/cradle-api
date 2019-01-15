<?php //-->
/**
 * This file is part of Cradle API Package.
 * (c) 2018 Sterling Technologies.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

require_once __DIR__ . '/package/events.php';
require_once __DIR__ . '/package/helpers.php';
require_once __DIR__ . '/src/app/events.php';
require_once __DIR__ . '/src/app/controller.php';
require_once __DIR__ . '/src/dialog/events.php';
require_once __DIR__ . '/src/dialog/controller.php';
require_once __DIR__ . '/src/rest/events.php';
require_once __DIR__ . '/src/rest/controller.php';
require_once __DIR__ . '/src/developer/controller.php';
require_once __DIR__ . '/src/webhook/events.php';

//bootstrap
$this
    ->preprocess(include __DIR__ . '/src/bootstrap/rest.php')
    ->preprocess(include __DIR__ . '/src/bootstrap/webhooks.php');

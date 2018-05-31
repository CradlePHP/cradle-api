<?php //-->
/**
 * This file is part of Cradle API Package.
 * (c) 2018 Sterling Technologies.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

require_once __DIR__ . '/package/events.php';
require_once __DIR__ . '/src/events.php';
require_once __DIR__ . '/src/controller.php';
require_once __DIR__ . '/src/App/events.php';
require_once __DIR__ . '/src/App/controller.php';
require_once __DIR__ . '/src/App/Validator.php';
require_once __DIR__ . '/src/Dialog/events.php';
require_once __DIR__ . '/src/Dialog/controller.php';
require_once __DIR__ . '/src/Session/events.php';
require_once __DIR__ . '/src/Session/controller.php';
require_once __DIR__ . '/src/Session/Validator.php';
require_once __DIR__ . '/src/Webhook/events.php';
require_once __DIR__ . '/src/Webhook/controller.php';
require_once __DIR__ . '/package/helpers.php';

//bootstrap
$this->preprocess(include __DIR__ . '/src/helpers.php');

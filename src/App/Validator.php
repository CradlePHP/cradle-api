<?php //-->
/**
 * This file is part of Cradle API Package.
 * (c) 2018 Sterling Technologies.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */
 namespace Cradle\Package\Api\App;

 use Cradle\Package\System\Schema;
 use Cradle\Module\System\Utility\Validator as UtilityValidator;

 use Cradle\Http\Request;
 use Cradle\Http\Response;

/**
 * Validator layer
 *
 * @vendor   cradlephp
 * @package  api
 * @author   April Sacil <aprilvsacil@gmail.com>
 * @standard PSR-2
 */
class Validator
{
    /**
     * Returns App Webhook Errors
     *
     * @param *array $data
     * @param array  $errors
     *
     * @return array
     */
    public static function getWebhookErrors(array $data, array $errors = [])
    {
        if (isset($data['app_webhooks'])
            && !empty($data['app_webhooks'])
            && (!isset($data['app_webhook_url'])
            || empty($data['app_webhook_url']))
        ) {
            $errors['app_webhook_url'] = 'Webhook URL is required if any of the event webhook is enabled.';
        }

        return $errors;
    }
}

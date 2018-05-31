<?php //-->
/**
 * This file is part of Cradle API Package.
 * (c) 2018 Sterling Technologies.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */
 namespace Cradle\Package\Api\Session;

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
     * Returns Session Access Errors
     *
     * @param *array $data
     * @param array  $errors
     *
     * @return array
     */
    public static function getAccessErrors(array $data, array $errors = [])
    {
        //code        Required
        if (!isset($data['code']) || empty($data['code'])) {
            $errors['code'] = 'Cannot be empty';
        }

        //client_id        Required
        if (!isset($data['client_id']) || empty($data['client_id'])) {
            $errors['client_id'] = 'Cannot be empty';
        }

        //client_secret     Required
        if (!isset($data['client_secret']) || empty($data['client_secret'])) {
            $errors['client_secret'] = 'Cannot be empty';
        }

        if (empty($errors)) {
            // we will be needing these to trigger an event
            $request = new Request();
            $response = new Response();

            // set $data
            $request->setStage($data);

            // get detail
            cradle()->trigger('session-detail', $request, $response);
            $results = $response->getResults();

            if (!$results) {
                $errors['code'] = 'Invalid Authorization Code';
            } else {
                if ($data['client_id'] !== $results['app_token']) {
                    $errors['client_id'] = 'Invalid Client Credentials';
                }

                if ($data['client_secret'] !== $results['app_secret']) {
                    $errors['client_secret'] = 'Invalid Client Credentials';
                }

                // code has to have less than 15 minutes life time
                if (strtotime($results['session_created']) > strtotime('-15 miniutes')) {
                    $errors['code'] = 'Authorization Code has expired';
                }

                // also it has to be PENDING
                if (strtoupper($results['session_status']) !== 'PENDING') {
                     $errors['code'] = 'Authorization Code has been used';
                }
            }
        }

        return $errors;
    }

    /**
     * Returns Optional Errors
     *
     * @param *array $data
     * @param array  $errors
     *
     * @return array
     */
    public static function getOptionalErrors(array $data, array $errors = [])
    {
        // no extra validations for now

        return $errors;
    }
}

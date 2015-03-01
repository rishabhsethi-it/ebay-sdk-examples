<?php

/**
 * Copyright 2015 rishabh sethi
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Include the SDK by using the autoloader from Composer.
 */
require __dir__ . '/vendor/autoload.php';

/**
 * Include the configuration values.
 *
 * Ensure that you have edited the configuration.php file
 * to include your application keys.
 *
 * For more information about getting your application keys, see:
 * http://devbay.net/sdk/guides/application-keys/
 */
$config = require __dir__ . '/configuration.php';

/**
 * The namespaces provided by the SDK.
 */
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Trading\Services;
use \DTS\eBaySDK\Trading\Types;


/**
 * Create the service object.
 *
 * For more information about creating a service object, see:
 * http://devbay.net/sdk/guides/getting-started/#service-object
 */
$service = new Services\TradingService(
            array('apiVersion' => $config['tradingApiVersion'],
                        'siteId' => Constants\SiteIds::US));

/**
 * Create the request object.
 * A SecretID and  SessionID is required,
 * to be added in configuration file 
 * For more information about creating a request object, see:
 * http://devbay.net/sdk/guides/getting-started/#request-object
 */

$request = new Types\FetchTokenRequestType(
        array('SecretID' => $config['sandbox']['SecretID'],
                'SessionID' => $config['sandbox']['SessionID']));
/**
 * An user token is required when using the Trading service.
 *
 * For more information about getting your user tokens, see:
 * http://devbay.net/sdk/guides/application-keys/
 */
$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
$request->RequesterCredentials->eBayAuthToken = $config['sandbox']['userToken'];

/**
 * Send the request to the FetchToken service operation.
 * For more information about calling a service operation, see:
 * http://devbay.net/sdk/guides/getting-started/#service-operation
 * 
 * If user has completed consent form generated,using
 * https://signin.ebay.com/ws/eBayISAPI.dll?SignIn&RUName=RUName&SessID=SessionID
 *
 * For more information about calling a Fetch Token service operation, see:
 * http://developer.ebay.com/Devzone/guides/ebayfeatures/Basics/Tokens-MultipleUsers.html
 * 
 */

$response = $service->FetchToken($request);

/**
 * Output the result of calling the service operation.
 *
 * For more information about working with the service response object, see:
 * http://devbay.net/sdk/guides/getting-started/#response-object
 */
if ($response->Ack !== 'Success')
{
    if (isset($response->Errors))
    {
        foreach ($response->Errors as $error)
        {
            printf("Error: %s\n", $error->ShortMessage);
        }
    }
} else
{
    printf("The Authentication Token is: %s\n", $response->eBayAuthToken);
}

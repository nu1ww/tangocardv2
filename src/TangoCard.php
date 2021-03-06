<?php

$base = realpath(dirname(__FILE__) . '/..');

//require "TangoCardBase.php";
//namespace Sourcefuse;

/**
 * Copyright 2014 Sourcefuse, Inc.
 *
 *    The MIT License (MIT)
 *
 *    Copyright (c) 2014 SourceFuse
 *
 *    Permission is hereby granted, free of charge, to any person obtaining a copy
 *    of this software and associated documentation files (the "Software"), to deal
 *    in the Software without restriction, including without limitation the rights
 *    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *    copies of the Software, and to permit persons to whom the Software is
 *    furnished to do so, subject to the following conditions:
 *
 *    The above copyright notice and this permission notice shall be included in all
 *    copies or substantial portions of the Software.
 *
 *    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 *    SOFTWARE.

 */
class TangoCard extends TangoCardBase
{

    /**
     * The Application Mode.
     *
     * @var string
     */
    protected $appMode = "production";

    /**
     * The Platform Name provided by Tangocard.
     *
     * @var string
     */
    protected $platformName;

    /**
     * The Platform Key provided by Tangocard.
     *
     * @var string
     */
    protected $platformKey;

    /**
     * $appVersion defines tangocard RAAS api version
     *
     * @var string
     */
    protected $tangoCardApiVersion = 'v2';

    /**
     * set application Configurations.
     */

    /**
     * Set the Application Mode.
     *
     * @param string $appMode The application mode
     *
     * @return BaseTangoCard
     */
    public function setAppMode($appMode)
    {
        if (in_array($appMode, array_keys(self::$appModes)))
            $this->appMode = $appMode;
        else
            throw new TangoCardAppModeInvalidException();

        return $this;
    }

    /**
     * @staticvar array $appModes contains application modes and its endpoints
     *
     *
     * public static $appModes = array(
     * 'sandbox' => 'https://sandbox.tangocard.com/raas',
     * 'production' => 'https://integration-api.tangocard.com/raas'
     * );
     */
    public static $appModes = array(
        'sandbox' => 'https://integration-api.tangocard.com/raas',
        'production' => 'https://integration-api.tangocard.com/raas'
    );

    /**
     * @staticvar array $url contains available tangocard api's url
     *
     */
    public static $url = array(
        'createCustomer' => 'customers',

        'createAccount' => 'accounts',
        'setAccountInfoByCustomer' => 'customers/',
        'getAccountInfoByCustomer' => 'customers/',
        'getAccountInfo' => 'accounts/',

        'registerCreditCard' => 'creditCards',
        'getCreditCards' => 'creditCards',
        'deleteCreditCard' => 'creditCardUnregisters',
        'fundAccount' => 'creditCardDeposits',

        'listCatalogs' => 'catalogs?verbose=true',

        'getPlacedAllOrder' => 'orders',
        'placeOrder' => 'orders',

        'getOrderInfo' => 'orders/',
        'reSendOrder' => 'orders'
    );

    /**
     * get Request Url for requested Api
     *
     * @param string $requestType The requested api
     *
     * @return BaseTangoCard
     */
    public function getRequestUrl($requestType)
    {
        $requestTypes = array_keys(self::$url);
        if (!in_array($requestType, $requestTypes)) {
            throw new TangoCardRequestTypeInvalidException();
        }
        $tangoCardApiUrl = self::$appModes[$this->appMode];
        $requestEndpoint = self::$url[$requestType];
        $url = $tangoCardApiUrl . "/" . $this->tangoCardApiVersion . "/" . $requestEndpoint;
        return $url;
    }

    /**
     * Get the Applicaton Mode.
     *
     * @return string the application Mode
     */
    public function getAppMode()
    {
        return $this->appMode;
    }

    /**
     * Set the Tango Card Api Version.
     *
     * @param string $apiVersion contains TangoCard Raas Api version
     *
     * @return BaseTangoCard
     */
    public function setTangoCardApiVersion($apiVersion)
    {
        $this->tangoCardApiVersion = $apiVersion;
        return $this;
    }

    /**
     * Get the Tango Card Api Version.
     *
     * @return string the Tangocard RAAS api version
     */
    public function getTangoCardApiVersion()
    {
        return $this->tangoCardApiVersion;
    }

    /**
     * Set the Platform Name.
     *
     * @param string $platformName The platform Name provided by Tango Card
     *
     * @return BaseTangoCard
     */
    public function setPlatformName($platformName)
    {
        $this->platformName = $platformName;
        return $this;
    }

    /**
     * Get the Platform Name.
     *
     * @return string the Platform Name provided by Tango Card
     */
    public function getPlatformName()
    {
        return $this->platformName;
    }

    /**
     * Set the Platform key.
     *
     * @param string $platformKey The Platform Key  (app secret) Provided by Tango Card
     *
     * @return BaseTangoCard
     */
    public function setPlatformKey($platfromKey)
    {
        $this->platformKey = $platfromKey;
        return $this;
    }

    /**
     * Get the Platform Key.
     *
     * @return string The Platform key (app secret) provided by Tango Card
     */
    public function getPlatformKey()
    {
        return $this->platformKey;
    }

    /**
     * Constructor
     */
    public function __construct($platformName, $platformKey)
    {
        $this->setPlatformName($platformName);
        $this->setPlatformKey($platformKey);
    }

    /**
     * Create new account.
     *
     * @param string $customer The Customer Name (The platform's customer)
     * @param string $accountIdentifier The Account Identifier (The identifier of the account for whom to place the order.)
     * @param string $email The Email
     *
     * @return array in response
     */
    public function createAccount($customerIdentifier, $accountIdentifier, $displayName, $email)
    {

        $data['accountIdentifier'] = $accountIdentifier;
        $data['displayName'] = $displayName;
        $data['contactEmail'] = $email;

        $requestUrl = $this->getRequestUrl('createAccount') . '/' . $customerIdentifier . '/' . accounts;
        $response = parent::tangoCardRequest($requestUrl, $data, TRUE);
        return json_decode($response);
    }


    public function createCustomer($customer, $accountIdentifier, $email)
    {
        $data['displayName'] = $customer;
        $data['customerIdentifier'] = $accountIdentifier;
        // $data['email'] = $email;
        $requestUrl = $this->getRequestUrl('createCustomer');
        $response = parent::tangoCardRequest($requestUrl, $data, TRUE);
        return json_decode($response);
    }

    /**
     * Register a Credit Card.
     *
     * @param string $customer The Customer Name (The platform's customer)
     * @param string $accountIdentifier The Account Identifier (The identifier of the account for whom to place the order.)
     * @param string $ccNumber The Credit Card Number
     * @param string $securityCode The Credit card's Security Code
     * @param string $expiration The Account Identifier
     * @param string $fName The Customer's First Name
     * @param string $lName The Customer's Last Name
     * @param string $address The Customer's Address
     * @param string $city The Customer's City
     * @param string $state The Customer's State
     * @param string $zip The Zip Code or postal Code
     * @param string $state State
     * @param string $country Country
     * @param string $email The Customrer's Email
     * @param string $clientIp The client ip (optional)
     *
     * @return array in response
     */
    public function registerCreditCard($customerIdentifier, $accountIdentifier,$number,$expiration,$verificationNumber,$firstName,$lastName,$emailAddress,$addressLine1,$addressLine2,$city,$country,$postalCode,$state, $clientIp = "0")
    {
        $data['customerIdentifier'] = $customerIdentifier;
        $data['accountIdentifier'] = $accountIdentifier;
        $data['label'] = 'Card -'.substr($number, -4);
        //If the client ip is not passed, check if $_SERVER REMOTE address is set(php is runnning as a server) else pass 0.0 broadcast address
        $data['ipAddress'] = $_SERVER['REMOTE_ADDR'];

        $ccInfo['number'] = $number;
        $ccInfo['expiration'] = $expiration;
        $ccInfo['verificationNumber'] = $verificationNumber;

        $billingInfo['firstName'] = $firstName;
        $billingInfo['lastName'] = $lastName;
        $billingInfo['emailAddress'] = $emailAddress;
        $billingInfo['addressLine1'] = $addressLine1;
        $billingInfo['addressLine2'] = $addressLine2;
        $billingInfo['city'] = $city;
        $billingInfo['country'] = $country;
        $billingInfo['postalCode'] = $postalCode;
        $billingInfo['state'] = $state;

        $data['billingAddress'] = $billingInfo;
        $data['creditCard'] = $ccInfo;
        $requestUrl = $this->getRequestUrl('registerCreditCard');
        $response = parent::tangoCardRequest($requestUrl, $data, TRUE);
        return json_decode($response);
    }





    public function getCreditCards()
    {
        $requestUrl = $this->getRequestUrl('getCreditCards');
        $response = parent::tangoCardRequest($requestUrl);
        return json_decode($response);
    }
    /**
     * Fund a platform's account.
     *
     * @param string $customer The Customer Name (The platform's customer)
     * @param string $accountIdentifier The Account Identifier (The identifier of the account for whom to place the order.)
     * @param string $ccToken The Register Credit Card's Token
     * @param string $securityCode The Credit card's Security Code
     * @param string $clientIp The client ip (optional)
     *
     * @return array in response
     */
    public function fundAccount($accountIdentifier, $customerIdentifier, $amount, $creditCardToken)
    {
        $data['accountIdentifier'] = $accountIdentifier;
        $data['customerIdentifier'] = $customerIdentifier;
        $data['amount'] = $amount;
        $data['creditCardToken'] = $creditCardToken;

        $requestUrl = $this->getRequestUrl('fundAccount');
        $response = parent::tangoCardRequest($requestUrl, $data, TRUE);
        return json_decode($response);
    }

    /**
     * Delete a registered Credit Card
     *
     * @param string $customer The Customer Name (The platform's customer)
     * @param string $accountIdentifier The Account Identifier (The identifier of the account for whom to place the order.)
     * @param string $ccToken The Register Credit Card's Token
     *
     * @return array in response
     */
    public function deleteCreditCard($customer, $accountIdentifier, $ccToken)
    {
        $data['customer'] = $customer;
        $data['account_identifier'] = $accountIdentifier;
        $data['cc_token'] = $ccToken;
        $requestUrl = $this->getRequestUrl('deleteCreditCard');
        $response = parent::tangoCardRequest($requestUrl, $data, TRUE);
        return json_decode($response);
    }

    /**
     * Place an order.
     *
     * @param string $customer The Customer Name (The platform's customer)
     * @param string $accountIdentifier The Account Identifier (The identifier of the account for whom to place the order.)
     * @param string $campaign Name of the E-mail Campaign
     * @param string $rewardFrom An optional 'from name' to display to the recipient
     * @param string $rewardSubject An optional subject line for the reward email.
     * @param string $rewardMessage An optional message to the recipient.
     * @param string $sku The SKU identifying the reward to be purchased
     * @param float $amount The desired amount (for variable-priced SKUs) of the reward. Must be present for variable-price SKUs, must not be present for static-price SKUs.
     * @param string $recipientName The name of the recipient.
     * @param string $recipientEmail The email address of the recipient.
     * @param bool $sendReward Whether Tango Card should send the reward. If this is false the returned object. by Default TRUE
     *
     * @return array in response
     */


    public function placeOrder($customerIdentifier, $accountIdentifier, $amount = NULL, $campaign, $emailSubject, $externalRefID, $message, $notes, $recipientEmail, $recipientFirstName, $recipientLastName, $senderEmail, $senderFirstName, $senderLastName, $sendEmail = TRUE, $utid)
    {

        $data['accountIdentifier'] = $accountIdentifier;
        $data['customerIdentifier'] = $customerIdentifier;
        if ($amount) {
            $data['amount'] = (int)$amount;
        }
        $data['campaign'] = $campaign;
        $data['externalRefID'] = $externalRefID;
        $data['emailSubject'] = $emailSubject;
        $data['message'] = $message;
        $data['notes'] = $notes;

        $data['recipient']['email'] = $recipientEmail;
        $data['recipient']['firstName'] = $recipientFirstName;
        $data['recipient']['lastName'] = $recipientLastName;


        $data['sendEmail'] = ($sendEmail) ? TRUE : FALSE;
        $data['utid'] = $utid;

        $data['sender']['email'] = $senderEmail;
        $data['sender']['firstName'] = $senderFirstName;
        $data['sender']['lastName'] = $senderLastName;
        echo json_encode($data);

        $requestUrl = $this->getRequestUrl('placeOrder');
        $response = parent::tangoCardRequest($requestUrl, $data, TRUE);
        return json_decode($response);
    }

    /**
     * Retrieve a historical order.
     *
     * @param string $orderId The orderId.
     *
     * @return array Order Information
     */
    public function getOrderInfo($orderId)
    {
        $requestUrl = $this->getRequestUrl('getOrderInfo') . $orderId;
        $response = parent::tangoCardRequest($requestUrl);
        return json_decode($response);
    }

    /**
     * Get Account's Info.
     *
     * @param string $customer The Customer Name (The platform's customer)
     * @param string $accountId The Account Identifier (The identifier of the account for whom to place the order.)
     *
     * @return array account Info
     */
    public function getAccountInfo($customer)
    {
        $requestUrl = $this->getRequestUrl('getAccountInfo') . $customer;
        $response = parent::tangoCardRequest($requestUrl);
        return json_decode($response);
    }

    public function setAccountInfoByCustomer($customer,$accountIdentifier,$contactEmail,$displayName)
    {
        $requestUrl = $this->getRequestUrl('setAccountInfoByCustomer') . $customer.'/accounts';
        $data['accountIdentifier'] = $accountIdentifier;
        $data['contactEmail'] = $contactEmail;
        $data['displayName'] = $displayName;
        $response = parent::tangoCardRequest($requestUrl, $data, TRUE);
        return json_decode($response);
    }


    public function getAccountInfoByCustomer($customer)
    {
        $requestUrl = $this->getRequestUrl('getAccountInfoByCustomer') . $customer.'/accounts';
        $response = parent::tangoCardRequest($requestUrl);
        return json_decode($response);
    }

    /**
     * A list of rewards
     *
     * @return array reward List
     */
    public function listCatalogs()
    {
        $requestUrl = $this->getRequestUrl('listCatalogs');
        $response = parent::tangoCardRequest($requestUrl);
        return json_decode($response);
    }

    /**
     * Get All Historic Orders
     *
     * @param string $customer The Customer Name (The platform's customer)
     * @param string $accountIdentifier The Account Identifier (The identifier of the account for whom to place the order.)
     * @param string $offset How far into the resultset to start
     * @param string $limit How many to return (maximum of 100).
     * @param string $startDate datetimes (ISO 8601) between which to search(optional)
     * @param string $endDate datetimes (ISO 8601) between which to search(optional)
     * @return array Order History
     */
    public function getOrderHistory($customer, $accountId, $offset = NULL, $limit = NULL, $startDate = NULL, $endDate = NULL)
    {
        $query = '';
        if ($customer) {
            $query .= 'customer=' . $customer;
        }
        if ($accountId) {
            $query .= '&account_identifier=' . $accountId;
        }

        if ($offset || $offset == 0) {
            $query .= '&offset=' . $offset;
        }
        if ($limit) {
            $query .= '&limit=' . $limit;
        }
        if ($startDate) {
            $query .= '&start_date=' . $startDate;
        }
        if ($endDate) {
            $query .= '&end_date=' . $endDate;
        }
        $requestUrl = $this->getRequestUrl('orderHistory') . $query;
        $response = parent::tangoCardRequest($requestUrl);
        return json_decode($response);
    }

}

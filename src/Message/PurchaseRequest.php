<?php

namespace Omnipay\AmazonPayments\Message;

use Omnipay\Common\Message\AbstractRequest;


class PurchaseRequest extends AbstractRequest
{   

    // protected $liveEndpoint = "https://mws.amazonservices.com/OffAmazonPayments/2013-01-01/";
    // protected $testEndpoint = "https://mws.amazonservices.com/OffAmazonPayments_Sandbox/2013-01-01/";

    protected $liveEndpoint = "https://static-na.payments-amazon.com/OffAmazonPayments/us/js/Widgets.js";
    protected $testEndpoint = "https://static-na.payments-amazon.com/OffAmazonPayments/us/sandbox/js/Widgets.js";

    public function getSellerId()
    {

        return $this->getParameter('seller_id');
    }

    public function setSellerId($value)
    {

        return $this->setParameter('seller_id', $value);
    }

    public function getAccessKey()
    {
        return $this->getParameter('access_key');
    }

    public function setAccessKey($value)
    {
        return $this->setParameter('access_key', $value);
    }

    public function getSecretKey()
    {
        return $this->getParameter('secret_key');
    }

    public function setSecretKey($value)
    {
        return $this->setParameter('secret_key', $value);
    }

    public function getClientId()
    {
        return $this->getParameter('client_id');
    }

    public function setClientId($value)
    {
        return $this->setParameter('client_id', $value);
    }

    public function getRegion()
    {
        return $this->getParameter('region');
    }

    public function setRegion($value)
    {
        return $this->setParameter('region', $value);
    }

    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    public function getTestMode()
    {
        return $this->getParameter('testMode');
    }

    public function setTestMode($value)
    {
        return $this->setParameter('testMode', $value);
    }

    public function getData()
    {   

        $config = array('merchant_id'   => $this->getParameter('seller_id'),
                        'access_key'    => $this->getParameter('access_key'),
                        'secret_key'    => $this->getParameter('secret_key'),
                        'client_id'     => $this->getParameter('client_id'),
                        'region'        => 'us',
                        'currency_Code' => 'USD',
                        'sandbox'       => $this->getParameter('testMode')
                );

        // Instantiate the client object with the configuration
        $client = new \PayWithAmazon\Client($config);

        // Create the parameters array to set the order
        $requestParameters = array();
        $requestParameters['amazon_order_reference_id'] = \Craft\craft()->httpSession->get('amazon_order_reference_id');

        // Confirm the order by making the ConfirmOrderReference API call
        $response = $client->confirmOrderReference($requestParameters);

        $responsearray['confirm'] = json_decode($response->toJson());

        // if response status is not 200 then create error
        if( $responsearray['confirm']->{'ResponseStatus'} != '200' ) {

            $responsearray['error'] = array( 
                //'error'   => ( $responsearray['confirm']->{'ResponseStatus'} != '200' ) ? true : '',
                'message' => $responsearray['confirm']->{'Error'}->{'Message'},
                'code'    => $responsearray['confirm']->{'Error'}->{'Code'}
            );
        }

        // If the API call was a success make the Authorize (with Capture) API call
        if($client->success)
        {
            $requestParameters['authorization_amount'] = $this->getAmount();
            $requestParameters['authorization_reference_id'] = uniqid('A01_REF_');
            $requestParameters['seller_Authorization_Note'] = 'Authorizing and capturing the payment';
            $requestParameters['transaction_timeout'] = 0;
            
            // For physical goods the capture_now is recommended to be set to false. The capture_now can be set to true if the order was a digital good
            $requestParameters['capture_now'] = false;
            $requestParameters['soft_descriptor'] = null;

            $response = $client->authorize($requestParameters);
            $responsearray['authorize'] = json_decode($response->toJson());
        }

        //print_r($responsearray);
        return $responsearray;
    }


    public function sendData($data)
    {      
        return $this->response = new \Omnipay\AmazonPayments\Message\PurchaseResponse($this, $data);
    }

    public function getEndpoint()
    {   
        return ( strtolower( $this->getTestMode() ) == 'true' ) ? $this->testEndpoint : $this->liveEndpoint;
    }
}

<?php
namespace Omnipay\AmazonPayments;

use Omnipay\Common\AbstractGateway;

/**
 * Amazon Gateway
 *
 * @link https://payments.amazon.com/developer
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'AmazonPayments';
    }

    public function getDefaultParameters()
    {
        return array(
          'seller_id'  => '',
          'access_key' => '',
          'secret_key' => '',
          'client_id'  => '',
          'region'     => '',
          'language'   => 'en_US',
          'testMode'   => '',
        );
    }

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

    public function authorize(array $parameters = array())
    {

        // get gateway settings from database
        $amazon_settings = \Craft\craft()->db->createCommand()
            ->select('settings')
            ->from('commerce_paymentmethods')
            ->where("name='Amazon'")
            ->queryRow();

        $obj = json_decode($amazon_settings['settings']);
        //var_dump(json_decode($amazon_settings['settings'], true));

        // define setttings we need fro Amazon
        $merchant_id = $obj->{'seller_id'}; // SellerID
        $access_key  = $obj->{'access_key'}; // MWS Access Key
        $secret_key  = $obj->{'secret_key'}; // MWS Secret Key
        $client_id   = $obj->{'client_id'}; // Login With Amazon Client ID

        $config = array('merchant_id'   => $merchant_id,
                        'access_key'    => $access_key,
                        'secret_key'    => $secret_key,
                        'client_id'     => $client_id,
                        'region'        => 'us',
                        'currency_Code' => 'USD',
                        'sandbox'       => true
                );

        // Instantiate the client object with the configuration
        $client = new \PayWithAmazon\Client($config);
        $requestParameters = array();

        // Create the parameters array to set the order
        $requestParameters['amount']                    = $_POST['amount'];//'19.95';
        $requestParameters['currency_code']             = 'USD';
        $requestParameters['seller_note']               = '';
        $requestParameters['seller_order_id']           = $_POST['orderId'];
        $requestParameters['store_name']                = 'Gundalow Juice';
        $requestParameters['seller_Id']                 = null;
        $requestParameters['platform_id']               = null;
        $requestParameters['custom_information']        = $_POST['orderUserEmail'];
        $requestParameters['mws_auth_token']            = null;
        $requestParameters['amazon_order_reference_id'] = $_POST['orderReferenceId']; 

        // Set the Order details by making the SetOrderReferenceDetails API call
        $response = $client->setOrderReferenceDetails($requestParameters);

        // If the API call was a success Get the Order Details by making the GetOrderReferenceDetails API call
        if($client->success)
        {
            $requestParameters['address_consent_token'] = $_POST['addressConsentToken'];
            $response = $client->getOrderReferenceDetails($requestParameters);
        }
        // Adding the Order Reference ID to the session so that we can use it in ConfirmAndAuthorize.php
        \Craft\craft()->httpSession->add('amazon_order_reference_id', $_POST['orderReferenceId']);
        //craft()->httpSession->get($key)

        // Pretty print the Json and then echo it for the Ajax success to take in
        $json = json_decode($response->toJson());
    
        return $json;

    }

    public function purchase(array $parameters = array())
    {   

        return $this->createRequest('\Omnipay\AmazonPayments\Message\PurchaseRequest', $parameters);
    }
    
    public function completePurchase(array $parameters = array())
    {   
       
        //return $this->createRequest('\Omnipay\AmazonPayments\Message\PurchaseCompleteRequest', $parameters);
    }
}

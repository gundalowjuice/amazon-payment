<?php
/**
 * test plugin for Craft CMS
 *
 * Test_AmazonPayments Controller
 *
 * --snip--
 * Generally speaking, controllers are the middlemen between the front end of the CP/website and your plugin’s
 * services. They contain action methods which handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering post data, saving it on a model,
 * passing the model off to a service, and then responding to the request appropriately depending on the service
 * method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what the method does (for example,
 * actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 * --snip--
 *
 * @author    kevin douglass
 * @copyright Copyright (c) 2016 kevin douglass
 * @link      kevindouglass.io
 * @package   AmazonPayments
 * @since     1
 */

namespace Craft;

class AmazonPayments_DetailsController extends BaseController
{

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     * @access protected
     */
    protected $allowAnonymous = true;

    /**
     * Handle a request going to our plugin's index action URL, e.g.: actions/details
     //craft()->runController('[PluginHandle]/[ControllerName]/[ActionName]');
     */
    public function actionGetDetails()
    {

        $gateway = craft()->commerce_gateways->getAllGateways()['AmazonPayments'];

        $json = $gateway->getGateway()->authorize();

        //https://craftcms.com/docs/plugins/controllers#baseController-methods
        $this->returnJson( json_encode($json, JSON_PRETTY_PRINT) );
    }
}
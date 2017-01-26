<?php
namespace Commerce\Gateways\Omnipay;

use Commerce\Gateways\OffsiteGatewayAdapter;

class AmazonPayments_GatewayAdapter extends OffsiteGatewayAdapter
{
    public function handle()
    {
        return "AmazonPayments";
    }

}

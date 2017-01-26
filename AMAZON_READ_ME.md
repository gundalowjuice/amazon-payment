

# Client / Developer Amazon Payments Resource
--------------
### Prerequisites
1. You must have an Amazon Payments Sandbox Business Account.
2. You must use the same email address and password for your Amazon Payments Sandbox Business Account and your Amazon Web Services account.
3. If you want to use this plugin for Production environment then you need to create a Amazon Payments Business Account.

--------------
### Sign Up for an Account

**Amazon Seller Central & Login and Pay — Sign Up**

[Full Guide on Setting up Amazon](https://payments.amazon.com/developer/documentation/lpwa/201951060)


*Quick Guide*
1. [Create Account](https://payments.amazon.com/signup)
2. [Set up Sandbox account](https://payments.amazon.com/developer/documentation/lpwa/201956330)
3. Add your website with *Login with Amazon*. Click the dropdown towards the top of the page and select Login with Amazon. The site must contain "https://".
4. Add SSL to server
5. Gather your credentials. Once logged into Seller Central, click Integration -> MWS Access Key from the top nav. You'll need the following
	1. Merchant ID (seller ID)
	2. MWS Access Key
	3. MWS Secret Key
	4. LWA Client ID and Client Secret — *this is the key from "Login with Amazon", NOT the MWS client secret key*
6. Create test account for purchasing products.
	1. In the top Nav, click Integration -> Test Accounts
	2. Click Create a new test account button

--------------



### Production Mode
1. Sign into seller central and click Amazon Payments(Production View).
2. The client will need to add their taxpay identification at this point.
3. After this point, the developer will need to log into Craft and switch TestMode to false
4. Not positive, but there may be updated MWS keys for production


--------------
# Developer Resource

Make sure to read the comments in payment.html and throughout the plugin.

#### Button Styles
* https://payments.amazon.com/developer/documentation/lpwa/201953980
* https://payments.amazon.com/merchant/tools
* The developer could probably create their own style as well with css

--------------

#### Github Examples
* https://amzn.github.io/login-and-pay-with-amazon-sdk-samples/
* https://amzn.github.io/login-and-pay-with-amazon-sdk-samples/simple.html

--------------

#### Plugin / Code Installation
* https://payments.amazon.com/developer/documentation/lpwa/201909330
* PHP 5.2.6 or higher
* Curl 7.26 or higher
* See screenshots folder in plugin for Seller Central pages.
* Add plugin *amazonpayments* to plugins directory and install it within Craft.
* Add payment.html to templates/shop/checkout/. The html file is a modified version of the original starter file from Craft. Important details between the comments "PAYMENT GATEWAYS START HERE / PAYMENT GATEWAYS END HERE".

--------------

#### Craft Process for Pay with Amazon 
1. User clicks Pay with Amazon button
2. Popup window appears that request user to login with Amazon credentials
3. If sucessful login, popup closes and the website redirects/refreshes to display credit card options. Cart details also sent to Amazon as a preauthorization.
4. User clicks pay now button. Data is sent to Amazon, Amazon Authoizes and Confrims payment
5. Website redirects to customer's order page
6. Website captures transaction, as well as Amazon

--------------

#### User Test Accounts for Amazon Pay
1. Test Account 1
	* e: kdouglasstest@gmail.com
	* p: jetboattests
2. Test Account 2
	* e: payment-test@amazon.com
	* p: test123

--------------
#### Kevin Douglass' Seller Credentials
*only use to get an undertanding of how things work. Developer and client need to create their own seller account*
* Seller ID: A4IFQ64BRLX95
* Access Key: AKIAJFV5UD6YJ5UB7N6Q
* Secret Key: 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
* Client ID: amzn1.application-oa2-client.feec23ef506b4b03a8ce192edbe0dcc8
* Region: Northeast
* Language: US_en
* Test Mode: True

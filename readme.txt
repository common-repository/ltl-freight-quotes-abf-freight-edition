=== LTL Freight Quotes - ABF Freight Edition ===
Contributors: enituretechnology
Tags: Eniture, ABF Freight,LTL freight rates,LTL freight quotes,shipping estimates
Requires at least: 6.4
Tested up to: 6.6.2
Stable tag: 3.3.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Real-time LTL freight quotes from ABF Freight. Fifteen day free trial.
 
== Description ==

ABF Freight Systems is a subsidiary of ArcBest Corporation (NASDAQ: ARCB). Headquartered in Fort Smith, Arkansas, it provides a variety of freight and logistics services, including less-than-truckload (LTL). This application retrieves your negotiated ABF LTL freight rates, takes action on them according to the application settings, and displays the result as shipping charges in the Shopify checkout process. If you don’t have an ABF Freight account, contact them at 800-610-5544.

**Key Features**

* Displays negotiated LTL shipping rates in the shopping cart.
* Provide quotes for shipments within the United States and to Canada.
* Custom label results displayed in the shopping cart.
* Display transit times with returned quotes.
* Product specific freight classes.
* Support for variable products.
* Define multiple warehouses.
* Identify which products drop ship from vendors.
* Product specific shipping parameters: weight, dimensions, freight class.
* Option to determine a product's class by using the built in density calculator.
* Option to include residential delivery fees.
* Option to include fees for lift gate service at the destination address.
* Option to mark up quoted rates by a set dollar amount or percentage.
* Works seamlessly with other quoting apps published by Eniture Technology.

**Requirements**

* WooCommerce 6.4 or newer.
* Your ABF Freight account ID.
* An API key from Eniture Technology.

== Installation ==

**Installation Overview**

Before installing this plugin you should have the following information handy:

* Your ABF Freight account ID.

If you need assistance obtaining any of the above information, contact your local ABF Freight office
or call the [ABF Freight](https://arcb.com/abf-freight) corporate headquarters at 800-610-5544.

A more comprehensive and graphically illustrated set of instructions can be found on the *Documentation* tab at
[eniture.com](https://eniture.com/woocommerce-abf-ltl-freight/).

**1. Install and activate the plugin**
In your WordPress dashboard, go to Plugins => Add New. Search for "LTL Freight Quotes - ABF Freight Edition", and click Install Now.
After the installation process completes, click the Activate Plugin link to activate the plugin.

**2. Get an API key from Eniture Technology**
Go to [Eniture Technology](https://eniture.com/woocommerce-abf-ltl-freight/) and pick a
subscription package. When you complete the registration process you will receive an email containing your Eniture API Key and
your login to eniture.com. Save your login information in a safe place. You will need it to access your customer dashboard
where you can manage your API keys and subscriptions. A credit card is not required for the free trial. If you opt for the free
trial you will need to login to your [Eniture Technology](http://eniture.com) dashboard before the trial period expires to purchase
a subscription to the API key. Without a paid subscription, the plugin will stop working once the trial period expires.

**3. Establish the connection**
Go to WooCommerce => Settings => ABF Freight. Use the *Connection* link to create a connection to ABF Freight.

**5. Select the plugin settings**
Go to WooCommerce => Settings => ABF Freight. Use the *Quote Settings* link to enter the required information and choose
the optional settings.


**6. Define warehouses and drop ship locations**
Go to WooCommerce => Settings => ABF Freight. Use the *Warehouses* link to enter your warehouses and drop ship locations.  You should define at least one warehouse, even if all of your products ship from drop ship locations. Products are quoted as shipping from the warehouse closest to the shopper unless they are assigned to a specific drop ship location. If you fail to define a warehouse and a product isn’t assigned to a drop ship location, the plugin will not return a quote for the product. Defining at least one warehouse ensures the plugin will always return a quote.

**7. Enable the plugin**
Go to WooCommerce => Settings => Shipping. Click on the Shipping Zones link. Add a US domestic shipping zone if one doesn’t already exist. Click the “+” sign to add a shipping method to the US domestic shipping zone and choose ABF Freight from the list.

**8. Configure your products**
Assign each of your products and product variations a weight, Shipping Class and freight classification. Products shipping LTL freight should have the Shipping Class set to “LTL Freight”. The Freight Classification should be chosen based upon how the product would be classified in the NMFC Freight Classification Directory. If you are unfamiliar with freight classes, contact the carrier and ask for assistance with properly identifying the freight classes for your  products. 

== Frequently Asked Questions ==

= What happens when my shopping cart contains products that ship LTL and products that would normally ship FedEx or UPS? =

If the shopping cart contains one or more products tagged to ship LTL freight, all of the products in the shopping cart 
are assumed to ship LTL freight. To ensure the most accurate quote possible, make sure that every product has a weight, dimensions and a freight classification recorded.

= What happens if I forget to identify a freight classification for a product? =

In the absence of a freight class, the plugin will determine the freight classification using the density calculation method. To do so the products weight and dimensions must be recorded. This is accurate in most cases, however identifying the proper freight class will be the most reliable method for ensuring accurate rate estimates.

= Why was the invoice I received from ABF Freight more than what was quoted by the plugin? =

One of the shipment parameters (weight, dimensions, freight class) is different, or additional services (such as residential 
delivery, lift gate, delivery by appointment and others) were required. Compare the details of the invoice to the shipping 
settings on the products included in the shipment. Consider making changes as needed. Remember that the weight of the packaging 
materials, such as a pallet, is included by the carrier in the billable weight for the shipment.

= How do I find out what freight classification to use for my products? =

Contact your local ABF Freight office for assistance. You might also consider getting a subscription to ClassIT offered 
by the National Motor Freight Traffic Association (NMFTA). Visit them online at classit.nmfta.org.

= How do I get a ABF Freight account? =

Check your phone book for local listings or call  800-610-5544.

= Where do I find my ABF Freight ID? =

To successfully connect the app to ABF Freight you need an API ID. If you don’t have one, sign into abfs.com and click on eCommerce in the navigation menu.
Locate and click on the section titled ABF API. Click on the Request link associated with the Rate Quote API. Complete and submit the form. The status will change to indicate that your request is being processed. 

= How do I get a Eniture API Key for my plugin? =

You must register your installation of the plugin, regardless of whether you are taking advantage of the trial period or 
purchased a API key outright. At the conclusion of the registration process an email will be sent to you that will include the 
Eniture API Key. You can also login to eniture.com using the username and password you created during the registration process 
and retrieve the Eniture API Key from the My API keys tab.

= How do I change my Eniture API Key from the trail version to one of the paid subscriptions? =

Login to eniture.com and navigate to the My API keys tab. There you will be able to manage the licensing of all of your 
Eniture Technology plugins.

= How do I install the plugin on another website? =

The plugin has a single site API key. To use it on another website you will need to purchase an additional API key. 
If you want to change the website with which the plugin is registered, login to eniture.com and navigate to the My API keys tab. 
There you will be able to change the domain name that is associated with the Eniture API Key.

= Do I have to purchase a second API key for my staging or development site? =

No. Each API key allows you to identify one domain for your production environment and one domain for your staging or 
development environment. The rate estimates returned in the staging environment will have the word “Sandbox” appended to them.

= Why isn’t the plugin working on my other website? =

If you can successfully test your credentials from the Connection page (WooCommerce > Settings > ABF Freight > Connections) 
then you have one or more of the following licensing issues:

1) You are using the Eniture API Key on more than one domain. The API keys are for single sites. You will need to purchase an additional API key.
2) Your trial period has expired.
3) Your current API key has expired and we have been unable to process your form of payment to renew it. Login to eniture.com and go to the My API keys tab to resolve any of these issues.

== Screenshots ==

1. Quote settings page
2. Warehouses and Drop Ships page
3. Quotes displayed in cart

== Changelog ==

= 3.3.7 =
* Fix: Resolved an issue with picking the freight class value for variant products.

= 3.3.6 =
* Fix: Restricted quote requests with incomplete data, ensuring smoother and more accurate processing.

= 3.3.5 =
* Update: Updated connection tab according to WordPress requirements 

= 3.3.4 =
* Update: Introduced an NMFC number field to the product page and included an option to use the NMFC number in the 'ABF Rates My Freight by Weight and' feature.

= 3.3.3 =
* Update: Resolved the UI loading issue in the product variants section.

= 3.3.2 =
* Update: Introduced a shipping rule for the liftgate weight limit.
* Update: Introduced backup rate feature.
* Update: Introduced error management feature.
* Fix: Corrected the tab navigation order in the plugin.
* Fix: Fixed the display of shipping rates on draft orders.

= 3.3.1 =
* Update: Introduced capability to suppress parcel rates once the weight threshold has been reached.
* Update: Compatibility with WordPress version 6.5.3
* Update: Compatibility with PHP version 8.2.0
* Fix:  Incorrect product variants displayed in the order widget.

= 3.3.0 =
* Update: Introduced Limited access delivery feature. 

= 3.2.6 =
* Fix: Markup fee applied to shipping quotes in the following order; 1) Product-specific Mark Up (Product settings);  2) Location-specific Handling Fee / Mark Up (Warehouse settings) and 3) General Handling Fee / Mark Up (Quote settings).

= 3.2.5 =
* Fix: Fixed the alignment issue with the Nesting field on the product detail page.

= 3.2.4 =
* Update: Introduced the handling unit feature.
* Update: Updated the description text in the warehouse.

= 3.2.3 =
* Update: Changed required plan from standard to basic for Show Delivery Estimates on the checkout 

= 3.2.2 =
* Update: Compatibility with WooCommerce HPOS(High-Performance Order Storage)

= 3.2.1 =
* Update: Modified expected delivery message at front-end from “Estimated number of days until delivery” to “Expected delivery by”.
* Fix: Inherent Flat Rate value of parent to variations.
* Fix: Fixed space character issue in city name. 

= 3.2.0 =
* Update: Introduced origin-level markup. 
* Update: Introduced product-level markup
* Update: Introduced a checkbox in the quote settings. On checked will show an additional tab for recent logs. 

= 3.1.17 =
* Update:  Introduced the option of freight class and dimensions

= 3.1.16 =
* Update: Added compatibility with "Address Type Disclosure" in Residential address detection

= 3.1.15 =
* Update: Compatibility with the Dynamic Discount addon.

= 3.1.14 =
* Update: Compatibility with WordPress version 6.1
* Update: Compatibility with WooCommerce version 7.0.1

= 3.1.13 =
* Update: Included origin address line 1 in ABF Freight API. 

= 3.1.12 =
* Fix: Label correction in release 3.1.11 

= 3.1.11 =
* Update: Added new feature, Only show LTL rates if the parcel shipment weight exceeds the weight threshold.

= 3.1.10 =
* Fix: Fixed character with HTML encoding.  

= 3.1.9 =
* Update: Introduced connectivity from the plugin to FreightDesk.Online using Company ID

= 3.1.8 =
* Update: Compatibility with WordPress version 6.0.
* Update: Included tabs for freightdesk.online and validate-addresses.com

= 3.1.7 =
* Update: Compatibility with WordPress multisite network
* Fix: Fixed support link. 

= 3.1.6 =
* Add: Adds discount based on third-party address

= 3.1.5 =
* Add: Adds consignee is responsible for payment for a customer

= 3.1.4 =
* Update: Compatibility with PHP version 8.1.
* Update: Compatibility with WordPress version 5.9.

= 3.1.3 =
* Update: Compatibility with preferred origin custom work.

= 3.1.2 =
* Fix: Fixes for PHP version 8.0.

= 3.1.1 =
* Update: Relocation of NMFC Number field along with freight class.

= 3.1.0 =
* Update: Updated compatibility with the Pallet Packaging plugin and analytics.

= 3.0.0 =
* Update: Compatibility with PHP version 8.0.
* Update: Compatibility with WordPress version 5.8.
* Fix: Corrected product page URL in connection settings tab.

= 2.4.1 =
* Update: Added feature "Weight threshold limit".
* Update: Added feature In-store pickup with terminal information.

= 2.4.0 =
* Update: CSV columns updated.
* Update: Pallet packaging.
* Update: CSV export fix.
* Update: Product images URLs for FDO.
* Update: Virtual product reference in order widget.
* Fix: Issue on the order page quotes.
* Fix: Issue for variable product on save button click.

= 2.3.1 =
* Update: Introduced new features, Compatibility with WordPress 5.7, Order detail widget for draft orders, improved order detail widget for Freightdesk.online, compatibly with Shippable add-on, compatibly with Account Details(ET) add-don(Capturing account number on checkout page).

= 2.3.0 =
* Update: Compatibility with WordPress 5.6. 

= 2.2.4 =
* Update: Introduced product nesting feature. 

= 2.2.3 =
* Update: Added handling unit weight index. 

= 2.2.2 =
* Update: Compatibility with WordPress 5.5, Compatibility with shipping solution freightdesk.online and plans update feature.

= 2.2.1 =
* Update: Compatibility with WordPress 5.4

= 2.2.0 = 
* Update: This update introduces: 1) Cut Off Time & Ship Date Offset. 2) Estimated delivery options. 3) An option to control shipment days of the week.

= 2.1.1 = 
* Update: Change of Label AS logic for shipments origate from multiple locations 

= 2.1.0 = 
* Update: Introduced settings to control quotes sorting on frontend

= 2.0.5 = 
* Update: Introduced settings for frontend message when shipping cannot be calculated 

= 2.0.4 =
* Fix: Removed repeated shipping option in case of Hold At Terminal 

= 2.0.3 =
* Update: Introduced Hold At Terminal feature 

= 2.0.2 =
* Update: Compatibility with WordPress 5.1

= 2.0.1 =
* Fix: Identify one warehouse and multiple drop ship locations in basic plan.

= 2.0.0 =
* Update: Introduced new features and Basic, Standard and Advanced plans.

= 1.2.1 =
* Update: Compatibility with WordPress 5.0

= 1.2.0 =
* Update: Introduced compatibility with the Residential Address Detection plugin.

= 1.1.0 =
* Update: Compatibility with WordPress 4.9

= 1.0.4 =
* Update: Compatibility with WordPress 4.8

= 1.0.3 =
* Fix: Multiplication of product quantity with product weight

= 1.0.2 =
* Update: Standardization of shipping parameter units of measure for API requests

= 1.0.2 =
* Update: Standardization of shipping parameter units of measure for API requests

= 1.0.1 =
* Fix: Error message changed.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

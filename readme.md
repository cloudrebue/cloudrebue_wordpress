# Cloud Rebue Bulk SMS API
Contributors: [Edward Muss](https://github.com/edwardmuss)
Requires at least: `WordPress v5.6`
Tested up to: `WordPress 5.9`
Version: `1.0.5`
Requires PHP Version: `7.0`
License: GPLv3
License URI: [https://www.gnu.org/licenses/gpl-3.0.html](https://www.gnu.org/licenses/gpl-3.0.html)

## Description
Send Woocomerce Notifications, Access Bulk SMS Portal

This plugin enables you to Send Woocomerce Notifications Via SMS straight from the WordPress backend or via the programmers API.

All you need is the plugin and [bulk.cloudrebue.co.ke] (https://bulk.cloudrebue.co.ke) account.

Main features:

* **📱 Send Woocomerce Order Notifications Via SMS**
* **❤️ Easy programmers API**

**Easy to get started:**

- Live chat support and mail support from bulk.cloudrebue.co.ke

**Backed by high quality, lowest pricing SMS-gateway:**


## Installation

This section describes how to install the plugin and get it working.

1. You can either install this plugin from the WordPress Plugin Directory,
  or manually  [download the plugin](https://github.com/cloudrebue/cloudrebue_wordpress/releases) and upload it through the 'Plugins > Add New' menu in WordPress
2. Login to [bulk.cloudrebue.co.ke](https://bulk.cloudrebue.co.ke) and generate API Keys.
3. Go to "Settings » CloudRebueSMS Settings" and add Your account details from your bulk.cloudrebue.co.ke account.
3. Activate order statuses you want to send sms on, leave blank to deactivate

## Screenshots

### Generate Token in your bulk account
![Token](https://github.com/cloudrebue/cloudrebue_wordpress/blob/master/Screenshot_1.png)

### Enter token in settings
![Token](https://github.com/cloudrebue/cloudrebue_wordpress/blob/master/Screenshot_2.png)


## Frequently Asked Questions

**Will the plugin Send personalized order messages**

Yes, It works really well; You have been provided with a number of woo merge tags to use eg 
**Hi `%billing_first_name%`, 
We have finished processing your order `#%order_number%` amounting to `%order_total%`. Thank you.** 



## How to use

### Most users: User Guide

The user interface is quite intuitive and straightforward.


## Advanced: Programmers API

Send an SMS to one or multiple recipients

[See our PHP SDK](https://github.com/cloudrebue/PHP-BULK-SDK)

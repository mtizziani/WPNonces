# WPNonces

This Project creates a WP-Package to use wp_nonce* functions in a OOP scope

## Version
0.1.1


## Description

Using wp_nonce* function in a OOP scope is hard. So i had the experience to write a extension to use Nonces in OOP scope.
You can simply create several WPNonce Objects to handle many nonces.


## Install

use composer for install:

    composer require mtizziani/wpnonces

    

## How to use

#### creating a WPNonce Object
    <?php
    use \Mtizziani\WPNonce
    $obj = new WPNonce();
    $obj->nonce();
    
object looks like this

    <\Mtizziani\WPNonces\WPNonce> {
        [private] <string|int> "action": -1
        [private] <string> "nonce": "26sadfg2"
        
        [public] <function> action( [string $action] ) {returns string}
        [public] <function> nonce( [string $action] ) {return string}
        [public] <function> ays() {returns mixed}
        [public] <function> field( string $fieldName ) {returns string}
        [public] <function> url( string $baseUrl, [string $name] ) {returns string}
        [public] <function> verify(string $nonce) {returns string}
    }
    

#### WPNonce->action([string $action]) *default value = -1.*
Setter and getter for the action property. If $action is a string the property will be set, else it
returns the value of the action.

    <?php
    $obj->action('myActionName');
    $actionResult = $obj->action();
    
#### WPNonce->nonce([string $action]);
if action is already set you can create a nonce string by calling nonce, otherwise you can give in a action name and the 
action will be set or overwritting.

    <?php
    $privateNonceKey = $obj->nonce();
     
    // call without setting action before or want to overwrite action name
    $privateNonceKey = $obj->nonce('myPrivateAction');
    
#### WPNonce->verify(string $nonceHash)
verify the nonce property against a given nonce. returns true if all is fine and false if nonce is not the same
or outdated.

    <?php
    $obj->nonce('action_a'); // creates a hash like '26sadfg2'
    
    $verified = $obj->verify('26sadfg2'); // returned true
    $notVerified = $obj->verify('3245sdf3'); // retured false
    
#### WPNonce->field(string fieldName)
Generates a hidden input node with the field name and the nonce as value for using in a form
    <?php
    $fieldString = $obj->field('myField'); 
    // returns '<input type="hidden" id="myField" name="myField" value="34Da3Bc">' for example

#### WPNonce->url(string baseURL, [string $name])
Generates a URL that is extended with the nonce hash.

    <?php
    $url = $obj->url('http://myExample.com');
    // returned like 'http://myExample.com?26sadfg2'
    
using ays with name attribute

    <?php
    $url = $obj->url('http://myExample.com', 'myNonce');
    // returned like 'http://myExample.com?myNonce=26sadfg2'
    
using base url with params
    
    <?php
    $url = $obj->url('http://myExample.com?param1=234');
    // returned like 'http://myExample.com?param1=234&_wpnonce=26sadfg2';
    
#### WPNonce->ays()  
Handles a ays message by the wp_nonce_ays command based on out obj. For more details please
have a look on [Developer Resource wp_nonce_ays](https://developer.wordpress.org/reference/functions/wp_nonce_ays/)

    <?php
    $obj->ays();
    

### additional a short plugin example

This sample shows you how to use my WPNonce Class in a form *(not tested)*
    
    <?php
    /*
    Plugin Name: WPNonce Sample Form
    Description: A sample to use Mtizziani\WPNonces in a form
    Author: Maik Tizziani
    Version: 0.0.1
    Licence: MIT
    */ 
     
    $fieldName = 'my_nonce_field;
    $actionName = 'my_nonce_action;
    
    $nonce = new WPNonce();
    $nonce->action($action);

    /**
     * generate the form
     */     
    function createFormElement() {
        ob_start(); ?>
            <form method="POST" action="">
                <p>
                    <?php echo Nonce->field($fieldName); ?>
                    <input type="submit" value="Submit"/>
                </p>
            </form>
        <?php
        return ob_get_clean();
    }
    add_shortcode('nonce_form', 'createFormElement');
     
    /**
     * processes the data submitted by the form
     */
    function processFormData() {
        if(isset($_POST[$fieldName])) {
            if(nonce->verify($_POST[$fieldName])) {
                echo 'nonce successfully verified';
                // ...
            } else {
                echo 'verification failed';
                // ...
            }  
        }
    }
    add_action('init', 'processFormData');
    
this sample is inpired by [Pippins Plugins](https://pippinsplugins.com/introduction-to-using-nonces-for-form-validation/)    


## Requirements

- PHP 7.2 (tested)
- Composer for installation


## Dev-Depencies
- PHPUnit ^6.5
- WP_Mock 0.3.0
 
  
### Refrences

- [Wordpress Developer Resources](https://developer.wordpress.org/?s=wp_nonce) for more information on wp functions
- [WP_Mock](https://github.com/10up/wp_mock) for mocking Wordpress functions
- [PHPUnit](https://phpunit.de/) for general unit testing

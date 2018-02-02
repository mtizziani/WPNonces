<?php

namespace Mtizziani\WPNonces;

/**
 * Class WPNonce
 *
 * @package Mtizziani\WPNonces
 */
class WPNonce
{

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $nonce = '';

    /**
     * a simple static function for testing if WPNonce can be called
     *
     * @return bool always return true
     */
    public static function test() {
        return true;
    }

    /**
     * getter and setter for action property
     *
     * if isset $actionName and $actionName is not an empty string after trim
     *   => property action will be set
     * else
     *   => returns an empty string
     *
     * @param string|NULL $nameString
     * @return string
     */
    public function name(string $nameString = NULL): string {
        if(!is_null($nameString)) {
            $nameString = trim($nameString);
            if(strlen($nameString) > 0) {
                $this->name = $nameString;
            }
        }
        return $this->name;
    }

    /**
     * create a nonce and get it back
     *
     * if isset $actionName
     *   => property action will also be set
     *
     * if property action is not empty
     *   => nonce will be created
     *
     * @param string|NULL $nameString
     * @return string
     */
    public function nonce(string $nameString = NULL): string {
        $action = $this->name($nameString);
        $value = '';
        if(strlen($action) > 0) {
            $value = wp_create_nonce($action);
        }
        $this->nonce = $value;
        return $this->nonce;
    }

    /**
     * verify a given nonce by using objects action
     *
     * @param string $nonce
     * @return bool
     */
    public function verify( string $nonce ): bool {
        return wp_verify_nonce($nonce);
    }

    /**
     * get a hidden input field with nonce settings
     *
     * @param bool $referer
     * @param bool $echo
     * @return string
     */

    /**
     * @param string $action
     * @param bool $referer
     * @param bool $echo
     * @return string
     */
    public function field(string $action = "-1",bool $referer = false, bool $echo = false): string {
        // parse action to integer if is default value
        if(is_numeric($action)) {
            $action = (int) $action;
        }
        // always use stored name
        return wp_nonce_field($action, $this->name, $referer, $echo);
    }

}
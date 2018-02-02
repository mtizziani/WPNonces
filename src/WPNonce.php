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
    protected $action = '';

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
     * @param string|NULL $actionName
     * @return string
     */
    public function action(string $actionName = NULL): string {
        if(!is_null($actionName)) {
            $actionName = trim($actionName);
            if(strlen($actionName) > 0) {
                $this->action = $actionName;
            }
        }
        return $this->action;
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
     * @param string|NULL $actionName
     * @return string
     */
    public function nonce(string $actionName = NULL): string {
        $action = $this->action($actionName);
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

}
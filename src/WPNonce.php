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
    protected $action = -1;

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
     * @param string|int|NULL $actionName
     * @return string
     */
    public function action(string $actionName = NULL): string {
        if(!is_null($actionName)) {
            $actionName = trim($actionName);
            if(strlen($actionName) > 0) {
                $this->action = $actionName;
            }
        }

        if($actionName == -1){
            $this->action = $actionName;
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

    /**
     * get a hidden input field with nonce settings
     *
     * @param bool $referer
     * @param bool $echo
     * @return string
     */
    public function field(string $name, bool $referer = false, bool $echo = false): string {
        return wp_nonce_field($this->action, $name, $referer, $echo);
    }

    /**
     * Display “Are You Sure” message to confirm the action being taken.
     *
     * @return mixed
     */
    public function ays() {
        // normal wp_nonce_ays does not return anything directly, managed by wp_die
        // but for testing we have to reply a string
        return wp_nonce_ays($this->action);
    }

    /**
     * Retrieve URL with nonce added to URL query.
     *
     * @param string $actionUrl
     * @param string $name
     * @return string
     */
    public function url(string $actionUrl, string $name = '_wpnonce'): string {
        return wp_nonce_url($actionUrl, $this->nonce(), $name);
    }

}
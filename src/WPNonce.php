<?php

namespace Mtizziani\WPNonces;

/**
 * Class Nonces
 *
 * @author Maik Tizziani <mtizziani@gmail.com>
 * @package Mtizziani\WPNonces
 */
class WPNonce
{
    protected $nonce = '';

    /**
     * a simple static function for testing if WPNonce can be called
     *
     * @return bool always return true
     */
    public static function test() {
        return true;
    }
}
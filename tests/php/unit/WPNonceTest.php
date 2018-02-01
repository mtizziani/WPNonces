<?php
/**
 * Created by PhpStorm.
 * User: maik
 * Date: 01.02.18
 * Time: 22:22
 */

namespace Mtizziani\WPNonces\Tests\php\unit;

use Mtizziani\WPNonces\{
    WPNonce as NonceRoot
};

class WPNonceTest extends \PHPUnit\Framework\TestCase
{

    /**
     * running before every test
     */
    public function setUp(){

    }

    /**
     * running after every test
     */
    public function tearDown(){

    }

    /**
     * @test
     */
    public function If_Static_Test_Function_Returns_True() {
        $result = NonceRoot::test();
        $this->assertTrue($result);
    }
}
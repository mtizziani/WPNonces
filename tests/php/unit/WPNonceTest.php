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

    protected $mockedNonceResult = '295a686963';

    /**
     * running before every test
     */
    public function setUp() {
        \WP_Mock::setUp();
        \WP_Mock::userFunction('wp_create_nonce', array('return' => $this->mockedNonceResult));
    }

    /**
     * running after every test
     */
    public function tearDown() {
        \WP_Mock::tearDown();
    }

    /**
     * @test
     */
    public function If_Static_Test_Function_Returns_True() {
        $result = NonceRoot::test();
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function if_action_is_not_set_return_empty_string() {
        // define what is accepted as correct
        $accepted = '';

        $root = new NonceRoot();
        $askedResult = $root->action();

        $this->assertEquals($root, $accepted);
    }

    /**
     * @test
     */
    public function if_action_will_be_set_and_returned() {
        // generate array with multiple action names
        $actionStrings = array('first_mocked_action', 'second_mocked_action');

        // run over the array for asserting
        foreach($actionStrings as $key => $val) {
            $root = new NonceRoot();
            $directResult = $root->action($val);
            $askedResult = $root->action();

            $this->assertEquals($directResult, $val);
            $this->assertEquals($askedResult, $val);
        }
    }

    /**
     * @test
     */
    public function if_nonce_is_not_create_returns_empty_string() {
        // define what is accepted as correct
        $accepted = '';

        $root = new NonceRoot();
        $askedResult = $root->nonce();

        $this->assertEquals($askedResult, $accepted);
    }

    /**
     * @test
     */
    public function if_nonce_will_be_create_and_returned(){
        // generate array with multiple action names
        $actionStrings = array('first_mocked_action', 'second_mocked_action');

        foreach($actionStrings as $key => $val) {
            $root = new NonceRoot();
            $directResult = $root->nonce($val);
            $askedResult = $root->nonce();

            $this->assertEquals($directResult, $this->mockedNonceResult);
            $this->assertEquals($askedResult, $this->mockedNonceResult);
        }
    }

    /**
     * @test
     */
    public function if_nonce_create_sets_action(){
        // generate array with multiple action names
        $actionStrings = array('first_mocked_action', 'second_mocked_action');

        foreach($actionStrings as $key => $val) {
            $root = new NonceRoot();
            $root->nonce($val);
            $resultAction = $root->action();

            $this->assertNotEmpty($resultAction);
        }
    }
}
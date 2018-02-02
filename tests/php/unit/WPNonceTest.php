<?php
/**
 * Created by PhpStorm.
 * User: maik
 * Date: 01.02.18
 * Time: 22:22
 */

namespace Mtizziani\WPNonces\Tests\php\unit {

    use \PHPUnit\Framework\{
        TestCase as TestCase
    };

    use Mtizziani\WPNonces\{
        WPNonce as NonceRoot
    };

    use WP_Mock;


    class WPNonceTest extends TestCase
    {

        protected $firstNonceHash = '295a686963';
        protected $secondNonceHash = 'c214gd5315';

        /** -------------------------------------------- helpers ---------------------------------------------------- */

        /**
         * helping method for mock wp_verify_nonce with result true or false by input
         *
         * @param string $nonce
         * @param NonceRoot $nonceObject
         */
        private function mockingHelper_verify(string $nonce, NonceRoot $nonceObject){
            $result = ($nonce == $nonceObject->nonce());
            WP_Mock::userFunction('wp_verify_nonce', array('return' => $result));
        }

        /** -------------------------------------------- setup ------------------------------------------------------ */

        /**
         * running before every test
         */
        public function setUp() {


            WP_Mock::setUp();
            WP_Mock::userFunction('wp_create_nonce', array('return' => $this->firstNonceHash));

        }

        /**
         * running after every test
         */
        public function tearDown() {
            WP_Mock::tearDown();
        }

        /** -------------------------------------------- tests ------------------------------------------------------ */

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

            $this->assertEquals($askedResult, $accepted);
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

                $this->assertEquals($directResult, $this->firstNonceHash);
                $this->assertEquals($askedResult, $this->firstNonceHash);
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

        /**
         * @test
         */
        public function if_verify_returns_true_on_correct_input(){
            // prepare test
            $root = new NonceRoot();
            $root->nonce('someActionName');
            $this->mockingHelper_verify($this->firstNonceHash, $root);

            // get result
            $result = $root->verify($this->firstNonceHash);

            // assertion
            $this->assertTrue($result);
        }

        /**
         * @test
         */
        public function if_verify_returns_false_on_incorrect_input(){
            // prepare test
            $root = new NonceRoot();
            $root->nonce('someActionName');
            $this->mockingHelper_verify($this->secondNonceHash, $root);

            // get result
            $result = $root->verify($this->secondNonceHash);

            // assertion
            $this->assertFalse($result);
        }
    }
}
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

        /**
         * helping method for wp_nonce_field
         *
         * @todo handling action, referer, echo in mock
         *
         * @param NonceRoot $nonceObject
         * @param $name
         * @param string|NULL $hash
         */
        private function mockingHelper_field(NonceRoot $nonceObject, $name, string $hash = NULL) {
            if(is_null($hash)){
                $hash = $nonceObject->nonce();
            }
            $out = '<input type="hidden" id="'.$name.'" name="'.$name.'" value="'.$hash.'">';
            WP_Mock::userFunction('wp_nonce_field', array(
                'return' => $out
            ));
        }

        /**
         * helping method for mocking wp_nonce_ays
         *
         * @param NonceRoot $nonceObject
         * @return string
         */
        private function mockingHelper_ays(NonceRoot $nonceObject) {
            $out = 'something';
            if('log-out' == $nonceObject->action()){
                $out = 'logout';
            }
            WP_Mock::userFunction('wp_nonce_ays', array(
                'return' => $out
            ));
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
            $actionStrings = array('first_mocked_action', 'second_mocked_action', -1);

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
            $actionStrings = array('first_mocked_action', 'second_mocked_action', -1);

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
            $actionStrings = array('first_mocked_action', 'second_mocked_action', -1);

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

        /**
         * @test
         */
        public function if_test_returns_a_correct_input_element() {
            // define what is accepted result
            $acceptedName ='_myNamedField';
            $accepted = '<input type="hidden" id="'.$acceptedName.'" name="'.$acceptedName.'" value="'.$this->firstNonceHash.'">';

            // prepare and assert test for matching
            $name = '_myNamedField';
            $root = new NonceRoot();
            $root->nonce(-1);
            $this->mockingHelper_field($root, $name);
            $result = $root->field('_myNamedField');

            $this->assertEquals($result, $accepted);
        }

        /**
         * @test
         */
        public function if_test_returns_a_incorrect_input_element() {
            // define what is accepted
            $acceptedName ='_myNamedField';
            $accepted = '<input type="hidden" id="'.$acceptedName.'" name="'.$acceptedName.'" value="'.$this->firstNonceHash.'">';

            // prepare and assert test for not matching
            $name = '_someOtherName';
            $root = new NonceRoot();
            $root->nonce(-1);
            $this->mockingHelper_field($root, $name, $this->secondNonceHash);
            $result = $root->field($name);

            $this->assertNotEquals($result, $accepted);
        }

        /**
         * @test
         */
        public function if_ays_returns_logout_on_action_logout(){
            $action = 'log-out';
            $accepted = 'logout';

            $root = new NonceRoot();
            $root->nonce($action);
            $this->mockingHelper_ays($root);
            $result = $root->ays();

            $this->assertEquals($result, $accepted);
        }

        /**
         * @test
         */
        public function if_ays_returns_something_on_any_other_action(){
            $testActions = ['my-action', 'other-action', 'random'];
            $accepted = 'something';

            foreach($testActions as $key => $val){
                $root = new NonceRoot();
                $root->nonce($val);
                $this->mockingHelper_ays($root);
                $result = $root->ays();

                $this->assertEquals($result, $accepted);
            }
        }

        /**
         * @test
         */
        public function if_nonce_url_returns_a_url_with_nonce_as_request_parameter() {
            // define params
            $url = 'http:://myUrl';
            $action = 'my_action';

            // define accepted
            $accepted = $url.'?'.$this->firstNonceHash;

            // create mock for wp_nonce_url
            WP_Mock::userFunction('wp_nonce_url', array(
                'return' => $url.'?'.$this->firstNonceHash
            ));

            // create nonce
            $root = new NonceRoot();
            $root->nonce($action);

            // request result
            $result = $root->url($url);

            // assertion
            $this->assertEquals($result);
        }
    }
}
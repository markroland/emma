<?php

class EmmaTest extends PHPUnit_Framework_TestCase {

    protected $EmmaClient;

    public function setup() {

        $this->EmmaClient = new markroland\Emma(
            EMMA_ACCOUNT_ID,
            EMMA_PUBLIC_KEY,
            EMMA_PRIVATE_KEY
        );
    }

    public function test_get_field_list() {
        $response = $this->EmmaClient->get_field_list(1);
        $this->assertNull($response);
    }
}
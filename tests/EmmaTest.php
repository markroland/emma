<?php

namespace MarkRoland\Emma\Tests;

use MarkRoland\Emma\Client;
use PHPUnit_Framework_TestCase;

class EmmaTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    protected $emmaClient;
    /**
     * @var int
     */
    protected $accountId = 123456;
    /**
     * @var string
     */
    protected $publicKey = 'XXXXXXXXXXXXXXXXXXXX';
    /**
     * @var string
     */
    protected $privateKey = 'XXXXXXXXXXXXXXXXXXXX';

    /**
     * Setup Emma Client
     */
    public function setup()
    {
        $this->emmaClient = new Client($this->accountId, $this->publicKey, $this->privateKey);
    }

    /**
     * @test
     */
    public function test_get_field_list()
    {
        $response = $this->emmaClient->get_field_list(1);

        $this->assertNull($response);
    }
}

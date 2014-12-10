<?php

/**
 * Tests for cURL-wrapper used in other tests
 *
 * @package php-bottle
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @license MIT
 */
class CurlTest extends BottleTestCase {
    private $url;

    public function setUp()
    {
        parent::setUp();
        $this->url = $this->buildUrl('curl.php');
    }

    public function testBasicRequest() {
        $req = send_request($this->url);
        $this->assertInternalType('array', $req);
        $this->assertEquals(200, $req['httpcode']);
        $this->assertEquals('Basic request success', $req['content']);
    }

    public function testGetRequest() {
        $req = send_request($this->url, 'GET', ['param' => 'value']);
        $this->assertEquals('GET:param=value'.PHP_EOL, $req['content']);

        $req = send_request($this->url, 'GET', ['param' => 'value', 'param2' => 'value2']);
        $this->assertEquals('GET:param=value'.PHP_EOL.'GET:param2=value2'.PHP_EOL, $req['content']);
    }

    public function testPostRequest() {
        $req = send_request($this->url, 'POST', ['param' => 'value']);
        $this->assertEquals('POST:param=value'.PHP_EOL, $req['content']);

        $req = send_request($this->url, 'POST', ['param' => 'value', 'param2' => 'value2']);
        $this->assertEquals('POST:param=value'.PHP_EOL.'POST:param2=value2'.PHP_EOL, $req['content']);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidMethod() {
        $req = send_request($this->url, 'FAIL', ['param' => 'value']);
    }
}

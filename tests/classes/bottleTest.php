<?php

/**
 * Functional tests for PHP-Bottle
 *
 * @package php-bottle
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @license MIT
 */
class BottleTest extends BottleTestCase {
    public function testIndexPage() {
        $url = $this->buildUrl();
        $res = send_request($url);
        $this->assertEquals('Success', $res['content']);
    }

    public function testParam() {
        $url = $this->buildUrl('param/test');
        $res = send_request($url);
        $this->assertEquals('Param: test', $res['content']);
    }

    public function testView() {
        $url = $this->buildUrl('view/test');
        $res = send_request($url);
        $assertedContent = <<<'EOL'
<!DOCTYPE html>
<html>
	<head>
        <meta charset="utf-8" />
		<title>view</title>
	</head>
	<body>
        test
	</body>
</html>

EOL;
        $this->assertEquals($assertedContent, $res['content']);
    }

    function testHeaderOK() {
        $url = $this->buildUrl('err');
        $res = send_request($url);
        $this->assertEquals(404, $res['httpcode']);
    }

    function testHeader500() {
        $url = $this->buildUrl('exception');
        $res = send_request($url);
        $this->assertEquals(500, $res['httpcode']);
    }

    function testHeaderRedirect() {
        $url = $this->buildUrl('redirect');
        $res = send_request($url);
        $this->assertEquals(302, $res['httpcode']);
        $this->assertArrayHasKey('Location', $res['headers']);
        $this->assertEquals('/redirected', $res['headers']['Location']);
    }

    function testRedirect() {
        $url = $this->buildUrl('redirect');
        $content = file_get_contents($url);
        $this->assertEquals('Redirected', $content);
    }

    function testHeader() {
        $url = $this->buildUrl('header');
        $res = send_request($url);

        $this->assertArrayHasKey('Content-type', $res['headers']);
        $this->assertEquals('text/plain', $res['headers']['Content-type']);

    }

    function testUrl() {
        $url = $this->buildUrl('url');
        $content = file_get_contents($url);
        $this->assertEquals('/redirected', $content);

        $url = $this->buildUrl('url2');
        $content = file_get_contents($url);
        $this->assertEquals('/param/name', $content);
    }

    function testRouteWithNonAscii() {
        $data = [
            'subdir_with_éééé/' => 'Success',
            'subdir_with_éééé/é' => 'Successé',
            'subdir_with_éééé/\'' => 'Success\'',
            'subdir_with_éééé/ ' => 'Success ',
        ];

        foreach ($data as $part => $result) {
            $url = $this->buildUrl($part);
            $content = file_get_contents($url);
            $this->assertEquals($result, $content);
        }
    }

    function testSimpleCondition() {
        $url = $this->buildUrl('restricted');
        $content = file_get_contents($url);
        $this->assertEquals('OK', $content);

        $url = $this->buildUrl('restricted2');
        $res = send_request($url);
        $this->assertEquals(403, $res['httpcode']);
    }

    function testArgCondition() {
        $url = $this->buildUrl('restricted3');
        $content = file_get_contents($url);
        $this->assertEquals('OK', $content);

        $url = $this->buildUrl('restricted4');
        $res = send_request($url);
        $this->assertEquals(403, $res['httpcode']);
    }

    function testDynArgCondition() {
        $url = $this->buildUrl('restricted5/url_param');
        $content = file_get_contents($url);
        $this->assertEquals('OK', $content);

        $url = $this->buildUrl('restricted5/error');
        $res = send_request($url);
        $this->assertEquals(403, $res['httpcode']);
    }

    function testGlobalContext() {
        $url = $this->buildUrl('global-context');
        $content = file_get_contents($url);
        $this->assertEquals('global:local:local2', $content);
    }

}

<?php
/**
 * Functional tests for PHP-Bottle
 *
 * @package php-bottle
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @license MIT
 */

define('APPLICATION_PATH', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

class BottleTest extends PHPUnit_Framework_TestCase {

    private $port = 8999;
    private $command = 'php -S localhost:%d 2>&1> /dev/null';
    private $pid = 0;

    /**
     * Setup fixture: starts a test HTTP server and sends it to background
     */
    public function setUp() {
        if(!function_exists('pcntl_fork')) {
            exit('pcntl_fork doesn\'t exists. Exiting...'.PHP_EOL);
        }
        $this->pid = posix_getpid();
        $this->command = sprintf($this->command, $this->port);
        $pid = pcntl_fork();

        if($pid == -1) {
            exit('Error forking.'.PHP_EOL);
        } else if($pid == 0) {
            ob_start();
            chdir(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'fixtures');
            exec($this->command);
            ob_end_clean();
            exit();
        }
        //echo 'Waiting for test server to launch...'.PHP_EOL;
        sleep(1);
    }

    public function testCanItRun() {
        $content = file_get_contents('http://localhost:'.$this->port);
    }

    public function testIndexPage() {
        $content = file_get_contents('http://localhost:'.$this->port);
        $this->assertEquals('Success', $content);
    }

    public function testParam() {
        $content = file_get_contents('http://localhost:'.$this->port.'/param/test');
        $this->assertEquals('Param: test', $content);
    }

    public function testView() {
        $content = file_get_contents('http://localhost:'.$this->port.'/view/test');
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
        $this->assertEquals($assertedContent, $content);
    }

    function testHeaderOK() {
        if(!function_exists('curl_init')) {
            $this->assertTrue(false, 'cURL is not available. Passing.');
        }
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'http://localhost:'.$this->port);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->assertEquals(200, $httpcode);

        curl_close($ch);
    }

    function testHeader404() {
        if(!function_exists('curl_init')) {
            $this->assertTrue(false, 'cURL is not available. Passing.');
        }
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'http://localhost:'.$this->port.'/err');
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->assertEquals(404, $httpcode);

        curl_close($ch);
    }

    function testHeader500() {
        if(!function_exists('curl_init')) {
            $this->assertTrue(false, 'cURL is not available. Passing.');
        }
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'http://localhost:'.$this->port.'/exception');
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->assertEquals(500, $httpcode);

        curl_close($ch);
    }

    function testHeaderRedirect() {
        if(!function_exists('curl_init')) {
            $this->assertTrue(false, 'cURL is not available. Passing.');
        }
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'http://localhost:'.$this->port.'/redirect');
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headers_raw = explode("\r\n", substr($response, 0, strpos($response, "\r\n\r\n")));
        $headers = [];
        foreach($headers_raw as $header) {
            if(strpos($header, ':') !== false) {
                list($k, $v) = explode(':', $header);
                $headers[$k] = trim($v);
            }
        }

        curl_close($ch);

        $this->assertEquals(302, $httpcode);
        $this->assertArrayHasKey('Location', $headers);
        $this->assertEquals('/redirected', $headers['Location']);
    }

    function testRedirect() {
        $content = file_get_contents('http://localhost:'.$this->port.'/redirect');
        $this->assertEquals('Redirected', $content);
    }

    function testHeader() {
        if(!function_exists('curl_init')) {
            $this->assertTrue(false, 'cURL is not available. Passing.');
        }
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'http://localhost:'.$this->port.'/header');
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $headers_raw = explode("\r\n", substr($response, 0, strpos($response, "\r\n\r\n")));
        $headers = [];
        foreach($headers_raw as $header) {
            if(strpos($header, ':') !== false) {
                list($k, $v) = explode(':', $header);
                $headers[$k] = trim($v);
            }
        }

        curl_close($ch);

        $this->assertArrayHasKey('Content-type', $headers);
        $this->assertEquals('text/plain', $headers['Content-type']);

    }

    function testUrl() {
        $content = file_get_contents('http://localhost:'.$this->port.'/url');
        $this->assertEquals('/redirected', $content);

        $content = file_get_contents('http://localhost:'.$this->port.'/url2');
        $this->assertEquals('/param/name', $content);
    }

    /**
     * Kills the background PHP tasks
     */
    public function TearDown() {
        //echo 'Killing fork...'.PHP_EOL;
        $forks = explode(PHP_EOL, exec('ps -C \''.$this->command.'\' -o pid='.$this->pid));
        foreach($forks as $fork) {
            if($fork != $this->pid) {
                //posix_kill($fork, SIGTERM);
                exec('kill '.$fork.'  2>&1 > /dev/null');
            }
        }
    }

}

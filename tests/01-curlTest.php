<?php
/**
 * Tests for cURL-wrapper used in other tests
 *
 * @package php-bottle
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @license MIT
 */

if(!defined('APPLICATION_PATH')) {
    define('APPLICATION_PATH', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
}
require_once('utils.php');

class CurlTest extends PHPUnit_Framework_TestCase {

    private $port = 8999;
    private $command = 'php -S localhost:%d 2>&1 > /dev/null';
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
            // ob_start();
            chdir(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'fixtures');
            exec($this->command);
            // ob_end_clean();
            exit();
        }
        //echo 'Waiting for test server to launch...'.PHP_EOL;
        sleep(1);
    }

    public function testBasicRequest() {
        $req = send_request('http://localhost:'.$this->port.'/curl.php');
        $this->assertInternalType('array', $req);
        $this->assertEquals(200, $req['httpcode']);
        $this->assertEquals('Basic request success', $req['content']);
    }

    public function testGetRequest() {
        $req = send_request('http://localhost:'.$this->port.'/curl.php', 'GET', ['param' => 'value']);
        $this->assertEquals('GET:param=value'.PHP_EOL, $req['content']);

        $req = send_request('http://localhost:'.$this->port.'/curl.php', 'GET', ['param' => 'value', 'param2' => 'value2']);
        $this->assertEquals('GET:param=value'.PHP_EOL.'GET:param2=value2'.PHP_EOL, $req['content']);
    }

    public function testPostRequest() {
        $req = send_request('http://localhost:'.$this->port.'/curl.php', 'POST', ['param' => 'value']);
        $this->assertEquals('POST:param=value'.PHP_EOL, $req['content']);

        $req = send_request('http://localhost:'.$this->port.'/curl.php', 'POST', ['param' => 'value', 'param2' => 'value2']);
        $this->assertEquals('POST:param=value'.PHP_EOL.'POST:param2=value2'.PHP_EOL, $req['content']);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidMethod() {
        $req = send_request('http://localhost:'.$this->port.'/curl.php', 'FAIL', ['param' => 'value']);
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

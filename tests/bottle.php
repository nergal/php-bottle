<?php
/**
 * Functional tests for PHP-Bottle
 *
 * @package php-bottle
 * @author Damien Nicolas <damien@gordon.re>
 * @version 0.1
 * @license MIT
 */

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
            chdir('fixtures');
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

    // @todo: write tests for various HTTP vars, such as headers, redirects, etc.
    // we don’t want to add dependencies only for tests

    /**
     * Kills the background PHP task
     */
    public function TearDown() {
        //echo 'Killing fork...'.PHP_EOL;
        $forks = explode(PHP_EOL, exec('ps -C \''.$this->command.'\' -o pid='.$this->pid));
        foreach($forks as $fork) {
            if($fork != $this->pid) {
                //posix_kill($fork, SIGTERM);
                exec('kill '.$fork.' 2>&1> /dev/null');
            }
        }
    }

}

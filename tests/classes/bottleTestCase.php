<?php
/**
 * Abstraction for test env
 *
 * @package php-bottle
 * @author Artem Poluhovich <me@2mio.com>
 * @version 0.1
 * @license MIT
 */
abstract class BottleTestCase extends PHPUnit_Framework_TestCase {
    private $port = 8999;
    private $command = 'php -t %s -S localhost:%d 2>&1 > /dev/null';
    private $pid = 0;

    /**
     * Setup fixture: starts a test HTTP server and sends it to background
     */
    public function setUp() {
        if(!function_exists('pcntl_fork')) {
            exit('pcntl_fork doesn\'t exists. Exiting...'.PHP_EOL);
        }

        $path = APPLICATION_PATH . 'fixtures';
        $this->pid = posix_getpid();
        $this->command = sprintf($this->command, $path, $this->port);
        $pid = pcntl_fork();

        if($pid == -1) {
            exit('Error forking.'.PHP_EOL);
        } else if($pid == 0) {
            exec($this->command);
            exit();
        }
        sleep(1);
    }

    /**
     * Kills the background PHP tasks
     */
    public function TearDown() {
        $forks = explode(PHP_EOL, exec('ps -C \''.$this->command.'\' -o pid='.$this->pid));
        foreach($forks as $fork) {
            if($fork != $this->pid) {
                //posix_kill($fork, SIGTERM);
                exec('kill '.$fork.'  2>&1 > /dev/null');
            }
        }
    }

    protected function buildUrl($uri = '/') {
        return 'http://localhost:' . $this->port . '/' . urlencode(ltrim($uri, '/'));
    }
}

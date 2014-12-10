<?php

require APPLICATION_PATH . '../src/bottle/exception.php';
require APPLICATION_PATH . '../src/bottle/view.php';

class ViewTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
    	$view = new Bottle_View;
    	$this->assertInstanceOf('Bottle_View', $view);
    }

    /**
     * @expectedException Bottle_Exception
     */
    public function testCreateWithNonexistingFilename()
    {
    	$someFile = 'this-is-some-filename.txt';
    	new Bottle_View($someFile);
    }

    public function testCreateWithExistingFilename()
    {
    	$someFile = 'fixtures/views/simpleview.html';
    	$view = new Bottle_View($someFile);

    	$output = $view->render(true);
    	$this->assertEquals($output, 'test');
    }

	/**
     * @expectedException Bottle_Exception
     */
    public function testSetFilenameWithNonexistingFilename()
    {
    	$someFile = 'this-is-some-filename.txt';
    	$view = new Bottle_View;

    	$view->setFilename($someFile);
    }

    public function testSetFilenameWithExistingFilename()
    {
    	$someFile = 'fixtures/views/simpleview.html';
    	$view = new Bottle_View;
    	$view->setFilename($someFile);

    	$output = $view->render(true);
    	$this->assertEquals($output, 'test');
    }

    public function testSetRoutes()
    {
    	$data = [
    		[null, []],
    		[[], []],
    		['abcd', ['abcd']],
    		[[1, 2, 3, 5], [1, 2, 3, 5]],
    		[['a', 'b', 'c', 'd'], ['a', 'b', 'c', 'd']],
    	];

    	$view = new Bottle_View;
    	foreach ($data as $item) {
    		list($src, $dest) = $item;

    		$view->setRoutes($src);
    		$this->assertEquals($dest, $view->routes);
    	}
    }

    public function testBindAndRender()
    {
    	$view = new Bottle_View;

    	$data = [
    		[
    			'fixtures/views/varview1.html',
    			'test',
    			'test',
    		],
    		    		[
    			'fixtures/views/varview1.html',
    			'asd',
    			'asd',
    		],
    		[
    			'fixtures/views/varview2.html',
    			'test',
    			'test:test:test',
    		],
    		[
    			'fixtures/views/varview2.html',
    			'asd',
    			'test:asd:test',
    		],
    		[
    			'fixtures/views/varview3.html',
    			'test',
    			'test:test',
    		],
    		[
    			'fixtures/views/varview3.html',
    			'asd',
    			'asd:asd',
    		],
    	];

    	foreach ($data as $item) {
    		list($filename, $src, $dest) = $item;

    		$view->setFilename($filename);
    		$view->bind(['test' => $src]);

    		$output = $view->render(true);

    		$this->assertEquals($dest, $output);
    	}

    	$data = [
			[
				['testOne' => 'asd', 'testTwo' => 'test'],
				'asd:test',
			],
			[
				['testOne' => 'test', 'testTwo' => 'asd'],
				'test:asd',
			],
    	];

		$view->setFilename('fixtures/views/varview4.html');
    	foreach ($data as $item) {
    		list($vars, $dest) = $item;
    		$view->bind($vars);
    		$output = $view->render(true);

    		$this->assertEquals($dest, $output);
    	}
    }

    // /**
    //  * @expectedException PHPUnit_Framework_Error
    //  */
    // public function testNotDefinedVariables()
    // {
    // 	$view = new Bottle_View('fixtures/views/varview1.html');
    // 	$view->render(true);
    // }

    public function testUrl()
    {
    	$data = [
    		[null, null, '/'],
    		['main', [], '/'],
    		['asd', [], '/asd'],
    		['dummyMul', [], '/mul/42'],
    		['paramMul', ['num' => 42], '/mul/42'],
    		['restList', [], '/rest/data/:id'],
    		['restList', ['id' => null], '/rest/data'],
    		['restPage', ['id' => 12, 'page' => 1], '/rest/data/12/1'],
    		['restPage', ['id' => 12], '/rest/data/12/:page'],
    		['restPage', ['page' => 12], '/rest/data/:id/12'],
    	];

    	$routes = [
    		'main' => '/',
    		'dummyMul' => '/mul/42',
    		'paramMul' => '/mul/:num',
    		'restList' => '/rest/data/:id',
    		'restPage' => '/rest/data/:id/:page',
    	];

    	$view = new Bottle_View;
    	$view->setRoutes($routes);

    	foreach ($data as $item) {
    		list($uri, $params, $dest) = $item;
    		$url = $view->url($uri, $params);

    		$this->assertEquals($dest, $url);
    	}
    }
}
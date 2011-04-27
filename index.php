<?php

require 'bottle.php';

/**
 * @route /mvc2/
 */
function index() {
    $name = 'test';
    return "<h1>Hello {$name}</h1>";
}

/**
 * @route /mvc2/test/:name/:data
 */
function test($name, $data) {
    return "<h2>Ololo, {$name} with {$data}!</h2>";
}

/**
 * @route /mvc2/:id
 * @view /views/test.html
 */
function test2($id) {
    return array('data' => $id * $id);
}
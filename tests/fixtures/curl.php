<?php

if(!count($_GET) && !count($_POST)) {
    echo 'Basic request success';
} else {
    foreach($_GET as $k => $v) {
        echo 'GET:'.$k.'='.$v.PHP_EOL;
    }
    foreach($_POST as $k => $v) {
        echo 'POST:'.$k.'='.$v.PHP_EOL;
    }
}

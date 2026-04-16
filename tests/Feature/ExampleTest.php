<?php

use \AsiRC\PhpHooks\HookManager;



it('calls a hook add action', function () {

    $hooks = new HookManager();
    $called = false;

    $date = new DateTime();

    $hooks->addAction('init.action', function($date) use (&$called) {
        $called = true;
    });

     $hooks->doAction('init.action' , $date);

    expect($called)->toBeTrue();
});



it('calls a hook add filter', function () {

    $hooks = new HookManager();
    $called = true;

    $val = 1;
    $hooks->addFilter('init', function($update) use (&$called) {
        $called = true;
        $update++;
    });

    $bdate  = $hooks->doFilter('init', $val);

    expect($called)->toBeTrue();
});


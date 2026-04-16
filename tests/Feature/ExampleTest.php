<?php

use \Asi\PhpHooks\HookManager;



it('calls a hook add action', function () {

    $hooks = new HookManager();
    $called = false;

    $date = new DateTime();
    // $date->modify("+1 day");
    // print($date->format('Y-m-d'));

    $hooks->addAction('init.action', function($date) use (&$called) {
        $called = true;
        $cDate = clone $date;
        $ndate = $cDate->modify("+1 day");
        // print($ndate->format('Y-m-d'));
        return $ndate;
    });

     $hooks->doAction('init.action' , $date);

    expect($called)->toBeTrue();
});



it('calls a hook add filter', function () {

    $hooks = new HookManager();
    $called = true;


    $hooks->addFilter('init', function($pdate) use (&$called) {
        $called = true;
        $cDate = clone $pdate;
        $ndate = $cDate->modify("+1 day");
        print($ndate->format('Y-m-d'));
    });

    
    $date = new DateTime();
    $strDate = $date->format('Y-m-d');
    print($strDate. PHP_EOL);

    $bdate  = $hooks->doFilter('init' , $date);

    // print($bdate->format('Y-m-d'));

    expect($called)->toBeTrue();
});


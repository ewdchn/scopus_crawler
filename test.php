<?php

namespace Test;
require_once "main.php";

/**
 * Created by JetBrains PhpStorm.
 * User: ewdchn
 * Date: 9/20/13
 * Time: 12:20 PM
 * To change this template use File | Settings | File Templates.
 */
function pagerGrabTest(){

}
function pageParseTest(){
    $L0eids = unserialize(file_get_contents("l0eid.txt"));
    echo "level 0 :" . count($L0eids) . "\n";
    $testEids = array_slice($L0eids,0,10);
    foreach($testEids as $entryeid=>$key)
    $Data=array();
    $children = array();
    parseEntries($testEids,$Data,$children);
    print_r($Data);
}
pageParseTest();

?>
<?php
/**
 * Created by PhpStorm.
 * User: ewdchn
 * Date: 11/10/13
 * Time: 11:18 PM
 */
require_once "main.php";
require_once "Parser.php";

set_time_limit(0);
/* ------------------------execution starts here----------------------- */

if (basename($argv[0]) !== basename(__FILE__)) {
    return;
} else {
    echo "main\n";

    $L0eids = unserialize(file_get_contents("l0eid.txt"));
    echo "level 0 :" . count($L0eids) . "\n";
    parseEntries($L0eids, $L0Data, $childrenArr);

//L1
    $L1eids = unserialize(file_get_contents("l1eid.txt"));
    echo "level 1 :" . count($L1eids) . "\n";
    parseEntries($L1eids, $L1Data, $childrenArr);

//L2
    $L2eids = unserialize(file_get_contents("l2eid.txt"));
    echo "level 2 :" . count($L2eids) . "\n";



    $host = '140.112.180.153';
    $con = mysql_connect($host, "ewdchn", "fj1-20") or die('Could not connect: ' . mysql_error());
    mysql_select_db("scopus", $con);
    mysql_query("TRUNCATE citations", $con);
    foreach ($childrenArr as $eid2 => $citArray) {
        foreach ($citArray as $eid1 => $key) {
            $sql = "INSERT INTO citations (eid1,eid2) VALUES ('$eid1','$eid2')";
            mysql_query($sql, $con);
        }
    }

}



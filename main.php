<?php

/**
 * Created by JetBrains PhpStorm.
 * User: ewdchn
 * Date: 9/17/13
 * Time: 5:37 PM
 * To change this template use File | Settings | File Templates.
 */
require_once 'Crawler.php';
require_once 'Parser.php';
require_once 'test.php';

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
{
    echo "platform:windows\n";
    define("DIR_SEP", '\\');
}
else
{
    define("DIR_SEP", '/');
}
define('resultCnt', 522);
define('TmpDir', "tmp");

$L0eids = array();
$L0Data = array();
$L1eids = array();
$L1Data = array();

function searchGrab()
{
    echo "Start Crawling L0...\n";
    \Common\checkOrCreateDir(TmpDir);

    foreach (range(1, 11) as $page)
    {
        $fileName = TmpDir . DIR_SEP . 'page' . $page . '.html';
        while (($response = \Crawler\Crawler::handleSearch($page)) === false);
        file_put_contents($fileName, $response);
    }
    echo "done\n";
}

function searchParse(&$_eidArray)
{
    //parse each page of search results and output the eid of result entries as array
    foreach (range(1, 11) as $page)
    {
        $fileName = TmpDir . DIR_SEP . 'page' . $page . '.html';
//        echo "parsing ",$fileName,"\n";
        $result = \Parser\parseSearchResultPage($fileName);
//        echo ",",count($result),",";
        $_eidArray = array_merge($_eidArray, $result);
    }
}


function L0Grab($_eidArray)
//given array of eids, grab the page and save in tmp/if the page exists in tmp it's jumped
{
    echo "Start Grabbing L0 Entries...";
    foreach ($_eidArray as $key => $eid)
    {
        echo ".";

        $fileName = TmpDir . DIR_SEP . 'entry_' . $eid . '.html';
        if (file_exists($fileName))
            continue;

        $response = \Crawler\Crawler::grabPaperEntry($eid);
        while ($response === false)
        {
            echo "!";
            $response = \Crawler\Crawler::grabPaperEntry($eid);
        }

        file_put_contents($fileName, $response);
    }
    echo "\ndone\n";
}

function L1Grab($_eidArray)
{
    echo "Start Grabbing L1 Entries...";
    foreach ($_eidArray as $eid => $key)
    {
        echo ".";

        $fileName = TmpDir . DIR_SEP . 'entry_' . $eid . '.html';
        if (file_exists($fileName))
            continue;

        $response = \Crawler\Crawler::grabPaperEntry($eid);
        while ($response === false)
        {
            echo "!";
            $response = \Crawler\Crawler::grabPaperEntry($eid);
        }

        file_put_contents($fileName, $response);
    }
    echo "\ndone\n";
}

function L1Parse($_eidArray,&$_L1){
    $missingCnt = 0;
    foreach($_eidArray as $eid=>$key){
        echo ".";
        $fileName = TmpDir . DIR_SEP . 'entry_' . $eid . '.html';
        $response = file_get_contents($fileName);
        $_L1[$eid] = \Parser\parseEntry($response);
        if($_L1[$eid]===false){
            echo " $eid\n";
            $missingCnt++;
            continue;
        }
        $_L1[$eid]['citation'] = \Parser\parseCitation($response);

        $L2eids = array();
        foreach ($_L1[$eid]['citation'] as $_eid)
            $L2eids[$_eid] = true;
    }
    echo "missing $missingCnt\n";
    return $L2eids;
}


function L0Parse($_eidArray, &$_L0)
//return list of L1 eids
{
    $L1eids = array();
    if (empty($_eidArray))
        throw new \Exception("array is empty");

    foreach ($_eidArray as $key => $eid)
    {
        $fileName = TmpDir . DIR_SEP . 'entry_' . $eid . '.html';
        $response = file_get_contents($fileName);
        $_L0[$eid] = \Parser\parseEntry($response);
        $_L0[$eid]['citation'] = \Parser\parseCitation($response);

        foreach ($_L0[$eid]['citation'] as $_eid)
                $L1eids[$_eid] = true;

    }

//  print_r($_L0[$key]);
    return $L1eids;
}

set_time_limit(0);
/* ------------------------execution starts here----------------------- */

//// crawl level 0
//searchGrab();
//searchParse($L0eids);
//file_put_contents("l0eid.txt", serialize($L0eids));

//parse/extract level 0
//$L0eids = unserialize(file_get_contents("l0eid.txt"));
//echo count($L0eids) . "\n";
//L0Grab($L0eids);

//$L1eids = L0Parse($L0eids, $L0Data);
//echo count($L1eids) . "\n";
//file_put_contents("l1eid.txt", serialize($L1eids));

$L1eids = unserialize(file_get_contents("l1eid.txt"));
//L1Grab($L1eids);
$L2eids = L1Parse($L1eids,$L1Data);
file_put_contents("l2eid.txt", serialize($L2eids));

//echo "\n",count($level0eids);
//foreach($level0eids as $eid){
//    echo level0_grabPaperEntry($eid);
//    return 0;
//}


return 0;
?>

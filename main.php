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
    define("DIR_SEP", '\/');
}
define('resultCnt', 522);
define('TmpDir', "tmp");

$L0eids = array();
$L0Data = array();

function searchGrab()
{
    echo "Start Crawling L0...\n";
    \Common\checkOrCreateDir(TmpDir);

    foreach (range(1, 11) as $page)
    {
        $fileName = TmpDir . DIR_SEP . 'page' . $page . '.html';
        file_put_contents($fileName, \Crawler\Crawler::handleSearch($page));
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
{
    echo "Start Grabbing L0 Entries...";
    foreach ($_eidArray as $key => $eid)
    {
        echo ".";
        $response = \Crawler\Crawler::grabPaperEntry($eid);
        while ($response === false)
        {
            echo "!";
            $response = \Crawler\Crawler::grabPaperEntry($eid);
        }
        $fileName = TmpDir . DIR_SEP . 'entry_' . $eid . '.html';
        if (!file_exists($fileName))
        {
            file_put_contents($fileName, $response);
        }
    }
    echo "\ndone\n";
}

function L0Parse($_eidArray, &$_L0)
{
    if (empty($_eidArray))
    {
        throw new \Exception("array is empty");
    }
    foreach ($_eidArray as $key => $eid)
    {
        $fileName = TmpDir . DIR_SEP . 'entry_' . $eid . '.html';
        $response = file_get_contents($fileName);
        $_L0[$key] = \Parser\parseEntry($response);
        print_r($_L0[$key]);
    }
}

set_time_limit(0);
/* ------------------------execution starts here----------------------- */

//// crawl level 0
//searchGrab();
//searchParse($L0eids);
//file_put_contents("l0eid.txt", serialize($L0eids));
//return;
//
//
//
//
//parse/extract level 0
$L0eids = unserialize(file_get_contents("l0eid.txt"));
//echo count($L0eids) . "\n";
//L0Grab($L0eids);
//return;
L0Parse($L0eids,$L0);

return;

//echo "\n",count($level0eids);
//foreach($level0eids as $eid){
//    echo level0_grabPaperEntry($eid);
//    return 0;
//}


return 0;
?>
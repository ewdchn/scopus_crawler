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
    define("DIR_SEP", '\\');
else
    define("DIR_SEP", '/');

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

function searchParse()
//parse each page of search results and output the eid of result entries as array
{
    $_eidArray = array();
    foreach (range(1, 11) as $page)
    {
        $fileName = TmpDir . DIR_SEP . 'page' . $page . '.html';
        $result = \Parser\parseSearchResultPage($fileName);
        foreach ($result as $key => $_eid)
            $_eidArray[$_eid] = true;
    }
    return $_eidArray;
}

function grabEntries($_eidArray)
//given eid array, grab the entry page and save in tmp/
{
    echo "Start Grabbing Entries...";
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

function parseEntries($_eidArray, &$_entryData)
//given eid array, find them in tmp folder, parse and return Data and eids of next level citations
{
    if (empty($_eidArray))
        throw new \Exception("array is empty");
    $missingCnt = 0;
    $L2eids = array();
    foreach ($_eidArray as $eid => $key)
    {
        $fileName = TmpDir . DIR_SEP . 'entry_' . $eid . '.html';
        $response = file_get_contents($fileName);
        $_entryData[$eid] = \Parser\parseEntry($response);
        if ($_entryData[$eid] === false)
        {
            echo " $eid\n";
            $missingCnt++;
            continue;
        }
        $_entryData[$eid]['citation'] = \Parser\parseCitation($response);

        foreach ($_entryData[$eid]['citation'] as $_eid)
            $L2eids[$_eid] = true;
    }
    echo "missing $missingCnt\n";
    return $L2eids;
}

set_time_limit(0);
/* ------------------------execution starts here----------------------- */

//// crawl level 0
//searchGrab();
$L0eids = searchParse();
file_put_contents("l0eid.txt", serialize($L0eids));


//parse/extract level 0
$L0eids = unserialize(file_get_contents("l0eid.txt"));
echo "level 0 " . count($L0eids) . "\n";
grabEntries($L0eids);
$L1eids = parseEntries($L0eids, $L0Data);
file_put_contents("l1eid.txt", serialize($L1eids));


//L1
$L1eids = unserialize(file_get_contents("l1eid.txt"));
echo "level 1 " . count($L1eids) . "\n";
grabEntries($L1eids);
$L2eids = parseEntries($L1eids, $L1Data);
file_put_contents("l2eid.txt", serialize($L2eids));


//L2
$L2eids = unserialize(file_get_contents("l2eid.txt"));
echo "level 2 " . count($L2eids) . "\n";
grabEntries($L2eids);

//echo "\n",count($level0eids);
//foreach($level0eids as $eid){
//    echo level0_grabPaperEntry($eid);
//    return 0;
//}


return 0;
?>

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
$missing = array();
$L0eids = array();
$L0Data = array();
$L1eids = array();
$L1Data = array();
$childrenArr = array();


//grab search query results
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

//parse search query results
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


//given eid array, grab the entry page and save in tmp/
function grabEntries($_eidArray)
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

//given eid array, find them in tmp folder, parse and return Data and eids of next level citations
function parseEntries($_eidArrayArr, &$_entryDataArr, &$_childrenArr)
{
    global $missing;

    if (empty($_eidArrayArr))
        throw new \Exception("array is empty");

    echo "parsing...";
    $missingCnt = 0;
    $parentLveid = array();
    foreach ($_eidArrayArr as $entryeid => $key)
    {
        echo ".";
        $fileName = TmpDir . DIR_SEP . 'entry_' . $entryeid . '.html';
        $response = file_get_contents($fileName);
        $_entryDataArr[$entryeid] = \Parser\parseEntry($response);
        // Error with Entry Page
        if ($_entryDataArr[$entryeid] === false)
        {
//            echo " $entryeid\n";
            echo "\n\!";
            $missingCnt++;
            $missing[$entryeid] = true;
            continue;
        }

        //get citations(pointer to parents)
        $_entryDataArr[$entryeid]['citation'] = \Parser\parseCitation($response);

        foreach ($_entryDataArr[$entryeid]['citation'] as $parenteid)
        {
            $parentLveid[$parenteid] = true;  // mark as existant next level
            //if Entry Page error occured, find it's children (last level)
            $_childrenArr[$parenteid][] = $entryeid;//add children to parent
        }
    }
    echo "missing $missingCnt\n";
    return $parentLveid;
}

set_time_limit(0);
/* ------------------------execution starts here----------------------- */

//// crawl level 0
//searchGrab();
$L0eids = searchParse();
file_put_contents("l0eid.txt", serialize($L0eids));


//parse/extract level 0
$L0eids = unserialize(file_get_contents("l0eid.txt"));
echo "level 0 :" . count($L0eids) . "\n";
grabEntries($L0eids);
$L1eids = parseEntries($L0eids, $L0Data,$childrenArr);
file_put_contents("l1eid.txt", serialize($L1eids));


//L1
$L1eids = unserialize(file_get_contents("l1eid.txt"));
echo "level 1 :" . count($L1eids) . "\n";
grabEntries($L1eids);
$L2eids = parseEntries($L1eids, $L1Data,$childrenArr);
file_put_contents("l2eid.txt", serialize($L2eids));


//L2
$L2eids = unserialize(file_get_contents("l2eid.txt"));
echo "level 2 :" . count($L2eids) . "\n";
grabEntries($L2eids);

foreach($missing as $eid=>$key){
    $key = $childrenArr[$eid];
}
file_put_contents("missing.txt",serialize($missing));
//echo "\n",count($level0eids);
//foreach($level0eids as $eid){
//    echo level0_grabPaperEntry($eid);
//    return 0;
//}


return 0;
?>

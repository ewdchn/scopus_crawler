<?php

/**
 *  The main body of the crawler
 *  Read/Write Files:
 *      L0eids.txt
 *      L1eids.txt
 *      L2eids.txt
 *
 *  Functions:
 *      searchGrab: get html of all search result pages
 *      searchParse: parse html of search result pages, output eids
 *      grabEntries: given eid array grab html of article pages
 *      parseEntries: parse html of article pages
 *
 */

require_once 'Crawler.php';
require_once 'Parser.php';

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


/*
 *
 *
 */
$childrenArr = array();


/*-------------------------------function definition-------------------------*/
//grab search query results
function searchGrab()
{
    echo "Start Crawling L0...\n";
    \Common\checkOrCreateDir(TmpDir);

    foreach (range(1, 11) as $page) {
        $fileName = TmpDir . DIR_SEP . 'page' . $page . '.html';
        while (($response = \Crawler\Crawler::handleSearch($page)) === false) ;
        file_put_contents($fileName, $response);
    }
    echo "done\n";
}

//parse search query results
function searchParse()
//parse each page of search results and output the eid of result entries as array
{
    $_eidArray = array();
    foreach (range(1, 11) as $page) {
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
    foreach ($_eidArray as $eid => $key) {
        echo ".";

        $fileName = TmpDir . DIR_SEP . 'entry_' . $eid . '.html';
        if (file_exists($fileName))
            continue;

        $response = \Crawler\Crawler::grabPaperEntry($eid);
        while ($response === false) {
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
    foreach ($_eidArrayArr as $entryeid => $key) {
        try {
            $fileName = TmpDir . DIR_SEP . 'entry_' . $entryeid . '.html';
//            echo "\n",$entryeid;
            $_entryDataArr[$entryeid] = \Parser\parseEntryPage($fileName);
            if ($_entryDataArr[$entryeid] === false) {
                echo '\n' . '!';
                $missing[$entryeid] = true;
                continue;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        /*Note : the entries cited by THIS entry is the parent
         */
        foreach ($_entryDataArr[$entryeid]['citation'] as $parenteid => $info) {
            $parentLveid[$parenteid] = true; // mark as existance at next level
            if (!isset($_childrenArr[$parenteid])) {
                $_childrenArr[$parenteid] = array();
            }
            $_childrenArr[$parenteid][$entryeid] = true; //add children(THIS) to parent
        }
    }
    echo "missing $missingCnt\n";
    return $parentLveid;
}

set_time_limit(0);

/* ----------------------------execution starts here---------------------------- */

$shortops = "s::";
$shortops .= "p::";
$shortops .= "v::";
$options = getopt($shortops);
if (!isset($options['s']))$options['s'] = False; else $options['s'] = True;
if (!isset($options['p']))$options['p'] = False; else $options['p'] = True;
if (!isset($options['v']))$options['v'] = False; else $options['v'] = True;
var_dump($options);

if (basename($argv[0]) !== basename(__FILE__)) {
    return;
} else {
    echo "main\n";
// crawl level 0
    if ($options['s']) {
        searchGrab();

        echo("Start ");
        $L0eids = searchParse();
        file_put_contents("l0eid.txt", serialize($L0eids));
    }

//parse/extract level 0
    if ($options['s']) {
        $L0eids = unserialize(file_get_contents("l0eid.txt"));
        echo "level 0 :" . count($L0eids) . "\n";
        grabEntries($L0eids);
        $L1eids = parseEntries($L0eids, $L0Data, $childrenArr);
        file_put_contents("l1eid.txt", serialize($L1eids));
    }

//L1
    if ($options['s']) {
        $L1eids = unserialize(file_get_contents("l1eid.txt"));
        echo "level 1 :" . count($L1eids) . "\n";
        grabEntries($L1eids);
        $L2eids = parseEntries($L1eids, $L1Data, $childrenArr);
        file_put_contents("l2eid.txt", serialize($L2eids));
    }

//L2
    if ($options['s']) {
        $L2eids = unserialize(file_get_contents("l2eid.txt"));
        echo "level 2 :" . count($L2eids) . "\n";
        grabEntries($L2eids);

        foreach ($missing as $eid => $key) {
            $key = $childrenArr[$eid];
        }
        file_put_contents("missing.txt", serialize($missing));
    }

    if ($options['p']){
        if($options['s']){

        }
        else{

        }
    }
}


?>

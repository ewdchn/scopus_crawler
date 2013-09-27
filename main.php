<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ewdchn
 * Date: 9/17/13
 * Time: 5:37 PM
 * To change this template use File | Settings | File Templates.
 */

require_once 'Crawler_L0.php';
require_once 'Parser_L0.php';
require_once 'test.php';

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
{ echo "platform:windows\n";define("DIR_SEP",'\\');} else {define ("DIR_SEP",'\/');}

CONST resultCnt = 522;
CONST L0_TmpDir = "L0";
$level0eids=array();
$level0 = array();
$crawler = NULL;

function searchCrawl(){
    echo "Start Crawling L0...\n";

    \Common\checkOrCreateDir(L0_TmpDir);

    foreach (range(1,11) as $page){
        $fileName = L0_TmpDir.DIR_SEP.'page'.$page.'.html';
        file_put_contents($fileName,\Crawler\Crawler::handleSearch($page));
    }
    echo "done\n";
}

function searchParse(&$_eidArray){
    //parse each page of search results and output the eid of result entries as array
    foreach(range(1,1) as $page){
        $fileName = L0_TmpDir.DIR_SEP.'page'.$page.'.html';
//        echo "parsing ",$fileName,"\n";
        $result = \Parser_L0\parsePage($fileName);
//        echo ",",count($result),",";
        $_eidArray =  array_merge($_eidArray,$result);
    }
}

function L0GrabEntries($_eidArray,&$_L0){
    echo "Start Grabbing L0 Entries...";
    foreach($_eidArray as $key=>$eid){
        echo ".";

        $response = \Crawler\Crawler::grabPaperEntry($eid);
        $_L0[$key] = \Parser\parseEntry($response);

        $fileName =  L0_TmpDir.DIR_SEP.'entry_'.$key.'.html';
        file_put_contents($fileName,$response);
    }
    echo "\ndone\n";
}






set_time_limit(0);
/*------------------------execution starts here-----------------------*/

//// crawl level 0
searchCrawl();
searchParse($level0eids);
//return;


//parse/extract level 0
echo count($level0eids)."\n";
L0GrabEntries($level0eids,$level0);
return;

//echo "\n",count($level0eids);
//foreach($level0eids as $eid){
//    echo level0_grabPaperEntry($eid);
//    return 0;
//}


return 0;

?>
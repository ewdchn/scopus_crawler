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
    define("DIR_SEP",'\\'); else define ("DIR_SEP",'/');

CONST resultCnt = 522;
CONST L0_TmpDir = "L0";
$level0eids=array();
$crawler = NULL;

function level0_Crawl(){
    echo "Start Crawling L0...\n";

    \Common\checkOrCreateDir(L0_TmpDir);

    foreach (range(1,11) as $page){
        $fileName = L0_TmpDir.DIR_SEP.'page'.$page.'.html';
        file_put_contents($fileName,\Crawler_L0\Crawler_L0::handleSearch($page));
    }
    echo "done\n";
}


function level0_GrabEntries($_eidArray){
    echo "Start Grabbing L0 Entries...";
    foreach($_eidArray as $key=>$eid){
        echo ".";
        $fileName =  L0_TmpDir.DIR_SEP.'entry_'.$key.'.html';
        file_put_contents($fileName,\Crawler_L0\Crawler_L0::grabPaperEntry($eid));
    }
    echo "\ndone\n";
}



function level0_Parse(&$_eidArray){
    //parse each page of search results and output the eid of result entries as array
    foreach(range(1,1) as $page){
        $fileName = 'page'.$page.'.html';
//        echo "parsing ",$fileName,"\n";
        $result = \Parser_L0\parsePage($fileName);
//        echo ",",count($result),",";
        $_eidArray =  array_merge($_eidArray,$result);
    }
}


set_time_limit(0);
/*------------------------execution starts here-----------------------*/

//// crawl level 0
//level0_Crawl();
//return;


//parse/extract level 0
level0_Parse($level0eids);
echo count($level0eids)."\n";
level0_GrabEntries($level0eids);
return;

//echo "\n",count($level0eids);
//foreach($level0eids as $eid){
//    echo level0_grabPaperEntry($eid);
//    return 0;
//}


return 0;

?>
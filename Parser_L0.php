<?php
namespace Parser_L0;

require_once 'simple_html_dom.php';

function parseSearchResult($_page){

    if($_page===false) return false;
    $html = \simple_html_dom\str_get_html($_page);
    if($html===false)
        throw new \Exception("ERROR: html parse ERROR");

    $ids = array();
    foreach($html->find('.docMain > .dataCol2 > .fldtextPad > .Bold > a') as $resultLnk){
        if($resultLnk->href !==false)
        {
            $lnk = html_entity_decode($resultLnk->href);
            preg_match('/(?<=eid\=)([^&]*)(?=&)/',$lnk,$id);
            $ids[] = $id[1];
            //            echo $id[1],"\n";
        }
        else {
            echo "EMPTY LINK\n";
        }
    }

    $html->clear();
    unset($html);

//    echo count($ids);
    return $ids;
}



function parsePage($_fileName){
    $fileContent = file_get_contents($_fileName);
    if($fileContent===false){
        throw new \Exception("ERROR: File Not Found");
    }
    return parseSearchResult($fileContent);

}

?>
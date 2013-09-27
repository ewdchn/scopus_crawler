<?php
namespace Parser;

require_once 'simple_html_dom.php';

function parseSearchResult($_page)
//return eids of search results(50) in search result page
{

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
            //  echo $id[1],"\n";
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


function parseSearchResultPage($_fileName)
{
    $fileContent = file_get_contents($_fileName);
    if($fileContent===false){
        throw new \Exception("ERROR: File Not Found: "."$_fileName");
    }
    return parseSearchResult($fileContent);

}


function parseEntry($_page)
{
    $entry = array();
    $html = \simple_html_dom\str_get_html($_page);

    //title
    if(!is_null($node=$html->find('.txtTitle',0)))
    {
        $entry['title'] = $node->innertext;
    }


    //author
    if(!is_null($node=$html->find('#authorlist',0)))
    {
           $entry['authorList']=$node->innertext;
    }

    //DOI
    foreach($html->find('.paddingR15') as $containter)
    {
           if(strpos($containter->innertext, "DOI")!==false){
               $entry["DOI"] = $containter->plaintext;
               break;
           }
    }


    //source
    if(!is_null($node=$html->find('.sourceTitle',0)))
    {
            $entry["source"] = $node->innertext;

    }

    //citation
    foreach($html->find('input[name=selectedEIDs]') as $eidContainer){
        $entry["citation"][] = $eidContainer->getAttribute('value');
    }
    $html->clear();
    unset($html);
    return $entry;
}

function parseEntryPage($_fileName){
    $fileContent = file_get_contents($_fileName);
    if($fileContent===false){
        throw new \Exception("ERROR: File Not Found: "."$_fileName");
        return false;
    }
    return parseEntry($fileContent);
}


?>
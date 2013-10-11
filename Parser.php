<?php

namespace Parser;

require_once 'simple_html_dom.php';

//return eids of search results(50) in search result page
function parseSearchResult($_page)
{
    if ($_page === false)
        return false;
    $html = \simple_html_dom\str_get_html($_page);
    if ($html === false)
        throw new \Exception("ERROR: html parse ERROR");

    $ids = array();
    foreach ($html->find('.docMain > .dataCol2 > .fldtextPad > .Bold > a') as $resultLnk)
    {
        if ($resultLnk->href !== false)
        {
            $lnk = html_entity_decode($resultLnk->href);
            preg_match('/(?<=eid\=)([^&]*)(?=&)/', $lnk, $id);
            $ids[] = $id[1];
            //  echo $id[1],"\n";
        }
        else
        {
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
    if ($fileContent === false)
    {
        throw new \Exception("ERROR: File Not Found: " . "$_fileName");
    }
    return parseSearchResult($fileContent);
}

function parseEntry(&$html)
{
    $entry = array();

    //title
    if (!is_null($node = $html->find('.txtTitle', 0))){
        $entry['title'] = trim(html_entity_decode(str_replace('&nbsp;',' ',$node->plaintext)));;
    }
    else{
//        throw new \Exception("No Title\n");
        echo "NO title";
        return false;
    }


    //author
    foreach($html->find('#authorlist span') as $authorSpan){
        if(($authorLnk=$authorSpan->find('a',0))!==null){
            $authorText = trim(html_entity_decode(str_replace('&nbsp;',' ',$authorLnk->plaintext)));
        } else{
            $authorText = trim(html_entity_decode(str_replace('&nbsp;',' ',$authorSpan->plaintext)));
        }
        if(!empty($authorText))
            $entry['author'][] = $authorText;
    }

    //DOI
    foreach ($html->find('.paddingR15') as $container) {
        if (strpos($container->plaintext, "DOI") !== false)
        {
            $entry['DOI'] = trim(str_replace('DOI:','',$container->plaintext));
            break;
        }
    }

    //source
    if (!is_null($node = $html->find('.sourceTitle', 0))){
        $entry["source"] = trim(html_entity_decode(str_replace('&nbsp;',' ',$node->plaintext)));
    }

    return $entry;
}

function parseCitationInfo(&$_container){
    $citation = array();

    $citation['eid']=$_container->getAttribute('value');

    return $citation;

}
//get eids in citation containers, return eids as array
function parseCitation(&$html)
{
    $arrCitation = array();


    foreach ($html->find('.referencesBlk') as $citContainer)
    {
        if (!($eidContainer = $citContainer->find('input[name=selectedEIDs]', 0)))
            echo "ERROR: No eid";
        else{
            $arrCitation[] = parseCitationInfo($eidContainer);

            //parse info here

        }
    }

//    echo count($eids) . "  citations\n";
    return $arrCitation;
}


//given eid, get the Entry Page and Parse it, return the structured entry data
function parseEntryPage($_fileName)
{
//    $fileName = TmpDir . DIR_SEP . 'entry_' . $_entryeid . '.html';
    $fileContent = file_get_contents($_fileName);
    if ($fileContent === false)
        throw new \Exception("ERROR: File Not Found: " . "$fileName");
    $html = \simple_html_dom\str_get_html($fileContent);
    $tmpEntry = parseEntry($html);
    $tmpEntry['citation'] = parseCitation($html);
    $html->clear();
    unset($html);
    return $tmpEntry;
}

?>
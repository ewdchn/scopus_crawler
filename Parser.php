<?php
/* Files containing function for parsing html from scopus
 *
 * Functions:
 *  parseSearchResultPage & parseSearchResult:
 *      parse target:   search result list pages
 *      parse items:    eids of matching articles
 *
 *  parseEntryPage & parseEntry:
 *      parse target:   html of article pages
 *      parse items:    title,author,DOI,source
 *
 *  parseCitation & parseCitationInfo:
 *      parse target:   html of article pages
 *      parse items:    title,author of articles cited in article page
 */
namespace Parser;

require_once 'simple_html_dom.php';


function customTrim($_text){
    return trim(html_entity_decode(str_replace('&nbsp;',' ',$_text)),', ');
}

//return eids of search results(50 per page) in search result page
function parseSearchResult($_page)
{
    if ($_page === false)   {return false;}
    if (($html = \simple_html_dom\str_get_html($_page)) === false)    {throw new \Exception("ERROR: html parse ERROR");}

    $ids = array();
    foreach ($html->find('.docMain > .dataCol2 > .fldtextPad > .Bold > a') as $resultLnk)
    {
        if ($resultLnk->href !== false)
        {
            $lnk = html_entity_decode($resultLnk->href);
            preg_match('/(?<=eid\=)([^&]*)(?=&)/', $lnk, $id);
            $ids[] = $id[1];
        }
        else    {echo "EMPTY LINK\n";}
    }

    $html->clear();
    unset($html);
    return $ids;
}

function parseSearchResultPage($_fileName)
{
    $fileContent = file_get_contents($_fileName);
    if ($fileContent === false) {throw new \Exception("ERROR: File Not Found: " . "$_fileName");}
    return parseSearchResult($fileContent);
}

function parseEntry(&$html)
{
    $entry = array();

    //title
    if (!is_null($node = $html->find('.txtTitle', 0))){
        $entry['title'] = customTrim($node->plaintext);
    }
    //Can't find title-> possible invalid page
    else     {return false;}


    //author
    $authorSpans = $html->find('#authorlist span');
    foreach($authorSpans as $authorSpan)
    {
        if(($authorLnk=$authorSpan->find('a',0))!==null){$authorText = customTrim($authorLnk->plaintext);}
        else                                            {$authorText = customTrim($authorSpan->plaintext);}
        if(!empty($authorText))                         {$entry['author'][] = $authorText;}
    }

    //DOI
    foreach ($html->find('.paddingR15') as $container)
    {
        if (strpos($container->plaintext, "DOI") !== false)
        {
            $entry['DOI'] = trim(str_replace('DOI:','',$container->plaintext));
            break;
        }
    }
//    if(!isset($entry['DOI']))echo "No DOI\n";

    //source
    if (!is_null($node = $html->find('.sourceTitle', 0))){$entry["source"] = customTrim($node->plaintext);}

    return $entry;
}

//given eid, get the Entry Page and Parse it, return the structured entry data
function parseEntryPage($_fileName)
{
    $fileContent = file_get_contents($_fileName);
    if ($fileContent === false)     {throw new \Exception("ERROR: File Not Found: " . "$_fileName");}
    $html = \simple_html_dom\str_get_html($fileContent);

    $tmpEntry = parseEntry($html);
    $tmpEntry['citation'] = parseCitation($html);

    $html->clear();
    unset($html);
    return $tmpEntry;
}

function parseCitationInfo(&$_container){
    $arrInfo = array();

    //Case: has refAuthorTitle or refAuthorTitle
    if(!is_null(($_container->find('.refAuthorTitle',0))) || !is_null(($_container->find('.refDocTitle',0)))){
        $arrInfo['title'] = $_container->find('.refDocTitle',0)->plaintext;
        $arrInfo['author']= $_container->find('.refAuthorTitle',0)->plaintext;
        /*parsing here to be done
         *
         *
         */

    }
    //Other Cases: deal later
    else {return true;}
    return $arrInfo;
}
/*Given the html DOM, grab all containers of citaiton blocks and:
 * Return an array of citation infos indexed by eids
 * [eid] -> (  author=_, title=_, src=_)
 */
function parseCitation(&$html)
{
    $arrCitation = array();
    foreach ($html->find('.referencesBlk') as $citContainer)
    {
        $eidContainer = $citContainer->find('input[name=selectedEIDs]', 0);
        $eid = $eidContainer->getAttribute('value');
        $arrCitation[$eid] = parseCitationInfo($citContainer);
    }
    return $arrCitation;
}



?>
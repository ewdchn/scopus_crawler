<?php

namespace Parser;
require_once 'simple_html_dom.php';

function parseSearchResult($_page){
    if($_page===false) return false;
    $html = \str_get_html($_page);

    $resultContainer = $html->find('.docMain .dataCol2 .dataCol2 a');
    foreach($resultContainer as $container){
        echo $container->plaintext;

    }
}


function parsePage($_fileName){
    return parseSearchResult(\file_get_contents($_fileName));
}

parsePage('page1.html');
?>
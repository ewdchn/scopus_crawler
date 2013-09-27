<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ewdchn
 * Date: 9/17/13
 * Time: 10:09 PM
 * To change this template use File | Settings | File Templates.
 */


namespace Common;
const COOKIESTORAGE='cookie.txt';

//global vars
$defaultOptions = array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HEADER => true,
    CURLOPT_FAILONERROR => true,
    CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.110 Safari/537.36",
    CURLOPT_COOKIEJAR => COOKIESTORAGE,
    CURLOPT_COOKIEFILE => COOKIESTORAGE,
);

function checkOrCreateDir($_dir){
    if ( file_exists($_dir) && is_dir($_dir)){
        if(is_writable ($_dir))
            return true;
        else throw new \Exception("ERROR: Dir not writable");
    }
    else{
        mkdir($_dir);
        if(is_writable ($_dir))
            return true;
        else throw new \Exception("ERROR: Dir not writable");
    }

}




function init($_cookieStorage = COOKIESTORAGE)
//visit the main scopus page
{
    echo "initializing session...";
    $options[CURLOPT_URL] = 'www.scopus.com';
    try{
        $response = getPage($options,$_cookieStorage);
    }
    catch (Exception $e){
        echo $e->getMessage();
        return false;
    }
    echo "done\n";
    return true;
}

function getPage($_options=NULL,$_cookieStorage=COOKIESTORAGE){

    global $defaultOptions;
    $options = $defaultOptions;
    foreach($_options as $key=>$value)
        $options[$key]=$value;
    $options[CURLOPT_COOKIEJAR] = $_cookieStorage;
    $options[CURLOPT_COOKIEFILE] = $_cookieStorage;
//  print_r($options);

    $ch = curl_init();
    curl_setopt_array($ch,$options);
    $tryCnt=0;
    do{
        if($tryCnt++>5){
            throw new \Exception("ERROR: No response");
        }
        else if($tryCnt>2){
            sleep(1);
        }
        $response = curl_exec($ch);
    }
    while(empty($response));

    curl_close($ch);
    return $response;
}


function paperEntryPage($_page){



}


?>
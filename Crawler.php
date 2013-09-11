<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ewdchn
 * Date: 9/10/13
 * Time: 12:16 PM
 * To change this template use File | Settings | File Templates.
 */

namespace scopus;


class Crawler
{
    const COOKIESTORAGE='cookie.txt';
    private static $crawler;
    private static $defaultOptions = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HEADER => true,
        CURLOPT_FAILONERROR => true,
        CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.110 Safari/537.36",
        CURLOPT_COOKIEJAR => self::COOKIESTORAGE,
        CURLOPT_COOKIEFILE => self::COOKIESTORAGE,
    );

    function __construct(){
        self::mainPage();
        self::$crawler=$this;
    }

    public static function mainPage()
        //visit the main scopus page
    {
        $options = self::$defaultOptions;
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public static function handleSearch($_page=1){
        $postStr = 'previous=false&next=false&downloadPdf=false&export=false&print=false&email=false&createBibliography=false&addToMyList=false&viewReferences=false&viewCitations=false&citationOverview=false&sot=b&sid=9D6EB6C391340D66BC16EA90316EBB60.mw4ft95QGjz1tIFG9A1uw%3A30&sdt=b&s=TITLE-ABS-KEY%28%22Digital+humanities%22+OR+%22humanities+computing%22%29&sl=61&sort=plf-f&stem=t&src=s&mltAll=f&searchWithinResultsDefault=nextPage&news=&clsYearCount=5&clsAuthnameCount=5&scla=%23+of+results&clsSubareaCount=5&sclsb=%23+of+results&clsDocTypeCount=5&clsSrctitleCount=5&scls=%23+of+results&clsKeyCount=5&sclk=%23+of+results&clsAffilCount=10&clsDocCntryCount=5&sclc=%23+of+results&clsSrctypeCount=5&clsLangCount=5&scll=%23+of+results&displayClusteringCountFlag=f&refinedSearchString=TITLE-ABS-KEY%28%22Digital+humanities%22+OR+%22humanities+computing%22%29&sortOrderFlag=f&sortFieldValue=plf-f&oldSelectAllCheckBox=false&oldSelectAllCheckBox=false&displayPerPageFlag=t&resultsPerPage=200&endPage=27&currentPage=2&documentJumpToPageDefault=t&count=522&scount=0&pageselecttotal=0&cc=10&offset=21&nextPageOffset=41&prevPageOffset=1&partialQuery=&sortField=RelevanceSortButton&resultsTab=&currentSource=s&oldResultsPerPage=20&clustering=&sortClusterField=&oldScls=&oldScla=&oldSclc=&oldSclsb=&ss=plf-f&ws=r-f&ps=r-f&ref=&clickedLink=&citeCnt=&mciteCt=&img=&tgt=&nlo=&nlr=&nls=&cs=r-f&contextBox=&origin=resultslist&selectDeselectAllAttempt=&recordid=&relpos=&pageEIDs=2-s2.0-84880321670%212-s2.0-84879866799%212-s2.0-84879565214%212-s2.0-84879509954%212-s2.0-84877923849%212-s2.0-84878971791%212-s2.0-84876032047%212-s2.0-84879407367%212-s2.0-84878451113%212-s2.0-84878439795%212-s2.0-84876058450%212-s2.0-84876022988%212-s2.0-84876061534%212-s2.0-84878462246%212-s2.0-84876061001%212-s2.0-84876057108%212-s2.0-84878430865%212-s2.0-84876020771%212-s2.0-84877658131%212-s2.0-84877043495&allSourceClusterCategories=Literary+and+Linguistic+Computing%23%23%23Lecture+Notes+in+Computer+Science+Including+Subseries+Lecture+Notes+in+Artificial+Intelligence+and+Lecture+Notes+in+Bioinformatics%23%23%23Computers+and+the+Humanities%23%23%23Literary+and+Linguistics+Computing%23%23%23Historical+Social+Research%23%23%23ACM+International+Conference+Proceeding+Series%23%23%23Proceedings+of+the+ACM+IEEE+Joint+Conference+on+Digital+Libraries%23%23%23IFIP+Transactions+A+Computer+Science+and+Technology%23%23%23Human+IT%23%23%23Arts+and+Humanities+in+Higher+Education&allAuthorClusterCategories=24343293100%23%23%2323472207200%23%23%2323568761100%23%23%2323472733800%23%23%238961334600%23%23%238877929200%23%23%237201610728%23%23%2315726786900%23%23%237006628288%23%23%237402266863&allCountryClusterCategories=United+States%23%23%23United+Kingdom%23%23%23Canada%23%23%23Italy%23%23%23Germany%23%23%23France%23%23%23Netherlands%23%23%23China%23%23%23Australia%23%23%23Japan&allYearClusterCategories=2013%23%23%232012%23%23%232011%23%23%232010%23%23%232009%23%23%232008%23%23%232007%23%23%232006%23%23%232005%23%23%232004&allDocTypeClusterCategories=ar%23%23%23cp%23%23%23re%23%23%23bk%23%23%23ed%23%23%23cr%23%23%23ip%23%23%23sh%23%23%23no%23%23%23er&allSubjectClusterCategories=COMP%23%23%23SOCI%23%23%23ARTS%23%23%23ENGI%23%23%23MATH%23%23%23BUSI%23%23%23EART%23%23%23PHYS%23%23%23ENVI%23%23%23DECI&allLanguageClusterCategories=English%23%23%23Chinese%23%23%23French%23%23%23Swedish%23%23%23German%23%23%23Spanish%23%23%23Italian%23%23%23Dutch%23%23%23Norwegian&allKeywordClusterCategories=Humanities+computing%23%23%23Digital+humanities%23%23%23Research%23%23%23Digital+libraries%23%23%23Students%23%23%23Humanities%23%23%23Engineering+education%23%23%23Humanities+research%23%23%23Digital+Humanities%23%23%23Teaching&allAffiliationClusterCategories=60011520%23%23%2360022148%23%23%2360021121%23%23%2360020304%23%23%2360026851%23%23%2360000745%23%23%2360025038%23%23%2360027550%23%23%2360029241%23%23%2360030835&allSourceTypeClusterCategories=j%23%23%23p%23%23%23k%23%23%23b%23%23%23d&st1=%22Digital+humanities%22+OR+%22humanities+computing%22&citedByJson=&extZone=&extOrigin=resultslist&originId=SC&selectedSources=&extSearchType=';
        parse_str($postStr,$post);
        unset($post['sid']);

        $options = self::$defaultOptions;
        $options[CURLOPT_URL] = "www.scopus.com/results/handle.url";
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = http_build_query($post);

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public static function quickSearch($cookie)
//        send request to http://www.scopus.com/search/submit/quick.url
    {
        $post = array(
            "src" => "s",
            "origin" => "resultslist",
            "searchtext" => '"Digital humanities" OR "humanities computing"',
        );

        $options = self::$defaultOptions;
        $options[CURLOPT_URL] = "http://www.scopus.com/search/submit/quick.url";
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = http_build_query($post);

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public static function search()
        //send request to http://www.scopus.com/search/submit/basic.url
    {
        $post = array(
            "searchterm1" => '"Digital humanities" OR "humanities computing"',
            "field1" => "TITLE-ABS-KEY",
            "dateType" => "Publication_Date_Type",
            "yearFrom" => "Before 1960",
            "yearTo" => "Present",
            "loadDate" => "7",
            "documenttype" => "All",
            "subjects" => "LFSC",
        );

        $options = self::$defaultOptions;
        $options[CURLOPT_URL] = "www.scopus.com/search/submit/basic.url";
        $options [CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = http_build_query($post);

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
set_time_limit(0);

$crawler = new Crawler();
$crawler->search();
echo $crawler->handleSearch();

?>
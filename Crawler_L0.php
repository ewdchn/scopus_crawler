<?php
namespace Crawler_L0;

require_once 'Crawler_shared.php';

class Crawler_L0
{
    private static $crawlerL0=NULL;

    function __construct(){
        global $inSession;
        if(!$inSession){
            if(\Common\init() === false){
                throw new \Exception("init ERROR");
            }
        }
        self::$crawlerL0=$this;
    }
    public static function checkSession(){
        if (!is_null(self::$crawlerL0)){
            return true;
        }else{
            self::$crawlerL0 = new Crawler_L0();
            self::searchInit();
        }
    }


    public static function searchInit()
        //send request to http://www.scopus.com/search/submit/basic.url, initiate search
    {
        if(is_null(self::$crawlerL0)){
            throw new \Exception("ERROR: No session");
        }
        echo "sending Search query\n";
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

        $options[CURLOPT_URL] = "www.scopus.com/search/submit/basic.url";
        $options [CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = http_build_query($post);

        return \Common\getPage($options);
    }

    public static function handleSearch($_page=1){
        self::checkSession();
        //send request to "www.scopus.com/results/handle.url", get result from pages
        echo "Crawling page $_page\n";
        $postStr = 'previous=false&next=false&downloadPdf=false&export=false&print=false&email=false&createBibliography=false&addToMyList=false&viewReferences=false&viewCitations=false&citationOverview=false&sot=b&sid=170D3EBCAAB4E930ABFCE42CE2C494A3.I0QkgbIjGqqLQ4Nw7dqZ4A%3A150&sdt=b&s=TITLE-ABS-KEY%28%22Digital+humanities%22+OR+%22humanities+computing%22%29&sl=61&sort=plf-f&stem=t&src=s&mltAll=f&searchWithinResultsDefault=f&news=&displayClusteringCountFlag=f&refinedSearchString=TITLE-ABS-KEY%28%22Digital+humanities%22+OR+%22humanities+computing%22%29&sortOrderFlag=f&sortFieldValue=plf-f&oldSelectAllCheckBox=false&oldSelectAllCheckBox=false&displayPerPageFlag=f&resultsPerPage=50&endPage=11&currentPage=1&documentJumpToPageDefault=t&count=522&scount=0&pageselecttotal=0&cc=10&offset=51&nextPageOffset=101&prevPageOffset=1&partialQuery=&sortField=RelevanceSortButton&resultsTab=&currentSource=s&oldResultsPerPage=50&clustering=&sortClusterField=&oldScls=&oldScla=&oldSclc=&oldSclsb=&ss=plf-f&ws=r-f&ps=r-f&ref=&clickedLink=go&citeCnt=&mciteCt=&img=&tgt=&nlo=&nlr=&nls=&cs=r-f&contextBox=&origin=resultslist&selectDeselectAllAttempt=&recordid=&relpos=&pageEIDs=2-s2.0-84873650358%212-s2.0-84878889794%212-s2.0-84875599220%212-s2.0-84873356145%212-s2.0-84873877121%212-s2.0-84873858800%212-s2.0-84873854160%212-s2.0-84870243336%212-s2.0-84874435364%212-s2.0-84870316488%212-s2.0-84873180712%212-s2.0-84873135302%212-s2.0-84875146840%212-s2.0-84873139292%212-s2.0-84876848167%212-s2.0-84873154032%212-s2.0-84873190032%212-s2.0-84873168364%212-s2.0-84872295774%212-s2.0-84874171902%212-s2.0-84870605087%212-s2.0-84871996779%212-s2.0-84875293575%212-s2.0-84873695475%212-s2.0-84871952571%212-s2.0-84868514668%212-s2.0-84871749499%212-s2.0-84872171332%212-s2.0-84878606034%212-s2.0-84875165012%212-s2.0-84878566075%212-s2.0-84868280635%212-s2.0-84873176395%212-s2.0-84872337681%212-s2.0-84875755567%212-s2.0-84877729864%212-s2.0-84874269230%212-s2.0-84878526394%212-s2.0-84871965822%212-s2.0-84868155100%212-s2.0-84869858544%212-s2.0-84869113557%212-s2.0-84869015069%212-s2.0-84869011428%212-s2.0-84868574274%212-s2.0-84868013361%212-s2.0-84868007810%212-s2.0-84867909521%212-s2.0-84867908869%212-s2.0-84867669898&allSourceClusterCategories=Literary+and+Linguistic+Computing%23%23%23Lecture+Notes+in+Computer+Science+Including+Subseries+Lecture+Notes+in+Artificial+Intelligence+and+Lecture+Notes+in+Bioinformatics%23%23%23Computers+and+the+Humanities%23%23%23Literary+and+Linguistics+Computing%23%23%23Historical+Social+Research%23%23%23ACM+International+Conference+Proceeding+Series%23%23%23Proceedings+of+the+ACM+IEEE+Joint+Conference+on+Digital+Libraries%23%23%23IFIP+Transactions+A+Computer+Science+and+Technology%23%23%23Human+IT%23%23%23Arts+and+Humanities+in+Higher+Education&allAuthorClusterCategories=24343293100%23%23%2323472207200%23%23%2323568761100%23%23%2323472733800%23%23%238961334600%23%23%238877929200%23%23%237201610728%23%23%2315726786900%23%23%237006628288%23%23%237402266863&allCountryClusterCategories=United+States%23%23%23United+Kingdom%23%23%23Canada%23%23%23Italy%23%23%23Germany%23%23%23France%23%23%23Netherlands%23%23%23China%23%23%23Australia%23%23%23Japan&allYearClusterCategories=2013%23%23%232012%23%23%232011%23%23%232010%23%23%232009%23%23%232008%23%23%232007%23%23%232006%23%23%232005%23%23%232004&allDocTypeClusterCategories=ar%23%23%23cp%23%23%23re%23%23%23bk%23%23%23ed%23%23%23cr%23%23%23ip%23%23%23sh%23%23%23no%23%23%23er&allSubjectClusterCategories=COMP%23%23%23SOCI%23%23%23ARTS%23%23%23ENGI%23%23%23MATH%23%23%23BUSI%23%23%23EART%23%23%23PHYS%23%23%23ENVI%23%23%23DECI&allLanguageClusterCategories=English%23%23%23Chinese%23%23%23French%23%23%23Swedish%23%23%23German%23%23%23Spanish%23%23%23Italian%23%23%23Dutch%23%23%23Norwegian&allKeywordClusterCategories=Humanities+computing%23%23%23Digital+humanities%23%23%23Research%23%23%23Digital+libraries%23%23%23Students%23%23%23Humanities%23%23%23Engineering+education%23%23%23Humanities+research%23%23%23Digital+Humanities%23%23%23Teaching&allAffiliationClusterCategories=60011520%23%23%2360022148%23%23%2360021121%23%23%2360020304%23%23%2360026851%23%23%2360000745%23%23%2360025038%23%23%2360027550%23%23%2360029241%23%23%2360030835&allSourceTypeClusterCategories=j%23%23%23p%23%23%23k%23%23%23b%23%23%23d&st1=%22Digital+humanities%22+OR+%22humanities+computing%22&citedByJson=&extZone=&extOrigin=resultslist&originId=SC&selectedSources=&extSearchType=';
        parse_str($postStr,$post);
        $post['currentPage']=(string)$_page;
        $post['offset']=(string)(($_page-1)*50+1);
        $post['nextPageOffset']=(string)(($_page-1)*50+51);
        unset($post['sid']);

        $options[CURLOPT_URL] = "www.scopus.com/results/handle.url";
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = http_build_query($post);

        return \Common\getPage($options);
    }


    public static function grabPaperEntry($_eid){
        self::checkSession();
//        echo "\n".$_eid."\n";
        $options[CURLOPT_URL] = 'www.scopus.com/record/display.url?eid='.$_eid.'&origin=resultslist';
        $options[CURLOPT_HEADER]=false;
        try{
            return \Common\getPage($options);
        }
        catch(Exception $e){
            echo $e->getMessage()."\n";
            return "";
        }
    }
}

?>
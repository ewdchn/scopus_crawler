<?php
namespace Level0;

require_once 'Crawler_shared.php';

class Crawler_L0
{
    private static $crawlerL0;

    function __construct(){
        global $inSession;
        if(!$inSession){
            \Crawler\init();
        }
        self::$crawlerL0=$this;
    }

    public static function handleSearch($_page=1){
    //send request to "www.scopus.com/results/handle.url", get result from pages
        echo "Crawling page $_page\n";
        $postStr = 'previous=false&next=false&downloadPdf=false&export=false&print=false&email=false&createBibliography=false&addToMyList=false&viewReferences=false&viewCitations=false&citationOverview=false&sot=b&sid=EEA6ECEEBF33FFC3A3748048C7F74AFD.kqQeWtawXauCyC8ghhRGJg%3A80&sdt=b&s=TITLE-ABS-KEY%28%22Digital+humanities%22+OR+%22humanities+computing%22%29&sl=61&sort=plf-f&stem=t&src=s&mltAll=f&searchWithinResultsDefault=t&news=&displayClusteringCountFlag=f&refinedSearchString=TITLE-ABS-KEY%28%22Digital+humanities%22+OR+%22humanities+computing%22%29&sortOrderFlag=f&sortFieldValue=plf-f&oldSelectAllCheckBox=false&oldSelectAllCheckBox=false&displayPerPageFlag=f&resultsPerPage=200&endPage=3&currentPage=2&documentJumpToPageDefault=t&go=Go&count=522&scount=0&pageselecttotal=0&cc=10&offset=1&nextPageOffset=201&prevPageOffset=&partialQuery=&sortField=RelevanceSortButton&resultsTab=&currentSource=s&oldResultsPerPage=200&clustering=&sortClusterField=&oldScls=&oldScla=&oldSclc=&oldSclsb=&ss=plf-f&ws=r-f&ps=r-f&ref=&clickedLink=&citeCnt=&mciteCt=&img=&tgt=&nlo=&nlr=&nls=&cs=r-f&contextBox=&origin=resultslist&selectDeselectAllAttempt=&recordid=&relpos=&pageEIDs=2-s2.0-84877613820%212-s2.0-84882273625%212-s2.0-84883161850%212-s2.0-84883129131%212-s2.0-84883090476%212-s2.0-84883129550%212-s2.0-84883096695%212-s2.0-84882935942%212-s2.0-84881150651%212-s2.0-84883179474%212-s2.0-84883166645%212-s2.0-84883149308%212-s2.0-84882244012%212-s2.0-84882267985%212-s2.0-84882266558%212-s2.0-84882264164%212-s2.0-84881594700%212-s2.0-84881409349%212-s2.0-84880945580%212-s2.0-84879950488%212-s2.0-84880321670%212-s2.0-84879866799%212-s2.0-84879565214%212-s2.0-84879509954%212-s2.0-84877923849%212-s2.0-84878971791%212-s2.0-84876032047%212-s2.0-84879407367%212-s2.0-84878451113%212-s2.0-84878439795%212-s2.0-84876058450%212-s2.0-84876022988%212-s2.0-84876061534%212-s2.0-84878462246%212-s2.0-84876061001%212-s2.0-84876057108%212-s2.0-84878430865%212-s2.0-84876020771%212-s2.0-84877658131%212-s2.0-84877043495%212-s2.0-84878237065%212-s2.0-84875952566%212-s2.0-84876214734%212-s2.0-84875155275%212-s2.0-84879801645%212-s2.0-84876221769%212-s2.0-84879016041%212-s2.0-84875024343%212-s2.0-84874597682%212-s2.0-84880897354%212-s2.0-84873650358%212-s2.0-84878889794%212-s2.0-84875599220%212-s2.0-84873356145%212-s2.0-84873877121%212-s2.0-84873858800%212-s2.0-84873854160%212-s2.0-84870243336%212-s2.0-84874435364%212-s2.0-84870316488%212-s2.0-84873180712%212-s2.0-84873135302%212-s2.0-84875146840%212-s2.0-84873139292%212-s2.0-84876848167%212-s2.0-84873154032%212-s2.0-84873190032%212-s2.0-84873168364%212-s2.0-84872295774%212-s2.0-84874171902%212-s2.0-84870605087%212-s2.0-84871996779%212-s2.0-84875293575%212-s2.0-84873695475%212-s2.0-84871952571%212-s2.0-84868514668%212-s2.0-84871749499%212-s2.0-84872171332%212-s2.0-84878606034%212-s2.0-84875165012%212-s2.0-84878566075%212-s2.0-84868280635%212-s2.0-84873176395%212-s2.0-84872337681%212-s2.0-84875755567%212-s2.0-84877729864%212-s2.0-84874269230%212-s2.0-84878526394%212-s2.0-84871965822%212-s2.0-84868155100%212-s2.0-84869858544%212-s2.0-84869113557%212-s2.0-84869015069%212-s2.0-84869011428%212-s2.0-84868574274%212-s2.0-84868013361%212-s2.0-84868007810%212-s2.0-84867909521%212-s2.0-84867908869%212-s2.0-84867669898%212-s2.0-84867456243%212-s2.0-84867453865%212-s2.0-84867223872%212-s2.0-84866663458%212-s2.0-84866020159%212-s2.0-84864283897%212-s2.0-84865384258%212-s2.0-84865325161%212-s2.0-84864540935%212-s2.0-84864530758%212-s2.0-84864552168%212-s2.0-84864566566%212-s2.0-84864539862%212-s2.0-84864542656%212-s2.0-84864573055%212-s2.0-84864548648%212-s2.0-84864537231%212-s2.0-84864538420%212-s2.0-84864352476%212-s2.0-84864450777%212-s2.0-84870173394%212-s2.0-84864275225%212-s2.0-84864204327%212-s2.0-84864187828%212-s2.0-84863957766%212-s2.0-84863548652%212-s2.0-84863550127%212-s2.0-84871525784%212-s2.0-84866544884%212-s2.0-84862851903%212-s2.0-84861566884%212-s2.0-84860649073%212-s2.0-84862104315%212-s2.0-84858974644%212-s2.0-84857678336%212-s2.0-84857674389%212-s2.0-84857673953%212-s2.0-84857775830%212-s2.0-84858246726%212-s2.0-84861494077%212-s2.0-84857264945%212-s2.0-84856925544%212-s2.0-84856890469%212-s2.0-84856817391%212-s2.0-84863115755%212-s2.0-84856878870%212-s2.0-84856889177%212-s2.0-84856862293%212-s2.0-84856141872%212-s2.0-84855774471%212-s2.0-84859447400%212-s2.0-84870919781%212-s2.0-84055213657%212-s2.0-83755170719%212-s2.0-83255166492%212-s2.0-83255176099%212-s2.0-84855795796%212-s2.0-84855797070%212-s2.0-84855766375%212-s2.0-84871996474%212-s2.0-84856349171%212-s2.0-84857935157%212-s2.0-82155180150%212-s2.0-84855761158%212-s2.0-84861425019%212-s2.0-84861453743%212-s2.0-84871333405%212-s2.0-84855763839%212-s2.0-84855812740%212-s2.0-84855802280%212-s2.0-84860749862%212-s2.0-84859339431%212-s2.0-84862188610%212-s2.0-84856103775%212-s2.0-84855786915%212-s2.0-84855362258%212-s2.0-84857702836%212-s2.0-80955157808%212-s2.0-80055051708%212-s2.0-83455188272%212-s2.0-82955187507%212-s2.0-80054792068%212-s2.0-80053261719%212-s2.0-80053128626%212-s2.0-80053036559%212-s2.0-80052986245%212-s2.0-80052700329%212-s2.0-80052012652%212-s2.0-80052782963%212-s2.0-80051956333%212-s2.0-82455186303%212-s2.0-80051918142%212-s2.0-80054114851%212-s2.0-80052712234%212-s2.0-80051960493%212-s2.0-79961223369%212-s2.0-79960509757%212-s2.0-79960483253%212-s2.0-79960525080%212-s2.0-79960475155&allSourceClusterCategories=Literary+and+Linguistic+Computing%23%23%23Lecture+Notes+in+Computer+Science+Including+Subseries+Lecture+Notes+in+Artificial+Intelligence+and+Lecture+Notes+in+Bioinformatics%23%23%23Computers+and+the+Humanities%23%23%23Literary+and+Linguistics+Computing%23%23%23Historical+Social+Research%23%23%23ACM+International+Conference+Proceeding+Series%23%23%23Proceedings+of+the+ACM+IEEE+Joint+Conference+on+Digital+Libraries%23%23%23IFIP+Transactions+A+Computer+Science+and+Technology%23%23%23Human+IT%23%23%23Arts+and+Humanities+in+Higher+Education&allAuthorClusterCategories=24343293100%23%23%2323472207200%23%23%2323568761100%23%23%2323472733800%23%23%238961334600%23%23%238877929200%23%23%237201610728%23%23%2315726786900%23%23%237006628288%23%23%237402266863&allCountryClusterCategories=United+States%23%23%23United+Kingdom%23%23%23Canada%23%23%23Italy%23%23%23Germany%23%23%23France%23%23%23Netherlands%23%23%23China%23%23%23Australia%23%23%23Japan&allYearClusterCategories=2013%23%23%232012%23%23%232011%23%23%232010%23%23%232009%23%23%232008%23%23%232007%23%23%232006%23%23%232005%23%23%232004&allDocTypeClusterCategories=ar%23%23%23cp%23%23%23re%23%23%23bk%23%23%23ed%23%23%23cr%23%23%23ip%23%23%23sh%23%23%23no%23%23%23er&allSubjectClusterCategories=COMP%23%23%23SOCI%23%23%23ARTS%23%23%23ENGI%23%23%23MATH%23%23%23BUSI%23%23%23EART%23%23%23PHYS%23%23%23ENVI%23%23%23DECI&allLanguageClusterCategories=English%23%23%23Chinese%23%23%23French%23%23%23Swedish%23%23%23German%23%23%23Spanish%23%23%23Italian%23%23%23Dutch%23%23%23Norwegian&allKeywordClusterCategories=Humanities+computing%23%23%23Digital+humanities%23%23%23Research%23%23%23Digital+libraries%23%23%23Students%23%23%23Humanities%23%23%23Engineering+education%23%23%23Humanities+research%23%23%23Digital+Humanities%23%23%23Teaching&allAffiliationClusterCategories=60011520%23%23%2360022148%23%23%2360021121%23%23%2360020304%23%23%2360026851%23%23%2360000745%23%23%2360025038%23%23%2360027550%23%23%2360029241%23%23%2360030835&allSourceTypeClusterCategories=j%23%23%23p%23%23%23k%23%23%23b%23%23%23d&st1=%22Digital+humanities%22+OR+%22humanities+computing%22&citedByJson=&extZone=&extOrigin=resultslist&originId=SC&selectedSources=&extSearchType=';
        parse_str($postStr,$post);
        $post['currentPage']=(string)$_page;
        unset($post['sid']);

        $options[CURLOPT_URL] = "www.scopus.com/results/handle.url";
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = http_build_query($post);

        return \Crawler\getPage($options);
    }

    public static function search()
    //send request to http://www.scopus.com/search/submit/basic.url, initiate search
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

        $options[CURLOPT_URL] = "www.scopus.com/search/submit/basic.url";
        $options [CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = http_build_query($post);

        return \Crawler\getPage($options);
    }
}

?>
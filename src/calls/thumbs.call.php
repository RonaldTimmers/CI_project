<?php

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
    exit;
}

    header('Content-Type: application/json');
    
    $ping789tt = 'RduikjTT967'; // password for the require file

    
    if ($_SERVER['HTTP_HOST'] == 'test.bimmith.com' || $_SERVER['HTTP_HOST'] == '87.213.252.132') {
            $baseURL = "http://test.bimmith.com/";  define('BASE_URL',  'http://test.bimmith.com/');
            $cdnURL = "http://test.bimmith.com/";   define('CDN_URL',  'http://test.bimmith.com/');
    } else {
            $baseURL = "https://www.compareimports.com/";   define('BASE_URL',  'https://www.compareimports.com/');
            $cdnURL = "https://cdn.compareimports.com/";    define('CDN_URL',  'https://cdn.compareimports.com/');
    }

    /* 
     * set static base address (pictures)
     * $staticURL = "https://static.compare-imports.com/";
     */
    $staticURL = "https://cdn.compare-imports.com/";    define('STATIC_URL',  'https://cdn.compare-imports.com/');
    
    
    require_once '../functions/mainfunctions.php'; // We gebruiken nog urlFriendly Function
    require_once (($_SERVER['DOCUMENT_ROOT']).'/vendor/autoload.php');
    
    use CI\Db;
    use CI\Controllers\ThumbsController; 
    
    //use CI\SearchQL;
    use CI\Filters;
    
    // Connect with needed databases          
    $db = new Db();
    $db->connect_linode(); // live website db
    
    
      
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }  
      
    $options = array();
      
 /*
  *  First Of All: Check Which data we got from the Call
  *  Then add them to the Options Array()
  *  
  */
    
    /*
     * REQUIRED
     */
    
    $pagetype                  = isset($_GET['pagetype']) ? test_input($_GET['pagetype']) : NULL;
    $options['querytype']      = isset($_GET['querytype']) ? test_input($_GET['querytype']) : NULL;
    
    $options['catlevel']       = isset($_GET['cat']) ? test_input($_GET['cat']) : NULL;
    $options['catid']          = isset($_GET['id']) ? test_input($_GET['id']) : NULL;
    $options['name']           = isset($_GET['name']) ? test_input($_GET['name']) : NULL;
    $options['catDescription'] = (isset($_GET['description']) AND $_GET['description'] != "") ? test_input($_GET['description']) : NULL;
    $options['totalProducts']  = isset($_GET['totalProducts']) ? test_input($_GET['totalProducts']) : NULL;

    //Second, the possible filters
    $options['page']           = isset($_GET['page']) ? test_input($_GET['page']) : NULL;
    $options['sort']           = isset($_GET['sort']) ? test_input($_GET['sort']) : NULL;
    $options['stars']          = isset($_GET['stars']) ? test_input($_GET['stars']) : NULL;
    $options['stock']          = isset($_GET['stock']) ? test_input($_GET['stock']) : NULL;
    $options['minprice']       = isset($_GET['minprice']) ? test_input($_GET['minprice']) : NULL;
    $options['maxprice']       = isset($_GET['maxprice']) ? test_input($_GET['maxprice']) : NULL;
    $options['filters']        = (isset($_GET['filters']) AND  $_GET['filters'] != "") ? test_input($_GET['filters']) : NULL;
    
    $options['shops']          = (isset($_GET['shops']) AND  $_GET['shops'] != "") ? test_input($_GET['shops']) : NULL;
    $searchKeywords       = isset($_GET['keywords']) ? test_input($_GET['keywords']) : NULL;
        
    /*
     * Set the Right Types for the variables in the Sphinx Query
     */
    
    settype($options['page'], "integer");
    settype($options['minprice'], 'float');
    settype($options['maxprice'], 'float');
    settype($options['stars'], "integer");  
    
    /*
    * Initiate the Connection with the Searchd server
    * and start to configurate the statement 
    */
   
    /*
     * Initiate Thumbs Controller
     * $mode, $searchKeywords, $secondQuery = 'false', $deviceType = null, $isSimilairThumbs = false, $options = array() 
     */
    
    $objectThumbs = ThumbsController::initThumbs( $pagetype, $searchKeywords, false,  null, false, $options );
    $objectThumbs->setThumbData()->getThumbData();
    

    
    /*
     * Aggregate Results for EndPoint
     */
    
    $searchThumbs['thumbs']             = $objectThumbs->thumbs;
    $searchThumbs['totalFound']         = $objectThumbs->searchQL->getTotalMaxMatches() ;
    $searchThumbs['maxprice']           = $objectThumbs->searchQL->getMaxPrice() ;
        
   
        
     echo json_encode($searchThumbs);    
        
        
      



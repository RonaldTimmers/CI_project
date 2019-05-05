<?php
ob_start();
define('TIME_START',  microtime(true) );

// password for the require file
$ping789tt = 'RduikjTT967'; 


/*
 * Define the Website Constants 
 * 
 */
   
define('ROOT', $_SERVER['DOCUMENT_ROOT']); 

/*
 * $baseURL depreceated for BASE_URL global constant 
 * $cndURL depreceated for CND_URL global constant 
 */
if ($_SERVER['HTTP_HOST'] == 'test.bimmith.com' || $_SERVER['HTTP_HOST'] == '87.213.252.132') {
        $baseURL = "http://test.bimmith.com/";  define('BASE_URL',  'http://test.bimmith.com/');
        $cdnURL = "http://test.bimmith.com/";   define('CDN_URL',  'http://test.bimmith.com/');
} else {
        $baseURL = "https://www.compareimports.com/";   define('BASE_URL',  'https://www.compareimports.com/');
        $cdnURL = "https://cdn.compareimports.com/";    define('CDN_URL',  'https://cdn.compareimports.com/');
}

$staticURL = "https://cdn.compare-imports.com/";    define('STATIC_URL',  'https://cdn.compare-imports.com/');
    
    
/*
* Check and Set the Adwords Values into Cookie
* These Values are added behind each specific link to a Affiliate shop
* In this case we are able to track sales more convenient
  
  Values:
  gclid = This is the auto tracking adwords code - If exist get other values
  campaignid = A number which represents a specific campaign
  adgroupid = A number which represents a specific ad-group
  device = c: computer, t: tablet, m: mobile
  GA_loc_physical_ms={loc_physical_ms}
  
  e.g.
  https://www.compareimports.com/?campaignid=700521257&adgroupid=32026340370&device=c

*/    

if (isset($_GET['gclid'])) {
    
    if ( isset($_GET['campaignid']) ) {
        setcookie("ga_campaignid", $_GET['campaignid'], time()+3600);  /* expire in 1 hour */
        setcookie("ga_adgroupid", $_GET['adgroupid'], time()+3600);
        setcookie("ga_device", $_GET['device'], time()+3600);          
    }
}


/* Search Handler 

 * Collect the posted search query
 * Remove multiple adjacent spaces
 * trim blank spaces at beginning and end
 * replace back slashes with custom replacement (rawurlencode encodement will cause bad links: error 404)
 * replace slashes with custom replacement (rawurlencode encodement will cause bad links: error 404)
 * Encode for url friendly use (convert forward slashes, blank spaces, etc.)
 *  
 */

    if (isset($_POST['searchInput'])) {
        $qclean = $_POST['searchInput'];					       
        $qclean = str_replace("  ", " ", $qclean);			       
        $qclean = trim($qclean, " ");		
        $qclean = str_replace("\\", "*bcksl*", $qclean);	     
        $searchphrase = str_replace("/", "*frwsl*", $qclean);		
        $qclean = rawurlencode($searchphrase);				   


         // If validation has passed, redirect to the URL rewritten search page
        if ($qclean != '') {								       	
           header( 'Location: '. $baseURL .'search/'. $qclean .'/');
        }
    } 
    
    elseif (isset($_GET['keywords']) || isset($_GET['wordfilter'] )) {
        
        if (isset($_GET['keywords'])) { $qclean = $_GET['keywords']; } 
        if (isset($_GET['wordfilter'])) { $qclean = $_GET['wordfilter']; } 				           
        $qclean = str_replace("  ", " ", $qclean);			        
        $qclean = trim($qclean, " ");						     
        $qclean = str_replace("\\", "*bcksl*", $qclean);	        
        $searchphrase = str_replace("/", "*frwsl*", $qclean);		
        $qclean = rawurlencode($searchphrase);	
            
    }    
	


/* Start Initiating Main Classes */     
    foreach (glob("functions/*.php") as $filename)
        {
            include_once $filename;
        } 

            
/*
 * To Do Fix Class Names According to PSR-4 to set Autoloading

        foreach (glob("classes/*.class.php") as $filename) { 
            include_once $filename;
        }   
 */        
// Composer Classes
require_once __DIR__ . '/vendor/autoload.php';
     

// Directly Used in Index 
use CI\Db;    
use CI\Pageinfo; 
use CI\Shops; // Moet Nog Eigen File krijgen Nu pageinfo?
use CI\Categoryinfo;
use CI\Shopreview;
use CI\Brands;
use CI\Navigation;
use CI\Product;
use CI\Pagecreator;
use CI\Pagecontent; // Moet Nog Eigen File krijgen Nu pageinfo?
use CI\MobileDetect;
use CI\BrandCarousel;


/*
 * Twig Template Loader
 * https://twig.symfony.com/doc/1.x/api.html
 */

$loader = new Twig_Loader_Filesystem( __DIR__ . '/templates');

$twig = new Twig_Environment($loader, array(
    'cache' => __DIR__ . '/templates/cache',
    'debug' => true,
    'auto_reload' => true
));


$twig->addGlobal('BASE_URL', BASE_URL);
$twig->addGlobal('CDN_URL', CDN_URL);
$twig->addGlobal('STATIC_URL', STATIC_URL);
 

//FOR DEVELOPMENT DATA ABOUT VARIABLES, NOT NEEDED ON LIVE WEBSITE   
    foreach (glob("classes/kint/*.class.php") as $filename)
         { 
             include_once $filename;
         }  
    
            
    // Connect with needed databases          
    $db = new Db();
    $db->connect_linode(); // live website db
    
    //Check if the user uses a mobile, tablet or computer
    $detect = new MobileDetect;
    // Depreceated 
    $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
    // New
    define('DEVICE_TYPE', ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer'));    
        

    /*      Get main page info 
    *       pageinfo.class.php
    */
    $pageinfo = new Pageinfo();
    $pageinfo->getInfo( $db->linode );
    
    
    
    /* DEVELOPMENT PURPOSE */
    //$db->connect_thuis(); // connect to home server
    
    if ($pageinfo->page['ref'] == 'home') {
        $db->connect_wp(); //wordpress blog db    
    }
    

    // Get active shops - in pageinfo.class.php    
    $shops = new Shops();
    $shops->set_activeShops($db->linode);
    $shops->set_otherShops($db->linode);
    
    // check if special header is needed
    set_header ($pageinfo->page['ref']); 
    
    
    // First get the current (sub/subsub)category info 
    $categoryinfo = new Categoryinfo();
    $categoryinfo->loadCats($db->linode, $baseURL);
 
    // Get Shop review information
    $shopreview = new Shopreview();
    
    // Get Specific Shop Information When Page is Known
    if ($pageinfo->page['ref'] == 'china-online-shops') {
    
        if (isset($shopreview->shopRef)){
            $pageinfo->page['ref'] = 'webshop-review';
            $shopreview->getShopReview ($db->linode); 
            
            /* Comment Script Variables */
            $PJ_THREAD = $shopreview->shopInfo['thread_id']; $PJ_THEME = 'theme1'; 
        }
    }
    
    /* Get Brand page information 
    File: brands.class.php */
    $brandpage = new Brands();
    
    // Get Specific Brand Information When Page is Known
    if ($pageinfo->page['ref'] == 'china-brands') {
        if (isset($brandpage->brandRef)) {
            $brandpage->getBrand ($db->linode);     
        }  
    }
    
     
    /* Get information for Main navbar and navigation wrapper
    File: navigation.class.php */
    $navbars = new Navigation();
    
    
    
    // Get Product information when not using the Modal view 
    // Otherwise the user already saw the view within the same window
    $sku = new Product(); 
    $sku->set_productinfo($db->linode);  
    
    

    
    
    if ( $pageinfo->page['ref'] == 'sku' && ( isset($sku->productinfo['id']) && $sku->productinfo['id'] > 0 ) && ( !isset($_GET['string']) || $_GET['string'] != $sku->productinfo['title_url'] ) ) {
        header("HTTP/1.1 301 Moved Permanently"); 
        header("Location: ". $baseURL . "sku/" . $sku->productinfo['id'] . "-" . $sku->productinfo['title_url'] ."/");
    }
    
    if ($pageinfo->page['source'] == 'new-arrivals.php' or $pageinfo->page['source'] == 'top-sellers.php')  {
        
        $page007 = new Pagecreator($baseURL, $categoryinfo);
        
        /*
         * 6-10-17 Removed 
         */
        //$page007->maxPages($db->linode,$categoryinfo->subsubcat, $categoryinfo->subcat, $categoryinfo->cat, $pageinfo->page['ref']);
        //$page007->maxPrice($db->linode,$categoryinfo->subsubcat, $categoryinfo->subcat, $categoryinfo->cat, $pageinfo->page['ref']);
        $page007->create_filterbox($db->linode);  
    }

          
	if ($pageinfo->page['source'] == 'sc.php') {
        // Initiate the page/thumb creator class with the max/nr of thumbs and get the current sorting preference + max pages + limits + next & prev page links
        $page007 = new Pagecreator($baseURL, $categoryinfo);
        //$page007->maxPages($db->linode,$categoryinfo->subsubcat, $categoryinfo->subcat, $categoryinfo->cat, $pageinfo->page['ref']); // DEPRECEATED NOW IN THUMBS CALL
        //$page007->maxPrice($db->linode,$categoryinfo->subsubcat, $categoryinfo->subcat, $categoryinfo->cat, $pageinfo->page['ref']); // DEPRECEATED NOW IN THUMBS CALL
        $page007->setMetadata($categoryinfo, 'subcat', null); // THIS IS IMPORTANT BECAUSE IT SET THE CAT ID AND P OR PS
        $page007->create_filterbox($db->linode);                                  
	}
    
    if ($pageinfo->page['ref'] == 'china-brands') {
        $page007 = new Pagecreator($baseURL);
        $page007->setMetadata($categoryinfo, 'brand', null);
    }
                           
    elseif ($pageinfo->page['source'] == 'search.php') {
        
        $page007 = new Pagecreator($baseURL);
        $page007->setMetadata($categoryinfo, 'search', $qclean);
        $page007->create_filterbox($db->linode); 
        
        
        /*
        * Needed here for now
        * Because of cookie set before output!
        * 7-Sept-17 Ronald
        */
        
        /*
        if ( isset($_COOKIE['searchHistory']) ) {
            $cookie = $_COOKIE['searchHistory'];
            $cookie =  stripslashes( $cookie );
            $cookie = json_decode( $cookie );
            
            
        } else {
            $cookie = array();
        }
        
        if (!in_array ($searchphrase, $cookie) ) {
           array_push($cookie, $searchphrase); 
        }
        
        
        $cookie = json_encode( $cookie );
        
        /*
         * Set a One week Cookie for SearchHistory
         */
        
        // setcookie('searchHistory', $cookie, time()+604800, '/');
        
        
        
    }
    
            
    $page007 = isset($page007) ? $page007 : "";
    $page007 = !empty($page007) ? $page007 : "";

    $qclean = isset($qclean) ? $qclean : "";
    $qclean = !empty($qclean) ? $qclean : "";
    
    
    // Get page content 
    // Dit is Eigenlijk de Router Class
    $pagecontent = new Pagecontent(); // Moet Eigen File Krijgen
    $pagecontent->getPageContent ( $ping789tt, $baseURL, $staticURL, $pageinfo, $categoryinfo, $sku, $db, $shopreview, $deviceType, $page007, $qclean, $shops, $brandpage, $twig );
    
    define( 'RESPONSE_CODE', http_response_code() );
    
    /*
    $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
    echo "After Loading PageContent Function: {$time}";
    */
     
   /* Receive Filenames for Bundle.JS and bundle.CSS compiled by webpack */
    $assets_path = glob($_SERVER['DOCUMENT_ROOT'] .'/bin/assets.json'); // Returns Array of Paths 
    $jsondata = file_get_contents($assets_path[0]); 
    $assets = json_decode($jsondata, true);
    
    /*
     * CSS CHUNKS
     */
    define('MAIN_CSS', substr($assets ['app']['css'], 1)); 
    define('CATEGORY_CSS', substr($assets ['category']['css'], 1)); 
    
    /*
     * JAVASCRIPT CHUNKS
     */
    define('VENDOR_JS',  substr($assets ['vendor']['js'], 1)); // JQuery Etc -> Always
    define('CATEGORY_JS',  substr($assets ['category']['js'], 1)); //category.js -> React Filter
    define('PRODUCT_JS',  substr($assets ['product']['js'], 1));  // Magnify, Charts
    define('DEFER_JS',  substr($assets ['defer']['js'], 1));  // Subscribe, Ad Block
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <?php 
        get_head($categoryinfo, $pageinfo, $sku, $shopreview, $brandpage, $deviceType, $qclean);
       
    ?>
          
</head>
<body>  

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5G7GSM5"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->



<header>
    <div id="top-wrapper"></div>
    
    
    
    <?php 
    /* 
    *   The Computer and Tablet - Header Version 
    *
    *
    */
    if ( DEVICE_TYPE == 'computer' || DEVICE_TYPE == 'tablet'  ) { ?>
        <div id="header-wrapper">
            <div id="header-box" class="container-fluid">
            
                <a href="<?php echo $baseURL; ?>">
                    <div id="logo-box" class="<?php if ( DEVICE_TYPE == 'computer') { echo 'dt';} else { echo 'tablet';}?> col-xs-4">
                       <span id="header-slogan">Find the Best Prices at China Shops</span>
                    </div>
                </a>
         
                <form id="search-box" class="<?php if ( DEVICE_TYPE == 'computer') { echo 'dt';} else { echo 'tablet';}?> col-xs-offset-1 col-lg-offset-0 col-lg-6 col-xs-7" method="post" action="<?php echo BASE_URL; ?>">
                    <button type="submit" id="searchButton" class="pull-right"></button>
                    <input type="text" id="searchInput" name="searchInput" placeholder="Find The Best Price!" autocomplete="off" <?php if (isset($searchphrase)) { echo 'value="'. $searchphrase .'"'; }?> >                    
                </form>   
                <div class="cb"></div>
                
            </div>          
        </div>
        
    <style>
.ui-autocomplete {      
    z-index: 99;
    position: relative;
    //left: 256px;
    //top: 39px;
    //width: 742px;
    margin: -1px 0 0 -15px;
    width: 460px;
    border: 1px solid #ccc;
    background-color: #fff;
    box-shadow: 2px 2px 2px #ccc;  
}     
        
    </style>
        
        <?php

        /* 
        * Category Navigatie Menu voor Mobile Devices (Phone and Tablets) 
        * 
        */
        if ( DEVICE_TYPE !== 'computer' ) { ?>
                <nav id="mobile-nav" class="hidden">    
                    <ul id="mobileNav-bar" class="<?php if ( DEVICE_TYPE == 'phone') { echo 'mobile';} else { echo 'tablet';}?> list-group col-xs-12 col-ms-12 col-sm-12">
                        <?php  $navbars->get_MobileNavbar($db->linode); ?>
                    </ul>
                </nav>
        <?php  } ?> 
    
    <?php }  
    
    /* 
    * The Mobile Header Version 
    * 
    * 
    */
    else { ?>
    
    <div id="header-wrapper" class="mobile">
        <div id="header-box" class="container-fluid">
                
            <div id="mobile-nav-button" class="col-xs-5">  
                <button id="categories-button" type="button" class="btn btn-lg btn-primary" >
                     <div id="hamburger-icon">
                        <span class="glyphicon glyphicon-menu-hamburger" onClick="toggleMenu()" aria-hidden="true"></span>         
                    </div>
                </button>
                
                <ul id="navigation-main" class="navBarTop">  
                </ul> 
            </div>


            <a href="<?php echo $baseURL; ?>">
                <div id="logo-box" class="col-xs-3"></div>
            </a>
                    
        </div>
        
        <div id="search-wrapper" class="mobile container-fluid row">    
            <form id="search-box" class="col-xs-12" method="post" action="<?php echo $baseURL; ?>">
                <button type="submit" id="searchButton" class="pull-right"></button>
                <input type="text" name="searchInput" id="searchInput" placeholder="Find The Best Price!" autocomplete="off" <?php if (isset($searchphrase)) { echo 'value="'. $searchphrase .'"'; }?> >
            </form>
        </div>   
    </div>
    
        <?php

        /* 
        * Category Navigatie Menu voor Mobile Devices (Phone and Tablets) 
        * 
        */
        if ( DEVICE_TYPE !== 'computer' ) { ?>
                <nav id="mobile-nav" class="hidden">    
                    <ul id="mobileNav-bar" class="list-group col-xs-12 col-ms-12 col-sm-12">
                        <?php  $navbars->get_MobileNavbar($db->linode); ?>
                    </ul>
                </nav>
        <?php  } ?> 
    

    
    <?php } ?>
    
    


    

    
     <?php 
     /* Submenu Balk do not Show on Phone */
     if ( DEVICE_TYPE !== 'phone' ) { ?>
    <div id="top-bar">
    
        <nav class="navbar container-fluid" id="topbar-box">

            <div id="top-bar-submenu" class="collapse navbar-collapse">
                <?php  
                /* Get the Submenu Bar with: Blog, Coupons, Shop Reviews -> navigation.class.php */
                
               
                    $sub_navigation_path = $_SERVER['DOCUMENT_ROOT'] .'/includes/html/main_top_navigation.html'; 
                    require $sub_navigation_path;
     
                    // $navbars->get_topNavbar($db->linode, $deviceType);
                ?>
            </div>
            
            
            
            <div class="navbar-header">
                <div id="nav-wrapper">  
                    <button id="categories-button" type="button" class="btn btn-default navbar-btn btn-primary" >
                         <div id="hamburger-icon">
                            <span class="glyphicon glyphicon-menu-hamburger" onClick="toggleMenu()" aria-hidden="true"></span>
                            <span onClick="toggleMenu()">CATEGORIES</span>            
                        </div>
                    </button>
                   
                   <ul id="navigation-main" class="navBarTop"> </ul>
                </div>
            </div>    
          

               
        </nav>       
    </div>
     <?php } else { ?>
         
         
         
         
    <?php  } ?>
    
</header>

<div id="main-wrapper" class="container-fluid">

          
        <?php
            if ( $pageinfo->page['ref'] !== 'home'  ) { 
                
                /*
                 * Never Show Carousel on SKU pages 
                 * Don't Show Carousel at top of sc/search pages, but at bottom 
                 * See Beneath! 
                 */
                if ( $pageinfo->page['ref'] !== 'sku' && $pageinfo->page['ref'] !== 'sc' && $pageinfo->page['ref'] !== 'search' && $pageinfo->page['ref'] !== 'china-brands' && $pageinfo->page['ref'] !== 'china-online-shops' && $pageinfo->page['ref'] !== 'webshop-review' ) { ?>
                    
                    <div class="brand-carousel col-xs-12">
                        <?php BrandCarousel::get_shopsBanner($shops->activeShops); ?>   
                    </div>    
                    <div class="clearfix"></div>
                
                <?php } ?>
                
                    <span class="breadcrumb-divider"></span>
                <?php
                
                if ( DEVICE_TYPE != 'phone' ) {
                    echo '<ol id="navigation-wrapper" class="breadcrumb dt-breadcrumb col-xs-12" itemscope itemtype="http://schema.org/BreadcrumbList">';
                            echo '<li>
                                    <a href="'. $baseURL .'">
                                    <span>Home</span>
                                    </a>
                                </li>';
                            if ( RESPONSE_CODE != 404 ) { $navbars->get_breadcrumb($categoryinfo, $pageinfo, $sku, $shopreview, $page007); }
                    echo '</ol>';       
                } else {
                echo '<ol id="navigation-wrapper" class="breadcrumb mobile-breadcrumb col-xs-12" itemscope itemtype="http://schema.org/BreadcrumbList">';
                        if ( RESPONSE_CODE != 404 ) { $navbars->get_mobile_breadcrumb($categoryinfo, $pageinfo, $sku, $shopreview); }
                echo '</ol>';  
                }
                ?>
                
                <div class="clearfix"></div>
                <hr class="breadcrumb-divider" />
                    
            <?php     
            }  
            ?>

              
                
                
        <?php if ($pageinfo->page['ref'] == 'sc'  || $pageinfo->page['ref'] == 'search' || $pageinfo->page['ref'] == 'new-arrivals' || $pageinfo->page['ref'] == 'top-sellers') { ?>
            <div id="left-bar" class="col-md-2" <?php if ($pageinfo->page['ref'] == 'home' and $deviceType == 'computer') {echo 'style="top: 483px;"';} ?>>  		 
                <!-- Code for SKU, Subcat or Cat pages - Get the small navbar for the cat we are in --> 
                <?php		
                    if ($pageinfo->page['ref'] == 'sku'){
                        // $navbars->get_subsubNav($db->linode, $sku->productinfo, $pageinfo->page['ref']);
                    }
                    if ($pageinfo->page['ref'] == 'sc') {
                        if ( RESPONSE_CODE != 404 ) { $navbars->get_subsubNav($db->linode, $categoryinfo->subcat, $pageinfo->page['ref'], $categoryinfo->cat, $categoryinfo->subsubcat); }
                    } 		
                    if ($pageinfo->page['ref'] == 'c') {
                         //  $navbars->get_subNav($db->linode, $categoryinfo->cat);
                    } 

                ?>
              
                    
                                 
                <?php if ($pageinfo->page['ref'] == 'sc' or $pageinfo->page['source'] == 'new-arrivals.php' or $pageinfo->page['source'] == 'top-sellers.php' or $pageinfo->page['source'] == 'search.php') { ?>
                    <!-- REACTJS DEVELOPMENT  -->
                    <div id="categoryfilter"></div>
                    <div id="shopfilter"></div>
                    <div id="attrfilter"></div>
                    <!-- REACTJS DEVELOPMENT -->
                <?php } 
                
                /*
                 * New Feature 3-10-17
                 * If Available Add Category Informatio Text in Sidebar  
                 * At first for SubCats Only
                 */
                
                if ( $pageinfo->page['ref'] == 'sc' && isset($categoryinfo->subcat['information']) ) {
                    echo '<b>China '. $categoryinfo->subcat['name'] .'</b>';
                    echo '<p>';
                    echo $categoryinfo->subcat['information'];
                    echo '</p>';
                }
                
                ?>
            </div>    
            
            <?php } 
            // $pageinfo->page['ref'] == 'home' and $deviceType == 'computer') || 
            ?>    
        
         <div id="main-box" class="<?php echo $mainBoxGrid = ($pageinfo->page['ref'] == 'sc' || $pageinfo->page['ref'] == 'search' || $pageinfo->page['ref'] == 'new-arrivals' || $pageinfo->page['ref'] == 'top-sellers') ? 'col-md-10' : 'col-md-12';?>">
                
                <div id="content-box">
                
                    <?php echo $pagecontent->main_content; ?>

                </div>
                <div class="clearfix"></div>
        </div>
        
        
        
        <div id="goTop">
            <a id="goTopButton" class="hidden" href="" onclick="scrollToTop();return false;"></a> 
        </div> 
        
        <div class="clearfix"></div>
        
        
        
        <?php if ( $pageinfo->page['ref'] == 'sc' || $pageinfo->page['ref'] == 'search' || $pageinfo->page['ref'] == 'china-brands' || $pageinfo->page['ref'] == 'china-online-shops' || $pageinfo->page['ref'] == 'webshop-review' ) { ?>
        
                    <hr class="breadcrumb-divider" />
                    <div class="brand-carousel col-xs-12">
                        <?php BrandCarousel::get_shopsBanner($shops->activeShops); ?>   
                    </div>    
                    <div class="clearfix"></div>
                    
                
        <?php } ?>
        
        
        
         
</div> <!-- Main Wrapper -->

<script type="text/javascript">
    window.baseURL = <?php echo json_encode($baseURL);?>; 
</script>

<script type="text/javascript" src="<?php echo $cdnURL . VENDOR_JS;?>"></script>
<script type="text/javascript" src="<?php  echo $cdnURL; ?>includes/jquery/functions.js"></script>


<script type="text/javascript">
        $("img").unveil(400);
</script> 
    


<?php if ( $pageinfo->page['ref'] == 'sc' || $pageinfo->page['ref'] == 'search' || $pageinfo->page['ref'] == 'china-brands'  ) { ?>
    <script type="text/javascript" src="<?php echo $cdnURL . CATEGORY_JS;?>" ></script> 
<?php } ?>
    

    
<?php if ( $pageinfo->page['ref'] == 'webshop-review' || $pageinfo->page['ref'] == 'sku' || $pageinfo->page['ref'] == 'sc' || $pageinfo->page['ref'] == 'search' || $pageinfo->page['ref'] == 'china-brands' ) { ?>
    <script  type="text/javascript" src="<?php echo $cdnURL . PRODUCT_JS;?>" ></script>
    <script  type="text/javascript" src="<?php echo $cdnURL;?>includes/jquery/clipboard.min.js" ></script>
<?php } ?>
    
<script type="text/javascript" src="<?php echo $cdnURL . DEFER_JS;?>"></script>


<script type="text/javascript" src="<?php echo $cdnURL; ?>includes/jquery/subscribe.js"></script>

<?php if ( $pageinfo->page['ref'] != 'sku' && $pageinfo->page['ref'] != 'sc' && $pageinfo->page['ref'] != 'search' && $pageinfo->page['ref'] != 'china-brands' ) { ?>
    <script defer type="text/javascript" src="//static.addtoany.com/menu/page.js"></script>
<?php } ?>






<?php if ( DEVICE_TYPE == 'computer' ) { ?>
    <script type="text/javascript">
        var baseURL = '<?php echo $baseURL;?>';
        get_JSnavbar(baseURL);
    </script>
<?php } ?> 
 
 
    
<script type="text/javascript">
        gotoTop();   
</script> 



<?php 
    /*
     * FIRST LOAD CRITICAL JAVASCRIPT
     */

    /* Get The Static Footer HTML */
    
    $footer_path = $_SERVER['DOCUMENT_ROOT'] .'/includes/html/main_footer_navigation.html'; 
    include $footer_path;
    
?>  
    
    


<?php if ( $pageinfo->page['ref'] == 'sku' || $pageinfo->page['ref'] == 'sc' || $pageinfo->page['ref'] == 'search' || $pageinfo->page['ref'] == 'china-brands' ) { ?>

<script type="text/javascript">

$(document).ready(function () {
    // First Time Run To Start the Magnify
    
    $('.product-image').magnify();
    
    $('.productpage-thumb').on({
        'mouseenter': function(){
            var product = $(this).closest('div');
            var newImage = $(this).attr('data-shop-src');
            
            product.find("img.product-image").attr('src', '/img/spinner.gif');
            product.find("img.product-image").attr('data-magnify-src', '/img/spinner.gif');
            product.find("img.product-image").attr('src', newImage);
            product.find("img.product-image").attr('data-magnify-src', newImage);
            $('.product-image').magnify();
        }		
    });
    
    $('#product-info-tabs').tabCollapse();
    
    
});


$( document ).ajaxComplete(function( event, xhr, settings ) {

    //console.log(window.baseURL + "src/relatedProductsHTML.call.php" );
    if ( settings.url === window.baseURL + "src/productHTML.call.php" ) {
        
        // Run the Clipboard Coupon Script for Modal
        new Clipboard('.get-coupon-code');
        
        // First Time Run To Start the Magnify After AJAX Call
        $('.product-image').magnify();
        
        $('.productpage-thumb').on({
            'mouseenter': function(){
                console.log('Run Normal Script Index');
                var product = $(this).closest('div');
                var newImage = $(this).attr('data-shop-src');
                product.find("img.product-image").attr('src', '/img/spinner.gif');
                product.find("img.product-image").attr('data-magnify-src', '/img/spinner.gif');
                product.find("img.product-image").attr('src', newImage);
                product.find("img.product-image").attr('data-magnify-src', newImage);
                $('.product-image').magnify();
            }		
        });
        
        $('#product-info-tabs').tabCollapse();
      
    }
});

</script>

<script type="text/javascript">
    /*
    $(document).ready(function lazy() {
        $("img").unveil(400);
    });
    */
    
    function lazy() {
        $("img").unveil(400);
    }
        
    $( document ).ajaxComplete(function( event, xhr, settings ) {
        //console.log( settings.url );
        
        //console.log(window.baseURL + "src/relatedProductsHTML.call.php" );
        if ( settings.url === window.baseURL + "src/relatedProductsHTML.call.php" ) {
            //console.log( "Triggered ajaxComplete handler." );
            
            $("img").trigger("unveil");
        }
    });
       
</script>

<script type="text/javascript">
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>


 <script type="text/javascript">new Clipboard('.get-coupon-code');</script>

<?php } ?>





<script type="text/javascript">

    $(document).ready(function() {
        $(function() {
            $('body').scrollspy({ target: '.navigation-menu' });
            $(".dropdown-toggle").dropdown();
        });
    });

</script>        
 

<script type="text/javascript">
$('#shop-logos-gallery').owlCarousel({
    loop: true,
    autoplay: false,
    autoplayHoverPause: true,
    margin: 5,
    autoplayTimeout: 3000,
    autoWidth: false,
    responsive:{
        0:{
            items:2
        },
        400:{
            items:2
        },
        600:{
            items:4
        },
        800:{
            items:4
        },
        1000:{
            items:5
        }, 
        1200:{
            items:6
        }          
    } 
}); 
</script>  

<script type="text/javascript">
$('#top-products-carousel').owlCarousel({
    loop: false,
    items: 4,
    margin: 30,
    autoWidth: false,
    dots: false,
    nav: true,
    rewindNav: false,
    stagePadding: 5,
    navText : ['<span class="glyphicon glyphicon-menu-left pull-left clickthrough-arrow" style="font-size: 3em; cursor:pointer; opacity: 0.8;" aria-hidden="true"></span>','<span class="glyphicon glyphicon-menu-right pull-right clickthrough-arrow" style="font-size: 3em; cursor:pointer; opacity: 0.8;" aria-hidden="true"></span>'],
    slideBy: 'page',
    responsive:{
        0:{
            items:1
        },
        600: {
             items:2   
        },
              
        1000:{
            items:4
        }
    }
}); 
</script>  

<script type="text/javascript">
    $('#product-images-gallery').owlCarousel({
        loop: false,
        items: 1
    }); 
    
    $( document ).ajaxComplete(function( event, xhr, settings ) {
        //console.log( settings.url );
        
        //console.log(window.baseURL + "src/relatedProductsHTML.call.php" );
        if ( settings.url === window.baseURL + "src/productHTML.call.php" ) {
                $('#product-images-gallery').owlCarousel({
                    loop: false,
                    items: 1
                });
                
        
        }
    });
</script>  





<script>
    $('.thumb').on({
    'mouseenter': function(){
            var product = $(this).offsetParent();
            var newImage = $(this).attr('src');
            product.find("img.product-thumb").attr('src', newImage);
            }		
    });
</script>


<script>
$(document).ready(function() {
    
        if ($(document).scrollTop() > 4) {
            $('#header-wrapper').addClass('fixed-header');
            
            /* Computer */
            $('#nav-wrapper').addClass('fixed-categories-nav'); // Only Computer
            $('#logo-box.dt').addClass('col-xs-offset-2'); // Only Computer
            
            $('#search-box.dt').addClass('col-xs-5'); // Only Computer
            $('#search-box.dt').removeClass('col-xs-7'); // Only Computer
            
            /* Tablet */
            $('#logo-box.tablet').removeClass('col-xs-4'); // Only Tablet
            $('#logo-box.tablet').addClass('col-xs-2'); // Only Tablet
            $('#logo-box.tablet').addClass('col-xs-offset-4'); // Only Tablet
            $('#search-box.tablet').removeClass('col-xs-offset-1'); // Only Tablet 
            $('#search-box.tablet').removeClass('col-xs-7'); // Only Tablet
            $('#search-box.tablet').addClass('col-xs-6'); // Only Tablet
        }
        else {
            $('#header-wrapper').removeClass('fixed-header');
            
            /* Computer */
            $('#nav-wrapper').removeClass('fixed-categories-nav'); // Only Computer
            $('#logo-box.dt').removeClass('col-xs-offset-2'); // Only Computer
            
            $('#search-box.dt').removeClass('col-xs-5'); // Only Computer
            $('#search-box.dt').addClass('col-xs-7'); // Only Computer
            
            /* Tablet */
            $('#logo-box.tablet').addClass('col-xs-4'); // Only Tablet
            $('#logo-box.tablet').removeClass('col-xs-2'); // Only Tablet
            $('#logo-box.tablet').removeClass('col-xs-offset-4'); // Only Tablet
            $('#search-box.tablet').addClass('col-xs-offset-1'); // Only Tablet 
            $('#search-box.tablet').addClass('col-xs-7'); // Only Tablet
            $('#search-box.tablet').removeClass('col-xs-6'); // Only Tablet
        }
    
    
    $(window).scroll(function() {
        if ($(document).scrollTop() > 4) {
            $('#header-wrapper').addClass('fixed-header');
            
            /* Computer */
            $('#nav-wrapper').addClass('fixed-categories-nav'); // Only Computer
            $('#logo-box.dt').addClass('col-xs-offset-2'); // Only Computer
            
            $('#search-box.dt').addClass('col-xs-5'); // Only Computer
            $('#search-box.dt').removeClass('col-xs-7'); // Only Computer
            
            /* Tablet */
            $('#logo-box.tablet').removeClass('col-xs-4'); // Only Tablet
            $('#logo-box.tablet').addClass('col-xs-2'); // Only Tablet
            $('#logo-box.tablet').addClass('col-xs-offset-4'); // Only Tablet
            $('#search-box.tablet').removeClass('col-xs-offset-1'); // Only Tablet 
            $('#search-box.tablet').removeClass('col-xs-7'); // Only Tablet
            $('#search-box.tablet').addClass('col-xs-6'); // Only Tablet
        }
        else {
            $('#header-wrapper').removeClass('fixed-header');
            
            /* Computer */
            $('#nav-wrapper').removeClass('fixed-categories-nav'); // Only Computer
            $('#logo-box.dt').removeClass('col-xs-offset-2'); // Only Computer
            
            $('#search-box.dt').removeClass('col-xs-5'); // Only Computer
            $('#search-box.dt').addClass('col-xs-7'); // Only Computer
            
            /* Tablet */
            $('#logo-box.tablet').addClass('col-xs-4'); // Only Tablet
            $('#logo-box.tablet').removeClass('col-xs-2'); // Only Tablet
            $('#logo-box.tablet').removeClass('col-xs-offset-4'); // Only Tablet
            $('#search-box.tablet').addClass('col-xs-offset-1'); // Only Tablet 
            $('#search-box.tablet').addClass('col-xs-7'); // Only Tablet
            $('#search-box.tablet').removeClass('col-xs-6'); // Only Tablet
        }
    });
    
});
</script>

<script type="text/javascript">

 
    $(".coupons-overview").on('click', function() {
        var source = $(this).attr("data-source");
        $( ".coupons-overview" ).removeClass( "active" );
        $(this).addClass( "active");
        
             $(".coupon-item").each(function() {
                 var source_item = $(this).attr("data-source");
                if (source_item == source || source == "all") {
                   $(this).show();
                } else {
                   $(this).hide();
                }
                 
             });
        
    });  
</script>   


<?php 
/*
$phpArray = explode('%20', $qclean);
$jsonArray = json_encode($phpArray);
*/
?>



<script type="text/javascript">
/*     
if ( "keywords" in window ) {

    if ( window.pagetype == "category" && ( window.querytype  == "description" || window.querytype == "name" ) ) {

    }  
    else {
        var searchTerm = window.keywords.split(' ');

        var ctx = document.querySelectorAll("h3.product-title");

        var instance = new Mark(ctx);

        var options = {
            "element": "span",
            "className": "highlight"
        };

        instance.mark(searchTerm, options);
    }
}                    
 */  
</script>



<script type="text/javascript">
 

var options = {

    url: function(phrase) {
          return window.baseURL + "src/autocomplete.call.php";
    },
    

    ajaxSettings: {
      dataType: "json",
      method: "POST",
      data: {
        dataType: "json"
      }
    },

    preparePostData: function(data) {
      data.phrase = $("#searchInput").val();

      return data;
    },
  
    getValue: "keyword",
  
    template: {
            type: "custom",
            method: function(value, item) {
                
                    return item.url + value + item.url_close;
                    
            }
    },
    
    list: {
       maxNumberOfElements: 10,
       match: {
           enabled: true
       }
    },
    
    minCharNumber: 0
    //requestDelay: 300
};

$("#searchInput").easyAutocomplete(options);
$('div.easy-autocomplete').removeAttr('style');  

<?php if(isset($cookie) ) { echo 'var cookie = '. $cookie .';'; } ?>
//console.log(cookie);



$('div.easy-autocomplete-container ul').html('<li>' + 'cookie' + '</li');


$("#searchInput").on('keyup', function () {
    console.log($("#searchInput").val().length);
    if ( $("#searchInput").val().length === 0) {
       $('div.easy-autocomplete-container ul').html('<li>' + 'cookie test agaisn' + '</li'); 
    }
    
});
    
</script>


<?php if ( $pageinfo->page['ref'] == 'sku' ){ ?>

<script type="text/javascript">

$(document).ready(function() {
    jQuery(function($) {
        
        $(window).on('scroll.related', function() {
            
            var elementOffset =  $('.modal-relatedProducts').offset().top ;
            var elementHeight =  $('.modal-relatedProducts').innerHeight();
            
           
            
            //console.log( elementOffset + ( elementHeight - 600  ) );
            //console.log( $(window).scrollTop() );
            
            
            if( elementOffset + ( elementHeight - 600 ) <=  $(window).scrollTop()) {
                $('.load-more-divider').before('<img class="loading-related center-block" src="/img/spinner.gif">');
                $(window).off('scroll.related');
                ajax_call( $(window), 1, 0);
            }
            
        });
    });   
});

</script>

<?php } ?>

<script type="text/javascript">

    function infinite_scroll( counter, offset ) {
    
        //console.log( 'Call infinite_scroll' );
        
        $('#product-modal').on('scroll.related', function() {
            
            //console.log( 'Scrolling Modal' );
            
            var elementOffset =  $('.modal-relatedProducts').offset().top ;
            var elementHeight =  $('.modal-relatedProducts').innerHeight();
           
            //console.log( elementOffset + ( elementHeight - 600  ) );
            //console.log( $('#product-modal').scrollTop() );
            
            
            if( elementOffset + ( elementHeight ) <=  $('#product-modal').scrollTop()) {
                $('.load-more-divider').before('<img class="loading-related center-block" src="/img/spinner.gif">');
                $('#product-modal').off('scroll.related');
                ajax_call( $('#product-modal'), counter, offset );
            }
            
        });
    }

</script>


<?php if ( $pageinfo->page['ref'] == 'home' ) { ?>
    
    <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "Organization",
      "name": "Compare Imports",
      "url": "https://www.compareimports.com",
      "sameAs": [
        "http://www.twitter.com/Compareimports",
        "https://www.google.com/+Compareimports",
        "http://www.facebook.com/CompareImports",
        "https://www.youtube.com/channel/UCJWpgIx3nHeHBTzTQUDYh7w",
        "http://instagram.com/compareimports/"
      ]
    }
    </script>

    <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "WebSite",
      "name": "Compare Imports",
      "url": "https://www.compareimports.com/",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://www.compareimports.com/search/{search_term_string}/",
        "query-input": "required name=search_term_string"
      }
    }
    </script>

<?php } ?>


<!-- <script type="text/javascript" src="<?php // echo BASE_URL; ?>includes/jquery/adb_detect.js"></script> -->

<script type="text/javascript">
    /*
    CookieChecker.setCallback(function(cookieAllowed, adblockEnabled) {
            
        console.log(cookieAllowed);
        console.log(adblockEnabled);
    });
    
    
    CookieChecker.run(); 
    */
</script>

<script type="text/javascript">
$(document).ready(function () {
        
        $('li#1-tab').click(function(){
            $('#tab1').load( '/includes/html/pricehistory_top_result_0.html' );
        });
        $('li#2-tab').click(function(){
            $('#tab2').load( '/includes/html/pricehistory_top_result_1.html' );
        });
        $('li#3-tab').click(function(){
            $('#tab3').load( '/includes/html/pricehistory_top_result_2.html' );
        });
        $('li#4-tab').click(function(){
            $('#tab4').load( '/includes/html/pricehistory_top_result_3.html' );
        });
        $('li#5-tab').click(function(){
            $('#tab5').load( '/includes/html/pricehistory_top_result_4.html' );
        });
        $('li#6-tab').click(function(){
            $('#tab6').load( '/includes/html/pricehistory_top_result_5.html' );
        });
        $('li#7-tab').click(function(){
            $('#tab7').load( '/includes/html/pricehistory_top_result_6.html' );
        });
        $('li#8-tab').click(function(){
            $('#tab8').load( '/includes/html/pricehistory_top_result_7.html' );
        });
        $('li#9-tab').click(function(){
            $('#tab9').load( '/includes/html/pricehistory_top_result_8.html' );
        });
        $('li#10-tab').click(function(){
            $('#tab10').load( '/includes/html/pricehistory_top_result_9.html' );
        });

});
</script>

</body>
</html>

<?php  // Kint::dump( $_SERVER  );?>
<?php  // Kint::dump( $GLOBALS );?>
<?php 
if (isset($shopreview->shopRef)){
    if (isset($shopreview->shopInfo['thread_id'])) {
        $comments_path = __DIR__ .'/comments/app/views/pjLayouts/pjActionLoad.php';
        include $comments_path;
        //include '/home/CI/public_html/_CI_Dev/comments/app/views/pjLayouts/pjActionLoad.php'; 
    }
}



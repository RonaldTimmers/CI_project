<?php
if ($ping789tt != "RduikjTT967") {
     exit('<h2>You cannot access this file directly</h2>');
} else {


	/*
	* NEW FUNCTIONS FOR GETTING ESSENTIAL INDEX PARTS! 
	*/
    
    //GET THE HEAD SECTION
    
    function get_head($categoryinfo, $pageinfo, $sku, $shopreview, $brandpage, $deviceType = null, $qclean = null) { 
        global $baseURL, $cdnURL;?>
        <meta charset="UTF-8" />
        
        <!-- BECAUSE OF DEV SERVER
        <!-- Page hiding snippet (recommended) 
        <style>.async-hide { opacity: 0 !important} </style>
        <script>(function(a,s,y,n,c,h,i,d,e){s.className+=' '+y;h.start=1*new Date;
        h.end=i=function(){s.className=s.className.replace(RegExp(' ?'+y),'')};
        (a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);h.timeout=c;
        })(window,document.documentElement,'async-hide','dataLayer', 2000,
        {'GTM-T2SDQMF':true});</script>
        
        <!-- Modified Analytics Script  
        <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-37081840-1', 'auto');
        ga('require', 'GTM-T2SDQMF');

        </script>
        -->
        
        <link rel="stylesheet" href="<?php echo $cdnURL . MAIN_CSS ;?>">
        
        
        <?php if ( $pageinfo->page['ref'] == 'sc' || $pageinfo->page['ref'] == 'search' || $pageinfo->page['ref'] == 'china-brands'  ) { ?>
            <link rel="stylesheet" href="<?php echo $cdnURL . CATEGORY_CSS ;?>">
        <?php } ?>
        
        <!-- BECAUSE OF DEV SERVER
       
        <!-- Google Tag Manager 
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-5G7GSM5');</script>
        <!-- End Google Tag Manager  

        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <script>
          (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-1491299795591872",
            enable_page_level_ads: true
          });
        </script>        
        -->
                
        
        <title><?php $categoryinfo->get_titles($pageinfo, $sku, $shopreview, $qclean);?></title>
        <meta name="description" content="<?php $categoryinfo->get_descriptions($pageinfo, $sku, $shopreview, $brandpage); ?>" />
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />	
        <meta name="robots" content="index, follow" />
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="msvalidate.01" content="482A244C1A9EEEBC50F945AD86AE5332" />       
        
        

        
        <meta property="og:type" content="website">
        <meta property="og:title" content="<?php $categoryinfo->get_titles( $pageinfo, $sku, $shopreview, $qclean  );?>">
        <meta property="og:description" content="<?php $categoryinfo->get_descriptions($pageinfo, $sku, $shopreview, $brandpage); ?>">
        <meta property="og:url" content="<?php echo 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"; ?>">
        <meta property="og:locale" content="en_us">
        <meta property="og:site_name" content="compareimports.com">
        <?php if ( $pageinfo->page['ref'] == 'sku'  ) { ?>
           <meta property="og:image" content="<?php echo $sku->productinfo['img'];?>">
        <?php } ?> 
        
        
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo $baseURL; ?>img/favicon.ico" />
        
        
        <?php if ($pageinfo->page['ref'] == 'sc' || $pageinfo->page['ref'] == 'search' || $pageinfo->page['ref'] == 'china-brands' || $pageinfo->page['ref'] == 'new-arrivals'  || $pageinfo->page['ref'] == 'top-sellers'  ) { 
            // Do Nothing
        } else { ?>
            <link rel="canonical" href="https://<?php echo $_SERVER['HTTP_HOST'] . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>">
        <?php } ?>
        
     <?php 
	}
        
      function get_js_files() {
        global $baseURL;?>
        <script src="<?php echo $baseURL; ?>includes/jquery/functions.js"></script>
      <?php }  
	 
     
     
    function set_bottom_footer($db) {         
                            $statement = $db->prepare("SELECT `bottomcolumn`,`ref`, `name`, `bottomorder` 
                                                        FROM `CMS_pages` 
                                                        WHERE `active` = 1 AND `bottombar` = 1 AND `ref` != 'home' 
                                                        ORDER BY `bottomcolumn` ASC, `bottomorder` ASC");
                            $statement->execute();
                            $footer_links = $statement->fetchAll(PDO::FETCH_GROUP);
                            /*
                            While($bottombar = $statement->fetch(PDO::FETCH_ASSOC)) {																						
                                    echo '<li> <a href="'. $baseURL . $bottombar['ref'] .'/">'. $bottombar['name'] .'</a> </li>';
                            }
                                    
                            */
                            unset($statement);
                            return $footer_links;
    }
     
     
     /* Set the bottom bar */
    function get_bottom_footer($db) {
        global $baseURL; ?>

                <footer class="footer">
                    <div class="container footer-container">
                        <div id="bottom-wrapper">
                                <div id="bottom-links" class="col-md-12 col-lg-12"> 
                                    
                                    <div class="col-ms-6 col-md-6 col-lg-4">
                                        <span class="bottom-header">CompareImports</span>
                                        <ul>
                                        
                                            <?php 
                                            
                                            $footer_links = set_bottom_footer($db);
                                            
                                            //var_dump($footer_links);
                                            
                                            foreach ($footer_links[1] as $footer_link) {
                                                echo '<li><a href="'. $baseURL . $footer_link['ref'] .'/">'. $footer_link['name'] .'</a> </li>'; 
                                            }
                                            
                                            ?>
          
                                        </ul>
                                    </div>
                                
                                    <div class="col-ms-6 col-md-6 col-lg-4">
                                        <span  class="bottom-header">Buying in China?</span>
                                        <ul>
                                        <?php
                                                foreach ($footer_links[2] as $footer_link) {
                                                    echo '<li><a href="'. $baseURL . $footer_link['ref'] .'/">'. $footer_link['name'] .'</a> </li>'; 
                                                }
                                        ?>
                                        </ul>
                                    </div>
                                    
                                    <div id="social-box" class="col-ms-12 col-md-12 col-lg-4">
                                    
                                    <span class="bottom-header">Connect With Us</span>
                                            <ul class="social_list">
                                                    
                                                    <!-- <li class="blog"><a href="<?php echo $baseURL .'blog/';?>" target="_blank" title="Blog"></a></li> -->
                                                    <li><a href="https://www.youtube.com/channel/UCJWpgIx3nHeHBTzTQUDYh7w" target="_blank" title="Youtube" rel="publisher"><span class="youtube"></span></a></li>
                                                    <li><a href="http://www.facebook.com/CompareImports?rel=author" target="_blank" title="Facebook"><span class="facebook"></span></a></li>
                                                    <li><a href="https://www.google.com/+Compareimports" target="_blank" title="Google+" rel="publisher"><span class="gplus"></span></a></li>
                                                    <li><a href="http://www.twitter.com/Compareimports" target="_blank" title="Twitter"><span class="twitter"></span></a></li>
                                                    <li><a href="http://instagram.com/compareimports/" target="_blank" title="Instagram"><span class="instagram"></span></a></li>
                                            </ul>  
                                          
                                            
                                            <div style="clear: both;"></div>    
                                    </div>
                                     <div class="col-ms-8 col-md-8">
                                        <span class="bottom-header">NewsLetter</span>
                                        <div class="subscribe wow fadeInUp">
                                         
                                            <form class="form-inline" method="post">
                                                <div class="form-group">
                                                    <label class="sr-only" for="subscribe-email">Email address</label>
                                                    <input type="text" name="email" placeholder="Enter your email..." class="subscribe-email form-control" id="subscribe-email">
                                                </div>
                                                <button type="submit" class="btn btn-primary">Subscribe</button>
                                            </form>
                                         
                                            <div class="success-message"></div>
                                            <div class="error-message"></div>
                                        </div>
                                    </div>   
                                </div>
                                <!--
                                 <div class="facebook-iframe col-md-3 hidden-xs hidden-ms hidden-md">
                                        <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fcompareimports%2F&tabs=timeline&width=340&height=300&small_header=true&adapt_container_width=true&hide_cover=false&show_facepile=false&appId" width="340" height="300" style="border:none;overflow:hidden" scrolling="no"></iframe>
                                 </div>
                                 -->


                                <div style="clear: both;"></div>
                                
                                <div id="copyright-box"><span class="glyphicon glyphicon-copyright-mark" aria-hidden="true"></span> 2013-2018 Compare Imports</div>

                        </div>
                    </div>  
                </footer>      
    <?php 
	}
	
   function set_header ($page) {
         if  ($page == "error-404") {
	      header('HTTP/1.1 404 Not Found');
        } if ($page == "error-403") {
		header('HTTP/1.1 403 Forbidden');
        }
   }   
   
   /*
    * Helper Funtion
    * 
    * 
    */
   
if (! function_exists('array_column')) {
    function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if ( !array_key_exists($columnKey, $value)) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            }
            else {
                if ( !array_key_exists($indexKey, $value)) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if ( ! is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}
   
   
   
/*
 * Functions Needed to Get the Search keywords In Array 
 */
   
   
function search_split_terms($terms){
    
    $terms = preg_replace("/\"(.*?)\"/e", "search_transform_term('\$1')", $terms);
    $terms = preg_split("/\s+|,/", $terms);

    $out = array();

    foreach($terms as $term){

        $term = preg_replace("/\{WHITESPACE-([0-9]+)\}/e", "chr(\$1)", $term);
        $term = preg_replace("/\{COMMA\}/", ",", $term);

        $out[] = $term;
    }

    return $out;
}

function search_transform_term($term){
    $term = preg_replace("/(\s)/e", "'{WHITESPACE-'.ord('\$1').'}'", $term);
    $term = preg_replace("/,/", "{COMMA}", $term);
    return $term;
}



function search_html_escape_terms($terms){
    $out = array();

    foreach($terms as $term){
        if (preg_match("/\s|,/", $term)){
            $out[] = '"'.HtmlSpecialChars($term).'"';
        }else{
            $out[] = HtmlSpecialChars($term);
        }
    }

    return $out;	
}

function search_pretty_terms($terms){

    if (count($terms) == 1){
        return array_pop($terms);
    }

    $last = array_pop($terms);

    return ucwords(implode(', ', $terms )). " and ". ucwords( $last );
}   
   
   
   
   
   
   
   
   
   
   
   
	/*
	* Connect to the MySQL database - read only
	*/
	function openDB() {
		global $db;
		$db = new mysqli('127.0.0.1', 'ci_db', 'kiotola1$', 'CI');
		$db->set_charset("utf8");
		if($db->connect_errno > 0){
			// for debugging could add: $db->connect_error
			die('Unable to connect to database.');
		}
	}

	/*
	* Connect to the MySQL database - read & write access
	*/
	function openDB_admin() {
		global $db;
		$db = new mysqli('127.0.0.1', 'ci_db', 'kiotola1$', 'CI');

		if($db->connect_errno > 0){
			// for debugging could add: $db->connect_error
			die('Unable to connect to database.');
		}
	}
	
	/*
	* Connect to the Wordpress MySQL database - read only
	*/
	function openDBwp() {
		global $dbwp;
		$dbwp = new mysqli('127.0.0.1', 'wrdpr', 'wrdpr87654321%$#', 'ddz0r_wrdp1');

		if($dbwp->connect_errno > 0){
			// for debugging could add: $db->connect_error
			die('Unable to connect to database.');
		}
	}
	
	/* 
	 * make an URL friendly string out of for example a sku title
         * We still use this function to create product urls 
         * 
         * DO NOT CHANGE! BECAUSE THE INDEXED PAGES WILL BE NOT AVAILABLE ANY MORE!!! 12-1-18
	 */
        
	function urlFriendly($string) {
            // remove all crazy signs except letters, numbers and spaces
            $string = preg_replace('/[^0-9a-zA-z ]/', '' , $string); 

            // if string is more then 95 check the first next space (to allow full words), with max of 120 string length
            if (strlen($string) > 95) {
                $limit = @strpos($string, ' ', 95);

                if (!isset($limit) || !is_numeric($limit) || $limit > 120) {
                        $limit = 120;
                }

                $string =  substr($string, 0, $limit);
            }

            // trim
            $string = trim($string);

            // replace spaces with -
            $string = str_replace(' ', '-', $string);
            $string = str_replace('--', '-', $string);
            
            // return the result
            return $string;
	}

        /*
	Truncated text to the nearest word based on a character count - substr() 
	http://www.beliefmedia.com/php-truncate-functions
	preg-match() 
	http://php.net/manual/en/function.preg-match.php
        */
 
        function titleShortner($string, $length, $trimmarker = '') {
            
            /*
             * First Remove Duplicates from Title
             */
            $string = preg_replace("/\b(\w+)\s+\\1\b/i", "$1", $string);

            $strlen = strlen($string);
            
            /* mb_substr forces a break at $length if no word (space) boundary */
            $string = trim(mb_substr($string, 0, $strlen));
            
            if ($strlen > $length) {
                preg_match('/^.{1,' . ($length - strlen($trimmarker)) . '}\b/su', $string, $match);
                $string = trim($match['0']) . $trimmarker;
            } else {
                $string = trim($string);
            }
            
            return $string;
        }
 
        
	/* 
	Set Page 
	*/
	function page_check($db, $page_input) {
		
		// connect to the database and get information
		$statement = $db->prepare("SELECT `ref`,`name`,`type`,`source`,`content` FROM `CMS_pages` WHERE `active` = 1 AND `ref` = :ref");	// prepare statement
		$statement->execute(array(':ref' => $page_input));										// execute query, setup binds to the page array
		$page = $statement->fetch(PDO::FETCH_ASSOC);											// fetch the results
		unset($statement);	
		
		// return page info as array
		return $page;
	}
	/* 
	Set Page 
	*/
	
	
	/* 
	* Get the Categories
	*/
	function loadCats() {
		global $baseURL, $subsubcat, $subcat, $cat, $db;
		$subsubcat = NULL;
		$subcat = NULL;
		$cat = NULL;
		
		if (isset($_GET['subsubcat'])) {
			if (!is_numeric($_GET['subsubcat'])) {
				die("Wrong input.");
			}
			
			$statement = $db->prepare("SELECT `id`,`subcat`,`name`,`ref` FROM `subsubcats` WHERE `id` = :id AND `del` = '0'");
			//$statement->bind_param('i', $_GET['subsubcat']);
			$statement->bindValue(':id', $_GET['subsubcat'], PDO::PARAM_INT);
                        $statement->execute();
			//$statement->store_result();
			//$statement->bind_result($subsubcat['id'], $subsubcat['subcat'], $subsubcat['name'], $subsubcat['ref']);
			$statement->fetch(PDO::FETCH_ASSOC);
			//$statement->free_result();
			//$statement->close();
                        $statement = null;	
                        unset($statement);
			
			$statement = $db->prepare("SELECT `id`,`cat`,`name`,`ref` FROM `subcats` WHERE `id` = :id");
			//$statement->bind_param('i', $subsubcat['subcat']);
			$statement->bindValue(':id', $subsubcat['subcat'], PDO::PARAM_INT);
                        $statement->execute();
			//$statement->store_result();
			//$statement->bind_result($subcat['id'], $subcat['cat'], $subcat['name'], $subcat['ref']);
			$statement->fetch(PDO::FETCH_ASSOC);
			//$statement->free_result();
			//$statement->close();
                        $statement = null;	
                        unset($statement);
			
			$statement = $db->prepare("SELECT `id`,`name`,`ref` FROM `categories` WHERE `id` = :id");
			//$statement->bind_param('i', $subcat['cat']);
			$statement->bindValue(':id', $subcat['cat'], PDO::PARAM_INT);
                        $statement->execute();
			//$statement->store_result();
			//$statement->bind_result($cat['id'], $cat['name'], $cat['ref']);
			$statement->fetch(PDO::FETCH_ASSOC);
			//$statement->free_result();
			//$statement->close();
                        $statement = null;	
                        unset($statement);
                        
		} elseif (isset($_GET['subcat'])) {
			if (!is_numeric($_GET['subcat'])) {
				die("Wrong input.");
			}
			
			$statement = $db->prepare("SELECT `id`,`cat`,`name`,`ref` FROM `subcats` WHERE `id` = :id AND `del` = '0'");
			//$statement->bind_param('i', $_GET['subcat']);
			$statement->bindValue(':id', $_GET['subcat'], PDO::PARAM_INT);
                        $statement->execute();
			//$statement->store_result();
			//$statement->bind_result($subcat['id'], $subcat['cat'], $subcat['name'], $subcat['ref']);
			$statement->fetch(PDO::FETCH_ASSOC);
			//$statement->free_result();
			//$statement->close();
                        $statement = null;	
                        unset($statement);
			
			$statement = $db->prepare("SELECT `id`,`name`,`ref` FROM `categories` WHERE `id` = :id");
			//$statement->bind_param('i', $subcat['cat']);
			$statement->bindValue(':id', $subcat['cat'], PDO::PARAM_INT);
                        $statement->execute();
			//$statement->store_result();
			//$statement->bind_result($cat['id'], $cat['name'], $cat['ref']);
			$statement->fetch(PDO::FETCH_ASSOC);
			//$statement->free_result();
			//$statement->close();
                        $statement = null;	
                        unset($statement);
                 
		} elseif (isset($_GET['cat'])) {
			if (!is_numeric($_GET['cat'])) {
				die("Wrong input.");
			}
			
			$statement = $db->prepare("SELECT `id`,`name`,`ref` FROM `categories` WHERE `id` = :id AND `del` = '0'");
			//$statement->bind_param('i', $_GET['cat']);
			$statement->bindValue(':id', $_GET['cat'], PDO::PARAM_INT);
                        $statement->execute();
			//$statement->store_result();
			//$statement->bind_result($cat['id'], $cat['name'], $cat['ref']);
			$statement->fetch(PDO::FETCH_ASSOC);
			//$statement->free_result();
			//$statement->close();
                        $statement = null;	
                        unset($statement);
		}
	}

	function topbar($db) {
		global $baseURL;
	// Output Topbar with ao (Bestbuy, Daily Deals)

	echo '<ul>';
		$statement = $db->prepare("SELECT `ref`,`name`,`topbarsub`,`toporder` FROM `CMS_pages` WHERE `active` = 1 AND `topbar` = 1 ORDER BY `toporder` ASC");
		$statement->execute();																										
					
			while ($topbar = $statement->fetch(PDO::FETCH_ASSOC)) {																								
			
					if ($topbar['topbarsub'] != 1) {					
					echo '<li><a href="'. $baseURL . $topbar['ref'] .'/">'. $topbar['name'] .'</a>';
					echo '<ul>';	
					}
					
					$statement2 = $db->prepare("SELECT `ref`,`name`,`topbarsub`,`toporder` FROM `CMS_pages` WHERE `active` = 1 AND `topbarsub` = 1 AND `toporder` = ?");
					$statement2->bindValue(1, $topbar['toporder'], PDO::PARAM_INT);
					$statement2->execute();																											

			
					while ($topbar2 = $statement2->fetch(PDO::FETCH_ASSOC)) {						
						echo '<li><a href="'. $baseURL . $topbar2['ref'] .'/">'. $topbar2['name'] .'</a></li>';			
					}	
			
					unset($statement2);
				
					echo '</ul>';
					echo '</li>';	
			}			
		echo '</ul>';		
	unset($statement);
        }
	

	/* 
	* Create the Top Navigation Bar
	*/
	function navBarTop($db) {
		global $baseURL;
		$count = 1; 
		
		echo '<ul class="navBarTop">';
		
			$statement = $db->prepare("SELECT cat.id, cat.name, cat.ref FROM categories cat WHERE `topbar` = 1 OR `topbar` = 0 AND `del` = 0 ORDER BY `order` ASC");
			$statement->execute();
                        
			while($navBarCat = $statement->fetch(PDO::FETCH_ASSOC)) {
				$column = 1;
                                $navBarCat['name'] = htmlentities($navBarCat['name'], ENT_QUOTES);
				
				echo '<li class="navBarTop-MainLi"><a href="'. $baseURL .'1/'. $navBarCat['id'] .'/'. $navBarCat['ref'] .'/">'. $navBarCat['name'] .' </a>';
				echo '<ul class="navBarTop-columnarGeneral navBarTop-columnar'. $count .'">';
				echo '<li class="leftColumnNavTop"><ul>';
				
				$statement2 = $db->prepare("SELECT `id`,`column`,`name`,`ref` FROM `subcats` WHERE `topbar` = 1 AND `del` = 0 AND `cat` = ? ORDER BY `column` ASC, `order` ASC");
				$statement2->bindValue(1, $navBarCat['id'], PDO::PARAM_INT);
				$statement2->execute();
				
				while($navBarSub = $statement2->fetch(PDO::FETCH_ASSOC)) {
                                $navBarSub['name'] = htmlentities($navBarSub['name'], ENT_QUOTES);
                                
					if ($column != $navBarSub['column']) {
						if ($navBarSub['column'] == 2) {
							echo '</ul></li><li class="centerColumnNavTop"><ul>';
						} elseif ($navBarSub['column'] == 3) {
							echo '</ul></li><li class="rightColumnNavTop"><ul>';
						}
					}
					
					echo '<li><a href="'. $baseURL .'2/'. $navBarSub['id'] .'/'. $navBarSub['ref'] .'/">'. $navBarSub['name'] .' </a>';
					echo '<ul>';
					
					$statement3 = $db->prepare("SELECT `id`,`name`,`ref` FROM `subsubcats` WHERE `topbar` = 1 AND `del` = 0 AND `subcat` = ? ORDER BY `order` ASC");
					$statement3->bindValue(1, $navBarSub['id'], PDO::PARAM_INT);
					$statement3->execute();

					
					while($navBarSubSub = $statement3->fetch(PDO::FETCH_ASSOC)) {
                                        $navBarSubSub['name'] = htmlentities($navBarSubSub['name'], ENT_QUOTES);
						echo '<li><a href="'. $baseURL .'3/'. $navBarSubSub['id'] .'/'. $navBarSubSub['ref'] .'/">'. $navBarSubSub['name'] .' </a></li>';
					}
					
                                        unset($statement3);
					
					echo '</ul>';
					echo '</li>';
					
					$column = $navBarSub['column'];
				}
				
                                unset($statement2);
				
				echo '</ul></li>';
				echo '</ul>';
				echo '</li>';
				
				$count = $count + 1;
			}
			
                        unset($statement);
		
                 /*      
			echo '<li class="navBarTop-OtherLi"><a href="">Other Categories</a>';
			echo '<ul class="navBarTop-columnarOther">';
			
			$count = 1;
			
			$statement = $db->prepare("SELECT `id`,`name`,`ref` FROM `categories` WHERE `topbar` = 0 AND `del` = 0 ORDER BY `order` ASC");
			$statement->execute();

			while($navBarCat = $statement->fetch(PDO::FETCH_ASSOC)) {
				$column = 1;
				$navBarCat['name'] = htmlspecialchars($navBarCat['name'], ENT_QUOTES);
                                
				echo '<li><a href="'. $baseURL .'1/'. $navBarCat['id'] .'/'. $navBarCat['ref'] .'/">'. $navBarCat['name'] .' </a>';
				echo '<ul class="navBarTop-OtherCat navBarTop-OtherCat'. $count .'">';
				echo '<li class="leftColumnNavTop"><ul>';
				
				$statement2 = $db->prepare("SELECT `id`,`column`,`name`,`ref` FROM `subcats` WHERE `topbar` = 1 AND `del` = 0 AND `cat` = ? ORDER BY `column` ASC, `order` ASC");
				$statement2->bindValue(1, $navBarCat['id'], PDO::PARAM_INT);
				$statement2->execute();
				
				while($navBarSub = $statement2->fetch(PDO::FETCH_ASSOC)) {
                                $navBarSub['name'] = htmlspecialchars($navBarSub['name'], ENT_QUOTES);    
					if ($column != $navBarSub['column']) {
						if ($navBarSub['column'] == 2) {
							echo '</ul></li><li class="centerColumnNavTop"><ul>';
						} elseif ($navBarSub['column'] == 3) {
							echo '</ul></li><li class="rightColumnNavTop"><ul>';
						}
					}
					
					echo '<li><a href="'. $baseURL .'2/'. $navBarSub['id'] .'/'. $navBarSub['ref'] .'/">'. $navBarSub['name'] .' </a>';
					echo '<ul>';
					
					$statement3 = $db->prepare("SELECT `id`,`name`,`ref` FROM `subsubcats` WHERE `topbar` = 1 AND `del` = 0 AND `subcat` = ? ORDER BY `order` ASC");
					$statement3->bindValue(1, $navBarSub['id'], PDO::PARAM_INT);
					$statement3->execute();
					
					while($navBarSubSub = $statement3->fetch(PDO::FETCH_ASSOC)) {
                                        $navBarSubSub['name'] = htmlspecialchars($navBarSubSub['name'], ENT_QUOTES);    
						echo '<li><a href="'. $baseURL .'3/'. $navBarSubSub['id'] .'/'. $navBarSubSub['ref'] .'/">'. $navBarSubSub['name'] .' </a></li>';
					}
					
                                        unset($statement3);
					
					echo '</ul>';
					echo '</li>';
					
					$column = $navBarSub['column'];
				}
				
                                unset($statement2);
				
				echo '</ul></li>';
				echo '</ul>';
				echo '</li>';
				
				$count = $count + 1;
			}
			
                        unset($statement);
			
			echo '</ul>';
			echo '</li>';
			*/
		echo '</ul>';
	}


	/* 
	* Print the Location Bar
	*/
	function locationBar($page_name,$page_ref,$cat,$subcat,$subsubcat) {
		global $baseURL;
		echo '<a href="'. $baseURL .'">Compare Imports</a>';
		if ($page_ref != 'c' && $page_ref != 'sc') {
			echo ' >> <a href="'. $baseURL . $page_ref .'/">'. $page_name .'</a>';
		}
		if (isset($cat)) {
			echo ' >> <a href="'. $baseURL .'1/'. $cat['id'] .'/'. $cat['ref'] .'/">'. $cat['name'] .'</a>';
		}
		if (isset($subcat)) {
			echo ' >> <a href="'. $baseURL .'2/'. $subcat['id'] .'/'. $subcat['ref'] .'/">'. $subcat['name'] .'</a>';
		}
		if (isset($subsubcat)) {
			echo ' >> <a href="'. $baseURL .'3/'. $subsubcat['id'] .'/'. $subsubcat['ref'] .'/">'. $subsubcat['name'] .'</a>';
		}
	}


	/* 
	* Print the Side Navigation Bar for pages like home
	*/
	function sideNav() {
		global $baseURL, $db;
		
		$statement = $db->prepare("SELECT `id`,`name`,`ref` FROM `categories` WHERE `sidebar` = 1 AND `del` = 0 ORDER BY `order` ASC");
		$statement->execute();
		$statement->store_result();
		$statement->bind_result($id, $name, $ref);

		while($statement->fetch()) {
			echo '<li><a href="'. $baseURL .'1/'. $id .'/'. $ref .'/">'. $name .'</a>';
			echo '<ul>';
			
			
			$statement2 = $db->prepare("SELECT `id`,`name`,`ref` FROM `subcats` WHERE `sidebar` = 1 AND `del` = 0 AND `cat` = ? ORDER BY `order` ASC");
			$statement2->bind_param('i', $id);
			$statement2->execute();
			$statement2->store_result();
			$statement2->bind_result($id2, $name2, $ref2);
			
			while($statement2->fetch()) {
				echo '<li><a href="'. $baseURL .'2/'. $id2 .'/'. $ref2 .'/">'. $name2 .'</a>';
				echo '<ul>';
				
				$statement3 = $db->prepare("SELECT `id`,`name`,`ref` FROM `subsubcats` WHERE `sidebar` = 1 AND `del` = 0 AND `subcat` = ? ORDER BY `order` ASC");
				$statement3->bind_param('i', $id2);
				$statement3->execute();
				$statement3->store_result();
				$statement3->bind_result($id3, $name3, $ref3);
				
				while($statement3->fetch()) {
					echo '<li><a href="'. $baseURL .'3/'. $id3 .'/'. $ref3 .'/">'. $name3 .'</a></li>';
				}
				
				$statement3->free_result();
				$statement3->close();
				
				echo '</ul>';
				echo '</li>';
			}
			
			$statement2->free_result();
			$statement2->close();
			
			echo '</ul>';
			echo '</li>';
		}
		
		$statement->free_result();
		$statement->close();
	}


	/* 
	* Print the Side Navigation Bar for a specific Category
	*/
	function sideNavCat($cat) {
		global $baseURL, $db;
		echo '<li><a href="'. $baseURL .'1/'. $cat['id'] .'/'. $cat['ref'] .'/">'. $cat['name'] .'</a>';
			echo '<ul>';
				
				$statement = $db->prepare("SELECT `id`,`name`,`ref` FROM `subcats` WHERE `cat` = ? AND `del` = 0 ORDER BY `order` ASC");
				$statement->bind_param('i', $cat['id']);
				$statement->execute();
				$statement->store_result();
				$statement->bind_result($id, $name, $ref);
				
				while($statement->fetch()) {
					echo '<li><a href="'. $baseURL .'2/'. $id .'/'. $ref .'/">'. $name .'</a>';
						echo '<ul>';
						
							$statement2 = $db->prepare("SELECT `id`,`name`,`ref` FROM `subsubcats` WHERE `subcat` = ? AND `del` = 0 ORDER BY `order` ASC");
							$statement2->bind_param('i', $id);
							$statement2->execute();
							$statement2->store_result();
							$statement2->bind_result($id2, $name2, $ref2);
						
							while($statement2->fetch()) {
								echo '<li><a href="'. $baseURL .'3/'. $id2 .'/'. $ref2 .'/">'. $name2 .'</a></li>';
							}
							
							$statement2->free_result();
							$statement2->close();
						
						echo '</ul>';
					echo '</li>';
				}
				
				$statement->free_result();
				$statement->close();
				
			echo '</ul>';
		echo '</li>';
	}

	/* 
	* Print the Side Navigation Bar for a specific Sub-Category
	*/
	function sideNavSubCat($cat,$subcat) {
		global $baseURL, $db;
		
		echo '<li><a href="'. $baseURL .'1/'. $cat['id'] .'/'. $cat['ref'] .'/">'. $cat['name'] .'</a>';
			echo '<ul>';
				echo '<li><a href="'. $baseURL .'2/'. $subcat['id'] .'/'. $subcat['ref'] .'/">'. $subcat['name'] .'</a>';
					echo '<ul>';
						
						$statement = $db->prepare("SELECT `id`,`name`,`ref` FROM `subsubcats` WHERE `subcat` = ? AND `del` = 0 ORDER BY `order` ASC");
						$statement->bind_param('i', $subcat['id']);
						$statement->execute();
						$statement->store_result();
						$statement->bind_result($id, $name, $ref);
						
						while($statement->fetch()) {
							echo '<li><a href="'. $baseURL .'3/'. $id .'/'. $ref .'/">'. $name .'</a></li>';
						}
						
						$statement->free_result();
						$statement->close();
						
					echo '</ul>';
				echo '</li>';
			echo '</ul>';
		echo '</li>';
	}
	
	/* 
	* Print the Side Link Bar for pages like home
	*/
	function sideLinks() {
		global $baseURL, $db;
		
		echo '
		<div style="clear: both"></div>
		<div id="LeftSideLinksWrapper">
			<a href="http://www.compareimports.com/facebook/"><div id="facebookbannerleft"></div></a>
			<div class="leftHeader">Current Shops</div>
			<div id="LeftSideLinks">';
			
				$statement = $db->prepare("SELECT `name`,`url` FROM `sources` WHERE `active` = '1' AND `current` = '1' ORDER BY `rank` ASC");
				$statement->execute();
				$statement->store_result();
				$statement->bind_result($name, $url);
				
				while($statement->fetch()) {
					$url = htmlspecialchars($url, ENT_QUOTES);
					echo '<a class="SiteLinks" rel="nofollow" target="_blank" href="'. $url .'"></a>';
				}
				
				$statement->free_result();
				$statement->close();
				
		echo '
			</div>
			<div class="leftHeader">Upcoming</div>
			<div id="upcomingLeftSideLinks">';
				
				$statement = $db->prepare("SELECT `name`,`small`,`url` FROM `sources` WHERE `active` = '1' AND `current` = '0' ORDER BY `rank` ASC");
				$statement->execute();
				$statement->store_result();
				$statement->bind_result($name, $logo, $url);
				
				while($statement->fetch()) {
					$url = htmlspecialchars($url, ENT_QUOTES);
					echo '<a class="SiteLinks" rel="nofollow" target="_blank" href="'. $url .'"><img class="sourceSmall" src="'. $baseURL .'img/sources/small/'. $logo .'" alt="'. $name .'" /></a>';
				}
				
				$statement->free_result();
				$statement->close();
				
		echo '
			</div>
		</div>';
	}
	
	/* 
	* Print the Side Filters
	*/
	function sideFilters($currStarsInput,$rangeMin,$rangeMax,$currURLstr) {
		global $baseURL;
		
		echo '
		<div style="clear:both;"></div>
		<div id="priceFilterDiv">
			<div class="leftHeader">Price Range</div>
			<form id="priceFilterForm" method="get">
				'. $currStarsInput .'
				<input type="hidden" id="minPrice" name="min" value="'. $rangeMin .'" />
				<input type="hidden" id="maxPrice" name="max" value="'. $rangeMax .'" />
				<label for="amount" hidden="hidden">Price range</label>
				<input type="text" id="amount" />
				<div id="slider-range"></div>
				<input id="priceApply" type="submit" value="Apply" />
			</form>
		</div>
		<div id="reviewFilterDiv">
			<div class="leftHeader">Reviews</div>
			<a href="?'. $currURLstr .'stars=5"><img src="'. $baseURL .'img/stars/5stars.png" /> & Up</a><br />
			<a href="?'. $currURLstr .'stars=4"><img src="'. $baseURL .'img/stars/4stars.png" /> & Up</a><br />
			<a href="?'. $currURLstr .'stars=3"><img src="'. $baseURL .'img/stars/3stars.png" /> & Up</a><br />
			<a href="?'. $currURLstr .'stars=2"><img src="'. $baseURL .'img/stars/2stars.png" /> & Up</a><br />
			<a href="?'. $currURLstr .'stars=1"><img src="'. $baseURL .'img/stars/1stars.png" /> & Up</a><br />
			<a href="?'. $currURLstr .'stars=0"><img src="'. $baseURL .'img/stars/0stars.png" /> & Up</a>
		</div>';
	}
	
	/*
	* Print the Top Filters
	*/
	function topFilters($currShopsInput,$currStarsInput,$rangeMin,$rangeMax,$keywords) {
		global $baseURL, $db;
		
			$shopArray = Array();
			$shopArray = explode('-',$currShopsInput);
		
			$select0 = '';
			$select1 = '';
			$select2 = '';
			$select3 = '';
			$select4 = '';
			$select5 = '';
			
			switch ($currStarsInput) {
				case 0:
					$select0 = 'checked="checked"';
					break;
				case 1:
					$select1 = 'checked="checked"';
					break;
				case 2:
					$select2 = 'checked="checked"';
					break;
				case 3:
					$select3 = 'checked="checked"';
					break;
				case 4:
					$select4 = 'checked="checked"';
					break;
				case 5:
					$select5 = 'checked="checked"';
					break;
				default:
					$select0 = 'checked="checked"';
					break;
			}
		
		echo '
		<div id="filterDivTopWrapper">
			<form id="filterForm" method="get">
		
			<div class="filterDivTop" id="filterDivTopPrice">
				<div class="filterHeader">Price Range</div>
				<div class="filterBox">
						<div class="priceBox">
							<input type="hidden" id="minPrice" name="min" value="'. $rangeMin .'" />
							<input type="hidden" id="maxPrice" name="max" value="'. $rangeMax .'" />
							<div id="slider-range"></div>
						</div>
						<div class="priceBox">
							<label for="amount" hidden="hidden">Price range</label>
							<input type="text" id="amount" />
						</div>
						<div style="clear: both;"></div>
				</div>
				<div style="clear: both;"></div>
			</div>
			
			<div class="filterDivTop" id="filterDivTopStars">
				<div class="filterHeader">Min. Stars</div>
				<div class="filterBox">
					<input type="radio" name="stars" value="0" '. $select0 .' /><img src="'. $baseURL .'img/stars/0stars.png" /> - 
					<input type="radio" name="stars" value="1" '. $select1 .' /><img src="'. $baseURL .'img/stars/1stars.png" /> - 
					<input type="radio" name="stars" value="2" '. $select2 .' /><img src="'. $baseURL .'img/stars/2stars.png" /> - 
					<input type="radio" name="stars" value="3" '. $select3 .' /><img src="'. $baseURL .'img/stars/3stars.png" /> - 
					<input type="radio" name="stars" value="4" '. $select4 .' /><img src="'. $baseURL .'img/stars/4stars.png" /> - 
					<input type="radio" name="stars" value="5" '. $select5 .' /><img src="'. $baseURL .'img/stars/5stars.png" />
				</div>
				<div style="clear: both;"></div>
			</div>
			
			<div class="filterDivTop" id="filterDivTopShops">
				<div class="filterHeader">Shops</div>
				<div class="filterBox">
			';
		
				$statement = $db->prepare("SELECT `id`,`name` FROM `sources` WHERE `active` = '1' AND `current` = '1' ORDER BY `rank` ASC");
				$statement->execute();
				$statement->store_result();
				$statement->bind_result($id, $name);
				
				while($statement->fetch()) {
					$selected = '';
					if ($currShopsInput != '') {
						foreach($shopArray as $key => $shop) {
							if ($id == $shop) {
								$selected = 'checked="checked"';
							}
						}
					} else {
						$selected = 'checked="checked"';
					}
					echo '<div class="shopSelect"><input type="checkbox" name="inclShop[]" value="'. $id .'" '. $selected .'> '. $name .'</div>';
				}
				
				$statement->free_result();
				$statement->close();
			
			echo '<div style="clear: both;"></div>
				</div>
				<div style="clear: both;"></div>
			</div>
			
			<div class="filterDivTop" id="filterDivTopText">
				<div class="filterHeader">Text Filter</div>
				<div class="filterBox">
					<input type="text" name="textFilter" id="textfilter" value="'. HtmlSpecialChars($keywords) .'" />
				</div>
				<div style="clear: both;"></div>
			</div>
			
			<div class="filterDivTop" id="filterDivTopApply">
				<div class="filterHeader">&nbsp;</div>
				<div class="filterBox">
					<div class="applyBox">
							<input id="priceApply" type="submit" value="Apply" />
						</div>
				</div>
				<div style="clear: both;"></div>
			</div>
			
			</form>

			<div style="clear: both;"></div>
		</div>';
	}

	
	/*
	* Creates product thumbs with links
	*/
	function CreateThumbs($db,$order1,$order2,$limit1,$limit2,$sql_string,$types_string,$keywords_array,$adult) {
		global $baseURL, $staticURL, $subsubcat, $subcat, $cat;
		
		$countT = 0;
		
		if(!isset($adult) || $adult == 0 || $adult == '') {
			$adultString = " AND T1.our_cat1 != '17'";
		} else {
			$adultString = "";
		}
		
		$param_array = Array();
		
		$whereString = "T1.ourCat = '10000000000'";
		
		if ($sql_string == '' OR !isset($sql_string)) {
			if (isset($_GET['subsubcat'])) {
				$whereString = "(T1.our_subsub1 = ? OR T1.our_subsub2 = ? OR T1.our_subsub3 = ?)";
				$param_array[] = $subsubcat['id'];
				$param_array[] = $subsubcat['id'];
				$param_array[] = $subsubcat['id'];
			} elseif (isset($_GET['subcat'])) {
				$whereString = "(T1.our_sub1 = ? OR T1.our_sub2 = ? OR T1.our_sub3 = ?)";
				$param_array[] = $subcat['id'];
				$param_array[] = $subcat['id'];
				$param_array[] = $subcat['id'];
			} elseif (isset($_GET['cat'])) {
				$whereString = "(T1.our_cat1 = ? OR T1.our_cat2 = ? OR T1.our_cat3 = ?)";
				$param_array[] = $cat['id'];
				$param_array[] = $cat['id'];
				$param_array[] = $cat['id'];
			}
		} else {
			$whereString = $sql_string;
			//$param_types .= $types_string;
			$param_array = $keywords_array;
			if (isset($_GET['cat']) && $_GET['cat'] != 0) {
				$param_array[] = $_GET['cat'];
				$param_array[] = $_GET['cat'];
				$param_array[] = $_GET['cat'];
				$whereString .= " AND (T1.our_cat1 = ? OR T1.our_cat2 = ? OR T1.our_cat3 = ?)";
			} elseif (isset($_GET['subsubcat'])) {
				$whereString .= " AND (T1.our_subsub1 = ? OR T1.our_subsub2 = ? OR T1.our_subsub3 = ?)";
				$param_array[] = $subsubcat['id'];
				$param_array[] = $subsubcat['id'];
				$param_array[] = $subsubcat['id'];
			} elseif (isset($_GET['subcat'])) {
				$whereString .= " AND (T1.our_sub1 = ? OR T1.our_sub2 = ? OR T1.our_sub3 = ?)";
				$param_array[] = $subcat['id'];
				$param_array[] = $subcat['id'];
				$param_array[] = $subcat['id'];
			}
		}
		
		$queryString = "SELECT T1.id, T1.title,T1.thumb_path,T1.price,T1.stars,T1.reviews,T1.source, T2.name, T2.logo 
                                FROM `product_details` T1 
                                LEFT JOIN `sources` T2 ON T2.id = T1.source 
                                WHERE ". $whereString . $adultString  ." AND T1.active = 1 AND T1.stock = 0
                                ORDER BY ". $order1 ." ". $order2 ." 
                                LIMIT ". $limit1 .",". $limit2 ."";
	
		
		// prepare statement
		$statement = $db->prepare($queryString);
		// execute & store
		$statement->execute($param_array);
		// bind results
		//$statement->bind_result($id, $title, $thumb, $USD, $stars, $reviews, $source, $source_name, $source_logo);
		// loop through & display the results
		while($productThumb = $statement->fetch(PDO::FETCH_ASSOC)) {
			$countT = 1;
			$productThumb['title'] = str_replace('"', '&quot;', $productThumb['title']);
			
			echo '
			<div class="fl popular-item">
				<div class="thumbimage">
					<a href="'. $baseURL .'sku/'. $productThumb['id'] .'-'. urlFriendly($productThumb['title']) .'/" title="'. $productThumb['title'] .'">
						<img src="'. $staticURL . $productThumb['thumb_path'] .'" alt="'. $productThumb['title'] .'" />
					</a>
				</div>
				<div class="thumbtitle"><a href="'. $baseURL .'sku/'. $productThumb['id'] .'-'. urlFriendly($productThumb['title']) .'/" title="'. $productThumb['title'] .'">'. $productThumb['title'] .'</a></div>
				<div class="specs">
					<div class="price">&#36;'. $productThumb['price']  .'</div>';
				
			echo '	
					<div class="stars"><img src="'. $baseURL .'img/stars/'. $productThumb['stars'] .'stars.png" alt="review stars" /> ('. $productThumb['reviews'] .')</div>
				<div class="'. $productThumb['logo'] .'"> </div><div class="source">Free Shipping*</div>
					<div style="clear:both;"></div>
				</div>
				<div style="clear:both;"></div>
			</div>';
			
		}
		
		if($countT == 0) {
			echo '<p id="noResult">No results.</p>';
		}
		
		echo '<div style="clear:both;"></div>';
		
			$statement = null;
                        unset($statement);
	}

	/*
	* Creates subcat thumbs
	*/
	function subCats() {
		global $baseURL, $subsubcat, $subcat, $cat, $db;
		
		$statement = $db->prepare("SELECT `id`,`name`,`ref`,`pic` FROM `subcats` WHERE `cat` = ? AND `catshow` = 1 AND `del` = 0 ORDER BY `order` ASC");
		$statement->bind_param('i', $cat['id']);
		$statement->execute();
		$statement->store_result();
		$statement->bind_result($id, $name, $ref, $pic);
		
		while($statement->fetch()) {
			
			echo '
			<div class="thumb">
				<div class="thumbtitleCat"><h2><a href="'. $baseURL .'2/'. $id .'/'. $ref .'/">'. $name .'</a></h2></div>
				<div class="thumbimage">
					<a href="'. $baseURL .'2/'. $id .'/'. $ref .'/">
						<img src="'. $baseURL .'img/subcats/'. $pic .'" width="190" height="143" alt="'. $name .'"/>
					</a>
				</div>
			</div>';
			
		}
		echo '<div style="clear:both;"></div>';
		
		$statement->free_result();
	}

	
	
	/* 
	* Get the maximum price
	*/
	function maxPrice($sql_string,$types_string,$keywords_array) {
		global $subsubcat, $subcat, $cat, $db;
		
		$param_types = '';
		$param_array = Array();
		
		$whereString = "T1.our_cat1 = '10000000000'";
		if ($sql_string == '' OR !isset($sql_string)) {
			if (isset($_GET['subsubcat'])) {
				$whereString = "(T1.our_subsub1 = ? OR T1.our_subsub2 = ? OR T1.our_subsub3 = ?)";
				$param_types .= 'iii';
				$param_array[] = $subsubcat['id'];
				$param_array[] = $subsubcat['id'];
				$param_array[] = $subsubcat['id'];
			} elseif (isset($_GET['subcat'])) {
				$whereString = "(T1.our_sub1 = ? OR T1.our_sub2 = ? OR T1.our_sub3 = ?)";
				$param_types .= 'iii';
				$param_array[] = $subcat['id'];
				$param_array[] = $subcat['id'];
				$param_array[] = $subcat['id'];
			} elseif (isset($_GET['cat'])) {
				$whereString = "(T1.our_cat1 = ? OR T1.our_cat2 = ? OR T1.our_cat3 = ?)";
				$param_types .= 'iii';
				$param_array[] = $cat['id'];
				$param_array[] = $cat['id'];
				$param_array[] = $cat['id'];
			}
		} else {
			$whereString = $sql_string;
			$param_types .= $types_string;
			$param_array = $keywords_array;
			if (isset($_GET['cat']) && $_GET['cat'] != 0) {
				$param_types .= 'iii';
				$param_array[] = $_GET['cat'];
				$param_array[] = $_GET['cat'];
				$param_array[] = $_GET['cat'];
				$whereString .= " AND (T1.our_cat1 = ? OR T1.our_cat2 = ? OR T1.our_cat3 = ?)";
			}
		}
		
		array_unshift($param_array, $param_types);
		
		// prepare statement
		$statement = $db->prepare("SELECT MAX(T1.price) As `priceMax` FROM `product_details` T1 WHERE ". $whereString ."");
		// dynamically bind params
		//call_user_func_array(array($statement,'bind_param'),$param_array);
		if ((count($param_array) - 1) === 1) {
			$statement->bind_param($param_array[0], $param_array[1]);
		} else if ((count($param_array) - 1) === 2) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2]);
		} else if ((count($param_array) - 1) === 3) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3]);
		} else if ((count($param_array) - 1) === 4) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4]);
		} else if ((count($param_array) - 1) === 5) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5]);
		} else if ((count($param_array) - 1) === 6) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6]);
		} else if ((count($param_array) - 1) === 7) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7]);
		} else if ((count($param_array) - 1) === 8) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8]);
		} else if ((count($param_array) - 1) === 9) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8],  $param_array[9]);
		} else if ((count($param_array) - 1) === 10) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8],  $param_array[9],  $param_array[10]);
		} else if ((count($param_array) - 1) === 11) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8],  $param_array[9],  $param_array[10], $param_array[11]);
		} else if ((count($param_array) - 1) === 12) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8],  $param_array[9],  $param_array[10], $param_array[11], $param_array[12]);
		} else if ((count($param_array) - 1) === 13) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8],  $param_array[9],  $param_array[10], $param_array[11], $param_array[12], $param_array[13]);
		} else if ((count($param_array) - 1) === 14) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8],  $param_array[9],  $param_array[10], $param_array[11], $param_array[12], $param_array[13], $param_array[14]);
		} else if ((count($param_array) - 1) === 15) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8],  $param_array[9],  $param_array[10], $param_array[11], $param_array[12], $param_array[13], $param_array[14], $param_array[15]);
		} else if ((count($param_array) - 1) === 16) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8],  $param_array[9],  $param_array[10], $param_array[11], $param_array[12], $param_array[13], $param_array[14], $param_array[15], $param_array[16]);
		} else if ((count($param_array) - 1) === 17) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8],  $param_array[9],  $param_array[10], $param_array[11], $param_array[12], $param_array[13], $param_array[14], $param_array[15], $param_array[16], $param_array[17]);
		} else if ((count($param_array) - 1) === 18) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8],  $param_array[9],  $param_array[10], $param_array[11], $param_array[12], $param_array[13], $param_array[14], $param_array[15], $param_array[16], $param_array[17], $param_array[18]);
		} else if ((count($param_array) - 1) === 19) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8],  $param_array[9],  $param_array[10], $param_array[11], $param_array[12], $param_array[13], $param_array[14], $param_array[15], $param_array[16], $param_array[17], $param_array[18], $param_array[19]);
		} else if ((count($param_array) - 1) === 20) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8],  $param_array[9],  $param_array[10], $param_array[11], $param_array[12], $param_array[13], $param_array[14], $param_array[15], $param_array[16], $param_array[17], $param_array[18], $param_array[19], $param_array[20]);
		} else if ((count($param_array) - 1) === 21) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8],  $param_array[9],  $param_array[10], $param_array[11], $param_array[12], $param_array[13], $param_array[14], $param_array[15], $param_array[16], $param_array[17], $param_array[18], $param_array[19], $param_array[20], $param_array[21]);
		} else if ((count($param_array) - 1) === 22) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8],  $param_array[9],  $param_array[10], $param_array[11], $param_array[12], $param_array[13], $param_array[14], $param_array[15], $param_array[16], $param_array[17], $param_array[18], $param_array[19], $param_array[20], $param_array[21], $param_array[22]);
		} else if ((count($param_array) - 1) === 23) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8],  $param_array[9],  $param_array[10], $param_array[11], $param_array[12], $param_array[13], $param_array[14], $param_array[15], $param_array[16], $param_array[17], $param_array[18], $param_array[19], $param_array[20], $param_array[21], $param_array[22], $param_array[23]);
		} else if ((count($param_array) - 1) === 24) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8],  $param_array[9],  $param_array[10], $param_array[11], $param_array[12], $param_array[13], $param_array[14], $param_array[15], $param_array[16], $param_array[17], $param_array[18], $param_array[19], $param_array[20], $param_array[21], $param_array[22], $param_array[23], $param_array[24]);
		} else if ((count($param_array) - 1) === 25) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8],  $param_array[9],  $param_array[10], $param_array[11], $param_array[12], $param_array[13], $param_array[14], $param_array[15], $param_array[16], $param_array[17], $param_array[18], $param_array[19], $param_array[20], $param_array[21], $param_array[22], $param_array[23], $param_array[24], $param_array[25]);
		} else if ((count($param_array) - 1) === 26) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8],  $param_array[9],  $param_array[10], $param_array[11], $param_array[12], $param_array[13], $param_array[14], $param_array[15], $param_array[16], $param_array[17], $param_array[18], $param_array[19], $param_array[20], $param_array[21], $param_array[22], $param_array[23], $param_array[24], $param_array[25], $param_array[26]);
		} else if ((count($param_array) - 1) === 27) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8],  $param_array[9],  $param_array[10], $param_array[11], $param_array[12], $param_array[13], $param_array[14], $param_array[15], $param_array[16], $param_array[17], $param_array[18], $param_array[19], $param_array[20], $param_array[21], $param_array[22], $param_array[23], $param_array[24], $param_array[25], $param_array[26], $param_array[27]);
		} else if ((count($param_array) - 1) === 28) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8],  $param_array[9],  $param_array[10], $param_array[11], $param_array[12], $param_array[13], $param_array[14], $param_array[15], $param_array[16], $param_array[17], $param_array[18], $param_array[19], $param_array[20], $param_array[21], $param_array[22], $param_array[23], $param_array[24], $param_array[25], $param_array[26], $param_array[27], $param_array[28]);
		} else if ((count($param_array) - 1) === 29) {
			$statement->bind_param($param_array[0], $param_array[1], $param_array[2], $param_array[3], $param_array[4], $param_array[5], $param_array[6], $param_array[7], $param_array[8],  $param_array[9],  $param_array[10], $param_array[11], $param_array[12], $param_array[13], $param_array[14], $param_array[15], $param_array[16], $param_array[17], $param_array[18], $param_array[19], $param_array[20], $param_array[21], $param_array[22], $param_array[23], $param_array[24], $param_array[25], $param_array[26], $param_array[27], $param_array[28], $param_array[29]);
		}
		
		// execute & store
		$statement->execute();
		$statement->store_result();
		$statement->bind_result($priceMax);
		$statement->fetch();
		// free the result to clear memory
		$statement->free_result();
		$statement->close();
		
		return $priceMax;
	}
}

        /* 
	* Get Titel for specific page
         * DEPRECEATED 12-1-18 -> We gebruiken categoryinfo.class.php
	*/
	function titel($page_name,$page_ref,$cat,$subcat,$subsubcat) {
		global $db;
		
		if ($page_ref == 'home' || $page_ref == 'about' || $page_ref == 'contact' || $page_ref == 'disclaimer' || $page_ref == 'faq' || $page_ref == 'search') {
		echo 'Compare Imports - Compare the hottest products and gadgets from China wholesale webshops';
		}
		
		 elseif ($page_ref == 'coupons') {
		echo 'Compare Imports - The best coupons for China wholesale webshops';
		}
		
		elseif ($page_ref == 'shop-reviews') {
		echo 'Compare Imports - Reviews for China wholesale webshops on price, shipping, service and return policy';
		}
		
		elseif ($page_ref != 'c' && $page_ref != 'sc' && $page_ref != 'sku') {
			echo 'Compare Imports - '. $page_name .'';
		}
		
		elseif (isset($subsubcat)) {
			echo 'Compare Imports - '. $subcat['name'] .' - '. $subsubcat['name'] .'';
		}
		
		elseif (isset($subcat)) {
			echo 'Compare Imports - '. $cat['name'] .' - '. $subcat['name'] .'';
		}
		
		elseif (isset($cat)) {
			echo 'Compare Imports - '. $cat['name'] .'';
		}
		
		elseif (isset($_GET['pid']) && ($page_ref == 'sku')) {
		$product = $_GET['pid'];
		
		// IK WIL EIGENLIJK NIET OPNIEUW EEN SQL QUERY (IVM MET ZELFDE INFO AL EERDER IN ANDERE FUNCTIE OPGEHAALD)
		
		// prepare statement
		$statement = $db->prepare("SELECT `title` FROM `product_details` WHERE `active` = 1 AND `id` = ?");
		$statement->bind_param('i', $product);
		// execute & store
		$statement->execute();
		$statement->store_result();
		$statement->bind_result($title);
		$statement->fetch();
		
		echo 'Compare Imports - '. $title;
		
		// free the result to clear memory
		$statement->free_result();
		$statement->close();

		}	
	}
	
			/* 
	* Get H1 for specific category page
	*/
	function categoryH1($subcat,$subsubcat,$cat) {
				
		if (isset($subsubcat)) {
			echo ''. $subcat['name'] .' - '. $subsubcat['name'] .'';
		}
		
		elseif (isset($subcat)) {
			echo ''. $subcat['name'] .'';
		}
		
		elseif (isset($cat)) {
			echo ''. $cat['name'] .'';
		}
	}
        
        
        	/* 
	* Get the description for a specific SUB-category
	*/
	
	function get_subcat_descr($subcat) {	
		global $db;
			
						// prepare statement
						$statement = $db->prepare("SELECT `description` FROM `subcats` WHERE `del` = 0 AND `id` = ?");
						$statement->bind_param('i', $subcat);
						// execute & store
						$statement->execute();
						$statement->store_result();
						$statement->bind_result($description);
						$statement->fetch();
                                                
                                                $description = htmlspecialchars($description, ENT_QUOTES);
                                                echo 'Take a look at cheap '. $description .' from wholesale China with free shipping';
                                                                                             
						// free the result to clear memory
						$statement->free_result();
						$statement->close();

	}
        
  
	
	/* 
	* Get the description for a specific sub-sub-category
	*/
	
	function get_subsubcat_descr($subsubcat) {	
		global $db;
			
						// prepare statement
						$statement = $db->prepare("SELECT `description` FROM `subsubcats` WHERE `del` = 0 AND `id` = ?");
						$statement->bind_param('i', $subsubcat);
						// execute & store
						$statement->execute();
						$statement->store_result();
						$statement->bind_result($description);
						$statement->fetch();
						
                                                $description = htmlspecialchars($description, ENT_QUOTES);
                                                echo 'Buy and compare cheap '. $description .' from wholesale China webshops with free shipping';
                                                
                                                // free the result to clear memory
						$statement->free_result();
						$statement->close();
									
	}
	
	
		/* 
	* Get the description for all other file/dbpages
	*/
	
	function get_page_dscrp($page_ref) {	
		global $db;
		
		
						// prepare statement
						$statement = $db->prepare("SELECT `description` FROM `CMS_pages` WHERE `active` = 1 AND `ref` = ?");
						$statement->bind_param('s', $page_ref);
						// execute & store
						$statement->execute();
						$statement->store_result();
						$statement->bind_result($description);
						$statement->fetch();
						
						// free the result to clear memory
						$statement->free_result();
						$statement->close();
						
						return $description;
		
	/*	
		if ($page_ref == 'home' || $page_ref == 'about' || $page_ref == 'contact' || $page_ref == 'disclaimer' || $page_ref == 'faq' || $page_ref == 'search') {
		echo 'Compare Imports - Compare the hottest products and gadgets from China';
		}
		
	    elseif ($page_ref == 'coupons') {
		echo 'Compare Imports - Find coupons for China wholesale webshops';
		}
		
		elseif ($page_ref == 'shop-reviews') {
		echo 'Compare Imports - Reviews for China wholesale webshops on pricing, shipping, service and return policy';
		}
		
		else {
		echo 'Buy and compare cheap gadgets and products with free shipping from wholesale China';
		} */
	}
	
	
	
		/* 
	* Get the description for a specific product/SKU
	*/
	
		function get_product_descr() {	
		global $db;
		
		$product = $_GET['pid'];
		
		// prepare statement
		$statement = $db->prepare("SELECT `title`, `price` FROM `product_details` WHERE `active` = 1 AND `id` = ?");
		$statement->bind_param('i', $product);
		// execute & store
		$statement->execute();
		$statement->store_result();
		$statement->bind_result($title, $USD);
		$statement->fetch();
		
		$title = htmlspecialchars($title, ENT_QUOTES);
		echo 'For just $'. $USD .', buy '. $title .' from China wholesale webshop'. $source . 'with free shipping';
		
		// free the result to clear memory
		$statement->free_result();
		$statement->close();
		}
	
	       
                
	
          
                
                
        /* 
	* Set the RightSideWrapper for a dbpage
	*/
	
function set_rightsidewrapper() {
		global $baseURL, $db;
		
		echo '<div id="rightSideWrapper">
					<div id="bbArchive">
					<h2>Archive</h2>';
				
		
		// prepare statement
		$statement = $db->prepare("SELECT `name`, `ref`, `title`, `publishdate` FROM `CMS_pages` WHERE `active` = 1 AND `ref` LIKE '%bb-tablets%' ORDER BY `name` ASC");
		// execute & store
		$statement->execute();
		$statement->store_result();
		$statement->bind_result($name, $ref, $title, $publishdate);
		
		echo '<h3>Tablets</h3>';	
		
		while ($statement->fetch()) {	
		echo '<div class="archiveItems">'. $title .'<li><a href="'. $baseURL . $ref .'/">'. $publishdate .'</a></li></div><br />';
		}
		// free the result to clear memory
		$statement->free_result();
		$statement->close();
		
				// prepare statement
				$statement = $db->prepare("SELECT `name`, `ref`, `title`, `publishdate` FROM `CMS_pages` WHERE `active` = 1 AND `ref` LIKE '%bb-phones%' ORDER BY `name` ASC");
				// execute & store
				$statement->execute();
				$statement->store_result();
				$statement->bind_result($name, $ref, $title, $publishdate);
				
				echo '<h3>Phones</h3>';	
				
				while ($statement->fetch()) {	
				echo '<div class="archiveItems">'. $title .'<li><a href="'. $baseURL . $ref .'/">'. $publishdate .'</a></li></div><br />';
				}
				// free the result to clear memory
				$statement->free_result();
				$statement->close();
		
						// prepare statement
						$statement = $db->prepare("SELECT `name`, `ref`, `title`, `publishdate` FROM `CMS_pages` WHERE `active` = 1 AND `ref` LIKE '%bb-mediaplayers%' ORDER BY `name` ASC");
						// execute & store
						$statement->execute();
						$statement->store_result();
						$statement->bind_result($name, $ref, $title, $publishdate);
						
						echo '<h3>Mediaplayers</h3>';	
						
						while ($statement->fetch()) {	
						echo '<div class="archiveItems">'. $title .'<li><a href="'. $baseURL . $ref .'/">'. $publishdate .'</a></li></div><br />';
						}
						// free the result to clear memory
						$statement->free_result();
						$statement->close();
	
		
		echo '</div></div>';
}

/* Set the blogpreview (leftside wrapper) */

function blogPreview() {
global $baseURL, $dbwp;


// connect to the database and get information
	$statement = $dbwp->prepare("SELECT `post_title`, `post_name`, `post_excerpt` FROM `wp_posts` WHERE `post_status` = 'publish' AND `post_type` = 'post' ORDER BY  `post_date` DESC 
	LIMIT 0 , 1");	// prepare statement
	$statement->execute();																											// execute query
	$statement->fetch(PDO::FETCH_ASSOC);	// fetch the results

		
	$blog['post_excerpt'] = substr($blog['post_excerpt'], 0, 150);

		echo 	'<div class="leftHeader">Newest Blog Post</div>
				<div id="LeftSideBlogPreview">
				<h2>'. $blog['post_title'] .' </h2><p>'. $blog['post_excerpt'] .'<br /><a href="'. $baseURL .'blog/'. $blog['post_name'] .'/" title="Go To Blog Post">(Read More..)</a> </p>
				</div>';
	
	$statement = null;	
	unset($statement);	
	
				
}	

?>
<?php


namespace CI;

class Navigation
{       
    /*  Used for the search category menu
    *   Pulls all main categories from db and creates a option list
    *  Used in: index.php, and blog pages.         
    */ 
        
    function get_cats($db) {
        $query =    'SELECT `id`, `name`
                    FROM `categories`
                    WHERE `topbar` = 1 AND `del` = 0
                    ORDER BY `order` ASC';

        $stmt = $db->query($query);
        
        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                echo '<option value="'. $row['id'] .'">'. $row['name'] .'</option>';
        }
    }

   	
    /*  This is the (tiny) main category navbar, which gets  the categories from the db asnd write html.
     *  The rest of the menu will load from the JSnavbar.html when people hover the #nav-wrapper. 
     *  In this manner we have far less link on the main webpages.
     *  Used in: On every page except c, sc and sku        
     */ 
        
    function get_TinycatNavbar($db) {
    global $baseURL; 
    
        $joined_query = 'SELECT id AS `cid`, name AS `cname`, ref AS `cref`
                        FROM `categories`
                        WHERE `topbar` = 1 AND `del` = 0
                        ORDER BY `order` ASC';
                    
        $stmt = $db->query($joined_query);

        
                // open menu
        while($nav = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $nav['cname'] = htmlentities($nav['cname'], ENT_QUOTES);
                    
            // normal first categories directly shown
            echo '<li class="navBarTop-MainLi" id="'. $nav['cref'] .'-icon"><a title="'. $nav['cname'] .'" href="'. $baseURL . $nav['cref'] .'-1-'. $nav['cid'] .'/">'. $nav['cname'] .' </a></li>';
        }	
    }
        
        
    function get_MobileNavbar($db) {
        global $baseURL; 
        
        /* Get the topbar Menu Items First */
        $joined_query = 'SELECT name, ref
                        FROM `CMS_pages`
                        WHERE `topbar` = 1 AND `active` = 1
                        ORDER BY `toporder` ASC';
                        
        $stmt = $db->query($joined_query);
        
        // open menu
        while($topbar = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $topbar['name'] = htmlentities($topbar['name'], ENT_QUOTES);
                    
            // normal first categories directly shown
            echo '<button type="button" class="mobileNav-MainLi list-group-item"><a title="'. $topbar['name'] .'" href="'. $baseURL . $topbar['ref'] .'/">'. $topbar['name'] .' </a></button>';
        }	
        
        
        $joined_query = 'SELECT id AS `cid`, name AS `cname`, ref AS `cref`
                        FROM `categories`
                        WHERE `topbar` = 1 AND `del` = 0
                        ORDER BY `order` ASC';
                    
        $stmt = $db->query($joined_query);

        
        // open menu
        while($nav = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $nav['cname'] = htmlentities($nav['cname'], ENT_QUOTES);
                    
            // normal first categories directly shown
            echo '<button type="button" class="mobileNav-MainLi list-group-item" id="'. $nav['cref'] .'-icon"><a title="'. $nav['cname'] .'" href="'. $baseURL . $nav['cref'] .'-1-'. $nav['cid'] .'/">'. $nav['cname'] .' </a></button>';
        }	
    }
        
    /*  This is the main category navbar, which gets all the categories, sub categories and subsubcategories from the db.
     *  Together with that, it immediatly builds up HTML structure to use on any page.
     *  Used in: index.php, and blog pages.     
     
        DEPRECEATED SEE get_HTMLnavigation() BELOW!
     */ 
    function get_catNavbar($db) {
		global $baseURL; 
		
		$joined_query = 'SELECT cat.id AS `cid`, cat.name AS `cname`, cat.ref AS `cref`, 
						s.id AS `sid`, s.ref AS `sref`, s.name AS `sname`, s.column AS `scolumn`,
						ss.id AS `ssid`, ss.ref AS `ssref`, ss.name AS `ssname`, ss.topbar AS `sstop`
						FROM `subsubcats` ss LEFT JOIN `subcats` s ON s.id = ss.subcat LEFT JOIN `categories` cat ON cat.id = s.cat
						WHERE ss.del = 0 AND s.topbar = 1 AND s.del = 0 AND cat.topbar = 1 AND cat.del = 0
						ORDER BY cat.order ASC, cat.id ASC, s.column ASC, s.order ASC, s.id ASC, ss.order ASC';
		$stmt = $db->query($joined_query);
		$cat_old = -1;
		$sub_old = -1;
		$column = -1;
		$count = 1;
		$count2 = 1;
		$count3 = 1;
		
                // open menu
		
		while($nav = $stmt->fetch(\PDO::FETCH_ASSOC)) {
			// check if we need to close a sub
			if ($sub_old != $nav['sid'] && $count2 > 1) {
				echo '</ul></li>';
			}
			
			// check if we need to close a column
			if (($column != $nav['scolumn'] || $cat_old != $nav['cid']) && $count3 > 1) {
				echo '</ul></li>';
			}
			
			// check if we need to close a cat
			if ($cat_old != $nav['cid'] && $count > 1) {
				echo '</ul></li>';
			}

			
			
			// check if we need to open a new cat
			if ($cat_old != $nav['cid']) {
				$nav['cname'] = htmlentities($nav['cname'], ENT_QUOTES);
					
                                        // normal first categories directly shown
					echo '<li class="navBarTop-MainLi" id="'. $nav['cref'] .'-icon"><a href="'. $baseURL .'1/'. $nav['cid'] .'/'. $nav['cref'] .'/">'. $nav['cname'] .' </a>';
					echo '<ul class="navBarTop-columnarGeneral navBarTop-columnar'. $count .'">';

				
                $count += 1;
			}
			
			// check if we need to open a new column
			if ($column != $nav['scolumn'] || $cat_old != $nav['cid']) {
				if(!isset($nav['scolumn']) || $nav['scolumn'] == '' || !in_array($nav['scolumn'], Array(1,2,3))) {
					$nav['scolumn'] = 1;
				}
			
				if ($nav['scolumn'] == 1) {
					echo '<li class="leftColumnNavTop"><ul>';
				} elseif ($nav['scolumn'] == 2) {
					echo '<li class="centerColumnNavTop"><ul>';
				} elseif ($nav['scolumn'] == 3) {
					echo '<li class="rightColumnNavTop"><ul>';
				}
				
				$column = $nav['scolumn'];
				$cat_old = $nav['cid'];
				
				$count3 += 1;
			}
			
			// check if we need to open a new sub
			if ($sub_old != $nav['sid']) {
				$nav['sname'] = htmlentities($nav['sname'], ENT_QUOTES);
				echo '<li><a href="'. $baseURL .'p/'. $nav['sid'] .'/'. $nav['sref'] .'/">'. $nav['sname'] .' </a>';
				echo '<ul>';
				
				$sub_old = $nav['sid'];
				
				$count2 += 1;
			}
			
			// create the subsub link if we need to show it (topbar = 1)
			if ($nav['sstop'] == 1) {
				$nav['ssname'] = htmlentities($nav['ssname'], ENT_QUOTES);
				echo '<li><a href="'. $baseURL .'ps/'. $nav['ssid'] .'/'. $nav['ssref'] .'/">'. $nav['ssname'] .' </a></li>';
			}
		}
		
		// close the last sub, column, cat & other-main
		echo '</ul></li>';
		echo '</ul></li>';
		echo '</ul></li>';
		echo '</ul></li>';
		
		// close the menu
	}
    
    /* New version from above, We create the navbar on site and just call the plain HTML
    */
    function get_HTMLnavigation () {
        include("includes/html/JSnavbar.html");
    }
    
    
    
	
     /*  
     *    Because we want smaller navbars on pages like: c, sc and sku we use these functions
     *  Used in index.php - conditional use of these functions     
     */ 
        
    function get_subsubNav($db, $subcat_info, $mode, $cat_info = null, $subsubcat_info = null ) {
        global $baseURL;
        
            if ($mode == 'sku') {
                if (isset($subcat_info['our_sub1']) && $subcat_info['our_sub1'] !== '0') {
                     $subcat['id'] = $subcat_info['our_sub1'];
                     $subcat['name'] = $subcat_info['sub1'];
                     $subcat['cat_ref'] = $subcat_info['cat1_ref'];
                }
                
                elseif (isset($subcat_info['our_sub2']) && $subcat_info['our_sub2'] !== '0') {
                     $subcat['id'] = $subcat_info['our_sub2'];
                     $subcat['name'] = $subcat_info['sub2'];
                     $subcat['cat_ref'] = $subcat_info['cat2_ref'];
                }
                         
                elseif (isset($subcat_info['our_sub3'])) {
                     $subcat['id'] = $subcat_info['our_sub3'];
                     $subcat['name'] = $subcat_info['sub3'];
                     $subcat['cat_ref'] = $subcat_info['cat3_ref'];
                }      
            }
            
            if ($mode == 'sc') {
                $subcat['id'] =  $subcat_info['id'];
                $subcat['name'] =  $subcat_info['name'];
                $subcat['cat_ref'] = $subcat_info['ref'];   
                $cat['ref'] =  $cat_info['ref'];                
            }
            
            
            /* Use most detailed category name as H1
            Check if subsub exist otherwise just use the subcat category 
            */
            
            $category_H1 = isset($subsubcat_info['name']) ? $subcat['name'] .' <br />'. $subsubcat_info['name'] : $subcat['name'];
                
                
            
        
           
        $stmt = $db->prepare("SELECT `id`,`name`,`ref` 
                            FROM `subsubcats` 
                            WHERE `subcat` = :id AND `del` = 0 
                            ORDER BY `order`");
                            
        $stmt->bindValue(':id', $subcat['id']);
        $stmt->execute();    
        
        if ($mode == 'sku') {
            echo '<div id="subcatnav-wrapper"><div id="navSubcatHead"><h2>'. $category_H1 .'</h2></div>';
        } 
        else {
           echo '<div id="subcatnav-wrapper"><div id="navSubcatHead"><h1>'. $category_H1 .'</h1></div>'; 
        }
        
        
        
        echo '<ul id="subsubnav-list">';

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $active_subsub = $subsubcat_info['id'] == $row['id'] ? 'active': '' ;  
            echo '<li><a title="'. $row['name'] .'" class="nav-link subsub '. $active_subsub .'" href="'. $baseURL . $cat['ref'] .'/'. $row['ref'] .'-3-'. $row['id'] .'/">'. $row['name'] .'</a></li>';
        }

        echo '</ul></div>';
    }
    
    
/* 
* Navigation Menu for the c.php pages 
*/    
    
function get_subNav($db, $cat) {
    global $baseURL;
    
    $stmt = $db->prepare("SELECT s.id AS `sid`, s.name AS `sname`, s.ref AS `sref`, ss.id AS `ssid`, ss.name AS `ssname`, ss.ref AS `ssref`
                         FROM `subcats` s 
                         RIGHT JOIN `subsubcats` ss 
                         ON s.id = ss.subcat
                         WHERE s.cat = ? AND s.del = 0 AND ss.del = 0
                         ORDER BY s.order");   
                         
    $stmt->execute(Array($cat['id']));
    
    $sub_old = -1;
    $subcount = 1;		

    $subsub_old = -1;
    $subsubcount = 1;
    
    echo '<div id="subcatnav-wrapper"><div id="navSubcatHead"><h1>'. $cat['name'] .'</h1></div>';
    echo '<ul id="nav-list">';

        while ($nav = $stmt->fetch(\PDO::FETCH_ASSOC)) {

            // check if we need to CLOSE a sub
            if ($sub_old != $nav['sid'] && $subcount > 1) {
                    echo '</ul></li>';
            }    

            // check if we need to CLOSE a subsub
            if ($subsub_old != $nav['ssid'] && $subsubcount > 1) {
                    echo '</li>';
            }

          // check if we need to OPEN a sub    
          if ($sub_old != $nav['sid']) {
              echo '<li><a title="'. $nav['sname'] .'" class="nav-link sub" href="'. $baseURL . $cat['ref'] .'/'. $nav['sref'] .'-2-'. $nav['sid'] .'/">'. $nav['sname'] .'</a>';
              echo '<ul class="subnav-list">';

            $sub_old = $nav['sid'];
            $subcount += 1;
            }

            // check if we need to OPEN a subsub
            if ($subsub_old != $nav['ssid']) {
            echo '<li><a title="'. $nav['ssname'] .'" class="nav-link subsub" href="'. $baseURL . $cat['ref'] .'/'. $nav['ssref'] .'-3-'. $nav['ssid'] .'/">'. $nav['ssname'] .'</a>';

            $subsub_old = $nav['ssid'];
            $subsubcount += 1;
            }
        }        
        
    echo '</ul></div>';    
}
        
	
     /*  
     *    Outputs the Topbar with ao (Bestbuy, Daily Deals, China Online Reviews)     
     */ 
    function get_topNavbar($db, $deviceType = null) {  
            global $baseURL;
	
        $limit_query = ($deviceType == 'phone' ?  'LIMIT 2' : ' ');
            
        
		echo '<ul class="nav navbar-nav">';
        
		$statement = $db->prepare("SELECT `ref`,`name`,`topbarsub`,`toporder` 
                                    FROM `CMS_pages` 
                                    WHERE `active` = 1 AND `topbar` = 1 
                                    ORDER BY `toporder` ASC ". $limit_query ."");
                                    
		$statement->execute();																										
					
			while ($topbar = $statement->fetch(\PDO::FETCH_ASSOC)) {
                    
                    if ($topbar['topbarsub'] == 1) {
                    
                        // $button = ($deviceType !== 'computer' ? 'type="button" data-toggle="dropdown"' : $button = '');
                      
                        echo '<li class="dropdown">
                                <a href="'. $baseURL . $topbar['ref'] .'/" class="dropdown-toggle" type="button" data-toggle="dropdown" aria-hashpopup="true" aria-expanded="false">'. $topbar['name'] .' <span class="caret"></span></a>';
                                
                    } else {
                        echo '<li><a href="'. $baseURL . $topbar['ref'] .'/">'. $topbar['name'] .'</a>';  
                    }
                    
                                   
                    
                    // Check if the main item got subpages 
					if ($topbar['topbarsub'] == 1) {	
                           
                        echo '<ul class="dropdown-menu">';
 

                        # 1 We have a submenu for China Shop Reviews  
                        if ($topbar['ref'] == 'china-online-shops') {
                            

                            
                                $statement2 = $db->prepare("SELECT reviews.ref, sources.name 
                                                            FROM `shop_reviews` AS reviews
                                                            INNER JOIN `sources` AS sources
                                                            ON reviews.source = sources.id
                                                            WHERE reviews.review = 1
                                                            ORDER BY position
                                                            LIMIT 0, 6
                                                            ");
                                
                                $statement2->execute();	
                                
                                while ($shopinfo = $statement2->fetch(\PDO::FETCH_ASSOC)) {
                                    echo '<li>
                                            <a href="'. $baseURL .'china-online-shops/'. $shopinfo['ref'] .'-review/" title="Read the '. $shopinfo['name'] .' Review">
                                                <label id="'. $shopinfo['ref'] .'_favicon" class="shop-label" title="'. $shopinfo['name'] .'"><span>'. $shopinfo['name'] .' Review</span></label>
                                            </a>
                                        </li>';
                
                                }
                                unset($statement2);
                                
                                echo    '<li role="separator" class="divider"></li>
                                        <li>
                                            <a class="text-center" href="'. $baseURL .'china-online-shops/" title="All Chinese Shops">All China Shops <span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span></a>
                                        </li>';
                                
                                
                        } 
                        
                        if ($topbar['ref'] == 'china-brands') {
                            

                            
                                $statement2 = $db->prepare("SELECT ref, brand 
                                                            FROM `brand_pages`
                                                            ORDER BY position
                                                            LIMIT 0, 6
                                                            ");
                                
                                $statement2->execute();	
                                while ($brandinfo = $statement2->fetch(\PDO::FETCH_ASSOC)) {
                                    echo '<li>
                                        <a href="'. $baseURL . $topbar['ref'] .'/'. $brandinfo['ref'] .'/" title="View all '. $brandinfo['brand'] .' Products">
                                        '. $brandinfo['brand'] .' Products</a>
                                    </li>';
                
                                }
                                unset($statement2);
                                echo    '<li role="separator" class="divider"></li>
                                        <li>
                                            <a class="text-center" href="'. $baseURL . $topbar['ref'] .'/" title="'. $topbar['name']  .'">All '. $topbar['name'] .' <span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span></a>
                                        </li>';
                                
                                
                        }
					
                        echo '</ul>';
                    
                    
                    
                    
                    
                    }
			echo '</li>';		
                    
                         

			}			
		echo '</ul>';		
		unset($statement);
        
        }
        
        
     /*  Main function to create the interactive breadcrumb wrapper (to indicate where people are on the site.
     *   Used in index.php   
     */ 
	function get_breadcrumb($categoryinfo, $pageinfo, $sku, $shopreview, $page007 = null) {
		global $baseURL;
		
                if ($pageinfo->page['ref'] == 'sc' || $pageinfo->page['ref'] == 'c') {
                           
                        if (isset($categoryinfo->cat))          {
                            echo '
                            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                            <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $categoryinfo->cat['name'] .'"  href="'. $baseURL . $categoryinfo->cat['ref'] .'-1-'. $categoryinfo->cat['id'] .'/"><span itemprop="name">'. $categoryinfo->cat['name'] .'</span></a>
                            <meta itemprop="position" content="1" />
                            </li>';
                            }	
                            
                        if (isset($categoryinfo->subcat))       {
                            echo '
                            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                            <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $categoryinfo->subcat['name'] .'"  href="'. $baseURL . $categoryinfo->cat['ref'] .'/'. $categoryinfo->subcat['ref'] .'-2-'. $categoryinfo->subcat['id'] .'/"><span itemprop="name">'. $categoryinfo->subcat['name'] .'</span></a>
                            <meta itemprop="position" content="2" />
                            </li>';
                            }
                            
                        if (isset($categoryinfo->subsubcat))    {
                            echo '
                            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                            <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $categoryinfo->subsubcat['name'] .'"  href="'. $baseURL . $categoryinfo->cat['ref'] .'/'. $categoryinfo->subsubcat['ref'] .'-3-'. $categoryinfo->subsubcat['id'] .'/"><span itemprop="name">'. $categoryinfo->subsubcat['name'] .'</span></a>
                            <meta itemprop="position" content="3" />
                            </li>';
                            }    
                }
                
                elseif($pageinfo->page['ref'] == 'sku') {
                    
                        if (isset($sku->productinfo['cat1'])) {

                                echo '
                                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $sku->productinfo['cat1'] .'" href="'. $baseURL . $sku->productinfo['cat1_ref'] .'-1-'. $sku->productinfo['our_cat1'] .'/"><span itemprop="name">'. $sku->productinfo['cat1'] .'</span></a>
                                <meta itemprop="position" content="1" />
                                </li>';
                                
                                echo '
                                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $sku->productinfo['sub1'] .'" href="'. $baseURL . $sku->productinfo['cat1_ref'] .'/'. $sku->productinfo['sub1_ref'] .'-2-'. $sku->productinfo['our_sub1'] .'/"><span itemprop="name">'. $sku->productinfo['sub1'] .'</span></a>
                                <meta itemprop="position" content="2" />
                                </li>';      
                                
                                echo '
                                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $sku->productinfo['subsub1'] .'" href="'. $baseURL . $sku->productinfo['cat1_ref'] .'/'. $sku->productinfo['subsub1_ref'] .'-3-'. $sku->productinfo['our_subsub1'] .'/"><span itemprop="name">'. $sku->productinfo['subsub1'] .'</span></a>
                                <meta itemprop="position" content="3" />
                                </li>'; 
                                
                                echo '
                                <li>
                                <a><span>This Product</span></a>
                                </li>'; 
                        }

                        elseif (isset($sku->productinfo['cat2'])) {
                                
                                
                                echo '
                                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $sku->productinfo['cat2'] .'" href="'. $baseURL . $sku->productinfo['cat2_ref'] .'-1-'. $sku->productinfo['our_cat2'] .'/"><span itemprop="name">'. $sku->productinfo['cat2'] .'</span></a>
                                <meta itemprop="position" content="1" />
                                </li>';
                                
                                echo '
                                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $sku->productinfo['sub2'] .'" href="'. $baseURL . $sku->productinfo['cat2_ref'] .'/'. $sku->productinfo['sub2_ref'] .'-2-'. $sku->productinfo['our_sub2'] .'/"><span itemprop="name">'. $sku->productinfo['sub2'] .'</span></a>
                                <meta itemprop="position" content="2" />
                                </li>';      
                                
                                echo '
                                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $sku->productinfo['subsub2'] .'" href="'. $baseURL . $sku->productinfo['cat2_ref'] .'/'. $sku->productinfo['subsub2_ref'] .'-3-'. $sku->productinfo['our_subsub2'] .'/"><span itemprop="name">'. $sku->productinfo['subsub2'] .'</span></a>
                                <meta itemprop="position" content="3" />
                                </li>'; 

                                echo '
                                <li>
                                <a><span>This Product</span></a>
                                </li>'; 
                        }
                        
                       elseif (isset($sku->productinfo['cat3'])) {
                                
                                echo '
                                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $sku->productinfo['cat3'] .'" href="'. $baseURL . $sku->productinfo['cat3_ref'] .'-1-'. $sku->productinfo['our_cat3'] .'/"><span itemprop="name">'. $sku->productinfo['cat3'] .'</span></a>
                                <meta itemprop="position" content="1" />
                                </li>';
                                
                                echo '
                                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $sku->productinfo['sub3'] .'" href="'. $baseURL . $sku->productinfo['cat3_ref'] .'/'. $sku->productinfo['sub3_ref'] .'-2-'. $sku->productinfo['our_sub3'] .'/"><span itemprop="name">'. $sku->productinfo['sub3'] .'</span></a>
                                <meta itemprop="position" content="2" />
                                </li>';      
                                
                                echo '
                                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $sku->productinfo['subsub3'] .'" href="'. $baseURL . $sku->productinfo['cat3_ref'] .'/'. $sku->productinfo['subsub3_ref'] .'-3-'. $sku->productinfo['our_subsub3'] .'/"><span itemprop="name">'. $sku->productinfo['subsub3'] .'</span></a>
                                <meta itemprop="position" content="3" />
                                </li>'; 
                                
                                echo '
                                <li>
                                <a><span>This Product</span></a>
                                </li>'; 
                        }
                    
                } 
				
                elseif ($pageinfo->page['ref'] == 'bestbuy') {
                    echo '<a href="'. $baseURL . $pageinfo->page['ref'] .'/">'. $pageinfo->page['name'] .' Overview</a>';	
                }

                elseif ($pageinfo->page['ref'] == 'china-online-shops') {
                    echo '
                    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="'. $baseURL . $pageinfo->page['ref'] .'/" title="'. $pageinfo->page['name'] .'"><span itemprop="name">'. $pageinfo->page['name'] .'</span></a>
                    <meta itemprop="position" content="1" />
                    </li>';
                }
                
                elseif ($pageinfo->page['ref'] == 'webshop-review') {
                    echo '
                    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="'. $baseURL .'china-online-shops/"><span itemprop="name">'. $pageinfo->page['name'] .'</span></a>
                    <meta itemprop="position" content="1" />
                    </li>';
                    
                    echo '
                    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $shopreview->shopInfo['name'] .' Review" href="'. $baseURL .'china-online-shops/'. $shopreview->shopInfo['ref'] .'-review/"><span itemprop="name">'. $shopreview->shopInfo['name'] .' Review</span></a>
                    <meta itemprop="position" content="2" />
                    </li>'; 
                }
                
                elseif ($pageinfo->page['ref'] == 'china-brands') {
                    echo '
                    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="'. $baseURL . $pageinfo->page['ref'] .'/" title="'. $pageinfo->page['name'] .'"><span itemprop="name">'. $pageinfo->page['name'] .'</span></a>
                    <meta itemprop="position" content="1" />
                    </li>';
                    
                    if (isset($categoryinfo->brand['ref'])) {
                       echo '
                        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $categoryinfo->brand['brand'] .' Overview" href="'. $baseURL . $pageinfo->page['ref'] .'/'. $categoryinfo->brand['ref'] .'/"><span itemprop="name">'. $categoryinfo->brand['brand'] .'</span></a>
                        <meta itemprop="position" content="2" />
                        </li>';   
                    }       
                }
		

                
                elseif ($pageinfo->page['ref'] != 'sc' || $pageinfo->page['ref'] != 'c' || $pageinfo->page['ref'] != 'bestbuy'){
                    echo '
                    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="#" title="'. $pageinfo->page['name'] .'"><span itemprop="name">'. $pageinfo->page['name'] .'</span></a>
                    <meta itemprop="position" content="1" />
                    </li>';                    
                }
                
                /* 
                 * Add Extra BreadCrumb field with search term on searh results page
                 * 
                 */
                
                if ($pageinfo->page['ref'] == 'search' ) {
                    echo '<li><span <span style="font-weight:700;font-size: 1.3em;">"'. $page007->keywords .'"</span></li>';
                          
                }          
        }   
        
function get_mobile_breadcrumb($categoryinfo, $pageinfo, $sku, $shopreview) {
		global $baseURL;
        
                if ($pageinfo->page['ref'] == 'c') {
                    
                    
                }
		
                elseif ($pageinfo->page['ref'] == 'sc') {
                           
                        if (isset($categoryinfo->subcat)){
                            echo '
                            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                            <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $categoryinfo->cat['name'] .'"  href="'. $baseURL . $categoryinfo->cat['ref'] .'-1-'. $categoryinfo->cat['id'] .'/"><span itemprop="name">'. $categoryinfo->cat['name'] .'</span></a>
                            <meta itemprop="position" content="1" />
                            </li>';
                            }	                            
                            
                        if (isset($categoryinfo->subsubcat)){
                            echo '
                            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                            <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $categoryinfo->subcat['name'] .'"  href="'. $baseURL . $categoryinfo->cat['ref'] .'/'. $categoryinfo->subcat['ref'] .'-2-'. $categoryinfo->subcat['id'] .'/"><span itemprop="name">'. $categoryinfo->subcat['name'] .'</span></a>
                            <meta itemprop="position" content="3" />
                            </li>';
                            }    
                }
                
                elseif($pageinfo->page['ref'] == 'sku') {
                    
                        if (isset($sku->productinfo['cat1'])) {
                           
                                echo '
                                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $sku->productinfo['sub1'] .'" href="'. $baseURL . $sku->productinfo['cat1_ref'] .'/'. $sku->productinfo['sub1_ref'] .'-2-'. $sku->productinfo['our_sub1'] .'/"><span itemprop="name">'. $sku->productinfo['sub1'] .'</span></a>
                                <meta itemprop="position" content="2" />
                                </li>';      
                                
                                echo '
                                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $sku->productinfo['subsub1'] .'" href="'. $baseURL . $sku->productinfo['cat1_ref'] .'/'. $sku->productinfo['subsub1_ref'] .'-3-'. $sku->productinfo['our_subsub1'] .'/"><span itemprop="name">'. $sku->productinfo['subsub1'] .'</span></a>
                                <meta itemprop="position" content="3" />
                                </li>'; 
                        }

                        elseif (isset($sku->productinfo['cat2'])) {
                                
                                
                                echo '
                                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $sku->productinfo['sub2'] .'" href="'. $baseURL . $sku->productinfo['cat2_ref'] .'/'. $sku->productinfo['sub2_ref'] .'-2-'. $sku->productinfo['our_sub2'] .'/"><span itemprop="name">'. $sku->productinfo['sub2'] .'</span></a>
                                <meta itemprop="position" content="2" />
                                </li>';      
                                
                                echo '
                                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $sku->productinfo['subsub2'] .'" href="'. $baseURL . $sku->productinfo['cat2_ref'] .'/'. $sku->productinfo['subsub2_ref'] .'-3-'. $sku->productinfo['our_subsub2'] .'/"><span itemprop="name">'. $sku->productinfo['subsub2'] .'</span></a>
                                <meta itemprop="position" content="3" />
                                </li>'; 
                        }
                        
                       elseif (isset($sku->productinfo['cat3'])) {
                                
                                
                                echo '
                                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $sku->productinfo['sub3'] .'" href="'. $baseURL . $sku->productinfo['cat3_ref'] .'/'. $sku->productinfo['sub3_ref'] .'-2-'. $sku->productinfo['our_sub3'] .'/"><span itemprop="name">'. $sku->productinfo['sub3'] .'</span></a>
                                <meta itemprop="position" content="2" />
                                </li>';      
                                
                                echo '
                                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $sku->productinfo['subsub3'] .'" href="'. $baseURL . $sku->productinfo['cat3_ref'] .'/'. $sku->productinfo['subsub3_ref'] .'-3-'. $sku->productinfo['our_subsub3'] .'/"><span itemprop="name">'. $sku->productinfo['subsub3'] .'</span></a>
                                <meta itemprop="position" content="3" />
                                </li>'; 
                        }
                    
                } 
				
                elseif ($pageinfo->page['ref'] == 'bestbuy') {
                        echo '<a href="'. $baseURL . $pageinfo->page['ref'] .'/">'. $pageinfo->page['name'] .' Overview</a>';	
                }

                elseif ($pageinfo->page['ref'] == 'china-online-shops') {
                    echo '
                    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="'. $baseURL . $pageinfo->page['ref'] .'/" title="'. $pageinfo->page['name'] .'"><span itemprop="name">'. $pageinfo->page['name'] .'</span></a>
                    <meta itemprop="position" content="1" />
                    </li>';
                }
                
                elseif ($pageinfo->page['ref'] == 'webshop-review') {
                    echo '
                    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="'. $baseURL .'china-online-shops/"><span itemprop="name">'. $pageinfo->page['name'] .'</span></a>
                    <meta itemprop="position" content="1" />
                    </li>';
                    
                    echo '
                    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $shopreview->shopInfo['name'] .' Review" href="'. $baseURL .'china-online-shops/'. $shopreview->shopInfo['ref'] .'-review/"><span itemprop="name">'. $shopreview->shopInfo['name'] .' Review</span></a>
                    <meta itemprop="position" content="2" />
                    </li>'; 
                }
                
                
                elseif ($pageinfo->page['ref'] == 'china-brands') {
                    echo '
                    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="'. $baseURL . $pageinfo->page['ref'] .'/" title="'. $pageinfo->page['name'] .'"><span itemprop="name">'. $pageinfo->page['name'] .'</span></a>
                    <meta itemprop="position" content="1" />
                    </li>';
                    
                    if (isset($categoryinfo->brand['ref'])) {
                       echo '
                        <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $categoryinfo->brand['brand'] .' Overview" href="'. $baseURL . $pageinfo->page['ref'] .'/'. $categoryinfo->brand['ref'] .'/"><span itemprop="name">'. $categoryinfo->brand['brand'] .'</span></a>
                        <meta itemprop="position" content="2" />
                        </li>';   
                    }      
                }
                
		
                elseif ($pageinfo->page['ref'] != 'sc' || $pageinfo->page['ref'] != 'c' || $pageinfo->page['ref'] != 'bestbuy'){
                    echo '
                    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="#" title="'. $pageinfo->page['name'] .'"><span itemprop="name">'. $pageinfo->page['name'] .'</span></a>
                    <meta itemprop="position" content="1" />
                    </li>';                    
                }
                
                
        }        
        
}

?>
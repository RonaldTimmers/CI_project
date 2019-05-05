<?php

namespace CI;

use \PDO;

class Categoryinfo {
    
    	public $brand;
        public $subsubcat;
        public $subcat;
        public $cat;
    
        
    function loadCats($db, $baseURL) {

        if (isset($_GET['cat'])) {
            if (!is_numeric($_GET['cat'])) {
                die("Wrong input.");
            }

            $statement = $db->prepare("SELECT `id`,`name`,`ref`,`description` FROM `categories` WHERE `id` = :id AND `del` = '0'");
            $statement->bindValue(':id', $_GET['cat'], \PDO::PARAM_INT);
            $statement->execute();
            $this->cat = $statement->fetch(\PDO::FETCH_ASSOC);

            $statement = null;	
            unset($statement);   

            if (isset($this->cat['ref']) && $this->cat['ref'] != $_GET['ref']) {
                header("HTTP/1.1 301 Moved Permanently");
                // header("Location: {$baseURL}1/{$this->cat['id']}/{$this->cat['ref']}/"); 
                // header("HTTP/1.1 302 Moved Temporarily");
                header("Location: {$baseURL}{$this->cat['ref']}-1-{$this->cat['id']}/"); 
            }                       
        } 
            
        elseif (isset($_GET['subcat'])) {

            if (!is_numeric($_GET['subcat'])) {
                die("Wrong input.");
            }

            $statement = $db->prepare("SELECT `id`,`cat`,`name`,`ref`,`description`, `information` FROM `subcats` WHERE `id` = :id AND `del` = '0' ");
            $statement->bindValue(':id', $_GET['subcat'], \PDO::PARAM_INT);
            $statement->execute();
            $this->subcat = $statement->fetch(\PDO::FETCH_ASSOC);


            $statement = null;	
            unset($statement);

            $statement = $db->prepare("SELECT `id`,`name`,`ref` FROM `categories` WHERE `id` = :id AND `del` = '0' ");
            $statement->bindValue(':id', $this->subcat['cat'], \PDO::PARAM_INT);
            $statement->execute();
            $this->cat = $statement->fetch(\PDO::FETCH_ASSOC);

            $statement = null;	
            unset($statement);

            if (isset($this->subcat['ref']) && $this->subcat['ref'] != $_GET['ref']) {
                header("HTTP/1.1 301 Moved Permanently");
                // header("Location: {$baseURL}p/{$this->subcat['id']}/{$this->subcat['ref']}/{$_GET['currentpage']}/"); 
                // header("HTTP/1.1 302 Moved Temporarily");
                header("Location: {$baseURL}{$this->cat['ref']}/{$this->subcat['ref']}-2-{$this->subcat['id']}/"); 
            }         
        } 
            
            elseif (isset($_GET['subsubcat'])) {
                    
                if (!is_numeric($_GET['subsubcat'])) {
                    die("Wrong input.");
                }

                $statement = $db->prepare("SELECT `id`,`subcat`,`name`,`ref`,`description` FROM `subsubcats` WHERE `id` = :id AND `del` = '0' ");
                $statement->bindValue(':id', $_GET['subsubcat'], \PDO::PARAM_INT);
                $statement->execute();
                $this->subsubcat = $statement->fetch(\PDO::FETCH_ASSOC);

                $statement = null;	
                unset($statement);

                $statement = $db->prepare("SELECT `id`,`cat`,`name`,`ref`,`description`, `information` FROM `subcats` WHERE `id` = :id AND `del` = '0' ");
                $statement->bindValue(':id', $this->subsubcat['subcat'], \PDO::PARAM_INT);
                $statement->execute();
                $this->subcat = $statement->fetch(\PDO::FETCH_ASSOC);

                $statement = null;	
                unset($statement);

                $statement = $db->prepare("SELECT `id`,`name`,`ref` FROM `categories` WHERE `id` = :id AND `del` = '0' ");
                $statement->bindValue(':id', $this->subcat['cat'], \PDO::PARAM_INT);
                $statement->execute();
                $this->cat = $statement->fetch(\PDO::FETCH_ASSOC);

                $statement = null;	
                unset($statement);   

                if (isset($this->subsubcat['ref']) && $this->subsubcat['ref'] != $_GET['ref']) {
                    header("HTTP/1.1 301 Moved Permanently");
                    header("Location: {$baseURL}{$this->cat['ref']}/{$this->subsubcat['ref']}-3-{$this->subsubcat['id']}/"); 
                }

                /*  Extra Implementation Because of Wrong Links Scraped By Google 
                    Which are Now Duplicated Category pages... Therefore put a 301 Redirect!
                    When the Link Structure contains the subcat ref, we redirect to the one with the cat ref 

                    WRONG: /power-tools/glue-gun-3-1059/ power-tools is a subcat 
                    CORRECT: /electrical-tools/glue-gun-3-1059/ electrical tools is a cat
                */

                if (isset($this->cat['ref']) && $this->cat['ref'] != $_GET['catref']) {
                    header("HTTP/1.1 301 Moved Permanently");
                    header("Location: {$baseURL}{$this->cat['ref']}/{$this->subsubcat['ref']}-3-{$this->subsubcat['id']}/"); 
                }
            } 
        
            elseif (isset($_GET['brand'])) {    
                    $statement = $db->prepare("SELECT `id`,`brand`,`ref`,`description` FROM `brand_pages` WHERE `ref` = :ref");
                    $statement->bindValue(':ref', $_GET['brand'], \PDO::PARAM_STR);
                    $statement->execute();
                    $this->brand = $statement->fetch(\PDO::FETCH_ASSOC);
                    
                    $statement = null;	
                    unset($statement);
            }
            
            
	} 
        
        function get_titles( $page, $sku, $shopreview, $qclean ) {

            if ( $page->page['ref'] == 'home' ) {
                echo 'China Price Tracker for Chinese Online Shops - CompareImports.com';
            }
            
            
            
            elseif ( $page->page['ref'] == 'search' ) {						// get the keywords
                $keywords = rawurldecode($qclean);					// decode raw url string
                //$keywords = str_replace('-', '', $keywords);
                $keywords = str_replace("*frwsl*", "/", $keywords);		// transform code back to forwardslash
                $keywords = str_replace("*bcksl*", "\\", $keywords);	// transform code back to backslash
                $keywords = str_replace("  ", " ", $keywords);			// Remove multiple adjacent spaces
                $keywords = trim($keywords, " ");	
                
                
                
                $keywords = search_html_escape_terms(search_split_terms(ucwords($keywords)));
                $keywords = implode(" ", $keywords);
                // echo $keywords .' - Search Results from China - CompareImports.com';
                echo $keywords .' - Catch the Best Price from Different Online China Shops - CompareImports.com';
            }
                    
                
            elseif ($page->page['ref'] == 'about' || $page->page['ref'] == 'contact' || $page->page['ref'] == 'disclaimer' || $page->page['ref'] == 'faq') {
                echo $page->page['name'] .' - CompareImports.com';
            }
            
            elseif ($page->page['ref'] == 'coupons') {
                echo 'All 2018 Coupon Codes from China Wholesale Shops - CompareImports.com';
            }
            
            elseif ($page->page['ref'] == 'china-online-shops') {  
                echo '20 Reviews from China Online Shops  - CompareImports.com'; 
            }
            
            elseif ($page->page['ref'] == 'webshop-review') {
                echo ''. $shopreview->shopInfo['name'] .' Review and Coupons - CompareImports.com';     
            }
            
            elseif ($page->page['ref'] == 'china-brands') {
                
                if (isset($this->brand['brand'])) {
                    echo 'Find the Best Prices for '. $this->brand['brand'] .' Products from China - CompareImports.com'; 
                } else {
                    echo 'Top 100 Brands From China - CompareImports.com';    
                }
                   
            }
            
            elseif ($page->page['ref'] != 'c' && $page->page['ref'] != 'sc' && $page->page['ref'] != 'sku') {
                echo $page->page['name'] .' - CompareImports.com';
            }

            elseif  (isset($this->subsubcat)) {
                echo $this->subcat['name'] .' - '. $this->subsubcat['name'] .' from China Shops - CompareImports.com';
            }
            
            elseif (isset($this->subcat)) {
                echo $this->subcat['name'] .' from China Shops - CompareImports.com';
            }
            
            elseif (isset($this->cat)) {
                echo $this->cat['name'] .' from China Shops - CompareImports.com';
            }
                
            /*
             * From SEO perspective create a title of around 60 characters long 
             * https://moz.com/learn/seo/title-tag
             * ' - CompareImports.com' is 21 chars
             * So we set the product specific title to 40 chars
             */
            elseif ($page->page['ref'] == 'sku' && (isset($sku->productinfo['id']))) {
                echo titleShortner($sku->productinfo['title'], 40) .' - CompareImports.com';
            } 
            
        }
        
        
        
        
        
        
        
        
        function get_descriptions($page, $sku, $shopreview, $brandpage ) {
                
            if ($page->page['ref'] == 'sku' && (isset($sku->productinfo['id']))) {
                $sku->productinfo['title'] = htmlspecialchars($sku->productinfo['title'], ENT_QUOTES);
                echo 'Just $'. $sku->productinfo['price'] .' at '. $sku->productinfo['source_name'] .' - Compare '. $sku->productinfo['title'] .' With All China Online Shops - CompareImports.com';       
            } 
             
            elseif ($page->page['ref'] == 'china-webshop-reviews') {
                echo '20 Top Shops from China. All your required information at one place about reliable and trustworthy shopping in China - CompareImports.com';
            }
            
            elseif ($page->page['ref'] == 'webshop-review') {
                echo $shopreview->shopInfo['description'];     
            }
            
            elseif ($page->page['ref'] == 'china-brands') {
                
                if (isset($this->brand['brand'])) {
                    echo $this->brand['brand'] .' - '. $brandpage->brandInfo['brand']['description']; 
                    
                } else {
                    echo 'Find all well-known Chinese brands like Xiaomi, DJI, Meizu and many more. Compare their products and find the best price in every Chinese shop - CompareImports.com';    
                }
                   
            }
            
            elseif ($page->page['ref'] != 'c' && $page->page['ref'] != 'sc' && $page->page['ref'] != 'sku') {
                echo $page->page['description'];
            }
            
            elseif  (isset($this->subsubcat)) {
                        
                if (!empty($this->subsubcat['description'])) {
                    $this->subsubcat['description'] = htmlspecialchars($this->subsubcat['description'], ENT_QUOTES);
                    echo 'Compare the best prices for '. $this->subsubcat['description'] .' from Chinese shops and wholesale with free shipping';
                        }
                        
                else {
                    echo 'Compare the best prices for '. $this->subsubcat['name'] .' from Chinese shops and wholesale with free shipping';   
                }
            }
		
            elseif (isset($this->subcat)) {
                        
                if (!empty($this->subcat['description'])) {
                    $this->subcat['description'] = htmlspecialchars($this->subcat['description'], ENT_QUOTES);
                    echo 'Compare the best prices for '. $this->subcat['description'] .' from Chinese shops and wholesale with free shipping';
                }    
                        
                else {
                    echo 'Compare the best prices for '. $this->subcat['name'] .' from Chinese shops and wholesale with free shipping';   
                }
            }
            
                elseif (isset($this->cat)) {
                    $this->cat['description'] = htmlspecialchars($this->cat['description'], ENT_QUOTES);
                    echo $this->cat['description'];
                }
                 
          }

}
?>
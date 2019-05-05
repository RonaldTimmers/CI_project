<?php

namespace CI;


class Pageinfo {	
    
    public $page;
    
    function getInfo($db) { 
        
        
        // Check if page input exists, default is home
        if ( !isset($_GET['source'] ) || $_GET['source'] == "" ) {
            $_GET['source'] = "home";				
        }
            
        $statement = $db->prepare("SELECT ref, name, type, source, content, title, description 
                                    FROM CMS_pages 
                                    WHERE active = 1 AND ref = :ref");																					
        $statement->bindValue(':ref', $_GET['source'], \PDO::PARAM_STR);
        $statement->execute();																											
        $result = $statement->fetch(\PDO::FETCH_ASSOC); // fetch the results
                 
        $this->page = $result;
      
        $statement = null;
        unset($statement);  

        /*      Check if the ABTEST GET Variable Exist
        *       We use later in the pagecontent function to call another ref source
        */
        if (isset($_GET['t']) && $_GET['t'] == 'true') {
             $this->page['abtest'] = 'ABTEST_';     
        } else {
            $this->page['abtest'] = '';  
        }    
    }   
}

class pageContent {
    
    public $main_content;
    
    
        function getPageContent($ping789tt, $baseURL, $staticURL, $pageinfo, $categoryinfo, $sku, $db,$shopreview, $deviceType, $page007, $qclean, $shops, $brandpage, $twig) {
        
        
        /*  Get right content and set header 
        *   According to the page['ref'] value
        
        */
        
        if ($pageinfo->page['type'] == "file") {
            
            if ($pageinfo->page['ref'] == 'blog') {
                ob_start();
                include($pageinfo->page['source']); 
                $this->main_content = ob_get_clean();
                
            } elseif ($pageinfo->page['ref'] == 'sku' && (!isset($sku->productinfo['id']) || $sku->productinfo['active'] == 0)) {
                $pageinfo->page['source'] = "sites/error404.php";
                ob_start();
                include($pageinfo->page['source']); 
                $this->main_content = ob_get_clean();
                header("HTTP/1.1 404 Not Found");
               
            } elseif ( $pageinfo->page['ref'] == 'c' && (!isset($categoryinfo->cat['id'])) ) {
                $pageinfo->page['source'] = "sites/error404.php";
                ob_start();
                include($pageinfo->page['source']); 
                $this->main_content = ob_get_clean();
                header("HTTP/1.1 404 Not Found"); 
                
            } elseif    ( $pageinfo->page['ref'] == 'sc' && 
                            (
                                ( isset($_GET['subcat']) && ( !isset($categoryinfo->cat['id']) || !isset($categoryinfo->subcat['id']) ) ) ||   
                                ( isset($_GET['subsubcat']) && ( !isset($categoryinfo->cat['id']) || !isset($categoryinfo->subcat['id']) || !isset($categoryinfo->subsubcat['id']) ) ) 
                            )
                        ) 
                {
                $pageinfo->page['source'] = "sites/error404.php";
                ob_start();
                include($pageinfo->page['source']); 
                $this->main_content = ob_get_clean();
                header("HTTP/1.1 404 Not Found"); 
                }
                
            else {              
                ob_start();
                    include_once("sites/". $pageinfo->page['abtest'] . $pageinfo->page['source']);
                    $this->main_content = ob_get_contents();
                ob_end_clean();
            }
        }   

        elseif ($pageinfo->page['type'] == "db") {
            ob_start();
            echo '<div id="dbcontent-box" class="col-md-offset-1 col-md-10">';
            echo $pageinfo->page['content'];
            echo '</div>';
            $this->main_content = ob_get_clean();
        }

        else {
            header("HTTP/1.1 404 Not Found");
            ob_start();
            include("sites/error404.php"); 
            $this->main_content = ob_get_clean();
        }
    }     
}

class Shops {
    
    public $activeShops = null;
    public $otherShops = null;    
    
    function set_activeShops($db) {
    // Now create the list of attribute filters

        $activeShopsString = "SELECT  id, ref, name, url
                                FROM `sources` 
                                WHERE active = 1 AND current = 1
                                ORDER BY rank ASC
                            ";
        
        
        $statement = $db->prepare($activeShopsString);
        $statement->execute();
        $activeShops = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->activeShops = $activeShops;
        
        $statement = null;
        unset($statement);          
    }    

    function set_otherShops($db) {
    // Now create the list of attribute filters

        $otherShopsString = "SELECT  id, ref, name, url
                            FROM `sources` 
                            WHERE active = 1 AND current = 0
                            ORDER BY id ASC
                            ";
        
        
        $statement = $db->prepare($otherShopsString);
        $statement->execute();
        $otherShops = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $this->otherShops = $otherShops;
        
        $statement = null; 
        unset($statement);          
    }   
}


?>
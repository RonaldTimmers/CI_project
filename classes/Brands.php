<?php 

namespace CI;

class Brands {
    public $brandInfo;
    public $brandRef;
    
    function __construct () {
        if (isset($_GET['brand'])){  
            $this->brandRef = $_GET['brand']; 
        }
    }
    
    
    function setBrands ($db, $baseURL) {
    
        $statement = $db->prepare(" SELECT brand.id, brand.brand, brand.ref, brand_cat.name As catname
                                    FROM `brand_pages` As brand 
                                    JOIN `brand_categories` As brand_cat
                                    ON brand.main_category = brand_cat.id
                                    ORDER BY brand.id");
                                   
                                               
        $statement->execute(); 
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        
        return $result;
        
        $statement = null;	
        unset($statement);  
    }
    
    
    
    public static function getBrandOverview_ABTEST($db, $baseURL) {
    
            $statement = $db->prepare(" SELECT brand.id, brand.brand, brand.ref, brand.keyword, brand.main_category, brand.categories, brand.website, brand.information, brand.description, brand_cat.name As catname
                                        FROM `brand_pages` As brand 
                                        JOIN `brand_categories` As brand_cat
                                        ON brand.main_category = brand_cat.id
                                        ORDER BY brand.main_category = 0, brand.main_category");
                                       
                                                   
            $statement->execute();
            
            $start_cat = 99;
            
            while ($brandInfo = $statement->fetch(\PDO::FETCH_ASSOC)){   ?>
                                             
                <div class="col-ms-6 col-sm-4 col-md-3 brand-item">
                    <div class="thumbnail">
                            <a href="<?php echo $baseURL .'china-brands/'. $brandInfo['ref'] .'/';?>" title="Take a Look at <?php echo $brandInfo['brand'];?>">
                                <img alt="<?php echo $brandInfo['brand'] . ' logo';?>" src="<?php echo $baseURL .'img/brands/'. $brandInfo['ref'] .'.png';?>">
                            </a>
                           <div class="inner">
                                <p><span class="glyphicon glyphicon-tags" aria-hidden="true" style="margin-right: 5px;"></span><?php echo ' '. $brandInfo['catname']; ?></p>
                                <p><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                                    <a href="<?php echo $brandInfo['website']; ?>" target="_blank" title="See the offical <?php echo $brandInfo['brand']; ?> website"><?php echo ' '. $brandInfo['website']; ?></a>
                                </p>
                           </div>  

                           <div class="webshop-review-button row">   
                                <a class="col-xs-10 col-xs-offset-1 btn btn-primary go-shopreview-button" href="<?php echo $baseURL .'china-brands/'. $brandInfo['ref'];?>/" title="<?php echo $brandInfo['brand']; ?> Products"><b><?php echo $brandInfo['brand'];?> Products</b></a> 
                            </div>
                           
                    </div>
                </div>  

           <?php         
            }    
                
            $statement = null;	
            unset($statement);  
    }
    
    public static function getBrandOverview ($db, $baseURL) {

        $statement = $db->prepare(" SELECT brand.id, brand.brand, brand.ref, brand.keyword, brand.main_category, brand.categories, brand.website, brand.information, brand.description, brand_cat.name As catname
                                    FROM `brand_pages` As brand 
                                    JOIN `brand_categories` As brand_cat
                                    ON brand.main_category = brand_cat.id
                                    ORDER BY brand.main_category = 0, brand.main_category");
                                   
                                               
        $statement->execute();
        
        $start_cat = 99;
        
        while ($brandInfo = $statement->fetch(\PDO::FETCH_ASSOC)){ 
            if ($start_cat != $brandInfo['main_category']) { ?>
                <div class="clearfix"></div>
                <div class="panel panel-default">
                    <div class="panel-body">
                            <h3 class="text-center brand-seperator"><?php echo $brandInfo['catname']; ?></h3>   
                    </div>
                </div>  
                <div class="clearfix"></div>
                
            <?php 
                
                $start_cat = $brandInfo['main_category'];
            
            } ?>        
                                         
            <div class="col-ms-6 col-sm-4 col-md-3 brand-item">
                <div class="thumbnail">
                        <a href="<?php echo $baseURL .'china-brands/'. $brandInfo['ref'] .'/';?>" title="Take a Look at <?php echo $brandInfo['brand'];?>">
                            <img alt="<?php echo $brandInfo['brand'] . ' logo';?>" src="<?php echo $baseURL .'img/brands/'. $brandInfo['ref'] .'.png';?>">
                        </a>
                        <hr />
                       <div class="inner">
                            <h2><?php echo $brandInfo['brand']; ?></h2>                           
                            <p><?php echo $brandInfo['website']; ?></p>
                       </div>    
                </div>
            </div>  

       <?php         
        }    
            
        $statement = null;	
        unset($statement);  
    }

    function getBrand ($db) {
        
        $statement = $db->prepare("  SELECT id, brand, ref, keyword, main_category, categories, website, information, description,
                                        (   
                                            SELECT COUNT(*) 
                                            FROM `brand_pages`
                                        ) As total                    
                                        FROM `brand_pages`
                                        WHERE ref = ?
                                   "); 
        
        $statement->execute(array($this->brandRef)); 
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        
       
        

        
        if ( $result['id'] == 1 ) {
            $prev_brand = $result['total'];
        } else {
            $prev_brand = $result['id'] - 1;
        }
        
        if ( $result['id'] == $result['total'] ) {
            $next_brand = 1;
        } else {
            $next_brand = $result['id'] + 1;
        }
        
        $statement4 = $db->prepare("SELECT ref, brand
                                   FROM `brand_pages`
                                   WHERE id IN (?,?) 
                                   ORDER BY FIELD (id, ?, ?) "); 
        
        $statement4->execute(array($prev_brand, $next_brand, $prev_brand, $next_brand)); 
        $prev_next = $statement4->fetchALL(\PDO::FETCH_ASSOC);
        
      
        $statement2 = $db->prepare("SELECT name, cat_id, cat_level
                                   FROM `brand_categories`
                                   WHERE id IN ( ". $result['categories'] ." )"); 
                                   
        $statement2->execute();
        
        $brand_categories = array();
        while ($cat_result = $statement2->fetch(\PDO::FETCH_ASSOC)) {
            if ($cat_result['name'] != 'Other') {
               $brand_categories[] = ['link' => $this->getCategory($db, $cat_result['cat_id'], $cat_result['cat_level']), 'name' => $cat_result['name']];   
            }        
        }
        
        $statement3 = $db->prepare("SELECT id, brand, ref
                                   FROM `brand_pages`
                                   WHERE main_category = ? AND id != ?"); 
        
        $statement3->execute(array($result['main_category'], $result['id'])); 
        
        $brand_alternatives = array();
        
        while ($alternative_result = $statement3->fetch(\PDO::FETCH_ASSOC)) {
            $brand_alternatives[] = ['name' => $alternative_result['brand'], 'ref' => $alternative_result['ref']];  
        }
        
        /*
         * Query to get all Brands 
         * To DO: Create this as main query and fill all other arrays with special functions!
         */
        
        $statement5 = $db->prepare("SELECT id, brand, ref
                                   FROM `brand_pages`
                                   ORDER BY brand ASC"); 
        
        $statement5->execute();
        $brand_all = $statement5->fetchALL(\PDO::FETCH_ASSOC);
        
        
        $this->brandInfo['prev_next'] = $prev_next;
        $this->brandInfo['brand'] = $result;
        $this->brandInfo['categories'] = $brand_categories;
        $this->brandInfo['alternative_brands'] = $brand_alternatives;
        $this->brandInfo['all_brands'] = $brand_all;
        
        
        $statement = null;	
        unset($statement); 
        $statement2 = null;	
        unset($statement2); 
        $statement3 = null;	
        unset($statement4); 
        $statement4 = null;	
        unset($statement5); 
        $statement5 = null;
    }  
    
    protected function getCategory ($db, $id, $level) {
                
                
                    $cat_level = ($level == 2) ? 'subcats' : 'subsubcats'; 
                    
                    if ($level == 2) {
                        $statement = $db->prepare("SELECT sub.ref As subref, sub.id As subid, category.ref As catref
                                                   FROM `". $cat_level ."` As sub
                                                   RIGHT JOIN `categories` As category 
                                                   ON sub.cat = category.id
                                                   WHERE sub.id = ". $id ." "); 
                                                   
                        $statement->execute();
                        $category = $statement->fetch(\PDO::FETCH_ASSOC);                    
                        
                        $link = $category['catref'] .'/'. $category['subref'] .'-'. $level .'-'. $category['subid'] .'/';               
                    } elseif ($level == 3) {
                        
                        $statement = $db->prepare("SELECT subsub.ref As subsubref, subsub.id As subsubid, category.ref As catref
                                                   FROM `". $cat_level ."` As subsub
                                                   RIGHT JOIN `subcats` As sub
                                                   ON subsub.subcat = sub.id 
                                                   RIGHT JOIN `categories` As category 
                                                   ON sub.cat = category.id
                                                   WHERE subsub.id = ". $id ." ");   
                        
                        $statement->execute();
                        $category = $statement->fetch(\PDO::FETCH_ASSOC); 
                        
                        $link = $category['catref'] .'/'. $category['subsubref'] .'-'. $level .'-'. $category['subsubid'] .'/';
                    }
                    

            
                
                return $link;
                
                $statement = null;	
                unset($statement); 
                

    
    }
    
    
} 




?>
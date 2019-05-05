<?php 

namespace CI;


class Shopreview {
    public $shopInfo;
    public $shopRef;
    public $shopIntro;
    public $shopsWithReview;
    
    
    
    function __construct () {
        if (isset($_GET['ref'])){  
        $this->shopRef = $_GET['ref']; 
        }
    }
    
    /* Function not used for Shopreviews ? 14-7-2017 */
    
    function setShopData ($db,$baseURL) {
        $statement = $db->prepare(" SELECT  T2.name, T2.ref, T2.url, T2.shipping_costs, 
                                                    (SELECT AVG(TRRT.rate) 
                                                    FROM `comments_phpreview_ratings_rating_types` AS TRRT 
                                                    WHERE TRRT.rating_id IN 
                                                        (SELECT TR.id 
                                                         FROM `comments_phpreview_ratings` AS `TR` 
                                                         WHERE TR.status='T' AND TR.thread_id = ( 	SELECT thread_id 
                                                                                                    FROM `comments_sources_threads`
                                                                                                    WHERE source_id = T2.id
                                                                                                )
                                                
                                                        ) 
                                                    ) AS avg_rate
                                                
                                               FROM `sources` T2
                                               WHERE T2.active = '1'
                                               ORDER BY avg_rate DESC
                                                   
                                        ");
                                                    
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $statement = null;	
        unset($statement);  
        
        return $result;
          
    }
    
    /*
     * ShopData for Overview page
     * 
     */
    function getShopData ($db,$baseURL) {
    
            $statement = $db->prepare(" SELECT     T1.ref, T1.review, T1.slogan, T1.city, T1.founded, T1.organization, T1.assortiment, T1.productamount, T2.name, T2.ref, T2.url, T2.shipping_costs, T2.wholesale, 
                                                    (SELECT AVG(TRRT.rate) 
                                                    FROM `comments_phpreview_ratings_rating_types` AS TRRT 
                                                    WHERE TRRT.rating_id IN 
                                                        (SELECT TR.id 
                                                         FROM `comments_phpreview_ratings` AS `TR` 
                                                         WHERE TR.status='T' AND TR.thread_id = 
                                                            ( 	
                                                                SELECT thread_id 
                                                                FROM `comments_sources_threads`
                                                                WHERE source_id = T2.id
                                                            )
                                                
                                                        ) 
                                                    ) AS avg_rate
                                                
                                               FROM `shop_reviews` T1
                                               LEFT JOIN `sources` T2 ON T2.id = T1.source 
                                               ORDER BY avg_rate DESC
                                                   
                                        ");
                                                    
                $statement->execute();
                while ($shopinfo = $statement->fetch(\PDO::FETCH_ASSOC)){ 
                            
                    switch ($shopinfo['shipping_costs']) {
                        case 0:
                        $shipping = 'Free Shipping';
                        break;
                        case 1:
                        $shipping = 'Free/Paid';
                        break;
                        case 2:
                        $shipping = 'Paid Shipping';
                        break;
                        default:
                        $shipping = 'Unknown';
                        break; 
                    }
                    
                    switch ($shopinfo['wholesale']) {
                        case 0: 
                        $wholesale = 'No Bulk Discount';
                        break;
                        case 1: 
                        $wholesale = 'Yes Wholesale Discount';
                        break;
                        default:
                        $wholesale = 'Unknown';
                        break;
                            
                    }
                
                    ?> 
                        
                           <div class="col-ms-6 col-sm-6 col-md-4 col-lg-3 webshop-item">
                                <div class="thumbnail">
                                        <a href="<?php echo $shopinfo['url']; ?>" rel="nofollow" target="blank" title="Take a Look at <?php echo $shopinfo['name']; ?>">
                                            <img alt="<?php echo $shopinfo['name'] . ' logo';?>" src="<?php echo $baseURL .'img/sources/big/'. $shopinfo['ref'] .'_logo.png'; ?>">
                                        </a>
                                        <hr />
                                       <div class="inner">
                                            <span class="webshop-slogan center-block"><?php echo $shopinfo['slogan']; ?></span> 
                                            <?php if ($shopinfo['review'] == 1) { ?>  
                                                <div class="webshop-rating center-block">
                                                    <div id="product-star-<?php echo round($shopinfo['avg_rate']); ?>"></div>
                                                </div>
                                            <?php } ?>   

                                               <p><span class="glyphicon glyphicon-heart-empty" aria-hidden="true"></span> <?php echo $shopinfo['assortiment']; ?></p>
                                               <p><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> <?php echo $shopinfo['city']; ?></p>
                                               <p><span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?php echo $shopinfo['founded']; ?></p>
                                               <p><span class="glyphicon glyphicon-tags" aria-hidden="true"></span>  <?php echo $shopinfo['productamount']; ?></p>
                                               <p><span class="glyphicon glyphicon-plane" aria-hidden="true"></span>  <?php echo $shipping; ?></p>
                                               <p><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>  <?php echo $wholesale; ?></p>
           
                                       </div>
                                        
                                            <div class="webshop-review-button row">
                                                <?php if ($shopinfo['review'] == 1) { ?>    
                                                <a class="col-xs-10 col-xs-offset-1 btn btn-primary go-shopreview-button" href="<?php echo $baseURL .'china-online-shops/'. $shopinfo['ref'];?>-review/" title="<?php echo $shopinfo['name']; ?> Review"><b><?php echo $shopinfo['name'];?> Review</b></a> 
                                                <?php } ?>
                                            </div>
                                      
                                </div>
                           </div>  

                               <?php
                    
                }        
                $statement = null;	
                unset($statement);  
    }

    
    
    /*
     * USED FOR SINGLE SHOPREVIEW PAGE
     */
    
    public function getShopRating ( $db, $thread_id ) {
        
        $stmt = $db->prepare("  SELECT RT.title, TRRT.rating_type_id,  FORMAT( AVG( TRRT.rate ) , 2 )  AS avg_rate
                                FROM `comments_phpreview_ratings_rating_types` AS `TRRT`
								LEFT JOIN `comments_phpreview_rating_types` AS `RT` 
								ON RT.id = TRRT.rating_type_id

                                WHERE TRRT.rating_id IN 
                                    (
                                    SELECT TR.id 
                                    FROM `comments_phpreview_ratings` AS `TR` 
                                    WHERE TR.status='T' AND TR.thread_id = ?
                                    ) 
                                GROUP BY TRRT.rating_type_id");
        
        $stmt->execute( array($thread_id) );
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $stmt = null;	
        unset($stmt); 
        
        return $result;
        
    } 
    
    public function getShopReviewCount ( $db, $thread_id  ) {
        
        $stmt = $db->prepare("  SELECT COUNT(TR.id) As totalReviews 
                                FROM `comments_phpreview_ratings` AS `TR` 
                                WHERE TR.status='T' AND TR.thread_id = ?");

       $stmt->execute( array($thread_id) );
       $result = $stmt->fetch(\PDO::FETCH_ASSOC);

       $stmt = null;	
       unset($stmt); 

       return $result;       
        
        
    }
    
    function getShopReview ($db) {
        
                $statement = $db->prepare("SELECT T1.id, T1.source, T1.ref, T1.title, T1.description, T1.alternative_shops, T1.mainscreen, T1.introduction, T1.ci_opinion, T1.payment, T1.shipping_delivery, T1.return_warranty, T1.customs, T1.point_system, T1.warehouses, T1.special_feat, T1.conclusion, T1.pros, T1.cons, T2.name, T2.small, T2.url, T3.thread_id,
                                            (   
                                                SELECT COUNT(*) 
                                                FROM `shop_reviews`
                                            ) As total
                                           FROM `shop_reviews` T1
                                           LEFT JOIN `sources` T2 ON T2.id = T1.source 
                                           LEFT JOIN `comments_sources_threads` T3 ON T3.source_id = T1.source
                                           WHERE T1.ref = ? "); 
                
                $statement->execute(array($this->shopRef));
                
                $result = $statement->fetch(\PDO::FETCH_ASSOC);
                $this->shopInfo  = $result;  
                
                if (isset($result['alternative_shops'])) {
                    $statement2 = $db->prepare("SELECT T1.source, T1.ref, T1.alternative_slogan, T2.name, T2.small, T2.url, T2.shipping_costs
                                               FROM `shop_reviews` T1
                                               LEFT JOIN `sources` T2 ON T2.id = T1.source 
                                               WHERE T1.source IN (". $result['alternative_shops'] .") AND T2.active = '1'"); 
                    
                    $statement2->execute();
                    while ($alternatives = $statement2->fetch(\PDO::FETCH_ASSOC)) {
                        
                        switch ($alternatives['shipping_costs']) {
                            case 0:
                            $shipping = 'Free Shipping';
                            break;
                            case 1:
                            $shipping = 'Free/Paid';
                            break;
                            case 2:
                            $shipping = 'Paid Shipping';
                            break;
                            default:
                            $shipping = 'Unknown Costs';
                            break; 
                        }
                        
                        
                        
                        $this->shopInfo['alternatives'][] = ['ref' => $alternatives['ref'], 'name' => $alternatives['name'], 'slogan' => $alternatives['alternative_slogan'], 'shipping' => $shipping ]; 
                    }
                    
                    $statement2 = null;	
                    unset($statement2); 
                    
                }
                
               
                /* Get the Shop Review IDs 
                 * Get the right keys for the prev and next shop
                 */
                
                $this->getShopsWithReview($db);
                //$key = array_search($result['id'], $this->shopsWithReview['id']);
                $key = array_search($result['id'], array_column($this->shopsWithReview, 'id'));
                
    
                
                $key_prev = $key - 1;
                $key_next = $key + 1;
                $keys = array_keys($this->shopsWithReview);
                $key_last = array_pop($keys);

                
                if ( $key == 0 ) {
                    $prev_shop = $this->shopsWithReview[$key_last]['id'];
                } else {
                    $prev_shop = $this->shopsWithReview[$key_prev]['id'];
                }

                if ( $key ==  $key_last ) {
                    $next_shop = $this->shopsWithReview[0]['id'];
                } else {
                    $next_shop = $this->shopsWithReview[$key_next]['id'];
                }
                
                
                $statement3 = $db->prepare("SELECT T1.ref, T2.name
                                            FROM `shop_reviews` T1
                                            LEFT JOIN `sources` T2 ON T2.id = T1.source 
                                            WHERE T1.id IN (?,?) 
                                            ORDER BY FIELD (T1.id, ?, ?) "); 

                $statement3->execute(array($prev_shop, $next_shop, $prev_shop, $next_shop));  
                
                $prev_next = $statement3->fetchALL(\PDO::FETCH_ASSOC);
                $this->shopInfo['prev_next'] = $prev_next;
                
                
                
                
                $statement3 = null;	
                unset($statement3);             
                
                $statement = null;	
                unset($statement); 
    }  
    
    
    /*
     * Get all Shops with a Review
     */
    
    
    function getShopsWithReview ($db) {
        
                $statement = $db->prepare("SELECT T1.id, T1.ref, T2.name
                                           FROM `shop_reviews` T1
                                           LEFT JOIN `sources` T2
                                           ON T1.source = T2.id
                                           WHERE T1.review = 1 
                                           "); 
                
                $statement->execute();
                $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
                
                $statement = null;	
                unset($statement); 
                
                $this->shopsWithReview = $result;
    }
    
    
    function setShopIntro ($db, $source) {
        
                $statement = $db->prepare("SELECT T1.review, T1.source, T1.ref, T1.ci_opinion, T1.founded, T1.productamount, T2.name, T3.overall, T3.overall2
                                                   FROM `shop_reviews` T1
                                                   LEFT JOIN `sources` T2 ON T2.id = T1.source 
                                                   LEFT JOIN `shop_stars` T3 ON T3.source = T1.source 
                                                   WHERE T1.source = :source");
                
                $statement->bindValue(':source', $source, \PDO::PARAM_STR);
                $statement->execute();
                $result = $statement->fetch(\PDO::FETCH_ASSOC);
                  
                $this->shopIntro  = $result;   
                
                $statement = null;	
                unset($statement); 
    }     
} 




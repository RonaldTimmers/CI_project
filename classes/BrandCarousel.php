<?php 
namespace CI;

class BrandCarousel {  


    function set_shopsBanner ($db) { 


                // connect to the database and get information
                $statement = $db->prepare("SELECT T1.id,T1.source,T1.title,T1.price,T1.oldprice,T1.off,T1.url,T1.time,T1.imgurl,T2.name,T2.logo 
                                           FROM `daily_deals` T1 
                                           LEFT JOIN `sources` T2 ON T2.id = T1.source
                                           WHERE (T1.time - $this->currenttime) BETWEEN :timeBoundLeft AND :timeBoundRight
                                           ORDER BY T1.time ASC 
                                           LIMIT :limit");	
                
                $statement->bindParam(':limit', $limit, \PDO::PARAM_INT);
                $statement->bindParam(':timeBoundLeft', $this->timeBoundLeft, \PDO::PARAM_INT);
                $statement->bindParam(':timeBoundRight', $this->timeBoundRight, \PDO::PARAM_INT);
                $statement->execute();														
                
                while ($DailyDeal = $statement->fetch(\PDO::FETCH_ASSOC)) {		
                        $counter++;
                                    
                        $this->timeleft = ($DailyDeal['time'] - $this->currenttime);  // Calculate timeleft of daily deal
                        $DailyDeal['title'] = htmlspecialchars($DailyDeal['title'], ENT_QUOTES);
                        $DailyDeal['url'] = htmlspecialchars($DailyDeal['url'], ENT_QUOTES);

                       if($this->timeleft > 0) {
                                        echo '	
                                            <div class="fl daily-item">
                                                <a href="'. $DailyDeal['url'] .'" title="'. $DailyDeal['title'] .'" rel="nofollow" target="_blank">
                                                <div class="daily-img"><img src="'. $staticURL .'dailydealsImages/'. $DailyDeal['imgurl'] .'" alt="'. $DailyDeal['title'] .'"></div>	
                                                <div class="daily-title">'. $DailyDeal['title'] .'</div>
                                                <div class="daily-source"><img src="'. $baseURL .'img/sources/'. $DailyDeal['logo'] .'.png" alt="'. $DailyDeal['name'] .'"></div>	
                                                <div class="daily-price">&#36;'. $DailyDeal['price'] .'</div>
                                                <div class="daily-oldprice">&#36;'. $DailyDeal['oldprice'] .'</div>';
                                                if  ($this->timeleft < 86400)   { echo '<div class="daily-24h"></div>'; }  
                                            echo '</a>					
                                            </div>';

                       }
                 }
        $statement = null;
        unset($statement);			
    }
    
    public static function get_shopsBanner ( $shops ) {
        Global $baseURL;
            
        echo '<div id="shop-logos-gallery" class="owl-carousel owl-theme">';
        
        foreach ($shops as $shop) {
            /*
            echo' 
            <a rel="nofollow" class="shop-carousel-logo" target="_blank" href="'. $shop["url"] .'" title="Look at China Webshop '. $shop["name"] .'" >
                <div class="'. $shop["ref"] .'_logo"></div>
            </a>
            ';
             * 
             */
            echo' 
            <a rel="nofollow" class="shop-carousel-logo" target="_blank" href="'. $baseURL . 'goto/shop/'. $shop["id"] .'/" title="Look at China Webshop '. $shop["name"] .'" >
                <div class="'. $shop["ref"] .'_logo"></div>
            </a>
            ';           
            
        }

        echo '</div>';
    }
}



<?php 

namespace CI;

class DailyDeals {  

    public $timeFrame = array('oneday', 'twodays', 'oneweek');
    public $timeShow = '24 Hours Left';
    public $countProducts = 1;
    
    public $Thumbs;
    
    private $currenttime;
    private $timeleft;
    private $timeBoundLeft;
    private $timeBoundRight;

    
    
    function __construct () {
        $this->currenttime =  time();
    }    
        
        

    function set_dailydeals ($db, $limit = 100, $timedomain = null) {
    $counter = 0;

        switch ($timedomain){
            case 'oneday':
                $this->timeBoundLeft = 0;
                $this->timeBoundRight = 86400;
                $this->timeFrame = $timedomain;
                $this->timeShow = '48 Hours Left';
                break;
            case 'twodays':
                $this->timeBoundLeft = 86400;
                $this->timeBoundRight = 172800;
                $this->timeFrame = $timedomain;
                $this->timeShow = 'One Week';
                break;
            case 'oneweek':
                $this->timeBoundLeft = 172800;
                $this->timeBoundRight = 604800;
                $this->timeFrame = $timedomain;
                $this->timeShow = 'Two Weeks';
                break;
            case 'twoweeks':
                $this->timeBoundLeft = 604800;
                $this->timeBoundRight = 1209600;
                $this->timeFrame = $timedomain;
                break;
            default:
                $this->timeBoundLeft = 0;
                $this->timeBoundRight = 1209600;
                break;
        }

        // connect to the database and get information
        /* CHANGED BECAUSE OF TIME FRAME 
        $statement = $db->prepare("SELECT T1.id,T1.source,T1.title,T1.price,T1.oldprice As list ,T1.off,T1.url,T1.time,T1.imgurl,T2.name,T2.logo 
                                   FROM `daily_deals` T1 
                                   LEFT JOIN `sources` T2 ON T2.id = T1.source
                                   WHERE (T1.time - $this->currenttime) BETWEEN :timeBoundLeft AND :timeBoundRight
                                   ORDER BY T1.time ASC 
                                   LIMIT :limit");	
        
        $statement->bindParam(':limit', $limit, \\PDO::PARAM_INT);
        $statement->bindParam(':timeBoundLeft', $this->timeBoundLeft, \PDO::PARAM_INT);
        $statement->bindParam(':timeBoundRight', $this->timeBoundRight, \PDO::PARAM_INT);
        */
        
        $statement = $db->prepare("SELECT T1.id,T1.source,T1.title,T1.price,T1.oldprice As list ,T1.off,T1.url,T1.time,T1.imgurl As thumb_path,T2.name,T2.logo 
                                   FROM `daily_deals` T1 
                                   LEFT JOIN `sources` T2 ON T2.id = T1.source
                                   ORDER BY T1.time ASC 
                                   LIMIT :limit");	
        $statement->bindParam(':limit', $limit, \PDO::PARAM_INT);
        
        $statement->execute();														
        
        $this->Thumbs = $statement->fetchAll(\PDO::FETCH_ASSOC);
        
        /* 
        DEPRECEATED: WE NOW CALL createThumbs::get_thumbs() Method !
        
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
                                        if($this->timeleft < 86400){ echo '<div class="daily-24h"></div>'; }  
                                    echo '</a>					
                                    </div>';

               }
         }
         */
                 
                 
    $statement = null;
    unset($statement);			
    }
    
    public static function get_dailydeals($Thumbs) {
    Global $baseURL, $staticURL;
        foreach ($Thumbs as $Thumb) { 
            
            echo
            ' 
            <div class="product-item col-sm-3 col-ms-6 col-xs-6" itemscope itemtype="http://schema.org/Product">
                    <span class="product-list-off label label-success pull-right">'. $Thumb['off'] .'% OFF</span>
                    <a  rel="nofollow" target="_blank"
                        href="'. $Thumb['url'] .'" title="'. htmlspecialchars($Thumb['title'], ENT_QUOTES) .'">
                        <div>
                            <img itemprop="image" id="product-thumb" class="img-responsive img-rounded center-block" src="'. $staticURL .'dailydealsImages/'. $Thumb['thumb_path'] .'" alt="'. htmlspecialchars($Thumb['title'], ENT_QUOTES) .'" />
                        </div>
                    </a>
                    <div class="product-inner">
                        <a  rel="nofollow" target="_blank"
                        href="'. $Thumb['url'] .'" title="'. htmlspecialchars($Thumb['title'], ENT_QUOTES) .'">
                        <h3 class="product-title center-block" itemprop="name">'. $Thumb['title'] .'</h3>
                            <span itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer">
                                    <div class="product-price">$'. $Thumb['price'] .' <span class="product-listprice">'. $Thumb['list'] .'</span></div>                  
                                    <meta itemprop="lowPrice" content="'. $Thumb['price'] .'" />
                                    <meta itemprop="priceCurrency" content="USD" />
                            </span>
                            <div class="product-source center-block row hidden-xs">
                                <div class="'. $Thumb['logo'] .'"></div>       
                            </div>
                        </a>
                    </div>
            </div>';
         
        
        }
        
        
    }
    
    
}
?>

<?php

namespace CI;

date_default_timezone_set('Europe/Amsterdam');


class Coupons {
    
    
    /* set_needed coupons 
    Options: 
        Source
        Limit
        Pagination
    */
    
    public $coupons; 
    private $coupons_total;
    
    
    function set_coupons ($db, $source = null) {
        
        $where_clause = isset($source) ? " WHERE T1.source = ? " : " " ;
        
        
        $statement = $db->prepare("SELECT T1.cat, T1.code, T1.deal, T1.end, T2.id, T2.name, T2.ref, T2.url 
                                   FROM `coupons` T1 
                                   LEFT JOIN `sources` T2 
                                   ON T2.id = T1.source 
                                   ". $where_clause ."
                                   ORDER BY T1.special DESC, T2.name ASC");		
                               
                               
     
			
        $statement->execute(Array($source));
                                                  
        $this->coupons = $statement->fetchAll(\PDO::FETCH_ASSOC);
        
        $today = new \DateTime();
        $today->format("d/m/Y");
        
        foreach ($this->coupons As $key => $coupon) {
            
            $coupon['end'] = date_format(new \DateTime($coupon['end']),"d/m/Y");
            if($coupon['end'] == "30/11/-0001" || $coupon['end'] == "01/01/1970") {$coupon['end'] = "Unknown";}
            
            if (($coupon['end'] > $today && !empty($coupon['code'])  && $coupon['code'] != "Click to Redeem") || 
                ($coupon['end'] == "NO END DATE" && !empty($coupon['code']) && $coupon['code'] != "Click to Redeem") 
                || ($coupon['end'] == "Unknown")) {
  
                    unset($this->coupons[$key]);
                } else {
                    $this->coupons[$key]['end'] = $coupon['end'];
                }
        }
        

        $statement = null;	
        unset($statement);			  
    }
    

    /*
     * New Public Function 
     * Called in product.class.php
     * To Get Information about the Available Coupons per Source
     * 
     * Return: Assoc Array with coupons
     */
    
    public static function set_source_coupons ($db, $source) {
        
        $where_clause = isset($source) ? " WHERE T1.source = ? " : " " ;
        $statement = $db->prepare("SELECT T1.code, T1.deal, T1.end, T1.benefit
                                   FROM `source_coupons` T1 
                                   RIGHT JOIN `sources` T2 
                                   ON T1.source = T2.id 
                                   ". $where_clause);		
                               
                               
     
			
        $statement->execute(Array($source));
                                                  
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $statement = null;	
        unset($statement); 
        
        return $result;
    }
    
    function  get_coupons() {
        Global $baseURL;
        
        if (isset($this->coupons)) {
            $coupons  =  $this->coupons; 
            
            foreach ($coupons as $coupon) { 
            ?>
                <div class="col-xs-6 col-md-4 coupon-item" data-source="<?php echo $coupon['id'];?>">
                    <div class="thumbnail">  
                        <img alt="<?php echo $coupon['name'];?>" src="<?php echo $baseURL ."img/sources/big/". $coupon['ref'] ."_logo.png";?>"> 
                        <div class="inner">
                            <h4><?php echo $coupon['deal'];?></h4>
                            <p class="text-right" style="color: #00CC00;"> 
                            <span class="glyphicon glyphicon-time"></span> 
                            <?php echo $coupon['end'];?>
                            </p> 
                            <div class="well well-sm text-center coupon-item-code">
                                <strong class="coupon-item-code-label"><?php echo $coupon['code'];?></strong>
                            </div> 
                            <div class="row"> 
                                <div class="col-xs-12"> 
                                <a target="_blank" role="button" class="col-xs-12 btn btn-primary" rel="nofollow" href="<?php echo $coupon['url'];?>" data-coupon="<?php echo $coupon['deal'];?>">
                                Use Coupon »</a> 
                                </div> 
                            </div>
                            
                            
                        </div>
                    </div>
                </div>    
                
            <?php   
            }  
        } else {
          continue;  
        }
    }
    
   function set_total_coupons($db) {
        
        $counted = array_count_values(array_map(function($coupon){return $coupon['id'];}, $this->coupons));
        $sources = array_keys($counted);
        $sources_string = implode(",", $sources);

        
        $statement = $db->prepare("SELECT T2.id, T2.name, T2.ref
                                   FROM `sources` T2
                                   WHERE T2.id IN (". $sources_string .")
                                   ");	                                 
                               	
        $statement->execute();
        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
        
        foreach ($results As $key => $result) {
                    $id = $result['id'];
                    $results[$key]['total'] = $counted[$id];
        }

        $this->coupons_total = $results;
   
        $statement = null;	
        unset($statement);    
    }
    
    
    

    function get_total_coupons() {
        Global $baseURL;
        $coupons_total = $this->coupons_total; ?>
        
        
        <div class="list-group">
        <div class="list-group-item coupons-overview active" data-source="all"><span><strong>Show All</strong></span></div>
        <?php
        foreach ($coupons_total As $coupons_source) { ?>
            
            <div class="list-group-item coupons-overview" data-source="<?php echo $coupons_source['id'];?>">
                 <span><strong><?php echo $coupons_source['name'];?></strong></span>
                 <span class="badge"><?php echo $coupons_source['total'];?></span> 
            </div>
        <?php     
        } 
        ?>
        
        </div>
        
    <?php    
    }
    
    


    
}
?>


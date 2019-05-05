<?php
date_default_timezone_set('Europe/Amsterdam');
if ($ping789tt != "RduikjTT967") {
     exit('<h2>You cannot access this file directly</h2>');
}
 

use CI\Coupons;

$coupons  =  new Coupons;
$coupons->set_coupons($db->linode, $shopreview->shopInfo['source']); 


if (isset($shopreview->shopInfo['thread_id'])) {
    
    /*
     * Calculate Webshop Score 
     */
    
    $ratings =  $shopreview->getShopRating( $db->linode, $shopreview->shopInfo['thread_id'] );
    $total = array_sum(array_column($ratings, 'avg_rate'));
    $total_rating_avg = $total / 4;
    
    $reviews = $shopreview->getShopReviewCount( $db->linode, $shopreview->shopInfo['thread_id'] );
    
    define('SHOP_NAME', $shopreview->shopInfo['name']);
    define('SHOP_REF', $shopreview->shopInfo['ref']);
}


//$PJ_THREAD = 1; 
//$PJ_THEME = 'theme1';        
?>




     
<div class="page-header col-md-offset-3 col-md-6 row">
    

    
    <div class="col-md-9">
        <h1>
        <?php echo $shopreview->shopInfo['name'];?> <br /> 
            <small itemprop="description">
            Review & Coupons - Share your thoughts and experience about <?php echo $shopreview->shopInfo['name'];?>
            </small>
        </h1>
    </div>
    
    <div class="col-md-3 shop-review-logo">
        <a title="Take a look at <?php echo $shopreview->shopInfo['name'];?>" href="<?php echo $shopreview->shopInfo['url'];?>" target="_blank" rel="nofollow">
            <img  class="img-responsive img-rounded center-block" src="<?php echo $baseURL .'img/sources/big/'. $shopreview->shopInfo['ref'] .'_logo.png'; ?>">
        </a>   
    </div>
    
        
<div id="webshop-review-box" itemid="<?php echo BASE_URL;?>china-online-shops/<?php echo $shopreview->shopInfo['ref'];?>-review/" itemscope itemtype="http://schema.org/Organization">
<meta itemprop="image" content="<?php echo $baseURL .'img/sources/big/'. $shopreview->shopInfo['ref'] .'_logo.png';?>"/>
<meta itemprop="name" content="<?php echo $shopreview->shopInfo['name'];?>"/>
<meta itemprop="url" href="http://www.<?php echo $shopreview->shopInfo['ref'];?>.com"/>

    <div id="dbcontent-box" class="col-md-offset-3 col-md-6 shop-review-body">        

       
            <?php echo $shopreview->shopInfo['introduction'];?>
    
            <a title="Take a look at <?php echo $shopreview->shopInfo['name'];?>" href="<?php echo $shopreview->shopInfo['url'];?>">
                <img alt="<?php echo $shopreview->shopInfo['name'] . ' Homepage';?>" class="img-responsive img-rounded center-block" src="<?php echo $baseURL .'img/sources/shopreview/'. $shopreview->shopInfo['ref'] .'/'. $shopreview->shopInfo['mainscreen'];?>">
            </a>

        
        <div class="clearfix"></div>
           

            <h2 id="experience-with-<?php echo $shopreview->shopInfo['name'];?>">CompareImports Experience with <?php echo $shopreview->shopInfo['name'];?></h2>
            
            
            <?php echo $shopreview->shopInfo['ci_opinion'];?>
            
            <div class="clearfix"></div>
            
            <div class="row comparison" > 
                <div class="col-sm-6"> 
                    <div class="panel panel-success"> 
                        <div class="panel-heading"> <h4 class="panel-title">
                        <span style="font-size: 120%;" class="glyphicon glyphicon-plus" aria-hidden="true"></span> Pros</h4> 
                        </div> 
                        <div class="panel-body"> 
                        <ul><?php echo $shopreview->shopInfo['pros'];?></ul> 
                        </div> 
                    </div> 
                </div>  
            
                <div class="col-sm-6"> 
                    <div class="panel panel-danger"> 
                        <div class="panel-heading"> 
                        <h4 class="panel-title">
                        <span style="font-size: 120%;" class="glyphicon glyphicon-minus" aria-hidden="true"></span> Cons</h4> 
                        </div> <div class="panel-body"> 
                        <ul><?php echo $shopreview->shopInfo['cons'];?></ul> 
                        </div> 
                    </div> 
                </div> 
            </div>
            
            <div class="clearfix"></div>
            
            <?php 
                        if ( $total_rating_avg < 1.5 ) {
                            $mainRating = 'bad';
                        } 
                        elseif ( $total_rating_avg < 2.5 ) {
                            $mainRating = 'suboptimal';
                        }
                        elseif ( $total_rating_avg < 4 ) {
                            $mainRating = 'acceptable';
                        }
                        else {
                            $mainRating = 'good';
                        }
            ?>
            
            
            <h3>
                User Reviews Score
            </h3>
            
            <div class="container-fluid shop_score_card"  itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                
                <meta itemprop="reviewCount" content="<?php echo $reviews['totalReviews'];?>"/>
                <meta itemprop="ratingValue" content="<?php echo number_format($total_rating_avg, 1);?>"/>
                <meta itemprop="bestRating" content="5.0"/>
                <meta itemprop="worstRating" content="0"/>
                
                <div class="col-md-offset-1 col-md-5" >
                    <div class="shop_total_container container-fluid text-center <?php echo $mainRating;?>_border">
                        <span class="shop_total_score">
                            <?php echo number_format($total_rating_avg, 1);?>
                        </span>
                        <span class="shop_total_divided">
                            / 5.0
                        </span>
                    </div>
                    <div class="container-fluid text-center">
                        <span class="shop_total_reviews">
                            <?php echo $reviews['totalReviews'];?> Review(s)
                        </span>
                    </div>
                    <div  class="shop_review_button_container text-center">
                        <a href="#review-form" type="button" class="btn btn-lg btn-default">WRITE A REVIEW</a>
                    </div>
                </div>
                
                
                  
            
                
                
                
                <div class="col-md-offset-1 col-md-5">

                <?php

                    foreach ( $ratings as $rating) { 

                        if ($rating['avg_rate'] < 1.5 ) {
                            $class = 'bad';
                        } 
                        elseif ( $rating['avg_rate'] < 2.5 ) {
                            $class = 'suboptimal';
                        }
                        elseif ( $rating['avg_rate'] < 4 ) {
                            $class = 'acceptable';
                        }
                        else {
                            $class = 'good';
                        }

                        ?>
                    <span class="shop_rating_title"><?php echo $rating['title'];?></span>
                        <div class="progress">
                            <div class="progress-bar <?php echo $class;?>" role="progressbar" aria-valuenow="<?php echo ($rating['avg_rate'] / 5 ) * 100;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($rating['avg_rate'] / 5 ) * 100;?>%;">
                                <span class="sr-only"><?php echo ($rating['avg_rate'] / 5 ) * 100; ?>% Complete</span>
                            </div>
                        </div>    
                    <span class="shop_rating_score pull-right <?php echo $class;?>_border"><?php echo number_format($rating['avg_rate'], 1);?></span>
                    <?php }

                ?>
                </div>
            </div>


            <h3 id="alternative-shops"><?php echo $shopreview->shopInfo['name'];?> Alternatives</h3>
            <div class="container-fluid">
            <?php 
                foreach ($shopreview->shopInfo['alternatives'] as $alternative_shop) { ?>
            
                    <div class="col-ms-4 col-sm-4 col-md-4 col-lg-4 webshop-item">
                            <div class="thumbnail alternative_shop_thumbnail">
                                    <a href="<?php echo $baseURL .'china-online-shops/'. $alternative_shop['ref'] .'-review/';?>" title="Review about <?php echo $alternative_shop['name'];?>">
                                        <img alt="<?php echo $alternative_shop['name'];?> logo" src="<?php echo BASE_URL;?>img/sources/big/<?php echo $alternative_shop['ref'];?>_logo.png">
                                    </a>
                                    <hr>

                                    <div class="container-fluid">
                                        <p><?php echo $alternative_shop['slogan'];?></p>
                                    </div>

                            </div>
                       </div>

                <?php }
            
            ?>
            </div>
            
                
            <?php if ( $shopreview->shopInfo['payment'] !== "" ) { ?>
                        <h3 id="payment-methods">
                            Payment Methods
                        </h3>

                        <div>
                            <?php echo $shopreview->shopInfo['payment'];?> 
                        </div>
            <?php } ?>

            <?php if ( $shopreview->shopInfo['shipping_delivery'] !== "" ) { ?>

                        <h3 id="shipping-and-delivery">
                            Shipping & Delivery
                        </h3>

                        <div>
                            <?php echo $shopreview->shopInfo['shipping_delivery'];?>
                        </div>
            <?php } ?>

            <?php if ( $shopreview->shopInfo['return_warranty'] !== "" ) { ?>
                        <h3 id="returning-and-warranty">
                            Returning & Warranty
                        </h3>

                        <div>
                            <?php echo $shopreview->shopInfo['return_warranty'];?>
                        </div>
            <?php } ?>

            <?php if ( $shopreview->shopInfo['customs'] !== "" ) { ?>               
                        <h3 id="customs-and-tax">
                           Customs & Tax
                        </h3>


                        <div>
                         <?php echo $shopreview->shopInfo['customs'];?>
                        </div>
            <?php } ?>

            <?php if ( $shopreview->shopInfo['point_system'] !== "" ) { ?>
                        <h3 id="point-system">
                            Point System
                        </h3>

                        <div>    
                            <?php echo $shopreview->shopInfo['point_system'];?> 
                        </div>
            <?php } ?>

            <?php if ( $shopreview->shopInfo['warehouses'] !== "" ) { ?>
                        <h3 id="warehouses">
                            Warehouses
                        </h3>

                    <div id="warehouses-content">               
                            <?php echo $shopreview->shopInfo['warehouses'];?>
                    </div>  
            <?php } ?>

            <?php if ( $shopreview->shopInfo['special_feat'] !== "" ) { ?>
                    <h3 id="special_feat">
                        Special Features
                    </h3>

                     <div id="special_feat-content">
                            <?php echo $shopreview->shopInfo['special_feat'];?>
                    </div>

            <?php } ?>                
            
            
            <h3 id="conclusion">Conclusion</h3>
            <?php echo $shopreview->shopInfo['conclusion'];?>
            

            <div class="container-fluid shop_score_card">
                <div class="col-md-offset-1 col-md-5" >
                    <div class="shop_total_container container-fluid text-center <?php echo $mainRating;?>_border">
                        <span class="shop_total_score">
                            <?php echo number_format($total_rating_avg, 1);?>
                        </span>
                        <span class="shop_total_divided">
                            / 5.0
                        </span>
                    </div>
                    <div class="container-fluid text-center">
                        <span class="shop_total_reviews">
                            <?php echo $reviews['totalReviews'];?> Review(s)
                        </span>
                    </div>
                    <div  class="shop_review_button_container container-fluid text-center">
                        <a href="#review-form" type="button" class="btn btn-lg btn-default">WRITE A REVIEW</a>
                    </div>
                </div>

                <div class="col-md-offset-1 col-md-5">
                    
                    <div class="container-fluid shop-review-logo" style="margin-top: 20px;
                                                                        margin-bottom: 20px;">
                        <a title="Take a look at <?php echo $shopreview->shopInfo['name'];?>" href="<?php echo $shopreview->shopInfo['url'];?>" target="_blank" rel="nofollow">
                            <img class="img-responsive img-rounded center-block" src="<?php echo $baseURL .'img/sources/big/'. $shopreview->shopInfo['ref'] .'_logo.png'; ?>">
                        </a>   
                    </div>
                    
                    <a href="<?php echo $shopreview->shopInfo['url'];?>" title="Take a look at <?php echo $shopreview->shopInfo['name'];?>" target="_blank" class="btn btn-primary btn-lg btn-block" role="button" rel="nofollow" data-shop="<?php echo $shopreview->shopInfo['name'];?>" style="    font-weight: 600;
                        font-size: 1.6em;
                        color: hsla(0, 0%, 100%, 0.92);">
                        Go to shop »
                    </a>
                </div>
            </div>
                

            
            <div class="panel panel-default"> 
                <div class="panel-heading" role="tab" id="coupons-heading">
                    <h3 id="coupons" class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#coupon-content" aria-expanded="false" aria-controls="coupon-content" class="center-block text-center webshop-content-panel"><?php echo $shopreview->shopInfo['name'];?> Coupons</a>
                     </h3>
                </div>
                
                <div id="coupon-content" class="panel-collapse collapse" role="tabpanel" aria-labelledby="coupon-heading">
                    <div class="panel-body">                    

                    <?php  
                        if (!empty($coupons->coupons)) {
                            $coupons->get_coupons();   
                        } else {
                            echo    'Sorry, We don\'t have any coupons available today.';
                        }
                    ?>
                    </div>
                </div>  
            </div>     
            
            


       
    </div>   <!-- DBcontentBox --> 


    <div class="tagcloud01 col-md-offset-3 col-md-6">
        <h3><strong>All China Shops</strong><br><small>Read Everything You Need To Know About China Shops!</small></h3>
        <ul>
        <?php 

        foreach ($shopreview->shopsWithReview As $shop) {
            echo '<li><a href="'. $baseURL . 'china-online-shops/' . $shop['ref'] .'-review/"  title="'. $shop['name'] .' Review and Info">';
                echo $shop['name'] .' Review';           
            echo '</a></li>';
        }   

        ?>
        </ul>
    </div>
    <?php 
    if (isset($shopreview->shopInfo['thread_id'])) { ?>
    
    <div id="review-box" class="col-md-offset-3 col-md-6"> 
        <h3 id="reviews">User Reviews</h3>
        {PR_LOAD}
    </div> 
    <?php } ?>
           

</div>    
 
<script type="text/javascript" src="//static.addtoany.com/menu/page.js"></script> 
 


<?php 
if (isset($shopreview->shopInfo['thread_id'])) {
    //include '/home/CI/public_html/_CI_Dev/comments/app/views/pjLayouts/pjActionLoad.php'; 
}
?>
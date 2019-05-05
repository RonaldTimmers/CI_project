<?php
namespace CI;

date_default_timezone_set('UTC');

use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Connection;
use Foolz\SphinxQL\Helper;
// use Foolz\SphinxQL\Drivers\Pdo\Connection;

use CI\Controllers\ThumbsController;

class Product {
    
       
    public $productinfo;
    public $similairproductsinfo;
    public $similairproducts;
   
    protected $product_ids;
    protected $total_product_ids;
    
    protected $main_product_id;
    protected $main_product_price;
    protected $main_product_source;
    protected $main_product_title;
    
    public $source_coupons;
    
    /* This Function sets the $productinfo variable with the main product features */
    
    function set_productinfo ($db, $pid = null) {
        global $baseURL, $staticURL; 
        
        if (isset($pid)) {
            $product = (int)$pid;
        }
        
        if (isset($_GET['pid'])) {
            $product = $_GET['pid'];
        }
        
        if (isset($product)) {
                            
            if (!is_numeric($product)) {
                $product = 0;
            }
           

            $queryString = "SELECT T1.id, T1.active, T1.stock, T1.title, T1.title_url, T12.img, T12.img2, T12.img3, T1.thumb_path, T1.thumb_path_2, T1.thumb_path_3, T1.thumbs_extra,
                                    T1.price, T1.list, T1.prev_price, T1.stars, T1.reviews, T1.source, T1.description, T1.updated, 
                                    T1.our_cat1, T1.our_cat2, T1.our_cat3, T1.our_sub1, T1.our_sub2, T1.our_sub3, T1.our_subsub1, T1.our_subsub2, T1.our_subsub3, 
                                    T2.name As `cat1`, T2.ref As `cat1_ref` , T3.name As `cat2`, T3.ref As `cat2_ref`, T4.name As `cat3`, T4.ref As `cat3_ref`, 
                                    T5.name As `sub1`, T5.ref As `sub1_ref`, T6.name As `sub2`, T6.ref As `sub2_ref`, T7.name As `sub3`, T7.ref As `sub3_ref`, 
                                    T8.name As `subsub1`, T8.ref As `subsub1_ref`, T9.name As `subsub2`, T9.ref As `subsub2_ref`, T10.name As `subsub3`, T10.ref As `subsub3_ref`, 
                                    T11.logo As `source_logo`, T11.name As `source_name`, T11.ref As `source_ref`, T11.shipping_costs, T11.wholesale, T13.review, T13.slogan, T13.productamount

            FROM `product_details` T1 
            LEFT JOIN `categories` T2 ON T2.id = T1.our_cat1
            LEFT JOIN `categories` T3 ON T3.id = T1.our_cat2
            LEFT JOIN `categories` T4 ON T4.id = T1.our_cat3
            LEFT JOIN `subcats` T5 ON T5.id = T1.our_sub1
            LEFT JOIN `subcats` T6 ON T6.id = T1.our_sub2
            LEFT JOIN `subcats` T7 ON T7.id = T1.our_sub3
            LEFT JOIN `subsubcats` T8 ON T8.id = T1.our_subsub1
            LEFT JOIN `subsubcats` T9 ON T9.id = T1.our_subsub2
            LEFT JOIN `subsubcats` T10 ON T10.id = T1.our_subsub3
            LEFT JOIN `sources` T11 ON T11.id = T1.source 
            LEFT JOIN `shop_reviews` T13 ON T13.source = T1.source 
            LEFT JOIN `product_urls` T12 ON T1.id = T12.pid 
            WHERE T1.id = :id AND T1.active = 1 AND T11.current = 1";
            
            /* AVG RATE QUERY SHOP
                                                (SELECT AVG(TRRT.rate) 
                                                    FROM `comments_phpreview_ratings_rating_types` AS TRRT 
                                                    WHERE TRRT.rating_id IN 
                                                        (SELECT TR.id 
                                                         FROM `comments_phpreview_ratings` AS `TR` 
                                                         WHERE TR.status='T' AND TR.thread_id = ( 	SELECT thread_id 
                                                                                                    FROM `comments_sources_threads`
                                                                                                    WHERE source_id = T11.id
                                                                                                )
                                                
                                                        ) 
                                    ) AS avg_rate
            */

            $statement = $db->prepare($queryString);
            $statement->bindValue(':id', $product, \PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(\PDO::FETCH_ASSOC);

            $this->productinfo =  $result; 
            
            $statement = null;	
            unset($statement);
            
            /*
             * Get the available coupons for the specific source
             * coupons.class.php
             */

            $this->source_coupons = Coupons::set_source_coupons( $db, $this->productinfo['source'] );
           
            
            /* After getting the Product Info Get the Similair Products */
            
        }            
    }
    
    function get_productdetails ( $product, $pricehistorydates = null, $productcoupons = null, $modal = true ) {
        // This will output the template for the product/sku details modal or product page
        // This method will put the html context into an output buffer
        // In this case we don't have to echo. 
        
        
        
        // TO DO: Create an seperate function which can be used for thumbs and sku 
        // First, just like with the thumbs.class.php get the thumb path if exist.
        // Create an image array to loop through
        
        $images = array();
        
        if ($product['thumbs_extra'] == 0) {
            $thumb = STATIC_URL . $product['thumb_path'];
            $origImage = $product['img'];
            $images[1]['thumb'] = $thumb;  
            $images[1]['orig'] = $origImage;             
        } else {
            for ($i=1; $i <= $product['thumbs_extra']; $i++) {
                if ($i == 1) {
                   $thumb = STATIC_URL . $product['thumb_path'];
                   $origImage = $product['img']; 
                }
                
                if ($i == 2) {
                    $thumb = STATIC_URL . $product['thumb_path_2'];
                    $origImage = $product['img2'];   
                }
                
                if ($i == 3) {
                    $thumb = STATIC_URL . $product['thumb_path_3'];
                    $origImage = $product['img3'];
                }
                if ($i > 3) {
                    break;
                }
                
                
                $images[$i]['thumb'] = $thumb;  
                $images[$i]['orig'] = $origImage; 
            }      
        }
   
        // Check if there is a discount and calculate it
        if ($product['price'] < $product['list']) {
            $percent  = round(($product['price'] / $product['list']), 2);
            $off = ( 1 - $percent ) * 100;
        }
        
        /*
         * Add prev_price code
         * round((1 - (history.price / (history.price - history.price_difference))) * 100, 0) As `discount` 
         */
        
        
        if ( $product['prev_price'] != Null ) {
            
            $difference = abs(round((1 - ( $product['price'] / ($product['price'] - ( $product['price'] - $product['prev_price'] ) ))) * 100, 0));
            
            
            $percent  = round(($product['prev_price'] / $product['price']), 2);
            
            $priceChangeStatus = ( 1 - $percent ) * 100 < 0 ? 'positive' : 'negative'; 
            
            if ( $priceChangeStatus == 'negative' ) {
                $difference = '+'. $difference; 
            }
            
        } 
        
        // Check if the product is in stock and create the right label
        $product_stock  = $product['stock'] == 0 ? '<span id="product-stock" class="label label-success">In Stock</span>' : '<span id="product-stock" class="label label-danger">Out of Stock</span>' ;  
        
        switch ($product['shipping_costs']) {
            case 0:
            $shipping = '<span class="label label-success" style="color: #fff;">Free Shipping</span>';
            break;
            case 1:
            $shipping = '<span class="label label-warning" style="color: #fff;">Free/Paid</span>';
            break;
            case 2:
            $shipping = '<span class="label label-danger" style="color: #fff;">Paid Shipping</span>';
            break;
            default:
            $shipping = '<span class="label label-default" style="color: #fff;">Unknown</span>';
            break; 
        }
        
        switch ($product['wholesale']) {
            case 0: 
            $wholesale = '<span class="label label-danger" style="color: #fff;">No Bulk Discount</span>';
            break;
            case 1: 
            $wholesale = '<span class="label label-success" style="color: #fff;">Yes Wholesale Discount</span>';
            break;
            default:
            $wholesale = '<span class="label label-default" style="color: #fff;">Unknown</span>';
            break;
                
        }
        
       
        /*
         * Get and Configure the Data From the Price History
         * We will use later on in the product details.
         */ 
        
        if (is_array($pricehistorydates)) {
            $prices = array_map(function ( $ar ) {return $ar['price'];}, $pricehistorydates);
            $dates = array_map(function ( $ar ) {return date("F j, Y", $ar['start']);}, $pricehistorydates);
            $minprice = min($prices);
            $maxprice = max($prices);
            $avgprice = array_sum($prices) / count($prices);



            /*
             * Check the value of the Current price
             * Current < Avg -> "Good Deal"
             * Current > Avg -> "Bad Deal" 
             * Min = Current -> "Best Price"
             * Max = Current -> "Worst Price"
             * 
             * NEED IN THIS ORDER BECAUSE OVERWRITE
             */
            if ($product['price'] < $avgprice) {
               $productPriceStatus = "<span class='badge badge-normal badge-large' data-placement='bottom' data-toggle='tooltip' title='We consider a product as a GOOD DEAL when the current price is cheaper than the known average price. Notice: This label is only valid for the price history of this specific product.'><span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span> Good Deal</span>"; 
            } 

            if ($product['price'] > $avgprice) {
              //$productPriceStatus = "<span class='badge badge-warning badge-large' data-placement='bottom' data-toggle='tooltip' title='We consider a product as a BAD DEAL when the current price is higher priced than the known average price. Notice: This label is only valid for the price history of this specific product.'><span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span> Bad Deal</span>"; 
                $productPriceStatus = "";
            } 

            if ($product['price'] == $minprice) {
               $productPriceStatus = "<span class='badge badge-success badge-large' data-placement='bottom' data-toggle='tooltip' title='We consider a product as the BEST PRICE when the current price is the cheapest known price. Notice: This label is only valid for the price history of this specific product.'><span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span> Best Price</span>"; 
            }

            if ($product['price'] == $maxprice) {
               // $productPriceStatus = "<span class='badge badge-danger badge-large' data-placement='bottom' data-toggle='tooltip' title='We consider a product as the WORST PRICE when the current price is the most expensive known price. Notice: This label is only valid for the price history of this specific product.'><span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span> Worst Price</span>"; 
                $productPriceStatus = "";
                
            } 

            $sortHighPrice = $pricehistorydates;
            $sortLowPrice = $pricehistorydates;

            usort($sortHighPrice, function ($a, $b) {
                return $b['price'] - $a['price'];
            });

            usort($sortLowPrice, function ($a, $b) {
                return $a['price'] - $b['price'];
            });   
        }
        



            
        // Start saving the actual HTML into the buffer. 
        // Which we can use later on.
        ob_start(); 
        
         // We fill the dataLayer for Marketing Purposes with the Needed Product data.
        ?>  
        <script>
        dataLayer.push({
            'page_type' : 'product',
            'product_ids': '<?php echo $product['id'];?>',
            'total_value': '<?php echo $product['price'];?>'
        });
        </script>
        
        <div class="product-details clearfix">
            
            <?php if ($modal == true) { ?>
            
                <!--- Remove Breadcrumb in Modal
                <div class=""><?php //$this->get_product_breadcrumb($product); ?></div>
                -->
                
                <button id="product-close" class="btn btn-danger pull-right" data-dismiss="modal" >
                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                </button>
            
            <?php } ?>
            
            <div class="container-fluid" id="product-info-container" itemid="https://<?php echo $_SERVER['HTTP_HOST'] . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>" itemscope itemtype="http://schema.org/Product">
                
                <!-- schema.org Data! -->
                
                <meta itemprop="name" content="<?php echo htmlspecialchars($product['title']);?>">
    
                <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <?php if ($product['stock'] == 1) { ?>
                        <link itemprop="availability" href="http://schema.org/OutOfStock"/>
                    <?php } else { ?>
                        <link itemprop="availability" href="http://schema.org/InStock"/>
                    <?php } ?>
                    
                    <link itemprop="itemCondition" href="http://schema.org/NewCondition"/> 
                    <meta itemprop="priceCurrency" content="USD">
                    <meta itemprop="price" content="<?php echo $product['price'];?>">
                    
                    <div itemprop="seller" itemscope itemtype="http://schema.org/Organization">
                        <meta itemprop="name" content="<?php echo $product['source_name'];?>"> 
                    </div> 
                    
                    
                </div>
                
                
                
                <div id="product-images-container" class="col-sm-5">
                    
                    <?php 
                        if ( DEVICE_TYPE == 'phone' or  DEVICE_TYPE == 'tablet') {
                            
                            /*
                             * In BrandCaroussel Need to Replace
                             */
                            ImageGallery::productImageGallery( $images ); 
                            
                        } else { ?>
                            
                                <img itemprop="image" class="product-image img-responsive img-rounded center-block" src="<?php echo $product['img'];?>"  data-magnify-src="<?php echo $product['img'];?>" alt="<?php echo htmlspecialchars($product['title']);?>">
          
                    
                            <ul id="product-images">
                                    <?php 
                                        foreach ($images as $image) {
                                            echo '
                                            <li class="col-xs-4">
                                                <img class="productpage-thumb img-responsive img-rounded" src="'. $image['thumb'] .'" data-shop-src="'. $image['orig'] .'">
                                            </li>';
                                        }   
                                    
                                    ?>
                            </ul>
                            
                        <?php }
                    ?>
                    
                                    
                </div>
                   
                <?php if ( DEVICE_TYPE == 'phone' ) { ?> 
                
                    <?php if ($product['stock'] == 1) { ?>
                        <div id="product-stock" style="margin-top:10px;">
                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true" style="font-size: 1.2em; font-weight: 600; color: red;"></span>
                            <span style="font-size: 1.2em; font-weight: 600; color: red;">According to our knowledge the product is currently out of stock.</span>
                        </div>
                    <?php } ?>
                
                    <div id="product-button-container">
                        
                        
                        <a id="product-buy" title="See More Details at <?php echo $product['source_name'];?>" class="btn btn-success btn-block" target="_blank" rel="nofollow" href="<?php echo BASE_URL;?>goto/<?php echo $product['id'];?>/">
                            <?php echo $product['source_name'];?>.com <span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span>    
                        </a>       
                        
                        <!-- Look at <?php //echo $product['source_name'];?>.com<span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span> -->
                        
                        <!--- Promotional Banner 
                            
                        <?php if ($product['source'] == 4 ) { ?>
                        <a title="<?php //echo $product['source_name'];?> 11th Anniversary!" class="shop-promotion col-xs-8 col-xs-offset-2" target="_blank" rel="nofollow" href="https://www.banggood.com/11annv/11annv.html?p=5!141510275320121293&utm_source=bbs&utm_medium=CI&utm_content=zhangruihua">
                            <img class="img-responsive img-rounded center-block" src="<?php //echo BASE_URL .'img/sources/promotions/banggood/banggood-11anni.gif';?>"  alt="Banggood 11th Anniversary Promotion 7 sep - 9 sep - 72 hours">
                        </a>  
                        <?php } ?>

                         Promotional Banner END -->

                        
                    </div>
                     <div class="clearfix"></div>
                
                <?php } ?>     
                
                    
                <div id="product-price-container" class="col-sm-7">    
                    <?php if ( DEVICE_TYPE == 'phone' ) { ?> 

                            <div id="product-price-wrapper" class="row container-fluid">

                                <span id="product-price"><a target="_blank" rel="nofollow" href="<?php echo BASE_URL;?>goto/<?php echo $product['id'];?>/">$<?php echo $product['price'];?></a></span>
                                <?php if ( isset( $difference ) ) { echo '<span id="product-price-discount" class="'. $priceChangeStatus .'">'. $difference .'%</span>';} ?>
                            </div>
                            
                            <?php if ($product['reviews'] > 0) { ?>
                                <hr />
                                <div class="row container-fluid rating-wrapper" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">

                                        <a target="_blank" rel="nofollow" href="<?php echo BASE_URL;?>goto/<?php echo $product['id'];?>/">
                                            <div id="product-rating" class="pull-left">
                                                <div id="product-star-<?php echo $product['stars'];?>"></div>
                                            </div>
                                            <meta itemprop="worstRating" content="0" />
                                            <meta itemprop="bestRating" content="5" />
                                            <meta itemprop="ratingValue" content="<?php echo $product['stars'];?>" />
                                            <meta itemprop="ratingCount" content="<?php echo $product['reviews'];?>" />
                                            <div id="product-review">( <?php echo $product['reviews'];?> Reviews )</div>
                                        </a>

                                 </div>
                            <?php } ?>
                        
                        
                        
                            <div class="row row-m-t container-fluid">
                                <span id="update-time"  class="hidden-xs"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> Last Checked: <b><?php echo date("F j, Y", $product['updated']) .' ';?></b> </span> 
                            </div>
                    <?php } ?>   
                
                
                
                    <div class="title-wrapper">
                        <a target="_blank" rel="nofollow" href="<?php echo BASE_URL;?>goto/<?php echo $product['id'];?>/">
                            <h1 id="product-title">
                                <?php echo $product['title'];?>
                            </h1>
                        </a>
                    </div>
                    
                    <div id="product-social-wrapper">
                        <!-- AddToAny BEGIN -->
                        <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                            <a class="a2a_dd" href="https://www.addtoany.com/share"></a>
                            <a class="a2a_button_facebook"></a>
                            <a class="a2a_button_twitter"></a>
                            <a class="a2a_button_google_plus"></a>
                            <a class="a2a_button_whatsapp"></a>
                            <a class="a2a_button_email"></a>
                            
                        </div>
                        <!-- AddToAny END -->   
                        <span id="addthis-text"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span> Share This Deal With Friends!</span>
                    </div>
                                 

                    
                    <hr />
                    
                    
                    <?php if ( DEVICE_TYPE != 'phone' ) {  ?> 
                    
                        <div> 
                            <div id="product-price-wrapper" class="row container-fluid">


                                <span id="product-price"><a target="_blank" rel="nofollow" href="<?php echo BASE_URL;?>goto/<?php echo $product['id'];?>/">$<?php echo $product['price'];?></a></span>
                                <?php if ( isset( $difference ) ) { echo '<span id="product-price-discount" class="'. $priceChangeStatus .'">'. $difference .'%</span>';} ?>
                                <span id="product-price-status" class="pull-right"><?php if ( isset($pricehistorydates) && sizeof($pricehistorydates) > 1 ) {echo $productPriceStatus;}?></span>
                            </div>
                            
                            
                            <?php if ( isset($product['prev_price']) && !empty($product['prev_price']) ) { ?>
                                <hr />
                                 <div class="row row-m-t container-fluid">                              
                                     <span id="product-prev-price"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Previous Price: <b>$<?php echo $product['prev_price'];?></b></span>  

                                 </div>
                            
                            <?php } ?>    
                            
                            
                            <div class="row row-m-t container-fluid">
                                <span id="update-time"  class="hidden-xs"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> Last Checked: <b><?php echo date("F j, Y", $product['updated']) .' ';?></b> </span> 
                            </div>
                            
                            <?php if ($product['reviews'] > 0) { ?>
                            <hr />
                            <div class="row container-fluid rating-wrapper" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                                
                                    <a target="_blank" rel="nofollow" href="<?php echo BASE_URL;?>goto/<?php echo $product['id'];?>/">
                                        <div id="product-rating" class="pull-left">
                                            <div id="product-star-<?php echo $product['stars'];?>"></div>
                                        </div>
                                        <meta itemprop="worstRating" content="0" />
                                        <meta itemprop="bestRating" content="5" />
                                        <meta itemprop="ratingValue" content="<?php echo $product['stars'];?>" />
                                        <meta itemprop="ratingCount" content="<?php echo $product['reviews'];?>" />
                                        <div id="product-review">( <?php echo $product['reviews'];?> Reviews )</div>
                                    </a>
                                
                             </div>
                            <?php } ?>

                            
                        </div>   
                    
                        <?php if ($product['stock'] == 1) { ?>
                            <hr />
                            <div id="product-stock" style="margin-top:10px;">
                                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true" style="font-size: 1.2em; font-weight: 600; color: red;"></span>
                                <span style="font-size: 1.2em; font-weight: 600; color: red;">According to our knowledge the product is currently out of stock.</span>
                            </div>
                        <?php } ?>
                        
                        <hr />
                        
                        <div id="product-button-container">
                            
                            <a id="product-buy" title="See More Details at <?php echo $product['source_name'];?>" class="btn btn-success btn-block" target="_blank" rel="nofollow" href="<?php echo BASE_URL;?>goto/<?php echo $product['id'];?>/">
                                <?php echo $product['source_name'];?>.com <span class="glyphicon glyphicon-chevron-right pull-right" aria-hidden="true"></span>      
                            </a>  
                            
                        </div>  
                            
                    
                    <?php } ?>      
       
                                                              
                        <div class='clearfix'></div>

                </div>
                
                <div class='clearfix'></div>
                
                
                <!-- Start Tab Navigation -->
                
                
                <ul id="product-info-tabs" class="nav nav-tabs" role="tablist">
                    <?php 
                    /* Check if priceHistory of Description Exist 
                     * If not Don't show the Tab
                     */
                    
                    $activeTab = 'aboutWebshop';
                    
                    if ( $product['description'] != "" && $product['description'] != " " ) { 
                        $activeTab = 'description';
                    }
                    
                    if ( isset( $pricehistorydates ) && sizeof($pricehistorydates) > 1 ) { 
                        $activeTab = 'priceHistory';
                    }
                    
                    /*
                     * Trigger the Right Tabs
                     */
                    
                    
                    if ( isset( $pricehistorydates ) && sizeof($pricehistorydates) > 1 ) { ?>
                        <li role="presentation" class="<?php echo $activeTab == 'priceHistory' ? 'active': '';?>"><a data-toggle="tab" href="#price-history"><span class="glyphicon glyphicon-flash" aria-hidden="true"></span> Price Tracker</a></li>
                    <?php } ?>
                    
                    <?php 
                    /* Check if Description of the Product Exist 
                     * If not Don't show the Tab
                     */
                    if ( $product['description'] != "" && $product['description'] != " " ) { ?>
                        <li role="presentation" class="<?php echo $activeTab == 'description' ? 'active': '';?>"><a data-toggle="tab" href="#description"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Product Description</a></li>
                    <?php } ?>  
                    
                    <li role="presentation" class="<?php echo $activeTab == 'aboutWebshop' ? 'active': '';?>"><a data-toggle="tab" href="#about-webshop"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> About <?php echo $product['source_name'];?></a></li>
                    
                    <?php 
                    /* Check if the source got category coupons
                     * If not Don't show the Tab
                     */
                    if ( !empty( $productcoupons ) ) { ?>
                        <li role="presentation"><a data-toggle="tab" href="#coupons-webshop"><span class="glyphicon glyphicon-tags" aria-hidden="true"></span>  Coupons</a></li>
                    <?php } ?>  
                    
                    
                    
                </ul>
                
                
                <?php // if ($product['source'] == 66 | $product['source'] == 28 | $product['source'] == 4 | $product['source'] == 2 | $product['source'] == 6 | $product['source'] == 25 | $product['source'] == 29) { ?>
                    <div class="tab-content row">
                         
                        
                        <div id="coupons-webshop" class="tab-pane fade" role="tabpanel">
                            <div id="coupon-container">
                            <?php
                            
                            if ( !empty( $productcoupons ) ) { 
                            
                                foreach  ( $productcoupons As $coupon) {

                                    echo '
                                    <div class="coupon-row-item clearfix">
                                        <div class="col-sm-2 col-xs-3 benefit text-center">'. $coupon['benefit'] .'</div>

                                        <div class="col-sm-6 col-xs-9 col-md-5 col-lg-6 title text-center">
                                           <a target="_blank" rel="nofollow" href="'. BASE_URL .'goto/'. $product['id'] .'/" title="'. $coupon['deal'] .'">'. $coupon['deal'] .'</a>          	                     
                                        </div>

                                        <div class="col-sm-3 col-xs-12 code">
                                           <a target="_blank" rel="nofollow" href="'. BASE_URL .'goto/'. $product['id'] .'/" class="get-coupon-code" data-toggle="collapse" data-target=".cover" data-clipboard-text="'. $coupon['code'] .'">
                                              <small>'. $coupon['code'] .'</small>
                                              <span class="cover collapse in">
                                                 <span class="hidden-sm hidden-md">Get Coupon Code</span>
                                                 <span class="visible-sm visible-md">Get Coupon</span>
                                              </span>
                                           </a>
                                           <div class="time-counter-container text-center"><span class="glyphicon glyphicon-time" aria-hidden="true"></span><span> '. $coupon['end'] .'</span></div>
                                        </div>
                                    </div>';
                                }      
                            }
                                ?>
                            </div>
                        </div>
                        
                       
                        
                        <?php 
                        /* Check if Description of the Product Exist 
                         * If not Don't show the Tab
                         */
                        if ( $product['description'] != "" && $product['description'] != " " ) { ?> 
                        
                        <!-- Description Tab -->
                        
                        <div id="description" class="tab-pane fade in <?php echo $activeTab == 'description' ? 'active': '';?>" role="tabpanel">
                            <?php echo $product['description']; ?>
                        </div>
                        <?php } ?>  
                        
                        
                        <!-- Price History Tab -->

                        <div id="price-history" class="tab-pane fade in <?php echo $activeTab == 'priceHistory' ? 'active': '';?>" role="tabpanel">
                            
                            
                        <?php 
                        /*
                         * Only Show the Chart and Table if we have more than 1 price in the database
                         * 
                         */

                        if ( isset( $pricehistorydates ) && sizeof($pricehistorydates) > 1 ) {

                        ?>
                            
                            
                            <div class="row container-fluid">
                                <div class="chart-container" style="position: relative; height:40vh; width:98%">
                                    <canvas id="price-history-chart" ></canvas>
                                </div>
                            </div>
                             
                            <hr />
                            
                            
                            <div class="row container-fluid">    
                                <div class="col-md-7">
                                    
                                    <h3><?php echo $product['source_name'];?> Price History</h3>
                                    <table>
                                    <tr><th>Type</th><th>Price</th><th>Date</th></tr>
                                    <?php
                                    echo '<tr><td>Current Price</td><td>$'. $pricehistorydates[0]['price'] .'</td><td>'. date("F j, Y", $pricehistorydates[0]['start']) .'</td></tr>';
                                    echo '<tr><td style="color: red;">Highest Price</td><td style="color: red;">$'. $sortHighPrice[0]['price'] .'</td><td style="color: red;">'. date("F j, Y", $sortHighPrice[0]['start']) .'</td></tr>';
                                    echo '<tr><td style="color: green;">Lowest Price</td><td  style="color: green;">$'. $sortLowPrice[0]['price'] .'</td><td  style="color: green;">'. date("F j, Y", $sortLowPrice[0]['start']) .'</td></tr>';
                                    echo '<tr><td style="font-weight: bold;">Average Price</td><td style="font-weight: bold;">$'. number_format($avgprice, 2) .'</td><td>-</td></tr>';
                                    ?> 
                                    </table>
                                </div>
                                
                                <div class="col-md-5">
                                    <h3>Latest 5 Price Changes</h3>
                                   
                                    <table>
                                        <tr><th>Date</th><th>Price</th></tr>
                                        
                                    
                                    <?php
                                    
                                    
                                    $count = 0;
                                    
                                    
                                    foreach ( $pricehistorydates As $pricehistorydate ) {
                                        echo '<tr>';
                                        if ($count < 5) {
                                            if ( $minprice == $pricehistorydate['price'] ) {
                                                $style = 'style="color: green;"';
                                            } elseif ( $maxprice == $pricehistorydate['price'] ) {
                                                $style = 'style="color: red;"';
                                            } else { $style = ''; }

                                            echo '<td>'. date("F j, Y", $pricehistorydate['start']) .'</td><td>$'. $pricehistorydate['price'] .'</td>';
                                        }
                                        echo '</tr>';
                                        
                                        $count++;
                                    }   
                                    
                                    
                                ?>
                                    </table>
                                </div>
                            </div> 
                            
                            <?php 
                            } 

                            /*
                             * If we have just 1 price in the database
                             */

                            else {
                                echo 'There is no known change in price since the first price for this product.';
                            }
                            ?>
                            
                            
                        </div>
                        
      
                        
                        <!-- About Webshop Tab -->
                        
                        <div id="about-webshop" class="tab-pane fade in <?php echo $activeTab == 'aboutWebshop' ? 'active': '';?>" role="tabpanel">
                                    
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-center">
                                        <img alt="<?php echo $product['source_name'] . ' logo';?>" src="<?php echo BASE_URL .'img/sources/big/'. $product['source_ref'] .'_logo.png'; ?>">
                                    </div>
                                    <hr />
                                    <div id="shop-quote" class="text-center">
                                        <p>"<?php echo $product['slogan'];?>"</p>
                                    </div>
                                </div>    
                                        
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="col-md-12">
                                        <p>Product Amount: <b><?php echo $product['productamount'];?> </b></p> 
                                    </div>
                                    <div class="col-md-12">
                                        <p>Shipping: <?php echo $shipping ;?> </p> 
                                    </div>
                                    <div class="col-md-12">
                                        <p>Wholesale: <?php echo $wholesale;?> </p> 
                                    </div>
                                    <?php if ($product['review'] == 1) { ?>

                                       <a href="<?php echo BASE_URL .'china-online-shops/'. $product['source_ref'] .'-review/';?>" >Read or Write a Review about <?php echo $product['source_name'];?></a>

                                    <?php } ?>

                                </div>
                            </div>
                        </div>
                       
                    </div>
                <?php // } ?>
                
                <!--- REMOVED CI SLOGAN
                <div id="product-ci-slogan-wrapper" class="col-md-12 hidden-xs">
                    <hr />
                    <div class="col-xs-4">
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span><span>Compare 20 China Online Shops</span>
                    </div>
                    <div class="col-xs-4">
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span><span>Real-time Searching on Price</span>
                    </div>
                    <div class="col-xs-4">
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span><span>Deals, Coupons, Brands, Reviews</span>
                    </div>
                </div>
                -->
                
            </div>
        </div>
        
    
        <div id="related-page-header">
            <h2>Related Products</h2>
        </div>
        
        <?php if ($modal == true) { ?>
            <div id="category-box" class="row container-fluid">
                <div class="modal-relatedProducts"></div>
            </div>
            
            <script>
                $('.product-thumb').on({
                    'mouseenter': function(){
                        var product = $(this).closest('div');
                        var newImage = $(this).attr('data-src');
                        product.find("img.product-image").attr('src', newImage);
                    }		
                });
                
            </script>
            
         <script defer type="text/javascript" src="//static.addtoany.com/menu/page.js"></script>    
        <?php } ?>



        
        
        
    <?php 
    
    // Return the above rendered HTML content to use in the index.php page
    return ob_get_clean(); 
    
    }    
    
    
    
    

    /* 
    
        This Function sets the $similairproducts variable with the similair products 
       Used IN:
       - Called within the set_productinfo()
       - relatedProductsHTML.call.php (AJAX Call)
       
    */
    public function set_similairproducts ( $db, $product_id, $product_source, $product_title, $product_price, $offset = 0 ) {
        
       

      
        /*  Start the SphinxClient class.
        **
        * MatchMode = Matching, is really a boolean yes/no. This document either matches this query, or it doesnt.
        * RankingMode = Ranking, is how to give a score to the matching documents, so that when sorting results by weight, the best ones come first. 
        *
        
        * Get the Product Price and set to Int
        * 
        */
        
        
        $this->main_product_id = (int) $product_id;
        $this->main_product_price = (float) $product_price;
        $this->main_product_source = $product_source;
        $this->main_product_title = $product_title;
        
        /* For Related products we Only use the Title of products 
        *  
        *
        */ 
        
        $searchphrase = $product_title;
        
        /* Test Input Function of relatedProductsHTML.call.php 
        * To DO: Create Basic function for stripping values
        *
        */
        
        $searchphrase = str_replace("  ", " ", $searchphrase);			        // Remove multiple adjacent spaces
        $searchphrase = trim($searchphrase, " ");						        // trim blank spaces at beginning and end
        //
        //Not Necessary when not working with User Input
        //$search_phrase = str_replace("\\", "*bcksl*", $search_phrase);	        // replace back slashes with custom replacement (rawurlencode encodement will cause bad links: error 404)
        //$searchphrase = str_replace("/", "*frwsl*", $search_phrase);		// replace slashes with custom replacement (rawurlencode encodement will cause bad links: error 404)
        $searchphrase = trim($searchphrase);
        $searchphrase = stripslashes($searchphrase);
        
        /* Removed This Part Because It's Letting the searchd server crashing
         * And it is not necessary -> will give wrong results
         */
        //$searchphrase = htmlspecialchars($searchphrase);
        $searchphrase = htmlspecialchars_decode($searchphrase, ENT_QUOTES);
        
        $searchphrase = str_replace(['<', '>', '\'', '"', '&'], ' ', $searchphrase);
        
        
        $search_array = explode(' ', $searchphrase);
        $search_array = array_map('strtolower', $search_array);
        
        
        

        
        /* Check if the words: for or part are in the title
        *  If not then exclude them from the related products
        *
        */ 
        
          
        

        
        //$searchphrase = implode(' ', $search_array);
        
        //$title_search = $importantsearchphrase .' '. $searchphrase;
        
        
         /*   Create and Exclude Words for Ranking List 
          * 
          */
        
        $exclude_ranking_words = ['original', 'sexy', 'new', 'authentic', 'presell', '-', '+', '/', 'and', 'with'];
        
        
        /*   Check Foreach exclude ranking word the search array
        * *  
          * 
          */
        
        foreach ($exclude_ranking_words as $exclude_word) {
            if  (in_array($exclude_word, $search_array)) {
                $pos = array_search($exclude_word, $search_array);
                unset($search_array[$pos]);
            }    
        }
        
        
        /*   Check if words exist in the Title which mention
        * *  a spare part. If the main product is not a part don't show other
         *  parts.
          * 
          */
        
        
        // $exclude_match_words = ['parts', 'for', 'spare'];
        $exclude_match_words = ['parts', 'spare'];
        $exclude = False;
        
        foreach ($exclude_match_words as $exclude_word) {
            if  (in_array($exclude_word, $search_array)) {
                $exclude = True;
                break;
            }  
        }
        
        if ($exclude) {
            $exclude_words = ' ';
        } else {
            // $exclude_words = ' -for -parts -spare ';
            $exclude_words = ' -parts -spare ';
        }
        
        
        
        
        /*
         *  How Many Keywords From the Title Do we Use 
         *  And Calculate the Keywoord Booster start
         */
        
        /*
         * Working Configuration
         * Used Keywords = 6
         * Part of String must match = 0.5
         */
        
        
        $used_keywords = 6;
        $string_match = 0.25; // Was 0.5 Before 23-8-17
        
        $keyword_boost = $used_keywords * 5; 
        $count = 0;
        
        foreach ($search_array As $key => $value) {
            if ( $count <= 1 ) {
               $important_search_array[$key] = $value .'^40'; 
            } 
            elseif ( $count >= 2 && $count <= 3 ) {
               $important_search_array[$key] = $value .'^20'; 
            } 
            elseif( $count >= 4 && $count <= $used_keywords - 1 ) {
                $important_search_array[$key] = $value .''; 
            }
            $keyword_boost-=5;
            ++$count;
        }
        
        $importantsearchphrase = implode(' ', $important_search_array);
        
 
        
        
        
        
        /*      Ceate a SphinxQL Connection object to use with SphinxQL
        * *    Move to Own Class
        */
        
        $max_matches = 48;
        
        if ( $offset < $max_matches ) {
            
        
            //$conn = new Connection();
            //$conn->setParams(array('host' => '23.239.9.21', 'port' => 9306));
            $conn = Db::connect_searchd();

            /*      Build the Required Template
            * *
            */

            $result = SphinxQL::create($conn)   ->select('id', 'title', 'title_url', 'thumb_path', 'thumb_path_2', 'thumb_path_3', 'thumbs_extra', 'price', 'list', 'stars', 'reviews', 'source', 'logo')
                                                //->select('*')
                                                ->from('productdetails')
                                                ->match('searchText', SphinxQL::expr('"'. $importantsearchphrase .'"/'. $string_match .' '. $exclude_words))
                                                // ->match('searchText', SphinxQL::expr('"'. $importantsearchphrase .'"/'. $string_match))
                                                ->where('stock', '=', 0)
                                                ->where('active', '=', 1)  
                                                ->where('current', '=', 1)
                                                ->where('source', '!=', 22) // Exclude Source Fasttech 
                                                ->where('id', '!=', $this->main_product_id)
                                                ->where('price', 'BETWEEN', array($product_price * 0.5, $product_price * 1.5))
                                                ->limit($offset, 12)
                                                ->orderBy('weight()', 'DESC')
                                                ->orderBy('price', 'ASC')
                                                //  ->option('ranker', SphinxQL::expr("expr('bm25 -(sum(min_hit_pos * 10))')"))
                                                //  ->option('ranker', 'bm25') // Werkt niet goed met Sphinx Config van 18-8-17
                                                //  ->option('ranker', SphinxQL::expr("expr('sum((4*lcs+2*(hit_count)*(tf_idf))*user_weight)*bm25')"))
                                                ->option('ranker', SphinxQL::expr("expr('sum((word_count+lcs)*100)+bm25')"))
                                                // ->option('ranker', 'sph04')
                                                ->option('max_matches', $max_matches)
                                                ->enqueue( Helper::create( $conn )->showMetaTotal() ) // this returns the object with SHOW META query prepared
                                                ->executeBatch();
            // $stmt->query('SHOW META');



            $this->similairproducts['thumbs'] = $result[0];

            if(isset($this->similairproducts['thumbs'])) {
                foreach ($this->similairproducts['thumbs'] as $i => $thumb) {
                    $this->similairproducts['thumbs'][$i]['URLtitle'] =  urlFriendly($thumb['title']);
                }
            }
        
        }
        
        //$this->set_match_ids($result[0]);
        //$this->get_match_ids($db, $this->main_product_id, $this->total_product_ids, $this->product_ids);
       
        
        
    }
    
    /*
     * DEPRECEATED 25-8-2017 
     * WE Now Get All The Needed Data in the SphinxQL query!
     * 
     
    
    function set_match_ids ($result) {
        // Now that we have the matches and weight, we are going to make a comma separated list of product_id's to pull additional details out of our DB.
        $count = 1;
        if (isset($result)) {
            foreach ($result AS $value) {
                    // else add it to comma seperated string
                    if ($count == 1) {
                        $product_ids = $value['id'];
                        
                    }
                    else {
                        $product_ids .= ',' . $value['id'];
                    }
                    $count++;
            }
        }
        
        $this->total_product_ids = $count - 1;
        $this->product_ids = $product_ids;
       
        
    }
    
    function get_match_ids ( $db, $product_id, $total_found, $product_ids, $offset = 0 ) {
        
        
        if ($total_found > 0) {
            // Here, we are using a MySQL IN clause to pull the product details from the DB for display.
            $statement =  $db->prepare('SELECT T1.id, T1.title, T1.thumb_path, T1.thumb_path_2, T1.thumb_path_3, T1.thumbs_extra, T1.price, T1.list, T1.stars, T1.reviews, T1.source, T2.logo
                                        FROM `product_details` T1 
                                        LEFT JOIN `sources` T2 ON T2.id = T1.source 
                                        WHERE 
                                        T1.id != '. $product_id .' AND
                                        T1.active = 1 AND 
                                        T2.current = 1 AND 
                                        T1.id IN (' . $product_ids . ') 
                                        ORDER BY FIELD(T1.id, ' . $product_ids . ')
                                        LIMIT '. $offset .', 8 '
                                        );

                      
            $statement->execute();
            $this->similairproducts['thumbs'] = $statement->fetchAll(\PDO::FETCH_ASSOC);
                        
        } 


        if(isset($this->similairproducts['thumbs'])) {
            foreach ($this->similairproducts['thumbs'] as $i => $thumb) {
                $this->similairproducts['thumbs'][$i]['URLtitle'] =  urlFriendly($thumb['title']);
            }
        }

        $statement = null;	
        unset($statement);
        $db = null;    
    }
    
     * */
     
    function set_productpricehistory ( $db, $product_id ) {
            $queryString = "SELECT T1.price, T1.start
                            FROM `product_price_history` T1 
                            WHERE T1.pid = :pid 
                            ORDER BY T1.start DESC
                            ";
            

            $statement = $db->prepare($queryString);
            $statement->bindValue(':pid', $product_id, \PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);  
            
            return $result;
    }
    
    function get_similairproducts ( $products ) {
        
        if (empty( $products )) {
            
            echo '<div class="cb"></div>
            <div class="alert alert-warning alert-dismissible fade in text-center" role="alert">
                <strong>Sorry</strong>, We don\'t have more results.
            </div>';
            
        } else {
            
        /*
        * Initiate Thumbs Controller
        * $mode, $searchKeywords, $secondQuery = 'false', $deviceType = null, $isSimilairThumbs = false, $options = array() 
        */
            
       
       /*
        * TO DO: COUPLE LOOSE THE THUMBCONTROLLER! 
        * 
        */
       
        $objectThumbs = ThumbsController::initThumbs( 'related-products', null, 'false', null, true, array() );
        
        $objectThumbs->thumbs = $products;
        

        // Configure and Format Thumbs -> Render as Last
        $objectThumbs->configureThumbs()->formatThumbsData()->renderThumbs();
        }   
    }
    
    function get_loadmore () {
        
        
        echo    '
                <div class="cb"></div>
                <hr class="load-more-divider" />
                ';
                /*
                <div class="container-fluid load-more-products">
                    <button class="btn btn-primary btn-lg center-block" onClick="ajax_call()">Load More  <span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span></button>
                </div>';
                */

        
        echo    '<script>
                window.main_product_id = '. json_encode( $this->main_product_id ) .'
                window.main_product_source = '. json_encode( $this->main_product_source ) .'
                window.main_product_title = '. json_encode( $this->main_product_title ) .'
                window.main_product_price = '. json_encode( $this->main_product_price ) .'
                </script>';  
        
        /*      
        echo    '<script>
                window.main_product_id = '. json_encode( $this->main_product_id ) .'
                window.product_ids = '. json_encode( $this->product_ids ) .'
                window.total_product_ids = '. json_encode( $this->total_product_ids ) .'
                </script>';  
         *
         */    
    }
    

    
    function get_product_breadcrumb ( $product ) {
        global $baseURL, $staticURL;  
        
        echo '<ol id="navigation-wrapper" class="breadcrumb dt-breadcrumb col-xs-12" itemscope itemtype="http://schema.org/BreadcrumbList">';
        echo '<li>
                <a href="'. $baseURL .'">
                <span>Home</span>
                </a>
            </li>';
        
        
        if (isset($product['cat1'])) {

                echo '
                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $product['cat1'] .'" href="'. $baseURL . $product['cat1_ref'] .'-1-'. $product['our_cat1'] .'/"><span itemprop="name">'. $product['cat1'] .'</span></a>
                <meta itemprop="position" content="1" />
                </li>';
                
                echo '
                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $product['sub1'] .'" href="'. $baseURL . $product['cat1_ref'] .'/'. $product['sub1_ref'] .'-2-'. $product['our_sub1'] .'/"><span itemprop="name">'. $product['sub1'] .'</span></a>
                <meta itemprop="position" content="2" />
                </li>';      
                
                echo '
                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $product['subsub1'] .'" href="'. $baseURL . $product['cat1_ref'] .'/'. $product['subsub1_ref'] .'-3-'. $product['our_subsub1'] .'/"><span itemprop="name">'. $product['subsub1'] .'</span></a>
                <meta itemprop="position" content="3" />
                </li>'; 
        }

        elseif (isset($product['cat2'])) {
                
                
                echo '
                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $product['cat2'] .'" href="'. $baseURL . $product['cat2_ref'] .'-1-'. $product['our_cat2'] .'/"><span itemprop="name">'. $product['cat2'] .'</span></a>
                <meta itemprop="position" content="1" />
                </li>';
                
                echo '
                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $product['sub2'] .'" href="'. $baseURL . $product['cat2_ref'] .'/'. $product['sub2_ref'] .'-2-'. $product['our_sub2'] .'/"><span itemprop="name">'. $product['sub2'] .'</span></a>
                <meta itemprop="position" content="2" />
                </li>';      
                
                echo '
                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $product['subsub2'] .'" href="'. $baseURL . $product['cat2_ref'] .'/'. $product['subsub2_ref'] .'-3-'. $product['our_subsub2'] .'/"><span itemprop="name">'. $product['subsub2'] .'</span></a>
                <meta itemprop="position" content="3" />
                </li>'; 
        }
        
       elseif (isset($product['cat3'])) {
                
                echo '
                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $product['cat3'] .'" href="'. $baseURL . $product['cat3_ref'] .'-1-'. $product['our_cat3'] .'/"><span itemprop="name">'. $product['cat3'] .'</span></a>
                <meta itemprop="position" content="1" />
                </li>';
                
                echo '
                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $product['sub3'] .'" href="'. $baseURL . $product['cat3_ref'] .'/'. $product['sub3_ref'] .'-2-'. $product['our_sub3'] .'/"><span itemprop="name">'. $product['sub3'] .'</span></a>
                <meta itemprop="position" content="2" />
                </li>';      
                
                echo '
                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemscope itemtype="http://schema.org/Thing" itemprop="item" title="'. $product['subsub3'] .'" href="'. $baseURL . $product['cat3_ref'] .'/'. $product['subsub3_ref'] .'-3-'. $product['our_subsub3'] .'/"><span itemprop="name">'. $product['subsub3'] .'</span></a>
                <meta itemprop="position" content="3" />
                </li>'; 
        }
        echo '</ol>';
  
    }
     
}
 

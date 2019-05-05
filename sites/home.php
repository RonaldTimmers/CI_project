<?php

namespace CI;

// use CI\BrandCarousel;



if (isset($_GET['shopbanner']) && !preg_match('/[^A-Za-z]/', $_GET['shopbanner'])) {
    $statement = $db->linode->prepare("SELECT `name`,`bigbanner`,`url` FROM `sources` WHERE `ref` = :ref");
    $statement->execute( Array(":ref" => $_GET['shopbanner']) );
    
    while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
            echo '<div id="shopbanner-box">
                    <a href="'. $row['url'] .'" rel="nofollow" title="Go to '. $row['name'] .' and shop!"><img id="shopbanner-img" alt="Go to '. $row['name'] .' and shop!" src="'. $baseURL .'img/sources/banners/'. $row['bigbanner'] .'" /></a>
            </div>';
    }
} else { 
?>
<div id="home_background"> 

</div>
 

<hr />
    <div id="product-ci-slogan-wrapper" class="container-fluid">
        <div class="col-xs-4">
            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span><span>Compare 20 China Online Shops</span>
        </div>
        <div class="col-xs-4">
            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span><span>Price Tracker and History</span>
        </div>
        <div class="col-xs-4">
            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span><span>Deals, Coupons, Brands, Reviews</span>
        </div>
    </div>
<hr />

<div class="brand-carousel container-fluid">
    <?php 
        // See brandcarousel.class.php
        BrandCarousel::get_shopsBanner($shops->activeShops);
    ?>
</div>

<?php 
$shops_list = $shopreview->setShopData($db->linode, $baseURL); 
$brands = $brandpage->setBrands($db->linode, $baseURL);
?>

<div id="home-toplists" class="row hidden-xs">
    <div id="toplist-wrapper" class="col-md-6"> 
        <h2 style="text-align: center; font-weight: 700;">Chinese Online Shops - Top 10</h2>
        <hr>
                    <ul class="container-fluid">
                        <?php 
                  
                        for ($i = 0; $i<10; $i++ ) { 
                        
                        switch ($shops_list[$i]['shipping_costs']) {
                            case 0:
                            $shipping = '<span class="label label-success">Free Shipping</span>';
                            break;
                            case 1:
                            $shipping = '<span class="label label-warning">Free/Paid</span>';
                            break;
                            case 2:
                            $shipping = '<span class="label label-danger">Paid Shipping</span>';
                            break;
                            default:
                            $shipping = '<span class="label label-default">Unknown</span>';
                            break; 
                        }
                        
                        ?>
                        <li class="list-item-group">   
                            <div class="col-xs-4"><span><?php echo ($i + 1). '. ';?><a href="<?php echo $baseURL .'china-online-shops/'. $shops_list[$i]['ref'];?>-review/" title="Read the <?php echo $shops_list[$i]['name']; ?> Review"><?php echo $shops_list[$i]['name']; ?></a></span></div>
                            <div class="col-xs-4"><div class="webshop-rating"><div id="product-star-<?php echo round($shops_list[$i]['avg_rate']); ?>"></div></div></div>
                            <div class="col-xs-4"><?php echo $shipping;?></div>
                        </li>
                        <?php 
                                    
                        } ?>  
                    </ul>
                    

                     <div class="cb"></div>
       
    </div>
    
    <div id="toplist-wrapper" class="col-md-6"> 
        <h2 style="text-align: center; font-weight: 700;">Brands from China</h2>
        <hr>
                        <ul class="col-xs-6">
                                 <?php  
                                    for ($i = 0; $i<10; $i++ ) { ?>
                                       <li class="list-item-group">   
                                            <div class="col-xs-12"><span><?php echo ($i + 1). '. ';?><a href="<?php echo $baseURL .'china-brands/'. $brands[$i]['ref'] .'/';?>" title="Take a Look at Chinese Brand <?php echo $brands[$i]['brand'];?>"><?php echo $brands[$i]['brand']; ?></a></span></div>
                                        </li>
                                    
                                   <?php }
                                 
                                 ?>
                        </ul>
            
                        <ul class="col-xs-6">
                                 <?php  
                                    for ($i = 10; $i<20; $i++ ) { ?>
                                       <li class="list-item-group">   
                                            <div class="col-xs-12"><span><?php echo ($i + 1). '. ';?><a href="<?php echo $baseURL .'china-brands/'. $brands[$i]['ref'] .'/';?>" title="Take a Look at Chinese Brand <?php echo $brands[$i]['brand'];?>"><?php echo $brands[$i]['brand']; ?></a></span></div>
                                        </li>
                                    
                                   <?php }
                                 
                                 ?>
                        </ul>
             <div class="cb"></div>
    </div>
</div>



<?php
}
?>

                

<div class="cb"></div>

<!--
<div id="blog-wrapper"> 
  <div id="blog-header" class="header"><h2>News/Reviews</h2></div> 
        <?php
            // $blogPreview = new blog();
            // $blogPreview->blogPreview($db->wp);
        ?>
  <!-- <div class="slider-prev" data-ng-click="prevBlog()"></div>
        <div class="slider-next" data-ng-click="nextBlog()"></div>  --> 
<!-- </div>
-->

<div class="cb"></div>


<div class="page-header">
  <h1>Find The Largest Price Decreases in China <br /><small>We Select Multiple Times a Day the Best Price Discount</small></h1>
</div>

<div class="price-history-box container-fluid">
<?php 

    $home_pricehistory = $_SERVER['DOCUMENT_ROOT'] .'/includes/html/home_pricehistory.html'; 
    include $home_pricehistory;

?>
</div>

<div class="brand-carousel container-fluid">
    <?php 
        // See brandcarousel.class.php
        BrandCarousel::get_shopsBanner($shops->otherShops);
    ?>
</div>

        

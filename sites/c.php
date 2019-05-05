<?php 
if ($ping789tt != "RduikjTT967") {
     exit('<h2>You cannot access this file directly</h2>');
}
 
use CI\Thumbs\SubcatThumbs; 
use CI\Controllers\ThumbsController; 

$subcatThumbs =  new SubcatThumbs();
$subcatThumbs->set_subcats($db->linode, $categoryinfo->cat); 


$objectTopThumbs = ThumbsController::initThumbs( 'category', $searchKeywords, 'false', DEVICE_TYPE, false, $options = array(
                                                                                                                'shops' => $shops->activeShops, 
                                                                                                                'catlevel' => $page007->p, 
                                                                                                                'catid' => $page007->refid, 
                                                                                                                'querytype' => $querytype ) 
                                                                                                                , 'top-products' );
$objectTopThumbs->setThumbData();


?>

<div class="row" id="category-header"><h1><?php echo $categoryinfo->cat['name'];?></h1></div>

<?php  
    $subcatThumbs->get_subcats($categoryinfo->cat); 
?>	

<div class="clearfix"></div>  

<div id="category-box" class="container-fluid row">
    <h3><strong>Top Discount Prices</strong><br><small>Find the best deals!</small></h3>
    
    <div id="top-products-carousel" class="owl-carousel owl-theme">
    <?php 
        // Configure and Format Thumbs -> Render as Last
        $objectTopThumbs->getThumbData()
                        ->configureThumbs()
                        ->formatThumbsData()
                        ->renderThumbs();    
    ?>
    </div>
</div>
    
    
    

	

	

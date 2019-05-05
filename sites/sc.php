<?php
    if ($ping789tt != "RduikjTT967") {
             exit('<h2>You cannot access this file directly</h2>');
    }   
    
   
    
    /*
     * TO DO: Must be Set in Configuration File
     */
    $develop = false;
    
    
    //use CI\SearchQL;    
    use CI\Controllers\ThumbsController; 
   
    
/*
 * Adding New Functionality 27-9-17
 * Filter Catgory on search term
 */

    /*
    * Get the Keywords
    * And remove not wanted characters
    * TODO CREATE SEPERATE CLASS OR FUNCTiON, ALSO used in INDEX and SEARCH
    * DEFINE KEYWORDS IF SET -> Action -> SC, WORDFILTER, SEARCH?
    */   
    
if ( isset($_GET['wordfilter']) ) {
    $keywords = $_GET['wordfilter'];							// get the keywords
    $keywords = rawurldecode($keywords);					// decode raw url string
    //$keywords = str_replace('-', '', $keywords);
    $keywords = str_replace("*frwsl*", "/", $keywords);		// transform code back to forwardslash
    $keywords = str_replace("*bcksl*", "\\", $keywords);	// transform code back to backslash
    $keywords = str_replace("  ", " ", $keywords);			// Remove multiple adjacent spaces
    $searchKeywords = trim($keywords, " ");						// trim blank spaces at beginning and end

   
    $querytype = 'wordfilter';
} 

else {
    
    if ( isset( $page007->description ) && $page007->description !== "" ) {
        $searchKeywords = $page007->description;
    } 
    else {
        if ( $page007->p == "p" ) {
            $searchKeywords = $categoryinfo->subcat['name'];
        } else {
            $searchKeywords = $categoryinfo->subsubcat['name'];
        }
        
    }
    
    $querytype = isset( $page007->description ) && $page007->description !== "" ? 'description' : 'name';
}
    
    /*
     * Initiate Thumbs Controller
     * $mode, $searchKeywords, $secondQuery = 'false', $deviceType = null, $isSimilairThumbs = false, $options = array() 
     */
     

   

    $objectTopThumbs = ThumbsController::initThumbs( 'category', $searchKeywords, 'false', DEVICE_TYPE, false, $options = array(
                                                                                                                    'shops' => $shops->activeShops, 
                                                                                                                    'catlevel' => $page007->p, 
                                                                                                                    'catid' => $page007->refid, 
                                                                                                                    'querytype' => $querytype ) 
                                                                                                                    , 'top-products' );
    

    
    
    $objectTopThumbs->setThumbData();
    
    

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
<hr/>
<div class="clearfix"></div>  

<div id="scroll-to-element"></div>


<a id="filter-dropdown-button" class="btn btn-block" role="button" data-toggle="collapse" href="#main-filter" aria-expanded="false" aria-controls="main-filter">
  Filters<br /> 
  <span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span> 
  <span class="glyphicon glyphicon-menu-up" aria-hidden="true"></span>
</a>

<!-- REACTJS DEVELOPMENT -->
<div id="render"></div>
<!-- REACTJS DEVELOPMENT -->


<h3><strong>Regular Products from
 <?php  if ( $page007->p == "p" ) {
            echo $categoryinfo->subcat['name'];
        } else {
            echo $categoryinfo->subsubcat['name'];
        }
        ?></strong></h3>

<div class="clearfix"></div>  

<div id="category-box" class="main-product-thumbs container-fluid row">

    <?php
        if ($develop == true) { ?>
            <div class="alert alert-danger" role="alert" >Sorry, we are working on the engine at the moment. Thanks for your patience.</div>
    <?php } else {
    
    $objectThumbs = ThumbsController::initThumbs( 
        'category', 
        $searchKeywords,
        'false',
        DEVICE_TYPE, 
        false, 
        $options = array(
            'shops' => $shops->activeShops, 
            'catlevel' => $page007->p, 
            'catid' => $page007->refid, 
            'querytype' => $querytype) 
        );
    $objectThumbs->setThumbData();

    // Configure and Format Thumbs -> Render as Last
    $objectThumbs   ->getThumbData()
                    ->configureThumbs()
                    ->formatThumbsData()
                    ->renderThumbs();
    } 
    ?>
    
</div>

<div class="clearfix"></div>  

<div id="pagination-bottom" class="text-center"></div>

<script type="text/javascript" >
    window.maxPrice = <?php echo json_encode( ceil($objectThumbs->searchQL->getMaxPrice()) );?>;
    window.totalProducts = <?php echo json_encode( $objectThumbs->searchQL->getTotalMaxMatches() );?>;
    
    window.pagetype = <?php echo json_encode('category');?>;
    window.querytype = <?php echo json_encode( $querytype );?>; // description, name or wordfilter
    
    window.cat = <?php echo json_encode($page007->p);?>;
    window.id = <?php echo json_encode($page007->refid);?>;
    
    // Can be removed - Because we provide necessary information in keywords!
   // window.name = <?php //echo json_encode($page007->name);?>;
   // window.description = <?php //echo json_encode($page007->description);?>;
    window.attributeFilters = <?php echo json_encode($page007->attributeFilters);?>;
    window.shops = <?php echo json_encode($shops->activeShops);?>;
    window.sorting = <?php echo json_encode('relevance');?>; 
    window.deviceType = <?php echo json_encode($deviceType);?>;
    window.category_href = window.location.href;
    <?php if (isset( $searchKeywords )) { echo 'window.keywords = '. json_encode( $searchKeywords ) .';'; } ?>
</script>



<div id="product-modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content"> 

    </div>
  </div>
</div>


<div class="clearfix"></div>  

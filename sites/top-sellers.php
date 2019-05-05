<?php
    if ($ping789tt != "RduikjTT967") {
             exit('<h2>You cannot access this file directly</h2>');
    }
    use CI\SearchQL;    
    
    use CI\Controllers\ThumbsController;
?>


<!-- REACTJS DEVELOPMENT -->
<div id="render"></div>
<!-- REACTJS DEVELOPMENT -->
<div class="clearfix"></div>  

<div id="category-box" class="container-fluid row">
	
<?php
    $searchQL = new SearchQL;

    $searchThumbs = $searchQL   ->setQueryArguments( '', $shops->activeShops, $options = array( 'catlevel' => 'top', 'sort' => 'popular' ) )
                                ->buildQuery( 'category' )
                                ->setSearchMeta()
                                ->setSearchResults( $db->linode )
                                ->getSearchResults();
    
    // Initiate Thumbs Controller
    $objectThumbs = ThumbsController::initThumbs( $searchThumbs, DEVICE_TYPE );

    // Configure and Format Thumbs -> Render as Last
    $objectThumbs->configureThumbs()->formatThumbsData()->renderThumbs();
                
    /*
     * DEPRECEATED
     */
    
    // $thumbs->set_thumbs_sphinx($db->linode, $page007->totalProducts, $page007->rangeMax, 'top', $page007->refid, null);
    // $thumbs->get_thumbs($thumbs->Thumbs);
?> 
</div>

<div class="clearfix"></div>  

<div id="pagination-bottom" class="paginator"></div>

<div id="product-modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content"> 

    </div>
  </div>
</div>

<div class="clearfix"></div>  

<script>
    window.pagetype = <?php echo json_encode('category');?>;
    window.cat = <?php echo json_encode('top');?>;
    window.maxPrice = <?php echo json_encode( $searchQL->getMaxPrice() );?>;
    window.totalProducts = <?php echo json_encode( $searchQL->getTotalMaxMatches() );?>;
    window.shops = <?php echo json_encode($shops->activeShops);?>;
    window.sorting = <?php echo json_encode('popular');?>; 
</script>

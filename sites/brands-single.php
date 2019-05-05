<?php
date_default_timezone_set('Europe/Amsterdam');
if ($ping789tt != "RduikjTT967") {
     exit('<h2>You cannot access this file directly</h2>');
}

//use CI\SearchQL;
use CI\Controllers\ThumbsController;



/* 
* NEEDED FOR REACT! 
* After this call react will take over and call the search.call.php to render the products
* First get the max price of this subset
*/
$searchKeywords = $brandpage->brandInfo['brand']['keyword'] .' -for -parts -spare '; // Extend the keyword query and find results wihtouth "for"


/*
 * Initiate Thumbs Controller
 * $mode, $searchKeywords, $secondQuery = 'false', $deviceType = null, $isSimilairThumbs = false, $options = array() 
 */

$objectTopThumbs = ThumbsController::initThumbs( 'brand', $searchKeywords, 'false', DEVICE_TYPE, false, $options = array( 'shops' => $shops->activeShops ), 'top-products' );
$objectTopThumbs->setThumbData();

$objectThumbs = ThumbsController::initThumbs( 'brand', $searchKeywords, 'false', DEVICE_TYPE, false, $options = array( 'shops' => $shops->activeShops ));
$objectThumbs->setThumbData();



/*
$search->runMaxPrice($keywords, null, 'brands');
$page007->rangeMax      =    $search->maxPrice;
$page007->totalProducts =    $search->found;
*/      

/*
$searchQL = new SearchQL;

$searchThumbs = $searchQL   ->setQueryArguments($keywords, $shops->activeShops)
                            ->buildQuery('brand')
                            ->setSearchMeta()
                            ->setSearchResults( $db->linode )
                            ->getSearchResults();


unset($searchQl);



$searchQL = new SearchQL;

$topThumbs = $searchQL      ->setQueryArguments($keywords, $shops->activeShops)
                            ->buildQuery('top-products')
                            ->setSearchMeta()
                            ->setSearchResults( $db->linode )
                            ->getSearchResults();
*/
                   

?>




<div class="page-header container-fluid">
    

    
     
    <div class="col-md-6">
        <h1>
        <?php echo $brandpage->brandInfo['brand']['brand'];?><br />
            <small itemprop="description">
            About, News, and Discounts 
            </small>
        </h1>
    </div>
    <div class="col-md-4">
        <div id="brand-sidebar" class="well text-center"> 
            <img class="img-responsive img-rounded center-block"  src="<?php echo $baseURL .'img/brands/'. $brandpage->brandInfo['brand']['ref'] .'.png';?>">
         
        </div>
    </div>
    
    <!---
    <a href="<?php //echo BASE_URL .'china-brands/'. $brandpage->brandInfo['prev_next'][0]['ref'] .'/'; ?>" title="<?php //echo $brandpage->brandInfo['prev_next'][0]['brand'];?> Products from China Shops">
        <div class="col-md-1 col-xs-6">
            <span class="glyphicon glyphicon-menu-left pull-left clickthrough-arrow" 
                style="font-size: 5em;
                        opacity: 0.8;" aria-hidden="true">
            </span>
            <img class="img-responsive img-rounded center-block"  src="<?php //echo $baseURL .'img/brands/'. $brandpage->brandInfo['prev_next'][0]['ref'] .'.png';?>">         
        </div>
    </a>
    
    <a href="<?php //echo BASE_URL .'china-brands/'. $brandpage->brandInfo['prev_next'][1]['ref'] .'/'; ?>" title="<?php // echo $brandpage->brandInfo['prev_next'][1]['brand'];?> Products from China Shops">
        <div class="col-md-1 col-xs-6">
            <span class="glyphicon glyphicon-menu-right pull-right clickthrough-arrow" 
                  style="font-size: 5em;
                        opacity: 0.8;"aria-hidden="true">
            </span>
            <img class="img-responsive img-rounded center-block"  src="<?php //echo $baseURL .'img/brands/'. $brandpage->brandInfo['prev_next'][1]['ref'] .'.png';?>">        
        </div>
    </a>
    -->
</div>






<div class="container-fluid">

    <div id="brand-rightbox" class="col-md-9">

        
    
        <!-- Brand Banner If Exist -->

        <?php
            $top_banners = glob($_SERVER['DOCUMENT_ROOT'] .'/img/brands/banners/'. $brandpage->brandInfo['brand']['ref'] .'/top_banners/*.jpg');

            if ( isset($top_banners) ) {

                foreach ($top_banners As $banner)  {
                    $img_url = str_replace(ROOT .'/', BASE_URL, $banner);

                    $info = pathinfo($banner);
                    $filename = basename($banner, '.'. $info['extension']);


                    echo '<a href="'. BASE_URL . $brandpage->brandInfo['categories'][0]['link'] .'?wordfilter='. $filename .'">';
                    echo '<img src="'. $img_url .'" class="img-responsive center-block" style="margin-bottom:20px;">';
                    echo '</a>';

                }     
            }
        ?>  
        
        
        <!-- Start Tab Navigation -->
                
                
        <ul id="product-info-tabs" class="nav nav-tabs" role="tablist">
        
            <?php 
            
            /*
             * Trigger the Right Tabs
             */
            
            ?>
           
            <li role="presentation" class="active"><a data-toggle="tab" href="#about"><span class="glyphicon glyphicon-flash" aria-hidden="true"></span> About <?php echo $brandpage->brandInfo['brand']['brand'];?></a></li>
       

            <li role="presentation"><a data-toggle="tab" href="#interesting"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Interesting Info</a></li>
           
            
            <li role="presentation"><a data-toggle="tab" href="#news"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> News</a></li>
            
  
            <li role="presentation"><a data-toggle="tab" href="#top-products"><span class="glyphicon glyphicon-tags" aria-hidden="true"></span>  Top Products</a></li>
          
            
            
        </ul>
        
        <div class="tab-content row">
        
            <div id="about" class="tab-pane fade in active" role="tabpanel">
                <p>
                <span>Website: <a href="<?php echo $brandpage->brandInfo['brand']['website'];?>" target="_blank" role="button" rel="nofollow"><b><?php echo $brandpage->brandInfo['brand']['website'];?></b></a></span> <br/>
                <span>Crunchbase Company Info: <a href="https://www.crunchbase.com/organization/dji" target="_blank" role="button" rel="nofollow"><b>https://www.crunchbase.com/organization/dji</b></a></span>  <br/><br/>

                
                
                <?php echo $brandpage->brandInfo['brand']['information'];?>
                
                
                </p>
            
            
            <?php if (!empty($brandpage->brandInfo['categories'])) { ?>
                  
                <p> <?php echo $brandpage->brandInfo['brand']['brand'];?> products are available in the following categories: 
                    
                    <?php 
                        $count = 0;
                        foreach ($brandpage->brandInfo['categories'] As $category) {
                            echo $seperator = ($count == 0) ?  '' : ' | ';
                            echo '<a href="'. $baseURL . $category['link'].'" class="category-brand-links" title="Take a Look at the '. $category['name'] .' Category">';
                                echo $category['name'];           
                            echo '</a>';
                            $count++;
                        }   
                    
                    ?>
      
                </p>
                
            <?php } ?>    
            </div>
            
            <div id="news" class="tab-pane fade in" role="tabpanel">
            
                
                
                <?php 
                    
                    $xml = simplexml_load_file('https://news.google.com/news/rss/search/section/q/'. $brandpage->brandInfo['brand']['ref'] .'/'. $brandpage->brandInfo['brand']['ref'] .'?hl=en&gl=US&ned=us');
                    // var_dump($xml);
                    $googleLink = str_replace( 'rss', 'news', $xml->channel->link );
                    $googleLink = str_replace( 'coms', 'com', $googleLink );
                    ?>
                    
                    <h5><?php echo $xml->channel->description;?> for <?php echo $brandpage->brandInfo['brand']['ref'];?></h5>
                    <span><a href="<?php echo $googleLink ;?>" rel="nofollow" target="_blank">See complete news list</a></span>
                    
                    <?php 
                    for ($i = 0; $i <= 3; $i++) {
                        
                        //var_dump($xml->channel->item[$i]->description);
                       
                        // MUST CREATE CHECK IF WE CAN LOAD HTML FOR SURE
                        $xpath = new DOMXPath(@DOMDocument::loadHTML($xml->channel->item[$i]->description));
                        $articleImg = $xpath->evaluate("string(//img/@src)");
                        $articleSource = $xpath->evaluate("string(//li/font/text() )");
                        $articleTitle = $xml->channel->item[$i]->title;
                        $articleLink = $xml->channel->item[$i]->link;
                        
                        //var_dump($articleTitle);
                        //var_dump($articleLink);
                        //var_dump($articleSource);
                        //var_dump($articleImg); ?>
                        
                        
                       
                        <div class="container-fluid">
                            <div class="container-fluid" style="-webkit-box-shadow: 0 1px 3px 0 rgba(0,0,0,0.16), 0 0 0 1px rgba(0,0,0,0.04);
                                        box-shadow: 0 1px 3px 0 rgba(0,0,0,0.16), 0 0 0 1px rgba(0,0,0,0.04);
                                        -webkit-transition: box-shadow .3s ease-in-out;
                                        transition: box-shadow .3s ease-in-out;
                                        background-color: #fff;
                                        -webkit-border-radius: 2px;
                                        border-radius: 2px;
                                        margin: 10px 10px;
                                        padding: 20px 20px;">
                                <a href="<?php echo $articleLink ;?>">
                                    <div  class="pull-left" style="display: block;">
                                        <img class="responsive-img" src="<?php echo $articleImg ;?>"></img>
                                    </div>
                                    <div class="pull-left col-md-9">
                                        <h4><b><?php echo $articleTitle ;?></b></h4>
                                        <span style="color: #757575;"><?php echo $articleSource ;?></span>
                                    </div>
                                </a>
                            </div>
                        </div>
                   
<?php               }
                         
            ?>
                
            
            </div>
            
            
        </div> <!-- END Tab content row -->
        

        
        
    </div> <!-- END Brand Box -->

    <div id="brand-leftbox" class="col-md-3">          
        
        <!-- AddToAny BEGIN -->
        <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
        <a class="a2a_button_facebook"></a>
        <a class="a2a_button_twitter"></a>
        <a class="a2a_button_google_plus"></a>
        <a class="a2a_button_whatsapp"></a>
        <a class="a2a_button_email"></a>
        <a class="a2a_dd" href="https://www.addtoany.com/share"></a>
        <br /><span id="addthis-text"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span> Share This Brand With Friends!</span>
        </div>
        <!-- AddToAny END -->       
        
        <?php if (!empty($brandpage->brandInfo['alternative_brands'])) { ?>
          <div id="alternative-brands">
                <div id="related-page-header">
                    <h2><?php echo $brandpage->brandInfo['brand']['brand'];?> Alternatives</h2>
                </div>
                <div class="row">
                <?php foreach ($brandpage->brandInfo['alternative_brands'] As $alternative) {
                    echo '<div class="col-xs-6">';
                        echo '<a href="'. $baseURL . 'china-brands/'. $alternative['ref'] .'/" title="See '. $alternative['name'] .' Products">';
                              echo '<img class="img-responsive alternative-brand-logos" src="'. $baseURL .'img/brands/'. $alternative['ref'] .'.png" alt="'. $alternative['name'] .'">';
                        echo '</a>';
                    echo '</div>';
                }      
                ?>
                </div>
           </div> 
        <?php } ?>
    </div>
</div>

<hr />
    
<!-- REACTJS DEVELOPMENT -->
<div id="render" style="display: none;"></div>
<!-- REACTJS DEVELOPMENT -->
<div class="clearfix"></div>  



<div id="category-box" class="container-fluid row">
    <h3><strong>Top Discount Prices</strong><br><small>Find the best <?php echo $brandpage->brandInfo['brand']['brand']; ?> deals!</small></h3>
    
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

<div id="scroll-to-element"></div>

<h3><strong>All Products From <?php echo $brandpage->brandInfo['brand']['brand']; ?> </strong><br><small>Search Just Once - Find All Stores at One Place! </small></h3>
<div id="category-box" class="container-fluid row  main-product-thumbs">
    
    
    <?php 
        // Configure and Format Thumbs -> Render as Last
        $objectThumbs   ->getThumbData()
                        ->configureThumbs()
                        ->formatThumbsData()
                        ->renderThumbs();
     ?>
</div>

<div class="clearfix"></div>  

<div id="pagination-bottom" class="text-center"></div>





<div class="page-header container-fluid">
    <a href="<?php echo BASE_URL .'china-brands/'. $brandpage->brandInfo['prev_next'][0]['ref'] .'/'; ?>" title="<?php echo $brandpage->brandInfo['prev_next'][0]['brand'];?> Products from China Shops">
        <div class="col-md-1 col-xs-6 pull-left">
            <span class="glyphicon glyphicon-menu-left pull-left clickthrough-arrow" 
                style="font-size: 5em;
                        opacity: 0.8;" aria-hidden="true">
            </span>
            <img class="img-responsive img-rounded center-block"  src="<?php echo $baseURL .'img/brands/'. $brandpage->brandInfo['prev_next'][0]['ref'] .'.png';?>">         
        </div>
    </a>
    

    
    <a href="<?php echo BASE_URL .'china-brands/'. $brandpage->brandInfo['prev_next'][1]['ref'] .'/'; ?>" title="<?php echo $brandpage->brandInfo['prev_next'][1]['brand'];?> Products from China Shops">
        <div class="col-md-1 col-xs-6 pull-right">
            <span class="glyphicon glyphicon-menu-right pull-right clickthrough-arrow" 
                  style="font-size: 5em;
                        opacity: 0.8;"aria-hidden="true">
            </span>
            <img class="img-responsive img-rounded center-block"  src="<?php echo $baseURL .'img/brands/'. $brandpage->brandInfo['prev_next'][1]['ref'] .'.png';?>">        
        </div>
    </a>
    
</div>




<div class="tagcloud01 container-fluid">
    <h3><strong>All China Brands</strong><br><small>Find Your Favorite Brand - Check Their Newest Products and Best Prices from Every Shop!</small></h3>
    <ul>
<?php 
    
    foreach ($brandpage->brandInfo['all_brands'] As $brand) {
        echo '<li><a href="'. $baseURL . 'china-brands/' .$brand['ref'].'/"  title="Find all '. $brand['brand'] .' Products">';
            echo $brand['brand'];           
        echo '</a></li>';
    }   

?>
    </ul>
</div>


<!--- Show Flagship Banners - If Available -->

<?php 

 
    
    $bottom_banners = glob($_SERVER['DOCUMENT_ROOT'] .'/img/brands/banners/'. $brandpage->brandInfo['brand']['ref'] .'/bottom_banners/*.jpg');
    
    if ( isset($bottom_banners) ) {
        
        foreach ($bottom_banners As $flagship)  {
            $img_url = str_replace(ROOT .'/', BASE_URL, $flagship);
            
            $info = pathinfo($flagship);
            $filename = basename($flagship, '.'. $info['extension']);
            
            echo '<a href="'. BASE_URL . $brandpage->brandInfo['categories'][0]['link'] .'?wordfilter='. $filename .'">';
            echo '<img src="'. $img_url .'" class="img-responsive center-block col-md-6">';
            echo '</a>';
        }     
    }

    
?>




<script>
        window.pagetype = <?php echo json_encode('brand');?>;
        window.cat = <?php echo json_encode($page007->p);?>;
        window.id = <?php echo json_encode($page007->refid);?>;
        window.maxPrice = <?php echo json_encode( $objectThumbs->searchQL->getMaxPrice() );?>;
        window.totalProducts = <?php echo json_encode( $objectThumbs->searchQL->getTotalMaxMatches() );?>;
        window.shops = <?php echo json_encode($shops->activeShops);?>;
        window.attributeFilters = <?php echo json_encode($page007->attributeFilters);?>;
        window.sorting = <?php echo json_encode('relevance');?>; 
        window.keywords = <?php echo json_encode($searchKeywords);?>;
</script>

<div id="product-modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content"> 

    </div>
  </div>
</div>

          
<script type="text/javascript" src="//static.addtoany.com/menu/page.js"></script> 
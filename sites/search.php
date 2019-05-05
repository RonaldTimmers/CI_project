<?php
if ($ping789tt != "RduikjTT967") {
     exit('<h2>You cannot access this file directly</h2>');
}


use CI\SearchSuggestion; 

use CI\Controllers\ThumbsController;
use CI\Controllers\SearchController;


$mode = 'search';

/*
 * Get the Keywords
 * And remove not wanted characters
 */    
					
$count = 0;


if ( isset($_GET['keywords']) ) {
    $keywords = $_GET['keywords'];                          // get the keywords
    $keywords = rawurldecode($keywords);                    // decode raw url string
    //$keywords = str_replace('-', '', $keywords);
    $keywords = str_replace("*frwsl*", "/", $keywords);     // transform code back to forwardslash
    $keywords = str_replace("*bcksl*", "\\", $keywords);    // transform code back to backslash
    $keywords = str_replace("  ", " ", $keywords);          // Remove multiple adjacent spaces
    $keywords = trim($keywords, " ");                       // trim blank spaces at beginning and end

    $page007->keywords = $keywords;

    // check if one of the keywords is longer than 1 characters
    $keys = preg_split("/[\s,]+/", $keywords);
    
    
    $new_keywords = '';
    foreach ($keys as $key) {
        $new_keywords .= $key;
        if (strlen($key) > 1) {
            $count = 1;
            if (substr($key, -1) != '*') {
                $new_keywords .= '*';
            }
        }
        $new_keywords .= ' ';
    }
    
    /*
    $keywords = trim($new_keywords, " ");
    */
    
    $searchKeywords = trim($keywords, " ");
}

/*
 * FIRST CHECK!
 * - Did the user searched something 
 * - Is the minimum length of 1 word larger then 2 character
 * 
 */

if ( !isset($_GET['keywords']) OR $_GET['keywords'] == "" OR $count == 0 ) { ?>

    <div id="search-wrong-term" class="alert alert-warning search-warning" role="alert">None of your keywords have the minimum length of 2 characters. Please try again with longer keywords.</div>

<?php } 

/*
 * GO ON WITH THE MAIN SEARCH FUNCTIONS
 */


else {

  
/*
 * ******************************
 * 
 *  GET SEARCH SUGGESTIONS
 * 
 * ************************************
 * 
 */

$searchSuggest = new SearchSuggestion(); 
$searchSuggest->setKeywordSuggestions( $searchKeywords );
$keywordSuggestions = $searchSuggest->getKeywordSuggestions();

    
/*
 * ******************************
 * 
 *  INITIAL FIRST QUERY
 * 
 * ************************************
 
 * 
 * $mode, $searchKeywords, $secondQuery = 'false', $deviceType = null, $isSimilairThumbs = false, $options = array() 
 */

$objectThumbs = ThumbsController::initThumbs( 
                    $mode, 
                    $searchKeywords, 
                    'false',
                    DEVICE_TYPE, 
                    false, 
                    $options = array(
                        'shops' => $shops->activeShops)
                    );


$objectThumbs->setThumbData();




/*
 * ******************************
 * 
 * START SEARCH CONTROLLER
 * 
 * ************************************
 */

/*
 * Now We Know The Metadata Needed for the Search Controller
 * To Control the Flow Further 
 * This is Based on number of results and number of keywords used.
 *
 * Use The Data From the First Query in the Search Controller
 * This Data is Needed to route te search result type and return the right thumb set and view
 */

$searchConfData = [
    'mode' => $mode,
    'searchKeywords' => $searchKeywords,
    'deviceType' => DEVICE_TYPE,
    'shops' => $shops->activeShops,
    'totalFound' => $objectThumbs->searchQL->getTotalFound(),
    'numberOfKeywords' => $objectThumbs->searchQL->getNumberKeywords(),
    'isSimilairThumbs' => false,
    'secondQuery' => 'false',
    'initialKeywords' => $objectThumbs->searchQL->getInitialKeywords(), 
    'keywordSuggestions' => $keywordSuggestions  
];

/*
 * Check Results and Reset / Get New Data
 * 
 * TODO NEW DATA ONLY NECESSARY WHEN NOT INITIAL SEARCH
 */

$objectThumbs = null;
$objectSearch = SearchController::initSearch( $searchConfData );

$objectThumbs = $objectSearch->checkTotalFound();

    
/*
 * END SEARCH CONTROLLER
 */


/*
 * START SEARCH PAGE HTML
 */


$objectSearch->getSearchTop();


?>
         
<div id="scroll-to-element"></div>
    
<div id="category-box" class="main-product-thumbs container-fluid row">
                
<?php

/*
 * ******************************
 * 
 * RENDER THE ACTUAL THUMBS
 * 
 * ************************************
 */

$objectSearch->getSearchResults();

?>
                
</div>

<div class="clearfix"></div>  

<div id="pagination-bottom" class="text-center"></div>  

<div id="product-modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content"> 

    </div>
  </div>
</div>

<div class="clearfix"></div>    

<script>
    window.pagetype = <?php echo json_encode($mode);?>;
    window.cat = <?php echo json_encode( $page007->p );?>;
    window.id = <?php echo json_encode( $page007->refid );?>;
    window.maxPrice = <?php echo json_encode( ceil($objectSearch->searchResults[0]->searchQL->getMaxPrice()) );?>;
    window.totalProducts = <?php echo json_encode( $objectSearch->searchResults[0]->searchQL->getTotalMaxMatches() );?>;
    window.shops = <?php echo json_encode( $shops->activeShops );?>;
    window.attributeFilters = <?php echo json_encode( $page007->attributeFilters );?>;
    window.categoryFilters = <?php echo json_encode( $objectSearch->searchResults[0]->searchQL->getSearchCategories() );?>;
    window.sorting = <?php echo json_encode( 'relevance' );?>; 
    window.keywords = <?php echo json_encode( $searchKeywords );?>;
    window.secondQuery = <?php echo json_encode( $objectSearch->searchConfData['secondQuery'] );?>;
</script>	

<?php             
}
    

<?php
namespace CI\Controllers;

use CI\Controllers\ThumbsController;
use CI\Views\SearchView;

/**
 * Description of SearchController
 *
 * @author Ronald
 */
class SearchController {

    /*
     * Pre Condition Variable and Configuration 
     */
    
    public $searchConfData;
    
    /*
     * Return Variable
     */
    
    public $searchResults;
    

    function __construct( $data ) {
        
        $this->searchConfData =  $data;
        
    }
    
    public static function initSearch( $data ){
        
        return new SearchController( $data );
        
    }
    
    /*
     * We have multiple search types: 
     * SecondQuery = 'less-strict' or 'multiple-words'
     */
    
    
    public function checkTotalFound() {
        
        if ( $this->searchConfData['totalFound'] == 0  ) {
            
            $this->checkNumberOfKeywordsUsed();  
            
        } else {
            
            $this->setSearchResults();
        }
    }
    
    private function setSearchResults(){

        
        $searchResults[] =  ThumbsController::initThumbs( 
                                $this->searchConfData['mode'], 
                                $this->searchConfData['searchKeywords'],
                                $this->searchConfData['secondQuery'],
                                $this->searchConfData['deviceType'],
                                $this->searchConfData['isSimilairThumbs'],
                                array(
                                   'shops' => $this->searchConfData['shops'] 
                                )
        )->setThumbData();
        
        $this->searchResults = $searchResults;
        
    }
    
    private function checkNumberOfKeywordsUsed() {
        
            
        if ( $this->searchConfData['numberOfKeywords'] > 3 ) {

            /*
             * Because the User searched with more than 3 words 
             * (Which all need to be true, but returned 0 results) 
             * We will set a less strict search query 
             * 
             * 'less-strict'
             */

            $this->searchConfData['secondQuery'] = 'less-strict';
           
            $this->setSearchResults();
            
            
          

        } 

        elseif ( $this->searchConfData['numberOfKeywords'] < 4 ) {

            /*
             * Because the User searched with less or 3 words 
             * And returned 0 results, 
             * Because there don't exist a product with all keywords

             * 
             * 'multiple-words'
             */

            $this->searchConfData['secondQuery'] = 'multiple-words';

            $this->setMultipleSearchResults();

        }  
    }
    
    

    
    
    private function setMultipleSearchResults( ){
        /*
         * We will set a multiple words search query
         * Create multiple search queries for each word. 
         */
        
        $keyword_array = explode(' ', $this->searchConfData['initialKeywords'] );

        foreach ( $keyword_array As $key => $keyword ) {
            
            
            $searchResults[$key] = ThumbsController::initThumbs( 
                                        $this->searchConfData['mode'], 
                                        $keyword,
                                        $this->searchConfData['secondQuery'],
                                        $this->searchConfData['deviceType'],
                                        $this->searchConfData['isSimilairThumbs'],
                                        array(
                                           'shops' => $this->searchConfData['shops'] 
                                        )
            )->setThumbData();      
            
            
            $searchTotalResults[$key] = $searchResults[$key]->searchQL->getTotalMaxMatches();
            
        }  
        
        /*
         *  Check How many 0 Results the SubQueries return
         *  If there are more or equal 0 results as Subqueries there is really no result!
         *  Otherwhise one of the queries is valid and need to be show.
         */
        
        
        $this->searchConfData['areThereSubResults'] = false;
        
        $array_count_no_results = array_count_values( $searchTotalResults );

        /*
         * $array_count_no_results['0'] is het aantal keer dat 0 / no results voorkomt
         * Wanneer dat minder is dan het aantal keywords dan zetten we sub results true
         * Dit gebruiken we om de No Results Page niet te triggeren
         */
       
        if ( isset($array_count_no_results['0']) ) {
            
            if ( $array_count_no_results['0'] < $this->searchConfData['numberOfKeywords'] ) {
                $this->searchConfData['areThereSubResults'] = true;
            } 
        }  
        
        $this->searchResults = $searchResults;
        
  
    }  
    
    public function getSearchTop  () {
        
        SearchView::initView( $this->searchConfData, $this->searchResults )->renderSearchTop();
        
    }
    
    public function getSearchResults () {
        
        SearchView::initView( $this->searchConfData, $this->searchResults )->renderSearchResults();
        
    }

    
    
}

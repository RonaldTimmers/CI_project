<?php

namespace CI\Views;

use CI\Controllers\ThumbsController;

/**
 * Description of SearchView
 *
 * @author Ronald
 */
class SearchView {
    
    
    private $searchConfData;
    private $searchResults;
    
    
    function __construct($searchConfData, $searchResults) {
            $this->searchConfData = $searchConfData;
            $this->searchResults = $searchResults;
    }
    
    
    
    
    public static function initView ( $searchConfData, $searchResults ) { 

        return new SearchView($searchConfData, $searchResults);
 
    }
    
    
    function renderSearchTop () {
        
    
        if ( $this->searchConfData['secondQuery'] == 'false' ) {
            
            self::callFiltersTemplate();
            self::callSearchSuggestionsTemplate( );
             
        } 
        
        elseif ( $this->searchConfData['secondQuery'] == 'less-strict' ) {
            
            self::callExtraNoticeTemplate( );
            self::callFiltersTemplate();
            self::callSearchSuggestionsTemplate(  );
            
        } 
        
        elseif ( $this->searchConfData['secondQuery'] == 'multiple-words' ) {
           
            
            if ( $this->searchConfData['areThereSubResults'] == true  ) {
                
                self::callExtraNoticeTemplate( );
                self::callSearchSuggestionsTemplate( );
                
                 
            } else {
                
                self::callExtraNoticeTemplate( );
                
                echo 
                '<div class="page-header">
                    <h1>Top 50 Best Price Drops<br><small>Find the Best Deals of the Moment</small></h1>
                </div>' ;
                include $_SERVER['DOCUMENT_ROOT'] .'/includes/html/pricehistory_top_result_0.html'; 
            }      
        }    
    }
    
    function renderSearchResults() {
         if ( $this->searchConfData['secondQuery'] == 'false' ) {
            self::callSearchResultTemplate ( ); 
             
         } 
         
         elseif ( $this->searchConfData['secondQuery'] == 'less-strict' ) { 
            self::callSearchResultTemplate ( ); 
             
         }
         
         elseif ( $this->searchConfData['secondQuery'] == 'multiple-words'  && $this->searchConfData['areThereSubResults'] == true) { 
            
            self::callSearchMultipleWordsTemplate ( );
             
         }
         
    }
    
    
    function callSearchResultTemplate ( ) {
        
        foreach ( $this->searchResults as $searchResult) {

            $searchResult       ->getThumbData()
                                ->configureThumbs()
                                ->formatThumbsData()
                                ->renderThumbs();

        }
    }
    
    function callSearchMultipleWordsTemplate () {
        global $twig;
        
        foreach ( $this->searchResults as $searchResult) {

            $searchResult       ->getThumbData()
                                ->configureThumbs()
                                ->formatThumbsData();

        }
        
        
        
        echo $twig->render( 'searchMultipleWordsTemplate.html.twig', 
                            array(  
                                    'searchResults' => $this->searchResults,
                                    'initialKeywords' => explode(" ", $this->searchConfData['initialKeywords']),
                                    'numberOfKeywords' => $this->searchConfData['numberOfKeywords']
                            )
        );
    }
    
    
    function callSearchSuggestionsTemplate( ) {
        global $twig;
        
        echo $twig->render( 'searchSuggestionsTemplate.html.twig', 
                            array(  'keywordSuggestions' => $this->searchConfData['keywordSuggestions'], 
                                    'BASE_URL' => BASE_URL,
                                    'STATIC_URL' => STATIC_URL )
        );
    }
    
    
    function callFiltersTemplate( ) {
        global $twig;
        
        echo $twig->render( 'filtersTemplate.html.twig' );
 
    }
    
    
    function callExtraNoticeTemplate( ) {
        global $twig;
        
        echo $twig->render( 'searchExtraNoticeTemplate.html.twig',
                            array( 'secondQuery' => $this->searchConfData['secondQuery'] )
        );
 
    }
}

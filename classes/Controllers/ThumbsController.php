<?php 
namespace CI\Controllers;

use CI\Views\ThumbsView;
use CI\SearchQL;
            
/*
 * Use this Controller in sc.php
 */

class ThumbsController {
    
    /*
     * Mode variables, this will initiate different configurations
     */
    
    public $mode;
    public $secondQuery;
    
    /*
     * Pre Condition Variables
     */
    
    public $searchKeywords;
    public $deviceType;
    public $isSimilairThumbs;
    
    /*
     * Configuation Variables
     */
    public $isModalLink;
    public $options;
    
    /*
     * Result Data
     */
    
    public $searchQL;
    
    /*
     * Result for View
     */
    
    public $thumbs;
    
    /**
    *
    *     Main Function which create the thumb for the PHP side.(We no also have a React.js thumb)
    *
    */

    function __construct($mode, $subMode, $searchKeywords, $secondQuery, $deviceType, $isSimilairThumbs, $options) {
        $this->mode = $mode;
        $this->subMode = $subMode;
        $this->searchKeywords = $searchKeywords;
        $this->secondQuery = $secondQuery;
        $this->deviceType = $deviceType;
        $this->isSimilairThumbs = $isSimilairThumbs;
        $this->options = $options;
     
    }
    
    public static function initThumbs( $mode, $searchKeywords, $secondQuery = 'false', $deviceType = null, $isSimilairThumbs = false, $options = array(), $subMode = 'default' ) {
        
        return new ThumbsController( $mode, $subMode, $searchKeywords, $secondQuery, $deviceType, $isSimilairThumbs, $options);
      
    }
    
    /*
     * Use SearchQL model for this 
     */
    
    public function setThumbData() {
        global $db;
        
        $this->searchQL = ( new SearchQL )
                            ->setQueryArguments( $this->searchKeywords, $this->options )
                            ->buildQuery( $this->mode, $this->subMode, $this->secondQuery )
                            ->setSearchMeta();
        
       
        
        
        switch ( $this->mode ) {
            case 'search':
                
                if ($this->secondQuery != 'multiple-words') {
                    $this->searchQL->setSearchCategories( $db->linode );
                }
                
                break;
            
            default:
                // Keep Empty For Other Modes
                break;
        }
        
        
        return $this;   
    }
    
    public function getThumbData() {
             
        $this->thumbs =     $this->searchQL ->setSearchThumbs()
                                            ->getSearchResults(); 
        
        
        return $this;
       
    }
    

    public function configureThumbs () {
   
        $this->deviceType != 'phone' ? $isModalLink = 'modal-link' : $isModalLink = 'normal-link';
        
        $this->isModalLink = $isModalLink;
        
        return $this;
        
    }
    
    public function formatThumbsData( ) {
     
       /*
        * CHECKEN OF DEZE CODE NOG NODIG IS! 26-01-18
        */
        
        if ( isset( $this->thumbs ) ) {
            
            foreach ( $this->thumbs as $thumb ) { 


                    if ( floatval( $thumb['list']) != 0 ) { $thumb_listprice = number_format( floatval( $thumb['list'] ) , 2, '.', '' ); } else { $thumb_listprice = '';}


                    if ($thumb['price'] < $thumb['list']) {
                        $percent  = round(($thumb['price'] / $thumb['list']), 2);
                        $off = ( 1 - $percent ) * 100;
                        $thumb['off'] = $off .'% OFF';
                        $thumb['list'] = '$'. $thumb['list'];
                    } else {
                        $thumb['off'] = "";
                        $thumb['list'] = "";
                    }       
            }   
            
        }
        

         
        return $this;
    }    
    
    public function renderThumbs()  {
        
        $thumbsView = new ThumbsView();
        $thumbsView->initView( $this->mode, $this->subMode, $this->isModalLink, $this->thumbs, $this->isSimilairThumbs );
    
    }    
}


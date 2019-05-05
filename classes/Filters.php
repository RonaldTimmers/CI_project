<?php


namespace CI;

/**
 * Description of filters
 *
 * @author Ronald
 */


class Filters {

    
    protected $sortAttr; 
    protected $sortOrder;
    protected $shopsIDs;
    
    function __construct() {
        
    }
    
    protected function setSort ($sort) {
        
        switch ($sort) {
            case "lowest":
                $this->sortAttr = ["price", "added"];
                $this->sortOrder = ["ASC", "DESC"];    
                break;
            case "highest":
                $this->sortAttr = ["price", "added"];
                $this->sortOrder = ["DESC", "DESC"];
                break;
            case "popular":
                $this->sortAttr = ["clicks", "added"];
                $this->sortOrder = ["DESC", "DESC"];
                break;
            case "rated":
                $this->sortAttr = ["stars", "reviews"];
                $this->sortOrder = ["DESC", "DESC"];
                break;
            case "newest":
                $this->sortAttr = ["added", "added"];
                $this->sortOrder = ["DESC", "DESC"];
                break;
            case "relevance":
                $this->sortAttr = ["weight()", "added"];
                $this->sortOrder = ["DESC", "DESC"];
                break;
            default:
                $this->sortAttr = ["weight()", "added"];
                $this->sortOrder = ["DESC", "DESC"];
        }
    }
    
    protected function setShops ( $shops ) {
        
        /*
         * We need the Check what kind of variable $shops is.
         * When used from the start it is an array, we used the filter later on we get a string.
         * 
         */
        
        if (is_array( $shops )) {
            
            foreach ($shops as $shop) {
                $this->shopsIDs[] = (int) $shop['id'];
            }  
            
        } else {
            
            $this->shopsIDs = explode( '-', $shops );
            
            foreach ($this->shopsIDs as $key => $shop) {
               $this->shopsIDs[$key] = (int) $shop;
            }
            
        }

    }
    
    protected function setFilters ( $filters ) {
        
        /*
         * Difficult Code !
         * 
         * 
         *  We need the Check what kind of variable $filters is.
         * 
         */
        
        $filters = str_replace( '&quot;', '"', $filters );
        $filters = json_decode( $filters, true ); 

        
       
        
        if (is_array( $filters )) {
            
            $filters = array_filter( $filters );
            $i = 0;
            $new_filters = array();
            
            foreach ( $filters as $key => $filter ) {  
                foreach ( $filter as $attribute ) {
                    
                    if ( $key == $attribute[0] ) {
                        settype($attribute[1], "integer"); 
                        $new_filters[$i][] = $attribute[1];   
                    }             
                } 
                $i++;
            }  
            
            /*
             * Return the New Array 
             * We can use this one for building the Query
             */
            
            return $new_filters; 
        } 

    }
    
    
}

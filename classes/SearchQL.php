<?php

namespace CI;

/**
 * Description of searchQL
 * 
 * Used in: search.php, search.call.php, relatedProductsHTML.call.php
 *
 * @author Ronald
 */

/*
 * Needed Dependencies
 */

use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Helper;
use CI\Filters;

class SearchQL extends Filters {
    
    
    public $secondQuery;
    
    protected $conn;
    private $sphinxQL;
    private $excludeWords = ['a', 'an', 'the', 'in', 'of', 'on', 'are', 'be', 'if', 'into', 'which', 'or', 'and', 'with', 'for', 'from'];
    
    /*
     * Initial Properties
     */
    
    
    protected $keywords;
    protected $initialKeywords;
    protected $numberKeywords;
    protected $stars;
    protected $offset;
    protected $page;
    protected $minprice;
    protected $maxprice;
    protected $filters;
    protected $items_page;
    
    protected $querytype;
    protected $catlevel;
    protected $catid;
    
    
    /*
     * After Search Properties
     */
    

    protected $totalFound;
    protected $totalMaxMatches;
    protected $result;
    protected $thumbs;
    protected $searchCategories;
    protected $searchCategoriesIDs;
    
    function __construct() {
        
        /*
         * Create a Connection with the Searchd Engine
         */
        
        $this->conn = Db::connect_searchd();    
        $this->sphinxQL = SphinxQL::create($this->conn);
    }
    
    public function setQueryArguments ( $keywords, $options = array() )  {
        
        /*
         * Set Default Values
         */
        
        $this->sort = 'relevance';
        $this->stars = 0;
        $this->page = 1;
        $this->minprice = 0;
        $this->maxprice = 99999;   
        $this->filters = null;
        $this->shops = null;
        $this->items_page = 48;
        $this->stock = false;
        
        $this->keywords = $keywords; 
        $this->initialKeywords = $this->keywords;
        $this->countSearchKeywords();
        
        
        
        /*
         * Overwrite Defaults Values
         * With the added options in the call
         */
        
        $object = (object) $options;
        foreach ( $object as $name => $value ) {
            if ( isset($value) ) {
                $this->$name = $value;
            } 
        }
        
        
    
        
        /*
         *  Further Defined in the Extended Filter.Class
         */
        
        $this->setShops( $this->shops );
        
        
        
        /*
         * Last Run Some Functions to Set the Configuration
         */
        
        
        $this->setSort( $this->sort );
        
        /*
         * We are using the filters we are getting, rewrite them in a usefull array
         * And push them back to the this-filters property
         * Now we can use the property for building the query 
         * Only usefull for category pages  ( sc.php and thumbs.call.php )
         */
        
        $this->filters = $this->setFilters ( $this->filters );
            
        
        
        return $this;
    }
    
    /*
     * This Method need to be re-written when we also going to use related products with this class
     * Because Those are getting other ^booster values
     */
    protected function countSearchKeywords () {
        
        $keyword_array = explode(' ', $this->initialKeywords);
        $this->numberKeywords = sizeof($keyword_array);
         
    }
    
    protected function setSearchPhrase () {
        if ($this->numberKeywords > 3) {
            $used_keywords = 6;
            $string_match = 0.5;
            
            $keyword_boost = $this->numberKeywords * 5; 
            $count = 0;
            $keyword_array = explode(' ', $this->initialKeywords);
            foreach ($keyword_array As $key => $value) {
                $keyword_array[$key] = $value .'^'. $keyword_boost;    
                $keyword_boost-=5;
            }
            
            $mainKeywords = array_slice($keyword_array, 0, 3);
            $maybeKeywords = array_slice($keyword_array, 3);        
            $this->keywords = implode(' ', $mainKeywords) .' MAYBE '. implode(' | ', $maybeKeywords); 
        }   
    }
    
    
    public function buildQuery ($mode = null, $subMode = null, $secondQuery = 'false') {
     
        /*
         * Mode Will Be a Case Switch Between the Different Purposes of the SearchD Query
         * http://sphinxsearch.com/docs/current.html#expression-ranker
         * 
         *  First: The Universal Parts on an SearchQL query
         */
        
        //(1 - ( $product['price'] / ($product['price'] - ( $product['price'] - $product['prev_price'] ) ))) * 100
        
        $this->sphinxQL   =  SphinxQL::create( $this->conn )
            //->select( 'id', 'weight()', 'PACKEDFACTORS() As r', '(1 - ( price / prev_price ))*100 As price_difference' ) // OLD VERSION
            ->select( 'id', '(1 - ( price / prev_price ))*100 As price_difference', 'title', 'title_url', 'thumb_path', 'thumb_path_2', 'thumb_path_3', 'thumbs_extra', 'price', 'prev_price', 'list', 'stars', 'reviews', 'source', 'logo', 'stock')  
            ->from('productdetails')
            ->option('max_matches', 10000); // Default -> If we Add this option again it will be overwritten
        
        /*
         * Second: The Standard values which will never change
         * Future Update
         */
        
        $this->offset = ($this->page * $this->items_page) - $this->items_page;
        
        /* 
         * NEW 21-11-17 RONALD
         * Change Items Per Page (Or in other words, returned Results
         * In the Case of SecondQuery = 'multiple-words'
         */
        
        if ( $secondQuery == 'multiple-words' ) {
            $this->items_page = 4;
        }
        
        /*
         * Items Per Page
         */
        
        $this->sphinxQL =   $this->sphinxQL
            ->limit($this->offset, $this->items_page);
        
        /*
         * Only in Stock Products
         */
        
        
        if ($this->stock === true || $this->stock === "true") {
            
            //var_dump( 'Only in Stock Products' );
            
            $this->sphinxQL =   $this->sphinxQL
                ->where('stock', '=', 0); 
        } else {
           // var_dump( 'All Active Products' );
        }
        

        
        
        /*
         * Third: Set the Filters we always need
         * Shops, Stars and Prices
         * setFilters
         */
        
        $this->sphinxQL =   $this->sphinxQL
            ->where('source', 'IN', $this->shopsIDs)
            ->where('price', 'BETWEEN', array( $this->minprice, $this->maxprice ))
            ->where('stars', 'BETWEEN', array( $this->stars, 5 ));
        
        
        /*
         * Fourth: Set the sorting options
         * Default is relevance/weigh() and added
         * setSort()
         */
        
        if ( $subMode != 'top-products' ) {
            $this->sphinxQL =   $this->sphinxQL
                ->orderBy( $this->sortAttr[0], $this->sortOrder[0] )
                ->orderBy( $this->sortAttr[1], $this->sortOrder[1] );     
        }

        
        
        
        /*
         * Fifth: The differences between:
         * search, related, brand, category
         */
        
        switch ( $mode ) {  
        /*
         * Available: search, related, brand, category
         */
            
            case 'search':
                // Old Search Ranker
                // $cl->SetRankingMode (SPH_RANK_EXPR, 'sum((4*lcs+2*(min_hit_pos==1)+exact_hit)*user_weight)*1000+bm25'); // SPH 4 SPH_RANK_SPH04
                
                /*
                 * When the User Types a Query longer than three seperate words we create another matching method.
                 * 
                 * 1-3 Keywords  = exact match -> All Words Must Be in the SearchText
                 * 4+ Keywords = phrase match or Maybe match -> The Words From the 4th are not necessary!  
                 */
                
                $this->setSearchPhrase();
                
                if ( ( $secondQuery == 'less-strict' ) AND $this->numberKeywords > 3 ) {
                    $this->keywords = '"'. $this->initialKeywords .'"/0.5 ';
                }
                
           
                
                $this->sphinxQL   = $this->sphinxQL
                    ->match(array('sku', 'searchText'), SphinxQL::expr($this->keywords))
                    ->option('ranker', SphinxQL::expr("expr('sum((4*lcs+2*(min_hit_pos==1)*(1/(1+min_gaps))+exact_hit)*user_weight)*1000+bm25')"))    
                    ->option('max_matches', 2000)
                    ->groupBy('title')
                    ->groupBy('source');
                    //->option('ranker', SphinxQL::expr("expr('sum((4*lcs+2*(min_hit_pos==1)+exact_hit)*user_weight)*1000+bm25')"))

                    /*
                     * This Functions call the ShowMeta, MaxPrice, Subsub Queries
                     */

                    $this->setTotalQuery();
                    $this->setMaxpriceQuery( $mode );
                    $this->setSubSubQuery();
            break;
        
            case 'related':
                
            break;
        
            case 'brand':
                $this->items_page = 24;
                $this->offset = ($this->page * $this->items_page) - $this->items_page;
                
                $this->sphinxQL   = $this->sphinxQL
                    ->match('searchText', SphinxQL::expr( $this->keywords ))
                    ->option('ranker', SphinxQL::expr("expr('bm25 -(sum(min_hit_pos * 10))')"))
                    ->option('max_matches', 2000)
                    ->groupBy('title')
                    ->groupBy('source')
                    ->limit($this->offset, $this->items_page); // Overwrite Earlier Default 
                
                /*
                 * These Functions call the ShowMeta, MaxPrice, Subsub Queries
                 */

                //$this->setTotalQuery();
               // $this->setMaxpriceQuery();
                                    
                break;
            
            /*
            case 'top-products':
                $this->items_page = 16;
                $this->offset = 0;
                
                $this->sphinxQL   = $this->sphinxQL
                    ->match('searchText', SphinxQL::expr( $this->keywords ))
                    ->where('price_difference', 'BETWEEN', array(0, 99) ) // Top-Products
                    ->where('price', '>=', 5 ) // Top-Products
                    ->option('ranker', SphinxQL::expr("expr('bm25 -(sum(min_hit_pos * 10))')"))
                    ->option('max_matches', 2000)
                    ->groupBy('title')
                    ->groupBy('source')
                    ->orderBy('price_difference', 'DESC') // Top-Products
                    ->limit($this->offset, $this->items_page); // Overwrite Earlier Default
                    
                    // EIGENLIJK NIET NODIG OM DEZE AAN TE ROEPEN
                    // VOOR NU WEL, DE setSearchMeta HEEFT DIT NU NOG NODIG!...
                
                    $this->setTotalQuery();
                    $this->setMaxpriceQuery();
                break;
            */
            
            case 'category':
                
                switch ( $this->catlevel ) {
                    case "p":
                        
                        settype( $this->catid, "integer");
                        $this->sphinxQL   = $this->sphinxQL
                            ->where('our_subs', 'IN', array( $this->catid  ) );
                        
                        if ( isset($this->filters) ) {
                            foreach ($this->filters as $attributeFilters) {
                                $this->sphinxQL = $this->sphinxQL->where('attrFilters', 'IN', $attributeFilters);
                            }   
                        }
                        break;
                   
                    case "ps":
        
                        settype( $this->catid, "integer");
                        $this->sphinxQL   = $this->sphinxQL
                            ->where('our_subsubs', 'IN', array( $this->catid  ) );
                        
                        if ( isset($this->filters) ) {
                            foreach ($this->filters as $attributeFilters) {
                                $this->sphinxQL = $this->sphinxQL->where('attrFilters', 'IN', $attributeFilters);
                            }   
                        }
                        break;

                    case "new":
                        $this->sphinxQL   = $this->sphinxQL
                            ->where('our_cats', 'NOT IN', array( 17 ) ) // Not in Adult
                            ->where('source', 'IN', array( 4, 28 ) ); // Only GB and BG Products
                        break;
                    
                    case "top":
                        $now = time();
                        $this->sphinxQL   = $this->sphinxQL
                            ->where('added', 'BETWEEN', array( 1483230622, $now )) // 1483230622 = 1 Jan 2017
                            ->where('our_cats', 'NOT IN', array( 17 ) ) // Not in Adult
                            ->where('source', 'IN', array( 2, 4, 6, 25, 28 ) ); // Only DX BG MITB LITB and GB Products
                    
                        break; 


                }   
                
                /*
                 * THIS PART Must be integrated in the Switch Case 'category' part
                 */
                
                if ($this->catlevel == 'p' || $this->catlevel == 'ps') {
                    if ( $this->querytype == 'description' ) {

                        $this->keywords = '"'. $this->initialKeywords .'"/1 ';

                        $this->sphinxQL = $this->sphinxQL
                            //->match('searchText', SphinxQL::expr( $this->keywords )) // A FullScan is to Simply not use a match
                            //->option('ranker', SphinxQL::expr("expr('sum(1+doc_word_count)')"))
                            ->option('max_matches', 10000);
                            //->groupBy('title')
                            //->groupBy('source');               
                    } 

                    elseif ( $this->querytype == 'name' ) {
                    
                        /*
                         * The Query Will do A full table Scan ( Within that category )
                         * Just leave the match function out
                         */

                        $this->sphinxQL = $this->sphinxQL
                            ->option('ranker', SphinxQL::expr("expr('sum(1+doc_word_count)')"))
                            ->option('max_matches', 10000)
                            ->groupBy('title')
                            ->groupBy('source'); 

                    }   
                    /*
                     * This option is for now only available in PS (subsubcat)
                     */
                    elseif ( $this->querytype == 'wordfilter' ) {
                        
                        $this->setSearchPhrase();
                        
                        $this->sphinxQL = $this->sphinxQL
                            ->match('searchText', SphinxQL::expr( $this->keywords ))
                            ->option('ranker', SphinxQL::expr("expr('sum((4*lcs+2*(min_hit_pos==1)*(1/(1+min_gaps))+exact_hit)*user_weight)*1000+bm25')"))    
                            ->option('max_matches', 10000);
                           // ->groupBy('title');
                    }
                    
                }
    
                
                /*
                * This Functions call the ShowMeta, MaxPrice
                */
                
                //$this->setTotalQuery();
                //$this->setMaxpriceQuery();
    
            break;
            
        }
        
        /*
         * Overwrite and Add Extra Query Needs
         */
        if ( $subMode == 'top-products' ) {

            $this->items_page = 16;
            $this->offset = 0;

            $this->sphinxQL   = $this->sphinxQL
                ->where('price_difference', 'BETWEEN', array(0, 99) ) // Top-Products
                ->where('price', '>=', 5 ) // Top-Products
                ->where('stock', '=', 0)
                ->orderBy('price_difference', 'DESC') // Top-Products
                ->option('max_matches', $this->items_page )
                ->limit($this->offset, $this->items_page); // Overwrite Earlier Default

                
        }
        
        
        /*
         * Last: Eventually Call the Execution Query
         * Of all the setup Queries
         */
        
        if ( $subMode != 'top-products' ) {
            $this->setTotalQuery();
            $this->setMaxpriceQuery( $mode );
        }
        
        $this->sphinxQL   = $this->sphinxQL->executeBatch();  
        
        return $this;
        
        /*
         * Next Call is the setSearchMeta method , 
         * which will rearrange the results is logical variables
         */
    }
    
    
    public function setTotalQuery( ) {
        
        $this->sphinxQL =     $this->sphinxQL             
                                    ->enqueue( Helper::create( $this->conn )->showMetaTotal() ) // this returns the object with SHOW META query prepared
                                    //->enqueue( Helper::create( $this->conn )->showMeta() )
                                    //->enqueue( Helper::create( $this->conn )->showStatus() )
                                    ->enqueue( null );

    }
    
    public function setMaxpriceQuery ( $mode ) {
        
        $this->sphinxQL =     $this->sphinxQL 
                                    ->select('MAX(price) as max_price')
                                    ->from('productdetails')
                                    ->where('source', 'IN', $this->shopsIDs)
                                    ->option('max_matches', 1);
        
        if ($this->stock === true || $this->stock === "true") {
            
            $this->sphinxQL =   $this->sphinxQL
                                ->where('stock', '=', 0); 
        } 
        
        if ( ( ($this->catlevel == 'p' || $this->catlevel == 'ps') &&  $this->querytype == 'wordfilter' ) || $mode == 'search' ) {
             $this->sphinxQL =     $this->sphinxQL 
                                        ->match('searchText', SphinxQL::expr($this->keywords));
        }
        
        
        /*
         * If We Are in A category we need to Add those elements to the Price Query
         * Future: NEED A SOLUTION WHERE IT s EASIER TO EXCHANGE NEEDED PARAMETERS TO QUERIES
         */
        
        if ( $this->catlevel == "p" ) {
            $this->sphinxQL   = $this->sphinxQL
                ->where('our_subs', 'IN', array( $this->catid  ) );    
        } 
        
        elseif ( $this->catlevel == "ps" ) {
            $this->sphinxQL   = $this->sphinxQL
                ->where('our_subsubs', 'IN', array( $this->catid  ) );    
        }
   
        
    }
    
    public function setSubSubQuery() {
        $this->sphinxQL =     $this->sphinxQL 
                                    ->enqueue( null )
                                    ->select( '@groupby as subsubcat' , 'count(*) as subsub_results' )
                                    ->from('productdetails')
                                    ->match('searchText', SphinxQL::expr($this->keywords))
                                    ->where('stock', '=', 0)
                                    ->where('source', 'IN', $this->shopsIDs)
                                    ->where('price', 'BETWEEN', array( $this->minprice, $this->maxprice ))
                                    ->where('stars', 'BETWEEN', array( $this->stars, 5 ))
                                    ->where('our_subsubs', 'NOT IN', array( 868 ) )
                                    ->groupBy('our_subsubs')
                                    ->option('ranker', SphinxQL::expr("expr('sum((4*lcs+2*(min_hit_pos==1)*(1/(1+min_gaps))+exact_hit)*user_weight)*1000+bm25')"))
                                    ->option('max_matches', 2000);
                        
    }
    
    public function setSearchMeta () {
        
        /*
         * Re wrtie the sphinxQL variables to normal known variables.
         */
        
        if (isset( $this->sphinxQL[1][1]['Value'] )) {$this->totalFound = (int) $this->sphinxQL[1][1]['Value'];} 
        if (isset( $this->sphinxQL[1][0]['Value'] )) {$this->totalMaxMatches = (int) $this->sphinxQL[1][0]['Value'];} 
        //if (isset( $this->sphinxQL[3][0]['max_price'] )) {$this->maxprice = $this->sphinxQL[3][0]['max_price'];}
        if (isset( $this->sphinxQL[2][0]['max_price'] )) { $this->maxprice = $this->sphinxQL[2][0]['max_price'];}
        if (isset( $this->sphinxQL[0] )) { $this->result = $this->sphinxQL[0];}
        

        
        return $this;
        
        
    }
    
    
    /*
     * Called in sites/search.php 
     */
    
    public function setSearchCategories ( $db ) {
            
            /*
             * First Select the result Categories IDs 
             * Create a string for selecting other metadata
             */
             
        
            if (!empty( $this->sphinxQL[3] )) {
                $this->searchCategoriesIDs = implode(', ', array_map(function ($entry) {

                           return $entry['subsubcat'];

                       }, $this->sphinxQL[3]));

                       /*
                        * Select Categorie, SubCategory and SubsubCategory metadata
                        * 
                        */

                       $statement =  $db->prepare('SELECT      cat.id AS `cid`, cat.name AS `cname`, cat.ref AS `cref`, 
                                                               s.id AS `sid`, s.ref AS `sref`, s.name AS `sname`, 
                                                               ss.id AS `ssid`, ss.ref AS `ssref`, ss.name AS `ssname`

                                                   FROM `subsubcats` ss 
                                                   LEFT JOIN `subcats` s ON s.id = ss.subcat 
                                                   LEFT JOIN `categories` cat ON cat.id = s.cat
                                                   WHERE ss.del = 0 AND s.topbar = 1 AND s.del = 0 AND cat.topbar = 1 AND cat.del = 0 
                                                   AND ss.id IN ('. $this->searchCategoriesIDs .') 
                                                   ORDER BY cat.order ASC, cat.id ASC, s.column ASC, s.order ASC, s.id ASC, ss.order ASC
                                                   '
                                                   );     
                       
                       
                       $statement->execute();
                       $this->searchCategories = $statement->fetchAll(\PDO::FETCH_ASSOC);



                       /*
                        * Add result count to the category data
                        */

                       foreach ( $this->sphinxQL[3] as $category) {

                           foreach ( $this->searchCategories as $key => $result ) {

                               if ( $category['subsubcat'] == $result['ssid'] ) {

                                   $this->searchCategories[$key]['count'] = $category['subsub_results'];
                               } 

                           }

                       }

                       
                
            }
            return $this;
       
    }
    
    protected function getSearchResultIDs () {
        // Now that we have the matches and weight, we are going to make a comma separated list of product_id's to pull additional details out of our DB.
        $count = 1;
        if (isset($this->result)) {
            foreach ($this->result AS $value) {
                    // else add it to comma seperated string
                    if ($count == 1) {
                        $product_ids = $value['id'];
                    }
                    else {
                        $product_ids .= ',' . $value['id'];
                    }
                    $count++;
            }
        }
        
        return $product_ids;  
        
    }
    
    /*
     * DEPRECEATED (Already for sc and search!)
     * This Function Needs to Be Replaced
     * Not Necessary when we directly use the data from sphinx results
     * 
     */
    
    
    
    public function setSearchResults ( $db ) {
        
        if ($this->totalFound > 0) {
            // Here, we are using a MySQL IN clause to pull the product details from the DB for display.
            $statement =  $db->prepare('SELECT T1.id, T1.title, T1.title_url, T1.thumb_path, T1.thumb_path_2, T1.thumb_path_3, T1.thumbs_extra, T1.price, T1.prev_price, T1.list, T1.stars, T1.reviews, T1.source, T2.logo
                                        FROM `product_details` T1 
                                        LEFT JOIN `sources` T2 ON T2.id = T1.source 
                                        WHERE 
                                        T1.active = 1 AND 
                                        T2.current = 1 AND 
                                        T1.id IN ('. $this->getSearchResultIDs() .') 
                                        ORDER BY FIELD(T1.id, '. $this->getSearchResultIDs() .')'
                                        );

                      
            $statement->execute();
            $this->thumbs = $statement->fetchAll(\PDO::FETCH_ASSOC);
                        
        } 
    

        if(isset($this->thumbs)) {
            foreach ($this->thumbs as $i => $thumb) {
                $this->thumbs[$i]['URLtitle'] =  urlFriendly($thumb['title']);
            }
        }

        unset($statement);
        $statement = null;
        
        return $this;
        
    }
    
    
    /*
     * 4-3-2018 Replacement Function
     * For using directly sphinx data
     */
    
    function setSearchThumbs () {
        
        $this->thumbs = $this->result;    // = $this->sphinxQL[0]
      
        return $this;
    }
    
    
    public function getSearchResults () {
        return $this->thumbs;
    }
    
    public function getSearchCategories () {
        return $this->searchCategories;
    }
    
    public function getTotalFound () {
        return $this->totalFound;
    }
    
    public function getTotalMaxMatches () {
        return $this->totalMaxMatches;
    }
    
    public function getMaxPrice () {
        return $this->maxprice;
    }
    
    public function getInitialKeywords () {
        return $this->initialKeywords;
    }
    
    public function getNumberKeywords () {
        return $this->numberKeywords;
    }
    
    
}


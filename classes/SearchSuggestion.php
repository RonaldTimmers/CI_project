<?php

namespace CI;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Foolz\SphinxQL\SphinxQL;

use CI\SearchQL;

class SearchSuggestion extends SearchQL {
    
    public $completeSuggestions;
    public $productSnippets;
    public $keywordSuggestions;
    
    
    protected $keywords;
    
    
    function __construct(){
        SearchQL::__construct();
    }
    
    
    function setCompleteSuggestions ( $input ) {
        
        $inputStar = $input .'*';
        
        $this->completeSuggestions   =  SphinxQL::create( $this->conn )
                                        ->select( 'keyword', 'freq' )
                                        ->from('autocomplete')
                                        ->match('keyword', SphinxQL::expr( $inputStar ))
                                        ->limit(0, 8)
                                        ->orderBy( 'weight()', 'DESC' )
                                        ->orderBy( 'freq', 'DESC' )
                                        ->groupBy( 'keyword' )
                                        ->option('ranker', 'sph04')
                                        ->option('max_matches', 8)
                                        ->execute();  
        
        
        
        
        $AE_suggestion = array( 'freq' => 9999, 
                                'keyword' => $input, 
                                'url' => '<a target="_blank" rel="nofollow" href="https://alitems.com/g/1e8d114494205609bfdc16525dc3e8/?subid=CI_AE&ulp=https://www.aliexpress.com/wholesale?SearchText='. $input .'">',
                                'url_close' => '<span> - <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search at <img src="'. BASE_URL .'img/favicons/aliexpress.png"> AliExpress </span></a>'
            
            );
        
         
          
            
        
            
        array_unshift( $this->completeSuggestions, $AE_suggestion );
        
        
        foreach ( $this->completeSuggestions As $key=>$suggestion ) {

            if ($key != 0) {
                $this->completeSuggestions[$key]['url'] = '<a href="'. BASE_URL .'search/'. $suggestion['keyword'] .'/">';
                $this->completeSuggestions[$key]['url_close'] = '</a>';
            } 
            
        }
        
      
        
         /*
        $this->productSnippets = SphinxQL::create( $this->conn )
                                ->enqueue( Helper::create( $this->conn )->callSnippets(  $data, 'autocomplete', $input, $options = array('around' => 0, 'limit_words' => 1, 'chunk_separator' => '', 'query_mode' => false, 'exact_phrase' => false)  ) ) 
                                ->execute();
        */

    }   
    

    
    function setKeywordSuggestions ( $input ) {
        
        define ( "LENGTH_THRESHOLD", 2 );
        define ( "LEVENSHTEIN_THRESHOLD", 2 );
        
        $trigrams = $this->BuildTrigrams( $input );
	$query = "\"$trigrams\"/1";
        
        
        $delta = LENGTH_THRESHOLD;
        $len = strlen($input);
        
        $this->keywordSuggestions   =  SphinxQL::create( $this->conn )
                                        ->select( '*', 'weight() as w', 'w+'. $delta .'-ABS(len-'. $len .') as myrank' )
                                        ->from('suggest')
                                        ->match( $query )
                                        ->limit(0, 4)
                                        ->orderBy( 'w', 'DESC' )
                                        ->orderBy( 'freq', 'DESC' )
                                        ->groupBy( 'keyword' )
                                        ->option('ranker', 'wordcount')
                                        ->execute();                                      
    }   
    
    function BuildTrigrams( $keyword )  {
        $t = "__" . $keyword . "__";
        $trigrams = "";

        for ($i = 0; $i < strlen($t) - 2; $i++){
            $trigrams .= substr($t, $i, 3) . " ";
        }

        return $trigrams;
    }       
    
    public function getCompleteSuggestions () {
        return $this->completeSuggestions;
    }
    
    public function getProductSnippets () {
        return $this->productSnippets;
    }
    
    public function getKeywordSuggestions () {
        return $this->keywordSuggestions;
    }
   
}
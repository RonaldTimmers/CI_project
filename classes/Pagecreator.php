<?php


namespace CI;


class Pagecreator {
	
    /* USED IN REACT FILTERS */
    /* This Data is Set in the setMetadata Method */
    public $p;          // Defines if we are on c, sc, search
   	public $refid;      // The id of the category, most specific used (if subsub then it's the subsubID)
    public $name; // The name of the most specific category
    public $description; // The description of the most specific category
    public $information; // The information shown of the most specific category
    
    public $rangeMax;   // max price within group of products  -> USES prMax 

    public $totalProducts; 
    public $attributeFilters = null; // create_filterbox()
    // public $shops = null;      // create_filterbox() -> now in pageinfo.class.php 
    public $keywords = ""; // Keywords used for search
    
    
    /* CHECK BENEATH IF STILL NECESSARY - TO DO */
    
	public $limit1;
	public $limit2;
	//public $pageitems; // items per page

    

	public $keywords_array; //price, stars and shops?
	public $keywords_array2; //price, stars and shops?
	public $currURLstr1; //The new made URL with alle $currURLstr as a part of it
	public $sql_string; //The actual query, used in sc.php instance of createthumbs->thumbs(sql_string)
	public $sql_string2; //The actual query, used in sc.php instance of createthumbs->thumbs(sql_string)
	public $baseURL;


	public $ref; // categorie ref



	public function __construct($baseURL) {
		$this->limit2 = 30;
		$this->baseURL = $baseURL;
        //$this->subcatid = $categoryinfo->subcat['id'];
	}
	
    function setMetadata($categoryinfo, $mode, $qclean){
        		
        if (isset( $_GET['subsubcat'] )) {
            $this->p = 'ps';
            $this->ref = $categoryinfo->subsubcat['ref'];
            $this->refid = $categoryinfo->subsubcat['id'];
            $this->name = $categoryinfo->subsubcat['name'];
            $this->description = $categoryinfo->subsubcat['description'];
           // $this->information = $categoryinfo->subsubcat['information'];
            
        } 
        
        elseif ( isset($_GET['subcat']) ) {
            $this->p = 'p';
            $this->ref = $categoryinfo->subcat['ref'];
            $this->refid = $categoryinfo->subcat['id'];
            $this->name = $categoryinfo->subcat['name'];
            $this->description = $categoryinfo->subcat['description'];
            $this->information = $categoryinfo->subcat['information'];
            
        } 
        
        elseif ( isset( $_GET['cat'] )) {
            $this->p = 'cat';
            $this->ref = $categoryinfo->cat['ref'];
            $this->refid = $categoryinfo->cat['id'];
            $this->name = $categoryinfo->cat['name'];
            $this->description = $categoryinfo->cat['description'];  
        } 
        
        elseif ( isset($_GET['brand']) ) {
            $this->p = 'brand';
            $this->ref = $categoryinfo->brand['ref'];
            $this->refid = $categoryinfo->brand['id'];
            $this->name = $categoryinfo->brand['brand'];
            $this->description = $categoryinfo->brand['description'];
        }
        	
        if (isset($mode) && $mode == 'search') {
            $this->p = 'search';
            $url_part1 = $this->baseURL .'search/'. $this->refid .'/'. $qclean .'/';
        } 
        else {
            $url_part1 = $this->baseURL . $this->p .'/'. $this->refid .'/'. $this->ref .'/';
        }  
    }  
	
	public function maxPrice($db, $subsubcat, $subcat, $cat, $pageref) {
		$param_array = array();
        $sortQuery = " ";
        $limitQuery = " ";
        $extraQuery = " ";
        $adultQuery = " ";
        
		$whereString = "`our_cat1` = '10000000000'";
		if ($this->sql_string2 == '' OR !isset($this->sql_string2)) {
			if (isset($_GET['subsubcat'])) {
				$whereString = "  AND (T1.our_subsub1 = ? OR T1.our_subsub2 = ? OR T1.our_subsub3 = ?)";
				$param_array[] = $subsubcat['id'];
				$param_array[] = $subsubcat['id'];
				$param_array[] = $subsubcat['id'];
			} elseif (isset($_GET['subcat'])) {
				$whereString = "  AND (T1.our_sub1 = ? OR T1.our_sub2 = ? OR T1.our_sub3 = ?)";
				$param_array[] = $subcat['id'];
				$param_array[] = $subcat['id'];
				$param_array[] = $subcat['id'];
			} elseif (isset($_GET['cat'])) {
				if ($cat['id'] == 0) {
					$whereString = " AND (T1.our_cat1 > ?)";
					$param_array[] = $cat['id'];
				} else {
					$whereString = " AND (T1.our_cat1 = ? OR T1.our_cat2 = ? OR T1.our_cat3 = ?)";
					$param_array[] = $cat['id'];
					$param_array[] = $cat['id'];
					$param_array[] = $cat['id'];
				}
			}
		} else {
			$whereString = $this->sql_string2;
			$param_array = $this->keywords_array2;
			if (isset($_GET['subsubcat'])) {
				$whereString .= " AND (T1.our_subsub1 = ? OR T1.our_subsub2 = ? OR T1.our_subsub3 = ?)";
				$param_array[] = $subsubcat['id'];
				$param_array[] = $subsubcat['id'];
				$param_array[] = $subsubcat['id'];
			} elseif (isset($_GET['subcat'])) {
				$whereString .= " AND (T1.our_sub1 = ? OR T1.our_sub2 = ? OR T1.our_sub3 = ?)";
				$param_array[] = $subcat['id'];
				$param_array[] = $subcat['id'];
				$param_array[] = $subcat['id'];
			} elseif (isset($_GET['cat']) && $_GET['cat'] != 0) {
				if ($cat['id'] == 0) {
					$whereString = " AND (T1.our_cat1 > ?)";
					$param_array[] = $cat['id'];
				} else {
					$whereString .= " AND (T1.our_cat1 = ? OR T1.our_cat2 = ? OR T1.our_cat3 = ?)";
					$param_array[] = $_GET['cat'];
					$param_array[] = $_GET['cat'];
					$param_array[] = $_GET['cat'];
				}
			}
		}
        
        if ($pageref == "new-arrivals") {
            $whereString = " ";
            $sortQuery = " ORDER BY T1.added DESC ";
            
            $backOneMonth = time() - 2592000;
            $extraQuery = " AND T1.added > ". $backOneMonth ." ";
            $adultQuery = " AND T1.our_cat1 != 17 ";
        }
        
        if ($pageref == "top-sellers") {
            $whereString = " ";
            $sortQuery = " ORDER BY T1.clicks DESC ";
            $extraQuery = " AND T1.clicks > 5 ";
            $adultQuery = " AND T1.our_cat1 != 17 ";
        }
		
		$queryString = "SELECT MAX(T1.price) As `priceMax` 
                        FROM `product_details` T1 
                        INNER JOIN `sources` T2
                        ON T1.source = T2.id  
                        WHERE T1.active = 1 AND T1.stock = 0 AND T2.current = 1 ". $whereString ." ". $extraQuery ." ". $adultQuery ."
                        ". $sortQuery ."
                        ". $limitQuery ." ";
                        
          
        // echo $queryString;
           
           
		$statement = $db->prepare($queryString);
		$statement->execute($param_array);
		$fetch = $statement->fetch(\PDO::FETCH_ASSOC);
		$statement = null;	
		unset($statement);
		$this->rangeMax = $fetch['priceMax'];
	}
	
	
	public function maxPages($db, $subsubcat, $subcat, $cat, $pageref) {
		$param_array = Array();
        $sortQuery = " ";
        $limitQuery = " ";
        $extraQuery = " ";
        $adultQuery = " ";
        
		$whereString = "`our_cat1` = '10000000000'";
		if ($this->sql_string == '' OR !isset($this->sql_string)) {
			if (isset($_GET['subsubcat'])) {
				$whereString = " AND (T1.our_subsub1 = ? OR T1.our_subsub2 = ? OR T1.our_subsub3 = ?)";
				$param_array[] = $subsubcat['id'];
				$param_array[] = $subsubcat['id'];
				$param_array[] = $subsubcat['id'];
			} elseif (isset($_GET['subcat'])) {
				$whereString = " AND (T1.our_sub1 = ? OR T1.our_sub2 = ? OR T1.our_sub3 = ?)";
				$param_array[] = $subcat['id'];
				$param_array[] = $subcat['id'];
				$param_array[] = $subcat['id'];
			} elseif (isset($_GET['cat'])) {
				if ($cat['id'] == 0) {
					$whereString = " AND (T1.our_cat1 > ?)";
					$param_array[] = $cat['id'];
				} else {
					$whereString = " AND (T1.our_cat1 = ? OR T1.our_cat2 = ? OR T1.our_cat3 = ?)";
					$param_array[] = $cat['id'];
					$param_array[] = $cat['id'];
					$param_array[] = $cat['id'];
				}
			}
		} else {
			$whereString = $this->sql_string;
			$param_array = $this->keywords_array;
			if (isset($_GET['subsubcat'])) {
				$whereString .= " AND (T1.our_subsub1 = ? OR T1.our_subsub2 = ? OR T1.our_subsub3 = ?)";
				$param_array[] = $subsubcat['id'];
				$param_array[] = $subsubcat['id'];
				$param_array[] = $subsubcat['id'];
			} elseif (isset($_GET['cat']) && $_GET['cat'] != 0) {
				if ($cat['id'] == 0) {
					$whereString = "(T1.our_cat1 > ?)";
					$param_array[] = $cat['id'];
				} else {
					$whereString .= " AND (T1.our_cat1 = ? OR T1.our_cat2 = ? OR T1.our_cat3 = ?)";
					$param_array[] = $_GET['cat'];
					$param_array[] = $_GET['cat'];
					$param_array[] = $_GET['cat'];
				}
			} elseif (isset($_GET['subcat'])) {
				$whereString .= " AND (T1.our_sub1 = ? OR T1.our_sub2 = ? OR T1.our_sub3 = ?)";
				$param_array[] = $subcat['id'];
				$param_array[] = $subcat['id'];
				$param_array[] = $subcat['id'];
			}
		}
        
        if ($pageref == "new-arrivals") {
            $whereString = " ";
            $sortQuery = " ORDER BY T1.added DESC ";
            
            $backOneMonth = time() - 2592000;
            $extraQuery = " AND T1.added > ". $backOneMonth ." ";
            $adultQuery = " AND T1.our_cat1 != 17 ";
            $this->totalProducts = 400;
        }
        
        if ($pageref == "top-sellers") {
            $whereString = " ";
            $sortQuery = " ORDER BY T1.clicks DESC ";
            $extraQuery = " AND T1.clicks > 5 ";
            $adultQuery = " AND T1.our_cat1 != 17 ";
            $this->totalProducts = 400;
        }
		
		//array_unshift($param_array);
		
		$queryString = "SELECT count(T1.id) As `idCount`, MAX(T1.price) As `priceMax` 
                        FROM `product_details` T1 
                        LEFT JOIN `sources` T2
                        ON T1.source = T2.id  
                        WHERE T1.active = 1  AND T2.current = 1 ". $whereString ." ". $extraQuery ." ". $adultQuery ."
                        ". $sortQuery ." ";
		//echo $queryString;
		//print_r($param_array);
		
		// prepare statement
		$statement = $db->prepare($queryString);
		// execute
		$statement->execute($param_array);
		// fetch
		$fetch = $statement->fetch(\PDO::FETCH_ASSOC);
		// free the result to clear memory
        $statement = null;	
		unset($statement);
		
		//$this->pmax = ceil($fetch['idCount'] / $this->limit2);
		//$this->pmax = max(1, $this->pmax);
        $this->rangeMax = $fetch['priceMax'];
        if ($pageref != "top-sellers" && $pageref != "new-arrivals") {
           $this->totalProducts = $fetch['idCount'];  
        }
       
	}
	     
        
	function create_filterbox($db) {
        // Now create the list of attribute filters
		if (isset($this->refid)) {

            //Get all data to setup a query. 
            //First, the categorie dependable 
            $cat =  $this->p;
            $id =   $this->refid;

            
            // Switch case for Categorie
            switch ($cat) {
                case "p":
                    $categorieQuery = " AND (T1.our_sub1 =  ". $id  ." OR T1.our_sub2 = ". $id ." OR T1.our_sub3 = ". $id .")";
                    $filterQuery = " f_a.filters_id IN ( SELECT f_s.filters_id FROM `filters_subcats` f_s WHERE f_s.subcats_id = ". $id ." ) ";
                    break;
                case "ps":
                    $categorieQuery = " AND (T1.our_subsub1 =  ". $id  ." OR T1.our_subsub2 = ". $id ." OR T1.our_subsub3 = ". $id .")";
                    $filterQuery = " f_a.filters_id IN ( SELECT f_s.filters_id FROM `filters_subsubcats` f_s WHERE f_s.subsubcats_id = ". $id ." ) ";
                    break;
                default:
            }

            $queryString = "SELECT attr.id, attr.name, attr.ref, filter.filtername
                            FROM `attributes` attr, `filters` filter, `filters_attributes` f_a
                            WHERE ". $filterQuery ."        
                            AND f_a.filters_id = filter.id
                            AND f_a.attributes_id = attr.id 
                            AND attr.del = 0
                            ORDER BY filter.order ASC
                            ";
                              
            $statement = $db->prepare($queryString);
            $statement->execute();
            
            while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                $result['filters'][$row['filtername']][$row['id']] = array('name' => $row['name'], 'ref' => $row['ref']);
            }
           
            if (isset($result)){
                $this->attributeFilters = $result;           
            } else {
                $this->attributeFilters = null;
            }

            $statement = null;
            
		}
        
        /*
        $activeShopsString = "SELECT  id, ref, name, url
                FROM `sources` 
                WHERE active = 1 AND current = 1
                ORDER BY id ASC
            ";
        
        
        $statement2 = $db->prepare($activeShopsString);
        $statement2->execute();
        $activeShops = $statement2->fetchAll(\PDO::FETCH_ASSOC);
        $this->shops = $activeShops;
        $statement2 = null;
        */
        
	}
       
}


?>
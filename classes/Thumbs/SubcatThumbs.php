<?php

namespace CI\Thumbs;

/**
 * Description of SubcatThumbs
 *
 * @author Ronald
 */

/* Creates subcat thumbs */
            
class SubcatThumbs {
    
    private $subcats;
    private $subsubcats;
    
    
    function set_subcats($db, $categoryinfo) {

		
        $statement = $db->prepare(" SELECT s.id, s.name, s.ref, s.pic
                                    FROM `subcats` s                                   
                                    WHERE s.cat = :cat AND s.catshow = 1 AND s.del = 0
                                    ORDER BY s.order ASC");      
                                    
        $statement->bindValue(':cat', $categoryinfo['id'], \PDO::PARAM_INT);
        $statement->execute();
        $subcats = $statement->fetchAll(\PDO::FETCH_ASSOC);
        unset($statement);	
       
       
       foreach ($subcats As $subcat) {
            $subcat_ids[] = $subcat['id'];
        }
        
        $subcat_ids_string = implode(',', $subcat_ids);
        
        
        $statement = $db->prepare(" SELECT ss.id, ss.name, ss.ref, ss.subcat
                                    FROM`subsubcats` ss                                    
                                    WHERE ss.subcat IN (". $subcat_ids_string .") AND ss.del = 0
                                    ORDER BY ss.order ASC");

        $statement->execute();
        $subsubcats = $statement->fetchAll(\PDO::FETCH_ASSOC);
        
        $statement = null;	
        unset($statement);
        
        $this->subcats = $subcats;
        $this->subsubcats = $subsubcats;
        
    }  
    
    function get_subcats ($categoryinfo) {
        global $baseURL;
        $subcats = $this->subcats;
        $subsubcats = $this->subsubcats;
        
        
        
        foreach ($subcats As $subcat) {
            echo '
            <div class="subcat-item col-ms-6 col-sm-4 col-md-4">
                <div class="clearfix"> 
                
                    <div class="links-holder">
                        <div class="subcat-title"><a href="'. $baseURL . $categoryinfo['ref'] .'/'. $subcat['ref'] .'-2-'. $subcat['id'] .'/"><h2>'. $subcat['name'] .'</h2></a></div>
                        <ul>';
                        
                        
            foreach ($subsubcats As $subsubcat) {
                if ($subsubcat['subcat'] == $subcat['id']) {
                   echo '
                   <li class="subsubcat-title"><a title="'. $subsubcat['name'] .'" class="" href="'. $baseURL . $categoryinfo['ref'] .'/'. $subsubcat['ref'] .'-3-'. $subsubcat['id'] .'/"><h3>'. $subsubcat['name'] .'</h3></a></li>
                   ';    
                }            
            }
            
            echo '</ul></div>
                    <div class="subcat-img">
                        <img class="img-responsive img-rounded center-block" src="'. $baseURL .'img/subcats/'. $subcat['pic'] .'"  alt="Category '. htmlspecialchars($subcat['name'], ENT_QUOTES) .'"/>
                    </div>
                </div>
            </div>';
            
        }
    }
      
      
    function subcatshotwrapper ($db) { 
        Global $baseURL;
        $limit = 0;
        
           $statement = $db->prepare("SELECT sub.id as id, sub.name as name, sub.ref as sub_ref, sub.pic as pic, cat.ref as cat_ref
                                      FROM subcats As sub 
                                      JOIN categories As cat
                                      ON sub.cat = cat.id
                                      WHERE sub.catshow = 1 AND sub.del = 0 AND sub.ref != 'unsorted' 
                                      ORDER BY sub.clicks DESC 
                                      LIMIT ". $limit .", 4");
           $statement->execute();

           while($obj = $statement->fetch(\PDO::FETCH_OBJ)) {
                echo '                                     
                    <div class="hot-item col-xs-6 col-sm-3">
                        <a href="'. $baseURL . $obj->cat_ref .'/'. $obj->sub_ref .'-2-'. $obj->id .'/">
                            <div class="hot-title">'. $obj->name .'</div>
                            <div class="hot-img">
                                    <img class="img-responsive img-rounded center-block" src="'. $baseURL .'img/subcats/'. $obj->pic .'" alt="Hot Category '. htmlspecialchars($obj->name, ENT_QUOTES) .'" />        
                            </div>
                        </a>
                    </div>';						
            }                                        


        $statement = null;
        unset($statement);
   
        }
}

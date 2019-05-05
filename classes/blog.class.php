<?php

namespace CI;

class blog {
	public $postname;
	private $id;
    
	
	function blogPreview($db) {
	global $baseURL;	
    $i = 0;
					// connect to the database and get information (txt)
					$statement = $db->prepare("SELECT ID, post_title, post_name, post_excerpt 
                                                               FROM wp_posts 
                                                               WHERE post_status = 'publish' AND post_type = 'post' 
                                                               ORDER BY  post_date DESC
                                                               LIMIT 0,2");
					$statement->execute();																										
					while ($blog = $statement->fetch(\PDO::FETCH_ASSOC)) {
					$blog['post_excerpt'] = substr($blog['post_excerpt'], 0, 300);

								 echo 	
								 '<div class="blog-box col-xs-12 col-sm-6">
                                     <div class="blog-text">
                                            <h4><a href="'. $baseURL .'blog/'. $blog['post_name'] .'">'. $blog['post_title'] .'</a></h4>
                                    </div>';
					
					$this->id = $blog['ID'];
					$this->postname = $blog['post_name'];
                    $this->blogPreviewPicture($db);
                    $i++;
                    }
                    
			$statement = null;	
			unset($statement);					
	}
			
	function blogPreviewPicture($db) {
	global $baseURL;
    
			$statement = $db->prepare("SELECT p.guid
                                                   FROM wp_postmeta AS pm
                                                   INNER JOIN wp_posts AS p ON pm.meta_value = p.ID 
                                                   WHERE pm.post_id =". $this->id ." AND pm.meta_key = '_thumbnail_id' 
                                                   "); 
			$statement->execute();																										
			while ($blogpicture = $statement->fetch(\PDO::FETCH_ASSOC)) {
                echo '<div class="blog-img"> 
                            <img src="'. $blogpicture['guid'] .'?fit=250%2C250" alt="'. $this->postname .'" class="img-responsive img-rounded center-block"></div>
                            <div class="cb"></div>
                            <a href="'. $baseURL .'blog/'. $this->postname .'/" title="Go To Blog Post">
                            <div class="fr news-moresign">></div>
                            <div class="fr news-more">Read More</div>
                            </a>
                            <div class="cb"></div>
                            </div>';
			}					
								
	
	$statement = null;	
	unset($statement);
	}
}


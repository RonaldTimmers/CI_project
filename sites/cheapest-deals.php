<?php
	if ($ping789tt != "RduikjTT967") {
		 exit('<h2>You cannot access this file directly</h2>');
	}
?>


	<div id="top-box">
            <div  id="page-title">
			 	<h1>Cheapest Deals</h1>
			</div>
	</div>
	<div style="clear: both;"></div>
	<div id="category-box">
	
			<?php
			$order1 = "T1.price";
			$order2 = "ASC";
			$limit1 = 0;
			$limit2 = 100;
			$sql_string = 'T1.id > 0';
			$keywords_array = array();
			
			$thumbs = new createThumbs();                        
			$thumbs->Create($db->linode,$order1,$order2,$limit1,$limit2,$sql_string,$keywords_array,1);
			?> 
	</div>

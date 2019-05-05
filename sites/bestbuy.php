<?php
	date_default_timezone_set('Europe/Amsterdam');

	if ($ping789tt != "RduikjTT967") {
		 exit('<h2>You cannot access this file directly</h2>');
	}

	// check if ref is set directly or if we need to get the latest by type
	if (!isset($_GET['ref']) && isset($_GET['type'])) {
			// ref not set directly, need to get the latest guide from a specific type
			//1 = phones, 2 = tablets, 3 = media players
			
			switch ($_GET['type']) {
				case "phones":
					$type_temp = 1;
					break;
				case "tablets":
					$type_temp = 2;
					break;
				case "mediaplayers":
					$type_temp = 3;
					break;
			}
			
			
			$statement = $db->linode->prepare("SELECT `ref` FROM `best_buy` WHERE `type` = ? ORDER BY `id` DESC");
			$statement->execute(Array($type_temp));
			$ref_temp = $statement->fetch();
			
			$link = $baseURL . 'bestbuy/' . $_GET['type'] .'/'. $ref_temp[0] .'/';
			
			echo '<div id="page-title"><h1>Best Buy Guide - '. $bestbuy['type'] .'</h1></div><div id="dbcontent-box">		<!-- CI logo -->	
				<div id="logo-bar"><div id="logo-box"></div></div>
				<div id="BestBuy">You will be redirected shortly to the most up to date version.</div>';
			
			echo '<script type="text/javascript">
			<!--
			window.location = "'. $link .'"
			//-->
			</script>';
			
	}
?>

<?php
		
/* Set Share Buttons - Moet in main functions ivm ook voorkomen op SKU page*/ 
		
	function get_Addthis() {
		echo '<!-- AddThis Button BEGIN -->
					<div class="addthis_toolbox addthis_default_style addthis_16x16_style">
					<a class="addthis_button_twitter"></a>
					<a class="addthis_button_facebook"></a>
					<a class="addthis_button_google_plusone_share"></a>
					<a class="addthis_button_pinterest_share"></a>
					<a class="addthis_button_email"></a>
					<a class="addthis_button_print"></a>
					<a class="addthis_button_compact"></a><a class="addthis_counter addthis_bubble_style"></a>
					</div>
					
					
					<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
					<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5236f40631e1a8aa"></script>
			<!-- AddThis Button END -->';
	}	

	
?>


<?php

/* Set Best Buy Home Page*/

if (isset($_GET['ref']) && isset($_GET['type'])) {
	$bestbuy = Array();	// declare Bestbuy array
	$product = Array();	// declare product thumbs array
	
	$bestbuy['ref'] = $_GET['ref'];
	if ($_GET['type'] == 'phones') {
		$bestbuy['type'] = 1; 
	} elseif ($_GET['type'] == 'tablets') {
		$bestbuy['type'] = 2; 
	} elseif ($_GET['type'] == 'mediaplayers') {
		$bestbuy['type'] = 3; 
	}
	
	// connect to the database and get information
	$statement = $db->linode->prepare("SELECT `type`,`date`,`ref`,`title`,`descrp`,`preface`,`html1`,`html2`,`html3`,`html4`,`html5`,`product1`,`product2`,`product3`,`product4`,`product5`,`product6`,`product7`,`product8`,`product9`,`product10`,`product11`,`product12`,`product13`,`product14`,`product15`,`product16` FROM `best_buy` WHERE `active` = 1 AND `ref` = ? AND `type` = ?");	// prepare statement
	$statement->execute(Array($bestbuy['ref'], $bestbuy['type']));																											// execute query
	$bestbuy = $statement->fetch();		// fetch the results
	
	$row1 = array($bestbuy['product1'], $bestbuy['product2'], $bestbuy['product3'], $bestbuy['product4']);
	$row2 = array($bestbuy['product5'], $bestbuy['product6'], $bestbuy['product7'], $bestbuy['product8']);
	$row3 = array($bestbuy['product9'], $bestbuy['product10'], $bestbuy['product11'], $bestbuy['product12']);
	$row4 = array($bestbuy['product13'], $bestbuy['product14'], $bestbuy['product15'], $bestbuy['product16']);

	$bestbuy['date'] = substr_replace($bestbuy['date'], '', 10);
	
	/* Get thumb product information */	
	$row = array();
	
	$productQuery = "SELECT T1.id,T1.title,T1.thumb_path,T1.price,T1.stars,T1.reviews,T1.source,T2.name,T2.logo 
					FROM `product_details` T1 LEFT JOIN `sources` T2 ON T2.id = T1.source 
					WHERE T1.active = 1 
						AND (T1.id = ? OR T1.id = ? OR T1.id = ? OR T1.id = ? OR T1.id = ? OR T1.id = ? OR T1.id = ? OR T1.id = ? OR T1.id = ? OR T1.id = ? OR T1.id = ? OR T1.id = ? OR T1.id = ? OR T1.id = ? OR T1.id = ? OR T1.id = ?)";
	
	$statement1 = $db->linode->prepare($productQuery);	// prepare statement
	$statement1->execute(Array($bestbuy['product1'],$bestbuy['product2'],$bestbuy['product3'],$bestbuy['product4'],$bestbuy['product5'],$bestbuy['product6'],$bestbuy['product7'],$bestbuy['product8'],$bestbuy['product9'],$bestbuy['product10'],$bestbuy['product11'],$bestbuy['product12'],$bestbuy['product13'],$bestbuy['product14'],$bestbuy['product15'],$bestbuy['product16']));			// execute query
	while ($thumb = $statement1->fetch()) {
		if (in_array($thumb['id'], $row1)) {
			$i = 1;
		} elseif (in_array($thumb['id'], $row2)) {
			$i = 2;
		} elseif (in_array($thumb['id'], $row3)) {
			$i = 3;
		} elseif (in_array($thumb['id'], $row4)) {
			$i = 4;
		}
		
		$row[$i][$thumb['id']]['id'] = $thumb['id'];
		$row[$i][$thumb['id']]['title'] = $thumb['title'];
		$row[$i][$thumb['id']]['thumb'] = $thumb['thumb_path'];
		$row[$i][$thumb['id']]['USD'] = $thumb['price'];
		$row[$i][$thumb['id']]['stars'] = $thumb['stars'];
		$row[$i][$thumb['id']]['reviews'] = $thumb['reviews'];
		$row[$i][$thumb['id']]['source'] = $thumb['source'];
		$row[$i][$thumb['id']]['name'] = $thumb['name'];
		$row[$i][$thumb['id']]['logo'] = $thumb['logo'];
	}
	
	/* ECHO Hoofd INFO*/

	if ($bestbuy['type'] == 1) {
		$bestbuy['type'] = 'Phones'; 
	} elseif ($bestbuy['type'] == 2) {
		$bestbuy['type'] = 'Tablets'; 
	} elseif ($bestbuy['type'] == 3) {
		$bestbuy['type'] = 'Mediaplayers'; 
	}
	

	
		echo '<div id="page-title"><h1>Best Buy Guide - '. $bestbuy['type'] .'</h1></div>
		<div style="clear: both;"></div>
		<div id="dbcontent-box">
                        <div id="BBshare">';
                        get_Addthis();
                        echo	'</div>';
			echo	'<div id="BestBuy">
					<div class="bbDescr">';
	
					echo '<h2>'. $bestbuy['title'] .'</h2>
						<p>'. $bestbuy['preface'] .'</p>
					</div>';
					
				/* ECHO 1 HTML Alinea*/
	
				echo $bestbuy['html1'];
				
				/* ECHO ROW 1 Products*/
				echo '<div class="bbthumbs-row">';
						foreach ($row[1] As $result) {	
							echo '<div class="fl product-item">
                                                <a href="'. $baseURL .'sku/'. $result['id'] .'-'. urlFriendly($result['title']) .'/" title="'. $result['title'] .'">
                                                    <div class="product-img">		
                                                        <img src="'. $staticURL . $result['thumb'] .'" alt="'. $result['title'] .'" />
                                                    </div>
                                                    <div class="product-title">'. $result['title'] .'</div>
                                                    <div class="product-price">&#36;'. $result['USD']  .'</div>
                                                    <a href="'. $baseURL .'goto/'. $result['id'] .'/" rel="nofollow" target="_blank" title="Go directly to the product at '. $result['name'] .'">
													<div class="product-source"><div class="'. $result['logo'] .'"></div></div> 
                                                    </a>
							<div class="product-stars"><span class="star0"><span class="star'. $result['stars'] .'"></span></span></div><div class="product-reviews"> ('. $result['reviews'] .')</div>
                                                </a>
                                        </div>';	
						}
					echo '<div style="clear:both;"></div>
				</div>';
				/* ECHO 2 HTML Alinea*/
				
				echo $bestbuy['html2'];
				
				/* ECHO ROW 2 Products*/
				echo '<div class="bbthumbs-row">';
						foreach ($row[2] As $result) {	
							echo '<div class="fl product-item">
                                                <a href="'. $baseURL .'sku/'. $result['id'] .'-'. urlFriendly($result['title']) .'/" title="'. $result['title'] .'">
                                                    <div class="product-img">		
                                                        <img src="'. $staticURL . $result['thumb'] .'" alt="'. $result['title'] .'" />
                                                    </div>
                                                    <div class="product-title">'. $result['title'] .'</div>
                                                    <div class="product-price">&#36;'. $result['USD']  .'</div>
                                                    <a href="'. $baseURL .'goto/'. $result['id'] .'/" rel="nofollow" target="_blank" title="Go directly to the product at '. $result['name'] .'">
													<div class="product-source"><div class="'. $result['logo'] .'"></div></div> 
                                                    </a>
							<div class="product-stars"><span class="star0"><span class="star'. $result['stars'] .'"></span></span></div><div class="product-reviews"> ('. $result['reviews'] .')</div>
                                                </a>
                                        </div>';	
						}
					echo '<div style="clear:both;"></div>
				</div>';
				
				/* ECHO 3 HTML Alinea*/
				
				echo $bestbuy['html3'];
				
				/* ECHO ROW 3 Products*/
				echo '<div class="bbthumbs-row">';
						foreach ($row[3] As $result) {	
							echo '<div class="fl product-item">
                                                <a href="'. $baseURL .'sku/'. $result['id'] .'-'. urlFriendly($result['title']) .'/" title="'. $result['title'] .'">
                                                    <div class="product-img">		
                                                        <img src="'. $staticURL . $result['thumb'] .'" alt="'. $result['title'] .'" />
                                                    </div>
                                                    <div class="product-title">'. $result['title'] .'</div>
                                                    <div class="product-price">&#36;'. $result['USD']  .'</div>
                                                    <a href="'. $baseURL .'goto/'. $result['id'] .'/" rel="nofollow" target="_blank" title="Go directly to the product at '. $result['name'] .'">
													<div class="product-source"><div class="'. $result['logo'] .'"></div></div> 
                                                    </a>
							<div class="product-stars"><span class="star0"><span class="star'. $result['stars'] .'"></span></span></div><div class="product-reviews"> ('. $result['reviews'] .')</div>
                                                </a>
                                        </div>';	
						}
				echo '<div style="clear:both;"></div>
				</div>';
				
				/* ECHO 4 HTML Alinea*/
				
				echo $bestbuy['html4'];
				
				/* ECHO ROW 4 Products*/
				echo '<div class="bbthumbs-row">';
						foreach ($row[4] As $result) {	
							echo '<div class="fl product-item">
                                                <a href="'. $baseURL .'sku/'. $result['id'] .'-'. urlFriendly($result['title']) .'/" title="'. $result['title'] .'">
                                                    <div class="product-img">		
                                                        <img src="'. $staticURL . $result['thumb'] .'" alt="'. $result['title'] .'" />
                                                    </div>
                                                    <div class="product-title">'. $result['title'] .'</div>
                                                    <div class="product-price">&#36;'. $result['USD']  .'</div>
                                                    <a href="'. $baseURL .'goto/'. $result['id'] .'/" rel="nofollow" target="_blank" title="Go directly to the product at '. $result['name'] .'">
							<div class="product-source"><div class="'. $result['logo'] .'"></div></div> 
                                                    </a>
							<div class="product-stars"><span class="star0"><span class="star'. $result['stars'] .'"></span></span></div><div class="product-reviews"> ('. $result['reviews'] .')</div>
                                                </a>
                                        </div>';	
						}
					echo '<div style="clear:both;"></div>
				</div>';
				
				/* ECHO 5 HTML Alinea*/
				
				echo $bestbuy['html5'];

			echo '</div></div>';
} else {
	/* GET Bestbuy OVERVIEW page*/
	
	echo ' <div  id="page-title"><h1>Best Buy Guides</h1></div><div style="clear: both;"></div><div id="dbcontent-box">
		<!-- CI logo -->	
		<div id="logo-bar"><div id="logo-box"></div></div>
	<div id="archive">'; 

		// get Phones
		$statement = $db->linode->query("SELECT `type`,`ref`,`title`,`date` FROM `best_buy` WHERE `active` = 1 AND `type` = 1 ORDER BY `date` DESC");
		
		echo '<h2>Phones</h2>'; 
		echo '<div class="bbArchiveList">'; 
			while ($phones = $statement->fetch()){
				$phones['type'] = 'phones';
				
				echo '<div class="bestbuyrow"><h3><a href="'. $baseURL .'bestbuy/'. $phones['type'] .'/'. $phones['ref'] .'/">'. $phones['title'] .'</a></h3><p><i>'. date("F j, Y", $phones['date']) .'</i></p></div>';
			}
		
		echo '</div><div style="clear:both;"></div>';																								

		// get Tablets
		$statement = $db->linode->query("SELECT `type`,`ref`,`title`,`date` FROM `best_buy` WHERE `active` = 1 AND `type` = 2 ORDER BY `date` DESC");													// setup binds to the bestbuy array
						 
		echo '<h2>Tablets</h2>';
		echo '<div class="bbArchiveList">';
			while ($tablets = $statement->fetch()){
				$tablets['type'] = 'tablets';

				echo '<div class="bestbuyrow"><h3><a href="'. $baseURL .'bestbuy/'. $tablets['type'] .'/'. $tablets['ref'] .'/">'. $tablets['title'] . '</a></h3><div style="clear:both;"></div><p><i>'. date("F j, Y", $tablets['date']) .'</i></p></div>';
			}
		echo '</div><div style="clear:both;"></div>';																								// free results (clear memory space)
			
		// get Mediaplayers
		$statement = $db->linode->query("SELECT `type`,`ref`,`title`,`date` FROM `best_buy` WHERE `active` = 1 AND `type` = 3 ORDER BY `date` DESC");	
		
		echo '<h2>Mediaplayers</h2>';  
		echo '<div class="bbArchiveList">';
			while ($mediaplayers = $statement->fetch()){
				$mediaplayers['type'] = 'mediaplayers';

				echo '<div class="bestbuyrow"><h3><a href="'. $baseURL .'bestbuy/'. $mediaplayers['type'] .'/'. $mediaplayers['ref'] .'/">'. $mediaplayers['title'] . '</a></h3><p><i>'. date("F j, Y", $mediaplayers['date']) .'</i></p></div>';
			}
		
		echo '</div><div style="clear:both;"></div>';

	echo '</div>';
}
?>

</div>
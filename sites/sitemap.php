<?php
	if ($ping789tt != "RduikjTT967") {
		 exit('<h2>You cannot access this file directly</h2>');
	}
?>

<?php
// sitemap queries

/* Set the informational links 

	$info_string = '<ul>';
	$stmt = $db->linode->query("SELECT `ref`,`name` FROM `CMS_pages` WHERE `active` = 1 ORDER BY `id`");																					// setup binds to the page array
	While($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {																						// fetch all the results in a loop
		if ($row['ref'] != 'error-403' && $row['ref'] != 'error-404' && $row['ref'] != 'search') {
			$info_string .= '<li><a href="'. $baseURL . $row['ref'] .'/">'. $row['name'] .'</a></li>';
		}
	}
	$info_string .= '</ul>';																								// free results (clear memory space)
/* Set the informational links */

?>



<div class="sitemap-wrapper">	
	<div id="page-title">
		<h1>SiteMap</h1>
	</div>
	<div style="clear: both;"></div>
	<div class="sitemap-box">

			<div class="categories">
				
				<?php
					//echo $cat_string;
                                        //include_once($baseURL .'includes/html/JSnavbar.html');
                                        include_once 'includes/html/sitemap.html';
				?>
			</div
			<div style="clear: both"></div>

	</div>
</div>
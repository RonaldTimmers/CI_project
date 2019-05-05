<?php
    //ini_set('display_errors', 'on');

    $ping789tt = 'RduikjTT967';						// password for the require file
    require_once ("functions/mainfunctions.php");	// require once
    require_once (($_SERVER['DOCUMENT_ROOT']).'/vendor/autoload.php');
    
    use CI\Db;
?>
<?php 	
    // set website base address
	if ($_SERVER['HTTP_HOST'] == 'test.bimmith.com' || $_SERVER['HTTP_HOST'] == '87.210.5.44') {
		$baseURL = "http://test.bimmith.com/";	
	} else {
		$baseURL = "https://www.compareimports.com/";	
	}
    
	// openDB();
    $db = new Db();
    $db->connect_linode();	
	
    $link = '';
	
    /*
     * Goto Part For SKU product
     * 
     */  
    
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $sql = "UPDATE `product_details` SET `clicks` = `clicks` + 1 WHERE `id` = :id ";
            $statement = $db->linode->prepare($sql);
            $statement->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
            $statement->execute();
            $statement->closeCursor();

            $sql = "SELECT `link`,`our_sub1` FROM `product_details` WHERE `id` = :id ";
            $statement = $db->linode->prepare($sql);
            $statement->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch();
            $statement->closeCursor();

            $sql = "UPDATE `subcats` SET `clicks` = `clicks` + 1 WHERE `id` = :sub ";
            $statement = $db->linode->prepare($sql);
            $statement->bindParam(':sub', $result['our_sub1'], PDO::PARAM_INT);
            $statement->execute();
            $statement->closeCursor();
            
            
            $link = $result['link'];
            
    }
    
    /*
     * Goto Part For ShopLogo
     * 
     */  

    if (isset($_GET['source_id']) && is_numeric($_GET['source_id'])) {
            $sql = "SELECT `url` FROM `sources` WHERE `id` = :id ";
            $statement = $db->linode->prepare($sql);
            $statement->bindParam(':id', $_GET['source_id'], PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch();
            $statement->closeCursor();
            
            
            $link = $result['url'];
    }

    $db = null;
    
    /*
     * 1. Check if the Cookie GCLID exist
     * 2. Check if the affiliate system used is AdmitAD
     * - Otherwise do Nothing
     */
    

    if ( strpos($link, 'ad.admitad.com') !== false && isset( $_COOKIE['gclid'] )) {
        $link .= "&subid4=". $_COOKIE['gclid'];
    }
    
    // xdebug_break();
    
    if ($link == '' || !isset($link)) {
            echo 'Error, we can\'t find your link';
    } else {
    
    // Send User to the Appropria
    header("Refresh:0.1; url=". $link);
}

?>
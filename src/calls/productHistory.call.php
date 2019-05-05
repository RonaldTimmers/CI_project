<?php
header('Content-Type: application/json');


$ping789tt = 'RduikjTT967'; // password for the require file

if ($_SERVER['HTTP_HOST'] == 'test.bimmith.com' || $_SERVER['HTTP_HOST'] == '87.213.252.132') {
        $baseURL = "http://test.bimmith.com/";  define('BASE_URL',  'http://test.bimmith.com/');
        $cdnURL = "http://test.bimmith.com/";   define('CDN_URL',  'http://test.bimmith.com/');
} 
else {
        $baseURL = "https://www.compareimports.com/";   define('BASE_URL',  'https://www.compareimports.com/');
        $cdnURL = "https://cdn.compareimports.com/";    define('CDN_URL',  'https://cdn.compareimports.com/');
}

/* 
 * set static base address (pictures)
 * $staticURL = "https://static.compare-imports.com/";
 */
$staticURL = "https://cdn.compare-imports.com/";    define('STATIC_URL',  'https://cdn.compare-imports.com/');



function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}  

/* We are getting the Product ID from de Javascript AJAX Call
   This is called in Mainfilters -> TODO Set in seperate file
*/
$pid = isset($_POST['pid']) ? test_input($_POST['pid']) : NULL;

require_once (($_SERVER['DOCUMENT_ROOT']).'/functions/mainfunctions.php');
require_once (($_SERVER['DOCUMENT_ROOT']).'/vendor/autoload.php');

use CI\Db;
use CI\Product;

$db = new Db();
$db->connect_linode();	
    
    
    

/* This will get the necessary product info 
    and store it into public variable $productinfo
*/
$sku = new Product;
$sku->set_productinfo($db->linode, $pid);
$sku->productinfo['URLtitle'] =  urlFriendly($sku->productinfo['title']);



$pricehistorydates = $sku->set_productpricehistory( $db->linode, $pid );
$prices = array_map(function ( $ar ) {return $ar['price'];}, $pricehistorydates);
$dates = array_map(function ( $ar ) {return date("F j, Y", $ar['start']);}, $pricehistorydates);


$db = null;
unset($db);

$product['info'] = $sku->productinfo;
$product['pricehistory'] = $pricehistorydates;
$product['prices'] = array_reverse($prices);
$product['dates'] = array_reverse($dates); 

/* In the END echo the array into a JSON object to be used in productHTML.call.php 
*/
echo json_encode($product);
        
?>
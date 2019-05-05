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


/*
require_once '../classes/constants.class.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/classes/coupons.class.php';
require_once '../classes/product.class.php';
require_once '../classes/brandcarousel.class.php';
*/


require_once (($_SERVER['DOCUMENT_ROOT']).'/vendor/autoload.php');


use CI\Product;
use CI\Coupons;
use CI\MobileDetect;

//Check if the user uses a mobile, tablet or computer
$detect = new MobileDetect;
define('DEVICE_TYPE', ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer'));    
 
/* 
* Incoming data from productJSON.call.php 
*/
$productInfo = isset($_POST['product']) ? $_POST['product'] : NULL;


/* Initiate product class and get_productdetails method - this will produce the 
* product details html 
* At the same time of the succes we will initiate the relatedProductsHTML.call.php 
* this will search and fetch the similair products. 
*/
 
$product = new Product();

$productInfo_array =  (array) $productInfo['info'];

$productPriceHistory_array =  null;
$productCoupons_array = null;

if (isset($productInfo['pricehistory'])) {
    $productPriceHistory_array =  (array) $productInfo['pricehistory'];  
} 

if (isset($productInfo['coupons'])) {
    $productCoupons_array =  (array) $productInfo['coupons'];  
} 

echo $product->get_productdetails($productInfo_array, $productPriceHistory_array, $productCoupons_array);

?>

<div class="cb"></div>

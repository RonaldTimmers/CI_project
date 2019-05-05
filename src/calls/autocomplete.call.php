<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


header('Content-Type: application/json');

$ping789tt = 'RduikjTT967'; // password for the require file

if ($_SERVER['HTTP_HOST'] == 'test.bimmith.com' || $_SERVER['HTTP_HOST'] == '87.213.252.132') {
        $baseURL = "http://test.bimmith.com/";  define('BASE_URL',  'http://test.bimmith.com/');
        $cdnURL = "http://test.bimmith.com/";   define('CDN_URL',  'http://test.bimmith.com/');
} else {
        $baseURL = "https://www.compareimports.com/";   define('BASE_URL',  'https://www.compareimports.com/');
        $cdnURL = "https://cdn.compareimports.com/";    define('CDN_URL',  'https://cdn.compareimports.com/');
}

/* 
 * set static base address (pictures)
 * $staticURL = "https://static.compare-imports.com/";
 */
$staticURL = "https://cdn.compare-imports.com/";    define('STATIC_URL',  'https://cdn.compare-imports.com/');

/*
require (($_SERVER['DOCUMENT_ROOT']).'/classes/db.class.php');
require (($_SERVER['DOCUMENT_ROOT']).'/vendor/autoload.php');
require (($_SERVER['DOCUMENT_ROOT']).'/classes/filters.class.php');
require (($_SERVER['DOCUMENT_ROOT']).'/classes/searchQL.class.php');
*/

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}  

require (($_SERVER['DOCUMENT_ROOT']).'/functions/mainfunctions.php');
require_once (($_SERVER['DOCUMENT_ROOT']).'/vendor/autoload.php');


use CI\Db;
use CI\Filters;
use CI\SearchQL;
use CI\SearchSuggestion;


/* The Call Part
 * 
 */




$input = isset($_POST['phrase']) ? test_input($_POST['phrase']) : NULL;

$searchInput = new SearchSuggestion(); 
$searchInput->setCompleteSuggestions( $input );

echo json_encode(  $searchInput->getCompleteSuggestions() );


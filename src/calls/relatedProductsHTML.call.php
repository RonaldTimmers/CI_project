<?php   
    $ping789tt = 'RduikjTT967'; // password for the require file

    
    header('Content-Type: application/json');
    
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
    

    require_once (($_SERVER['DOCUMENT_ROOT']).'/functions/mainfunctions.php');
    require_once (($_SERVER['DOCUMENT_ROOT']).'/vendor/autoload.php');
    
    use CI\Db;
    use CI\Product;
    use CI\Controllers\ThumbsController; 
    
    
    $loader = new Twig_Loader_Filesystem( $_SERVER['DOCUMENT_ROOT'] .'/templates');

    $twig = new Twig_Environment($loader, array(
        'cache' => $_SERVER['DOCUMENT_ROOT'] .'/templates/cache',
        'debug' => true,
        'auto_reload' => true
    ));


    $twig->addGlobal('BASE_URL', BASE_URL);
    $twig->addGlobal('CDN_URL', CDN_URL);
    $twig->addGlobal('STATIC_URL', STATIC_URL);
    
    
    
    
    $db = new Db();
    $db->connect_linode();	

    
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }  
    
    $action = isset($_POST['action']) ? test_input($_POST['action']) : NULL;
    
    
    switch($action) {
        case 'call':
            // Getting Data From AJAX Call
            $product_title = isset($_POST['product_title']) ? test_input($_POST['product_title']) : NULL;
            $product_source = isset($_POST['product_source']) ? test_input($_POST['product_source']) : NULL;
            $product_price = isset($_POST['product_price']) ? test_input($_POST['product_price']) : NULL;
            $product_id = isset($_POST['product_id']) ? test_input($_POST['product_id']) : NULL;
            
            // Initiate product to set similairs products and to fetch HTML similair products.
            /* See product.class.php */
            $product = new Product();
            $product->set_similairproducts( $db->linode, $product_id, $product_source, $product_title, $product_price );
            $product->get_similairproducts( $product->similairproducts['thumbs'] );
            $product->get_loadmore();
            break;
        
        case 'load_more':
            $main_product_id = isset($_POST['main_product_id']) ? test_input($_POST['main_product_id']) : NULL;
            $main_product_source = isset($_POST['main_product_source']) ? test_input($_POST['main_product_source']) : NULL;
            $main_product_title = isset($_POST['main_product_title']) ? test_input($_POST['main_product_title']) : NULL;
            $main_product_price = isset($_POST['main_product_price']) ? test_input($_POST['main_product_price']) : NULL;
            
            $offset = isset($_POST['offset']) ? test_input($_POST['offset']) : NULL;
            
           
            //$total_product_ids = isset($_POST['total_product_ids']) ? test_input($_POST['total_product_ids']) : NULL;
            //$product_ids = isset($_POST['product_ids']) ? test_input($_POST['product_ids']) : NULL;
            
            
            $product = new Product();
            //$product->get_match_ids($db->linode, $main_product_id, $total_product_ids, $product_ids, $offset);
            $product->set_similairproducts( $db->linode, $main_product_id, $main_product_source, $main_product_title, $main_product_price, $offset );
            $product->get_similairproducts( $product->similairproducts['thumbs'] );
            // $product->get_loadmore();
            
            break;
        default:
        break;
    }
    


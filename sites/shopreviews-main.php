<?php
date_default_timezone_set('Europe/Amsterdam');       
    
	if ($ping789tt != "RduikjTT967") {
		 exit('<h2>You cannot access this file directly</h2>');
	}
     
if (!isset($shopreview->shopRef)) { 
?>
    
<div class="page-header">
    <h1>China Online Shops<br>
        <small>
        A list of the best Chinese online shopping sites
        </small>
    </h1>
</div>


<section class="text-content">
    <p><b>China online shopping</b> can be overwhelming, so where to start? 
    We all know the stories about undelivered parcels, long <b>delivery times</b>, <b>warranty</b> problems or bad <b>customer support</b>. 
    All issues you don't want when buying online. So, should we even buy something in China? Yes, certainly and we will help you with that! Below the list with the <b>best Chinese Online Shops</b>.</p>
</section> 

    <hr />
    <div class="clearfix"></div>
    
<section>
    <div class="container-fluid row">
        <?php $shopreview->getShopData($db->linode, $baseURL); ?>
    </div>
</section>
       
    <div class="clearfix"></div>   
    
    <hr />
    
<section class="text-content">
    <p>The earlier stories are all true, but <b>wholesale stores</b> from China are changing. 
    We (<a href="<?php echo $baseURL .'about/';?>">China Experts from CompareImports</a>) are buying products from <b>Chinese online stores</b> since 2011 and their quality improved in several ways.
    Chinese stores are <b>reliable shopping partners</b> these days.Yet, there will remain differences. </p>

    <p>Thus, we wrote reviews and shared interesting information about each webshop. 
    What is their warranty policy? Do they have big Chinese brands? Do they offer a lucrative point system? And are there interesting <b>coupons for discounts</b>?
    Read the individual <b>shop reviews</b> to find the answers to these questions. 
    Besides, you can also help other customers with telling your personal tips and stories. </p>           
</section>
      

<?php }

else {
    include 'shopreviews.php';
}

?>
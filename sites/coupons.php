<?php
    if ($ping789tt != "RduikjTT967") {
             exit('<h2>You cannot access this file directly</h2>');
    }
    
        
    use CI\Coupons;
        
    $coupons  =  new Coupons;
    $coupons->set_coupons($db->linode);
    $coupons->set_total_coupons($db->linode);
?>

         
         
    <div class="page-header">
        <h1>China Webshop Coupon Codes<br />
            <small>
            Coupon codes give you the ability to get a discount on the ordered products. 
            Mostly you can activate them at the shopping cart of the online webshop.
            </small>
        </h1>
    </div>
        
    
    <div class="hidden-xs hidden-ms col-sm-3">
        <?php $coupons->get_total_coupons(); ?>
    </div>
        
    <div id="" class="col-ms-12 col-sm-9">
        <?php $coupons->get_coupons();?>
    </div>
    
	
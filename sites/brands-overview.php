<?php
date_default_timezone_set('Europe/Amsterdam');
if ($ping789tt != "RduikjTT967") {
     exit('<h2>You cannot access this file directly</h2>');
}

use CI\Brands;
     
if (!isset($brandpage->brandRef)) { 
?>
    
<div class="page-header">
    <h1>Brands from China<br>
        <small>
        A list of all the Chinese brands worth to know
        </small>
    </h1>
</div>

<div id="dbcontent-box">
<p>In this section, you will find all well-known brands from China. This will be leading global brands, upcoming brands, and brands which perform very well in China. <b>These Chinese brands sell quadcopters, drones, smartphones, tablets, tv boxes, RC toys and (smart)watches. </b></p>

<p>We know that there are many good and qualitative products from China. However, sometimes you don't see them through the overwhelming offer. Because that difficulty we started this overview.</p> 

<p><b>If you buy something from one of those brands you will buy excellent products</b>. Click on a brand logo to see their offer at Chinese shops.</p>

</div>       
<hr />
<div>
    <?php 
    
    Brands::getBrandOverview_ABTEST($db->linode, $baseURL);
    
    ?>
</div> 
<div class="clearfix"></div>         
    

<?php }

else {
    include 'brands-single.php';
}

?>
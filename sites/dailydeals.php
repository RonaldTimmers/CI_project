 <?php 
 
if ($ping789tt != "RduikjTT967") {
     exit('<h2>You cannot access this file directly</h2>');
}
 
use CI\DailyDeals; 
 
$dailydealspage = new DailyDeals();
$dailydealspage->set_dailydeals($db->linode); 
?>



<?php
// foreach ($dailydealspage->timeFrame as $timeframe) { ?>   
    <div class="page-header">
        <h1>China Daily Flash Deals<br />
            <small>
            China webshops offer new flash deals everday, find them all on CompareImports in one overview.
            </small>
        </h1>
    </div>
    
    <div class="clearfix"></div>
    
    <div id="category-box">
        <?php
            DailyDeals::get_dailydeals($dailydealspage->Thumbs);      
        ?>
        <div class="clearfix"></div>
    
    </div>  
    
<?php
//}     
?> 
                    
                    
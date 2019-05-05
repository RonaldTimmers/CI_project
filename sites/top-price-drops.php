<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<div class="page-header">
  <h1>Top 500 Best Price Decreases in China<br /><small>Find the Best Deals of the Moment</small></h1>
</div>


    <ul class="nav nav-tabs" role="tablist">

        <li id="1-tab" role="presentation" class="active"><a data-toggle="tab1" href="#tab1">Top 50</a></li>
        
        <li id="2-tab" role="presentation"><a data-toggle="tab" href="#tab2">50-100</a></li>
       
        <li id="3-tab" role="presentation"><a data-toggle="tab" href="#tab3">100-150</a></li>

        <li id="4-tab" role="presentation"><a data-toggle="tab" href="#tab4">150-200</a></li>
        
        <li id="5-tab" role="presentation"><a data-toggle="tab" href="#tab5">200-250</a></li>
        
        <li id="6-tab" role="presentation"><a data-toggle="tab" href="#tab6">250-300</a></li>
        
        <li id="7-tab" role="presentation"><a data-toggle="tab" href="#tab7">300-350</a></li>
        
        <li id="8-tab" role="presentation"><a data-toggle="tab" href="#tab8">350-400</a></li>
        
        <li id="9-tab" role="presentation"><a data-toggle="tab" href="#tab9">400-450</a></li>
                
        <li id="10-tab" role="presentation"><a data-toggle="tab" href="#tab10">450-500</a></li>
        
    </ul>


<div class="price-history-box container-fluid">
    <div class="tab-content row">
        <div id="tab1" class="tab-pane fade in active" role="tabpanel">
        <?php 

            $home_pricehistory = $_SERVER['DOCUMENT_ROOT'] .'/includes/html/pricehistory_top_result_0.html'; 
            include $home_pricehistory;

        ?>
        </div>

        <div id="tab2" class="tab-pane fade" role="tabpanel">
        </div>

        <div id="tab3" class="tab-pane fade" role="tabpanel">
        </div>
        
        <div id="tab4" class="tab-pane fade" role="tabpanel">
        </div>
        
        <div id="tab5" class="tab-pane fade" role="tabpanel">
        </div>
        
        <div id="tab6" class="tab-pane fade" role="tabpanel">
        </div>
        
        <div id="tab7" class="tab-pane fade" role="tabpanel">
        </div>
        
        <div id="tab8" class="tab-pane fade" role="tabpanel">
        </div>
        
        <div id="tab9" class="tab-pane fade" role="tabpanel">
        </div>
        
        <div id="tab10" class="tab-pane fade" role="tabpanel">
        </div>
        
        
    </div>
</div>
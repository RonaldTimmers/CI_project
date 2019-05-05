<?php 

$pricehistorydates = $sku->set_productpricehistory( $db->linode, $sku->productinfo['id'] );
$prices = array_map(function ( $ar ) {return $ar['price'];}, $pricehistorydates);
$dates = array_map(function ( $ar ) {return date("F j, Y", $ar['start']);}, $pricehistorydates);

?>

<div id="product-box">

    <?php 
        echo $sku->get_productdetails( $sku->productinfo, $pricehistorydates, $sku->source_coupons, $modal = false );    
    ?>    
    


</div>

<div class="cb"></div>

                                     
<div id="category-box" class="main-product-thumbs">
    <div class="modal-relatedProducts">
        
        <?php 
            $sku->set_similairproducts( $db->linode, $sku->productinfo['id'], $sku->productinfo['source'], $sku->productinfo['title'], $sku->productinfo['price'] );
            $sku->get_similairproducts( $sku->similairproducts['thumbs'] );
            $sku->get_loadmore();
        ?> 
    </div>
</div>
<div class="cb"></div>


<div id="product-button-container-fixed" class="hidden" style="z-index: 1000; bottom: 2rem; right: 2rem; position:fixed;">
    <div>
        <a id="product-buy-fixed" title="See More Details at <?php echo $sku->productinfo['source_name'];?>" class="btn btn-success pull-right" target="_blank" rel="nofollow" href="<?php echo BASE_URL;?>goto/<?php echo $sku->productinfo['id'];?>/" style="padding: 10px;
                                                                        border-radius: 5px;
                                                                        box-shadow: 0 1px 1px rgba(0,0,0,.5);
                                                                        text-shadow: rgba(0,0,0,.3) 0 1px 1px;
                                                                        background-color: #fa9558;
                                                                        background-image: linear-gradient(#9cd162,#80bb43); text-decoration:underline;">
            More Details at <?php echo $sku->productinfo['source_name'];?>.com<span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>    
        </a>
    </div>
</div>


<script type="text/javascript" src="//static.addtoany.com/menu/page.js"></script>


<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(event) { 
        // The element for the chart
        var ctx = document.getElementById('price-history-chart').getContext('2d');

        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'line',

            // The data for our dataset
            data: {
                labels: <?php  echo json_encode( array_reverse($dates) );?>,
                //labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [{
                    //label: "Price History",
                    type: 'line',
                    borderColor: 'rgb(12,79,89)',
                    data: <?php echo json_encode( array_reverse($prices) );?>,
                    //data: [0, 10, 5, 2, 20, 30, 45],
                    pointRadius: 3,
                    lineTension: 0.2,
                    fill: false,
                    borderWidth: 3

                }]
            },

            // Configuration options go here
            options: {            
                maintainAspectRatio: false,
                responsive: true,
                animation: {
                    duration: 0 // general animation time
                },
                scales: {
                    yAxes: [{
                            scaleLabel: {
                                    display: true,
                                    labelString: 'Price ($)'
                            }
                    }]
                }, 
                legend: {
                    display: false
                }
            }
        });       
    });


</script>
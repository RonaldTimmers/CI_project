import React from 'react';
import ReactDOM from 'react-dom';
import { connect } from 'react-redux';


import { getNewThumbs, checkURL, getSearchThumbs } from './Containers';

import Head from './components/Head'; //Import Pagination Component
import Pricefilter from './components/Pricefilter'; //Import Filter Components
import Sortfilter from './components/Sortfilter'; //Import Filter Components
import Starfilter from './components/Starfilter'; //Import Filter Components
import Stockfilter from './components/Stockfilter'; //Import Filter Components
import Paginator from './components/Paginator'; //Import Pagination Component


import Thumbs from "./Thumbs";
// import Loader from "./components/Loader"; Not Needed!

import store from "../store";

import { createHistory } from 'history'
import * as Cookies from "js-cookie";

const history = createHistory();
 
// Get the current location 
const location = history.location;
// Listen for changes to the current location. 
/*
const unlisten = history.listen((location, action) => {
  // location is an object like window.location 
  console.log(action, location.pathname, location.state)
})
*/

//import store from "../store"; 
const mapStateToProps = (state) => {
    
	var page_items = null;

	switch ( state.filterReducer.pagetype ) {
		case 'category':
			page_items = 48;
		break;

		case 'brand':
			page_items = 24;
		break;

		default:
 			page_items = 48;
		break;
	}

    return {
    		page: state.filterReducer.page,
    		pagetype: state.filterReducer.pagetype,
    		page_items: page_items,
	        sort: state.filterReducer.sort,
	        stars: state.filterReducer.stars,
	        stock: state.filterReducer.stock,
	        prices: {
		        priceRangeMin: state.filterReducer.priceRange.min,
		        priceRangeMax: state.filterReducer.priceRange.max,
		        priceValueMin: state.filterReducer.priceValue.min,
		        priceValueMax: state.filterReducer.priceValue.max
		    },
		    filter: state.filterReducer.filterString                 
    }
} 


// Here we describe the newthumbs to a store Change.
// When the main store changes the getNewThumbs function will be called.
// store.subscribe(getNewThumbs(this.returnMetadata));
class Mainfilter extends React.Component {
	constructor(props) {
		super(props);
		Cookies.set('fromModal', "false");
		this.state = 	{	
                                    productCount: window.totalProducts,
                                    maxPrice: window.maxPrice
                                };
	}

	componentWillMount () {
		// This function will check if there already a URL query exist
		// On back or forward button, or when pasted in URL back
		checkURL();
		Cookies.set('categoryPath', window.location.href);	
	}


	componentDidMount (nextProps) {
		//console.log("***********************CALL THUMBS COMPONENT DID MOUNT*******************");
		if (window.cat == 'p' || window.cat == 'ps') {
			// getNewThumbs(this.returnMetadata);
		} 

		if (window.cat == 'search' || window.cat == 'brand') {
			// getSearchThumbs(this.returnMetadata);
		}


		ReactDOM.render(<Paginator page={this.props.page} totalProducts={this.state.productCount} page_items={this.props.page_items} />, document.getElementById("pagination-bottom"));
		
		$('.thumb').on({
    		'mouseenter': function(){
        		var product = $(this).offsetParent();
        		var newImage = $(this).attr('src');
        		product.find("img.product-thumb").attr('src', newImage);
   	 		}		
		});


		/* 
			The Next Block is necessary to handle the Modal Product View 
			TO DO: replace this part somewhere else. However, it need to be called after each 
			new thumb overview. So when mainfilter triggers new products we need a recall. Therefore
			Placed for now in the ComponentDidMount listener.
		*/

		$(window).on('popstate', function() {
		    // We check this in order to always close the modal when the user navigates.
		    // Otherwise the modal maybe keep in front of the content
		    //console.log("on.popstate -> history.state", location.state );
			let currentState = history.getCurrentLocation();
			//Object.prototype.query = URL.query;
			console.log('currentState: ', currentState);

			if (currentState.state == null ) {
				$('#product-modal').modal('hide');  
			} 

			else if (currentState.state.page == "Category") {
				$('#product-modal').modal('hide'); 
			}

			else if (currentState.state.page == "SKU") {
				console.log("We are still on a SKU page");
			    let link = currentState.pathname;
		    	let pid = currentState.state.id; 
		    	let popstate = true;

				productInfo(event, link, pid, popstate);
			} 



	              
		     
		});

		$(document).on('hidden.bs.modal', '#product-modal', function(){
	    	console.log("Cookies categoryPath", Cookies.get('categoryPath'));

		    //history.goBack();
    		history.push({
			  pathname: Cookies.get('categoryPath'),
			  state: {page: "Category"}
			});

    		


			Cookies.set('fromModal', "false");
		   // }
		});


		$(document).on('click', '.modal-link', function(event){
		    let link = $(this).attr('href');
		    let pid = $(this).attr('data-id'); 
			productInfo(event, link, pid);
		});

		function productInfo (event, link, pid, popstate = false) {
			event.preventDefault();
			
			
			// We have to set a cookie in order to let the system know later on we openend te model
			// Therefore no refresh of products is need when the user clicks back. 
			// This can easily be accomplished with a small cookie.
			// Will be used in the container.js CheckURL When popstate triggers.
		    Cookies.set('fromModal', "true");
		    

			// Setting the basic variables needed for the call
		    var baseURL = window.baseURL;
		    let productJSON = baseURL + "src/productJSON.call.php";
		    
	        
		    // Push the new url name of the offical href link to the SKU
		    if (popstate == false) {
	    		history.push({
				  pathname: link,
				  state: {
				  			page: "SKU",
				  			id: pid
			  			}
				});	
		    }

		    
    		
		    $.ajax({
		        type: "POST",
		        url: productJSON,
		        dataType: "json",
		        data: {pid: pid},
		        success: function(product) {
		            productModal(product);
		        },
		      	error: function(product) {
		         	console.log( "Error Occured");
		         	console.log("data", product);
		      	}
	    	}); 
		}
		

		

		function productModal (product) {
		    var baseURL = window.baseURL;
		    let productHTML = baseURL + "src/productHTML.call.php";
		    let productHistory = baseURL + "src/productHistory.call.php";
		    let relatedProductsHTML = baseURL + "src/relatedProductsHTML.call.php";

		    //console.log(product.info);
		    //console.log(product.pricehistory);
		    //console.log(product.coupons);



		    /*
		    * 	Now we have the necessary product data
		    * 	Open the modal and clean the earlier data and set loader
		    *
		    */

		    $('#product-modal').modal('show');

		    /* 
		    *	First update the model with empty html. 
			*	Needed for when the user clicks a second product
			*	THe modal will be cleaned up before loading new content
			*/

		    $( '.modal-content' ).html('<div id="modal-spinner" class="container-fluid" style="display:block;position:relative;top:80px;"><img class="center-block" src="' + baseURL + '/img/ci-loader.gif" /></div>');
	      	$( '.modal-content' ).css({
			    backgroundColor: "#fff",
			    minHeight: "400px"
		  	});

		    $.ajax({
		        type: "POST",
		        url: productHTML,
		        dataType: "html",
		        data: {product: product},
		        success: function(data) {
		        	//console.log('data', data)

		        	/*
		        	* 	Put the data (product data) into the html modal
		        	* 	Then Call necessary JS functions.
		        	*/


		            $('.modal-content').html(data); 
		            $('[data-toggle="tooltip"]').tooltip(); 		

            		var element =  document.getElementById('price-history-chart');
					
					if (typeof(element) != 'undefined' && element != null)
					{
					  	// exists.

						// The element for the chart
				        var ctx = document.getElementById('price-history-chart').getContext('2d');

				        var chart = new Chart(ctx, {
				            // The type of chart we want to create
				            type: 'line',

				            // The data for our dataset
				            data: {
				                // labels: <?php // echo json_encode( array_reverse($dates) );?>,
				                labels: product.dates,
				                datasets: [{
				                    //label: "Price History",
				                    type: 'line',
				                    borderColor: 'rgb(12,79,89)',
				                    // data: <?php //echo json_encode( array_reverse($prices) );?>,
				                    data: product.prices,
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
					}


        		    $.ajax({
				        type: "POST",
				        url: relatedProductsHTML,
				        dataType: "html",
				        data: { 				action: "call", 
	                                            product_source: product.info.source, 
	                                            product_title: product.info.title,
	                                            product_id: product.info.id,
	                                            product_price: product.info.price
                                            },
				        success: function ( data ) {
				        	//console.log("data", data);
							console.log('Related Products is SUCCESS');

				        	/*
				        	* 	When we got the related products
				        	*	Load them into the Html 
				        	*/

				            $('.modal-relatedProducts').html(data);
				            window.lazy();

			            	/*
			            	* When Modal Opens Set (Reset) Counter = 1 and Offset = 0
			            	*/

			            

				            window.infinite_scroll( 1, 0 );
					            
				        
				        },
				      	error: function ( data ) {
				         	console.log( "Error Occured");
				         	console.log("data", data);
				      	}
		    		}); 

		        },
		      	error: function(data) {
		         	console.log( "Error Occured");
		         	console.log("data", data);
		      	}
	    	}); 	
		}

		/*
		function thumbImageFunction () {
	        $('.productpage-thumb').on({
	    		'mouseenter': function(){
	    			console.log('Run Script MainFilter');
	        		var product = $(this).offsetParent();
	        		var newImage = $(this).attr('src');
	        		var oldImage = product.find("img.product-image");
	        		oldImage.attr('src', newImage);
	        		oldImage.attr('data-magnify-src', newImage);
	        		window.run_magnify();
	   	 		}		
			});
		}
		*/

	}




	componentWillReceiveProps (nextProps) {
		console.log("***********************CALL THUMBS WILLRECEIVE PROPS********************")
			if (window.cat == 'search' || window.cat  == 'brand') {			
				getSearchThumbs(this.returnMetadata);
			}

			else {
				getNewThumbs(this.returnMetadata);
			} 
	}


	returnMetadata = (count, maxprice) => {
		if (window.cat == 'search' || window.cat == 'p' || window.cat == 'ps') {
			
		}

		this.setState({productCount: count, maxPrice: maxprice + 1});
	}

    render() {
    	console.log("Render MainFilter Component");
    	/*
				      	<div className="paginator col-md-5">
							<Paginator page={this.props.page} totalProducts={this.state.productCount} />
						</div>
    	*/
		return (
			<div>
				<Head page={this.props.page} page_items={this.props.page_items} totalProducts={this.state.productCount} />
				<div>
		        	<div>
		            	<div id="main-filter" className="filter row col-md-9 collapse">
		            		<div className="col-md-3">
		            			<Pricefilter prices={this.props.prices} />
		            		</div>
		            		<div className="col-md-9"> 
			            		<Starfilter page={this.props.page} stars={this.props.stars} />	
			            		<Stockfilter page={this.props.page} stock={this.props.stock} />	
				            	<Sortfilter page={this.props.page} sort={this.props.sort} />
		            		</div>
		            	</div>
            	     	<div id="results-filter" className="col-md-3 pull-right">
			            		<strong>{this.state.productCount} - Total Results</strong> 
		            	</div>

			      	</div>
	      	 		<div className="row">
	      	 			<div id="activefilter-box" className="container-fluid"></div>
	      	 		</div>  
	      	 		<hr />	
	      		</div>
  			</div>
        );
	}

	/*
			      	<div className="row">
			      		<Stockfilter page={this.props.page} stock={this.props.stock} />
			      	</div>

	*/

	componentDidUpdate () {

		ReactDOM.render(<Paginator page={this.props.page} totalProducts={this.state.productCount} page_items={this.props.page_items} />, document.getElementById("pagination-bottom") );

		Cookies.set('categoryPath', window.location.href);	
		
		$('.thumb').on({
            'mouseenter': function(){
                    var product = $(this).offsetParent();
                    var newImage = $(this).attr('src');
                    product.find("img.product-thumb").attr('src', newImage);
            }		
		});
        
        /*        
        if ( "keywords" in window ) {

	        var searchTerm = window.keywords.split(' ');

	        var ctx = document.querySelectorAll("h3.product-title");

	        var instance = new Mark(ctx);

	        var options = {
	            "element": "span",
	            "className": "highlight"
	        };

	        instance.mark(searchTerm, options);
        } 
        */        
 	}
}

export default connect(mapStateToProps)(Mainfilter);


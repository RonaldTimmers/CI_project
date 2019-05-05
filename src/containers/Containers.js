import React from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios'; // HTTP/AJAX Caller 

import Thumbs from "../components/Thumbs";
import Message from "../components/Message";
import Loader from "../components/Loader";

import store from "../../store";
import { getURLQuery } from "./Actions";

import * as Cookies from "js-cookie";

// Set the URL for the DB call
//const thumbsCall = 'https://' + window.location.hostname + '/src/thumbs.call.php'
//const searchCall = 'https://' + window.location.hostname + '/src/search.call.php'

const thumbsCall = window.baseURL + 'src/thumbs.call.php'
const searchCall = window.baseURL + 'src/search.call.php'

const baseElements = {
	//baseURL: "https://" + window.location.hostname + "/",
	baseURL: window.baseURL,
	staticURL: "https://static.compare-imports.com/",
	productItems: document.getElementsByClassName("fl product-item").length
}


import { createHistory, useQueries } from 'history'
const history = useQueries(createHistory)()


export function	getNewThumbs (callback) {
			console.log("**************************GET NEW THUMBS CALLED***********************");
			// Second, get current state of other Filter objects
			let currentState = store.getState();
				
			// Define the config with data for the Query 
			var axiosConfig = {
				params: {
					pagetype: window.pagetype,
					querytype: window.querytype,
					cat: window.cat,
					id: window.id,
					name: window.name,
					description: window.description,
					totalProducts: window.totalProducts,
					page: currentState.filterReducer.page,
					sort: currentState.filterReducer.sort,
					stars: currentState.filterReducer.stars,
					stock: currentState.filterReducer.stock,
					minprice: currentState.filterReducer.priceValue.min,
					maxprice: currentState.filterReducer.priceValue.max,
					filters: currentState.filterReducer.filterString,
					shops: currentState.filterReducer.shopsString,
					keywords: window.keywords
				}
			}
			console.log("axiosConfig: ", axiosConfig);

			// Set the CSS Box where to render the elemtents on the page
			const categoryBox = document.querySelector(".main-product-thumbs");
			
			// Start Rendering the Loading Spinner
			ReactDOM.render(<Loader />, categoryBox);

		   	axios.get(thumbsCall, axiosConfig)
			.then((response) => {
				console.log('Response Data: ', response.data);


				// Update the actual thumbs
				if (response.data.count != 0) {
					ReactDOM.render(<Thumbs products={response.data.thumbs} baseElements={baseElements} />, categoryBox);
					window.lazy();
				} else {
					ReactDOM.render(<Message />, categoryBox);
				}

				// Callback to return total product count
				callback(response.data.totalFound, response.data.maxprice);
				//callback(response.data.count, response.data.priceMax);
			})
			.catch((err) => {
				console.log("Error: ", err);
			})	
	}

	export function	getSearchThumbs (callback) {
			console.log("**************************GET NEW THUMBS CALLED***********************");
			// Second, get current state of other Filter objects
			let currentState = store.getState();
				
			// Define the config with data for the Query 
			var axiosConfig = {
				params: {
					pagetype: window.pagetype,
					cat: window.cat,
					id: window.id,
					page: currentState.filterReducer.page,
					sort: currentState.filterReducer.sort,
					stars: currentState.filterReducer.stars,
					stock: currentState.filterReducer.stock,
					minprice: currentState.filterReducer.priceValue.min,
					maxprice: currentState.filterReducer.priceValue.max,
					filters: currentState.filterReducer.filterString,
					shops: currentState.filterReducer.shopsString,
					keywords: window.keywords,
                    secondQuery: window.secondQuery
				}
			}
			console.log("axiosConfig: ", axiosConfig);

			// Set the CSS Box where to render the elemtents on the page
			const categoryBox = document.querySelector(".main-product-thumbs");
			
			// Start Rendering the Loading Spinner
			ReactDOM.render(<Loader />, categoryBox);

		   	axios.get(searchCall, axiosConfig)
			.then((response) => {
				console.log('Response: ', response.data);


				// Update the actual thumbs
				if (response.data.count != 0) {
                    ReactDOM.render(<Thumbs products={response.data.thumbs} baseElements={baseElements} />, categoryBox);
					window.lazy();
				} else {
					ReactDOM.render(<Message />, categoryBox);
				}

				// Callback to return total product count
                callback(response.data.totalFound, response.data.maxprice);
                // callback(response.data.count, response.data.priceMax);
			})
			.catch((err) => {
				console.log("Error: ", err);
			})	
	}



	export function	checkURL () {
			let URL = history.getCurrentLocation();
			console.log('Mainfilter componentWillMount: ', URL);

			
			console.log("Window First Time triggered");
			console.log("page: " + URL.query.page);
		 	console.log("sort: " + URL.query.sort);
		 	console.log("stars: " + URL.query.stars);
		 	console.log("minprice: " + URL.query.minprice);
		 	console.log("maxprice: " + URL.query.maxprice);
		 	console.log("filters: " + URL.query.filters);
		 	console.log("shops: " + URL.query.shops);
			
		 	// Set Default Values
		 	let page = "1";
		 	let sort = window.sorting;
		 	let stars = "0";
		 	let stock = false;
			let minprice = "0";
			let maxprice = parseInt(window.maxPrice);
			let filterString = "";
			let filterStatus = {};

			if (window.shops == null || window.shops == "undefined" ) {
			   var shopsString = "";
			} 

			else {
			   let shops = window.shops;
			   var activeShops = [];
			   Object.keys(shops).map( (shop) => {
			               activeShops.push(shops[shop].id);
			   })

			   var shopsString = activeShops.join("-");
			}



 			// Create Filter var in order to check if they exist
			// if (Object.keys(URL.query).length === 0 || "wordfilter" in URL.query || "XDEBUG_SESSION_START" in URL.query ) {
			if ( "page" in URL.query || "sort" in URL.query || "stars" in URL.query || "stock" in URL.query || "shops" in URL.query || "minprice" in URL.query || "maxprice" in URL.query || "filters" in URL.query) {
				console.log("URL QUERY Exist");
				
				if ("page" in URL.query) {
					console.log("Page Exist");
					page = URL.query.page;
				}

				if ("sort" in URL.query) {
					console.log("Sort Exist");
					sort = URL.query.sort;
				} 

				if ("stars" in URL.query) {
					console.log("Stars Exist");
					stars = URL.query.stars;
				} 

				if ("stock" in URL.query) {
					console.log("Stock Exist");
					stock = URL.query.stock;
				} 

				if ("shops" in URL.query) {
					console.log("Shops Exist");
					shopsString = URL.query.shops;
				} 

				if ("minprice" in URL.query || "maxprice" in URL.query)  {
					console.log("Price Exist");
					minprice = URL.query.minprice;
					maxprice = URL.query.maxprice;
			 	} 

				if ("filters" in URL.query) {
					console.log("Filters Exist");
					filterString = URL.query.filters;
					var activefilters = new Array();
					activefilters = filterString.split('-');

					let filters = window.attributeFilters.filters;
					var statusState = {}
					Object.keys(filters).map( (filter) => {
						Object.keys(filters[filter]).map( (attribute) => {
								var active = false;
								if (activefilters.includes(attribute)) {active = true;}
							 	statusState = Object.assign({}, statusState, { [attribute]: {active: active, name: filters[filter][attribute].name, group: filter}})	
						})
					})

					var currentState = store.getState();
					console.log("currentState: ", currentState);

					var requestArray = [];
					activefilters.map((id) => {
						var group = currentState.filterReducer.filterStatus.Status[id].group;

						var smallarray = [];
						smallarray.push(group, id);
						requestArray.push(smallarray);
					})

					console.log("requestArray: ", requestArray);

					// Check if request array got any active filters if not return empty string
					if (requestArray === undefined || requestArray.length == 0) {
						var requestString = "";
					} else {
						var theARRAY = {};
						
						Object.keys(filters).map((group) => { 	
							theARRAY[group] = requestArray.filter((count) => { return count[0] == group });
							
						})
					
						console.log("theARRAY: ", theARRAY);

						var requestString = JSON.stringify(theARRAY);
					}


					console.log("requestString: ", requestString);

				} 

				else {

					if (window.attributeFilters == null || window.attributeFilters == "undefined" ) {
						var statusState = {};
					} 

					else {
						console.log("window.attributeFilters is defined!");
						let filters = window.attributeFilters.filters;
						var statusState = {}
						Object.keys(filters).map( (filter) => {
							Object.keys(filters[filter]).map( (attribute) => {
								 	statusState = Object.assign({}, statusState, { [attribute]: {active: false, name: filters[filter][attribute].name, group: filter}})	
							})
						})
					}
				}

				console.log("checked page: ", page);
			 	console.log("checked sort: ", sort);
			 	console.log("checked stars: ", stars);
			 	console.log("checked stock: ", stock);
			 	console.log("checked minprice: ", minprice);
			 	console.log("checked maxprice: ", maxprice);
			 	console.log("checked requestString: ", requestString);
			 	console.log("checked shopsString: ", shopsString);
				
				store.dispatch(getURLQuery(page, sort, stars, stock, minprice, maxprice, requestString, statusState, shopsString));
			

			} else {
				console.log("No Call From URL QUERY");
			}

		// Setting an global event to trigger on browser go back or forward
		window.onpopstate = (event) => {  
			console.log("Window onpopstate triggered");
			let URL = history.getCurrentLocation()
			console.log("page: " + URL.query.page);
		 	console.log("sort: " + URL.query.sort);
		 	console.log("stars: " + URL.query.stars);
		 	console.log("shops: " + URL.query.shops);
		 	console.log("minprice: " + URL.query.minprice);
		 	console.log("maxprice: " + URL.query.maxprice);
		 	console.log("filters: " + URL.query.filters);
			
		 	// Set Default Values
		 	let page = "1";
		 	let sort = window.sorting;
		 	let stars = "0";
		 	let stock = false;
			let minprice = "0";
			let maxprice = parseInt(window.maxPrice);
			let requestString = "";
			var currentState = store.getState();
			var statusState = currentState.filterReducer.filterStatus.Status;


			if (window.shops == null || window.shops == "undefined" ) {
			   var shopsString = "";
			} else {
			   let shops = window.shops;
			   var activeShops = [];
			   Object.keys(shops).map( (shop) => {
			               activeShops.push(shops[shop].id);
			   })

			   var shopsString = activeShops.join("-");
			}

 			// Create Filter var in order to check if they exist
			if ( "page" in URL.query || "sort" in URL.query || "stars" in URL.query || "stock" in URL.query || "shops" in URL.query || "minprice" in URL.query || "maxprice" in URL.query || "filters" in URL.query ) {
				console.log("URL QUERY Exist");

				if ("page" in URL.query) {
					console.log("Page Exist");
					page = URL.query.page;
				}

				if ("sort" in URL.query) {
					console.log("Sort Exist");
					sort = URL.query.sort;
				} 

				if ("stars" in URL.query) {
					console.log("Stars Exist");
					stars = URL.query.stars;
				} 

				if ("stock" in URL.query) {
					console.log("Stock Exist");
					stock = URL.query.stock;
				} 

				if ("shops" in URL.query) {
					console.log("Shops Exist");
					shopsString = URL.query.shops;
				} 

				if ("minprice" || "maxprice" in URL.query)  {
					console.log("Price Exist");
					minprice = URL.query.minprice;
					maxprice = URL.query.maxprice;
			 	} 

				if ("filters" in URL.query) {
					console.log("Filters Exist");
					filterString = URL.query.filters;
					var activefilters = new Array();
					activefilters = filterString.split('-');

					let filters = window.attributeFilters.filters;
					
					statusState = {}
					
					Object.keys(filters).map( (filter) => {
						Object.keys(filters[filter]).map( (attribute) => {
								var active = false;
								if (activefilters.includes(attribute)) {active = true;}
							 	statusState = Object.assign({}, statusState, { [attribute]: {active: active, name: filters[filter][attribute].name, group: filter}})	
						})
					})

					var currentState = store.getState();
					console.log("currentState: ", currentState);

					var requestArray = [];
					activefilters.map((id) => {
						var group = currentState.filterReducer.filterStatus.Status[id].group;

						var smallarray = [];
						smallarray.push(group, id);
						requestArray.push(smallarray);
					})

					console.log("requestArray: ", requestArray);

					// Check if request array got any active filters if not return empty string
					if (requestArray === undefined || requestArray.length == 0) {
						requestString = "";
					} else {
						var theARRAY = {};
						
						Object.keys(filters).map((group) => { 	
							theARRAY[group] = requestArray.filter((count) => { return count[0] == group });
							
						})
					
						console.log("theARRAY: ", theARRAY);

						requestString = JSON.stringify(theARRAY);
					}


					console.log("requestString: ", requestString);
				} 

				console.log("checked page: ", page);
			 	console.log("checked sort: ", sort);
			 	console.log("checked stars: ", stars);
			 	console.log("checked stock: ", stock);
			 	console.log("checked minprice: ", minprice);
			 	console.log("checked maxprice: ", maxprice);
			 	console.log("checked requestString: ", requestString);
			 	console.log("checked shopsString: ", shopsString);
				

				console.log("Cookies.get(fromModal): ", Cookies.get('fromModal'));

				if (Cookies.get('fromModal') == "true") {
					console.log("From Modal");
				} else {
					console.log("Not From Modal");
					store.dispatch(getURLQuery(page, sort, stars, stock, minprice, maxprice, requestString, statusState, shopsString));
				}



			 	

				Cookies.set('fromModal', "false");
				console.log("Cookies.get(fromModal): ", Cookies.get('fromModal'));	


			} else {
				console.log("No Call From URL QUERY");
							
			} 
			
		};
	}


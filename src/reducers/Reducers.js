import { combineReducers } from 'redux';
//import { routerReducer } from 'react-router-redux'
//import { Map, List } from 'immutable'

// Get the filters and create for each filter/attribute a active state.
//console.log(window.attributeFilters);

if (window.attributeFilters == null || window.attributeFilters == "undefined" ) {
	var statusState = {};
} else {
	console.log("window.attributeFilters is defined!");
	let filters = window.attributeFilters.filters;
	var statusState = {}
	Object.keys(filters).map( (filter) => {
		Object.keys(filters[filter]).map( (attribute) => {
			 	statusState = Object.assign({}, statusState, { [attribute]: {active: false, name: filters[filter][attribute].name, group: filter}})	
		})
	})
}



// Create Function Of This Part 
// Needed to set initial Shops and always get back to! 
// Used in GetURLQuery Action
// TODO: Create Universal Function 


if ( window.shops == null || window.shops == "undefined" ) {
	var shopsString = "";
} else {
	let shops = window.shops;
	var activeShops = [];
	Object.keys(shops).map( (shop) => {
		 	activeShops.push(shops[shop].id);
	})

	var shopsString = activeShops.join("-");
}

/*
*  Get the Category Filters from search.php 
*  Set a string with id's from subsubcats
*  window.categoryFilters
*/

if (window.categoryFilters == null || window.categoryFilters == "undefined" ) {
	var categoryString = "";
} else {
	let categories = window.categoryFilters;

	var activeCategories = [];

	Object.keys( categories ).map( ( category ) => {
	 	activeCategories.push( categories[category].ssid);
	})

	var categoryString = activeCategories.join("-");
}

const initialFilterState = {  
  sort: window.sorting,
  stars: "0",
  priceRange: {min: "0", max: window.maxPrice}, //PHP Data, See bottom sc.php
  priceValue: {min: "0", max: window.maxPrice},
  page: "1",
  pagetype: window.pagetype, 
  stock: false,
  //shops: activeShops,
  shopsString: shopsString,
  categoryString: categoryString,
  filterCats: window.categoryFilters,
  filterString: "",
  filterStatus: {Status: statusState}
}	

function filterReducer(state = initialFilterState, action) {  
  	switch (action.type) {
		case "SORT_FILTER": {
			return {...state, sort: action.sort}
			break;
	  	}

	  	case "STAR_FILTER": {
	  		return {...state, stars: action.stars, page: action.page}
	  		break;
		}

	  	case "PRICE_FILTER": {
	  		return {...state, priceValue: {min: action.min, max: action.max}, page: action.page } 
	  		break;
		}

	  	case "PAGE_FILTER": {
	  		return {...state, page: action.page} 
	  		break;
		}

		case "STOCK_FILTER": {
			return {...state, stock: action.stock,  page: action.page}
			break;
		}

	  	case "SHOP_FILTER": {
	  		return {...state, 
	  				//shops: action.shops, 
	  				shopsString: action.shopsString, page: action.page} 
	  		break;
		}

	  	case "CATEGORY_FILTER": {
	  		return {...state, 
	  				categoryString: action.categoryString, page: action.page} 
	  		break;
		}

		case "STATUS_FILTERS":{
			return {...state, filterStatus: {Status: action.filterStatus}, filterString: action.requestString, page: action.page}
			break;
		}

	  	case "URL_QUERY": {
	  		return {...state, 
	  				page: action.page, 
	  				sort: action.sort, 
	  				stars: action.stars,
	  				stock: action.stock, 
	  				priceValue: {min: action.minprice, max: action.maxprice}, 
	  				filterString: action.requestString, 
	  				filterStatus: {Status: action.filterStatus}, 
	  				//shops: action.shops, 
	  				shopsString: action.shopsString 
	  				} 
	  		break;
		}
	    default: {
	      	return state;
	  	}
    }
};



// Combine Reducers to Use them all in the Store
const reducers = combineReducers({
	filterReducer: filterReducer
});

export default reducers;


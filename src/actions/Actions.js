import { getNewThumbs } from '../containers/Containers';


// ACTIONS for Fetch Status Change
export const START_FETCHING = 'START_FETCHING'
export const DONE_FETCHING = 'DONE_FETCHING'

export function startFetching() {  
   return  { 
	   	type: START_FETCHING,
	   	loaded: false
   }
}

export function doneFetching() {  
   return  { 
	   	type: DONE_FETCHING,
	   	loaded: true
   } 
}

// ACTIONS for Filter State Change
export const SORT_FILTER = 'SORT_FILTER'
export const STAR_FILTER = 'STAR_FILTER'
export const PRICE_FILTER = 'PRICE_FILTER'
export const STOCK_FILTER = 'STOCK_FILTER'
export const PAGE_FILTER = 'PAGE_FILTER' 
export const SHOP_FILTER = 'SHOP_FILTER'
export const STATUS_FILTERS = 'STATUS_FILTERS'    
export const URL_QUERY = 'URL_QUERY'


export function filterSort(sort) {  
      return  { 
	   	type: SORT_FILTER, 
	   	sort: sort 
   	}
}

export function filterStars(stars) {  
   	return  { 
	   	type: STAR_FILTER, 
	   	stars: String(stars),
	   	page: "1" 
   	}
}

export function filterPrice(maxValue, minValue = "0") {  
   	return  { 
	   	type: PRICE_FILTER, 
   		min: minValue,
   		max: maxValue,
   		page: "1"
	}	
}

export function filterStock(stock) {  
      return  { 
	   	type: STOCK_FILTER, 
	   	stock: stock,
   		page: "1"
   	}
}

export function filterShop(shopsString) {  
      return  { 
         type: SHOP_FILTER, 
         //shops: shops,
         shopsString: shopsString,
         page: "1"
   }  
}

export function filterCategory(categoryString) {  
      return  { 
         type: CATEGORY_FILTER, 
         //shops: shops,
         categoryString: categoryString,
         page: "1"
   }  
}

export function setPage(page) {  
   	return  { 
	   	type: PAGE_FILTER, 
   		page: String(page)
	}	
}

export function setFilterStatus(requestString, filterStatus) {  
	return  { 
		type: STATUS_FILTERS,
		requestString: requestString,
		filterStatus: filterStatus,
		page: "1"
	}
}



export function getURLQuery(page = "1", sort = 'relevance', stars = "0", stock = true, minprice = "0", maxprice = window.maxPrice, requestString = "", filterStatus = {}, shopsString = "") {  
   	
	// When calling this Action to Reset Filters
	// We have to Set the Shop Filters to the initial Status. 
	// This part is also Used in Reducer for the intitial State 
	// TODO: Create Universal Function 

	if (shopsString == "") {
		let shops = window.shops;
		var activeShops = [];
		
		Object.keys(shops).map( (shop) => {
		 	activeShops.push(shops[shop].id);
		})

		var shopsString = activeShops.join("-");
	}


   	return  { 
	   	type: URL_QUERY, 
   		page: page,
   		sort: sort,
   		stars: stars,
   		stock: stock,
   		minprice: minprice,
   		maxprice: maxprice,
		requestString: requestString,
		filterStatus: filterStatus,
		//shops: shops,
		shopsString: shopsString
	}	
}

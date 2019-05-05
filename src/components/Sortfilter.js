import React from 'react';
import store from "../../store";

import { filterSort } from '../Actions';

import { createHistory, useQueries } from 'history'
const history = useQueries(createHistory)()



class Sortfilter extends React.Component {
	constructor(props) {
		super(props);
	  	this.state = {selected: ''};		
	}

	componentWillReceiveProps (nextProps) {
		this.setState({selected : nextProps.sort})
	}

	handler = (event) => {
		this.setState({selected : event.target.value})
		
		// Change the main store State
		store.dispatch(filterSort(event.target.value));

		
		// Change the URL
		let location = history.getCurrentLocation()
		history.push({
		  pathname: location.pathname,
		  query: Object.assign({}, location.query, {sort: event.target.value}) 
		})
	}

  	isActive = (value) => {
		return 'btn btn-default btn-sm btn-responsive '+((value===this.state.selected) ?'active':'');
	}
 	


    render() {
    	console.log("Render Sort Component");
    	return(
		      <form id="sorting-filter" className="col-md-6">
		        <label>
		          Sorting:
		          <select value={this.state.value} onChange={this.handler}>
		          		<option value="relevance">Relevance</option>
			            <option value="lowest">Price Low - High</option>
			            <option value="highest">Price High - Low</option>
			            <option value="popular">Popular</option>
			            <option value="rated">Best Rated</option>
			            <option value="newest">Newest</option>
		          </select>
		        </label>
		      </form>
    		);


	}
}

export default Sortfilter;


	    	/*
		return (
            <div id="sort-filter">
              <ul className="list-inline"> 
				<li><a className={this.isActive("lowest")} 		onClick={this.handler.bind(this, "lowest")}>Lowest Price</a></li>
				<li><a className={this.isActive("highest")}  	onClick={this.handler.bind(this, "highest")}>Highest Price</a></li>
				<li><a className={this.isActive("popular")}  	onClick={this.handler.bind(this, "popular")}>Popular</a></li>
				<li><a className={this.isActive("rated")}  		onClick={this.handler.bind(this, "rated")}>Best Rated</a></li>
				<li><a className={this.isActive("newest")}  	onClick={this.handler.bind(this, "newest")}>Newest</a></li>
              	</ul>
            </div>
        );
        */

	
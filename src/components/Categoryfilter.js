import React from 'react';
import ReactDOM from 'react-dom';
import { connect } from 'react-redux';

import store from "../../store";
let _ = require('underscore')
//const history = useQueries(createHistory)()



const mapStateToProps = (state) => {
    return {
    		categoryString: state.filterReducer.categoryString
    }
}


class Categoryfilter extends React.Component {
	constructor(props) {

		super(props);
		this.state = {status: this.props.categoryString}

	}



	render () {

		

		// This render method only gives the important stuff through
		// The initial Filters and the status of each filter
		
		if (window.categoryFilters == null || window.categoryFilters == "undefined" ) {
			return (<div></div>);
		} 
		
		else {


			/*
			* First Group SubsubCategories By Subcategory
			* 
			*/

			var categories = _.groupBy(window.categoryFilters, function( category )  {
  				return category.sname;
			});

			/*
			* This Part is needed to get the total products for a sub category
			* This will be needed for the sorting.
			*/

			var totalCount = {};

			Object.keys( categories ).map(( subcategory ) => {

				totalCount[subcategory] = [];
					
				categories[subcategory].map(( sscategory ) => {
					totalCount[subcategory].push( parseInt(sscategory.count) );
				})

				categories[subcategory].total = totalCount[subcategory].reduce(function(sum, value) {
				  	return sum + value;
				}, 0);

			})

			
			return (
				<div id="category-wrapper">
					<span id="specific-cat-header"><strong>Specify Category </strong></span><span className="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span> 	
					{ Object.keys( categories )
						.sort((a, b) => parseInt( categories[b].total ) - parseInt( categories[a].total ))
						.map(( category ) => {


					 	return <CategoryBox key={category} 
										 	category={categories[category]}
										 	subcatname={ category }
							 	/>; 
					})}
						
				</div>
			);
		}
	}
}

class CategoryBox extends React.Component {
	constructor(props) {
		super(props);

	}

	render () {
		
			/*
			* Remove the earlier added total
			* Not needed in the View
			*/
			var categories = this.props.category.filter(( category ) => { return this.props.category[category] != 'total' });
		

			return (
				<div className="cat-filter-wrapper">
					<h4><strong>{this.props.subcatname}</strong></h4>
						<ul>
							
							{ Object.keys( categories ).map(( singlecategory ) => {
								return <Category 	key={ singlecategory }
													singlecategory={ categories[singlecategory] } 
		
							/>;
							})}
						</ul>
				</div>
			);
		}
	
}

class Category extends React.Component {
	constructor(props) {
		super(props);
	}

	render () {
		return (
				
			<li>
				<a href={window.baseURL + this.props.singlecategory.cref + '/' + this.props.singlecategory.ssref + '-3-' + this.props.singlecategory.ssid + '/?wordfilter=' + encodeURI(window.keywords) }>{this.props.singlecategory.ssname} <b>({this.props.singlecategory.count})</b></a>
			</li>

		);
	}
	
}




export default connect(mapStateToProps)(Categoryfilter);
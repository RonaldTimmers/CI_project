import React from 'react';
import { connect } from 'react-redux';

import store from "../../store";
import { filterShop } from '../Actions';


import { createHistory, useQueries } from 'history'
const history = useQueries(createHistory)()

const mapStateToProps = (state) => {
    return {
    		//activeShops: state.filterReducer.shops,
    		shopsString: state.filterReducer.shopsString
    }
}


class Shopfilter extends React.Component {
	constructor(props) {
		super(props);
	  	this.state = {menuOpen: false, checkState: false};	
	}

	_switchFilter = (id) => {
		
		/* To Do: Not Only Give ID but also mode 

		Select/Deselect One
		Select Recommend
		Uncheck All
		Select All

		*/


		if ( this.state.checkState == true ) {
			var shopsString = ["0"];
		} else {
			var shopsString = this.props.shopsString.split("-");
		}

		
		/* Set uncheck to false */
		this.setState({ checkState: false });

		if (shopsString.indexOf(id) == -1) {
			shopsString.push(id);

		} else {
			shopsString.splice(shopsString.indexOf(id), 1);
		}


		shopsString = shopsString.join("-");

		
		console.log("shopsString: ", typeof shopsString);
		

		// Change the URL
		if (shopsString.length === 0) {
			// Remove the filters query because shops is zero
			let location = history.getCurrentLocation()
			shopsString = "0";
			delete location.query['shops'];
			history.push({
			  pathname: location.pathname,
			  query: Object.assign({}, location.query)	  
			})	
		} else {
			let location = history.getCurrentLocation()
			
			history.push({
			  pathname: location.pathname,
			  query: Object.assign({}, location.query, {shops: shopsString, page: 1}) 
			})
		}

		store.dispatch(filterShop(shopsString));
	}

	_selectAll = () => {

		this.setState({ menuOpen: true, checkState: false });

		/* TO DO: Create Set ShopString Function
		   See Also Reducers */

		let shops = window.shops;
		var activeShops = [];

		Object.keys(shops).map( (shop) => {
			 	activeShops.push(shops[shop].id);
		})

		var shopsString = activeShops.join("-");

		let location = history.getCurrentLocation()
		
		history.push({
		  pathname: location.pathname,
		  query: Object.assign({}, location.query, {shops: shopsString, page: 1}) 
		})

		store.dispatch(filterShop(shopsString));

	}

	_uncheckAll = () => {
		console.log('this.state.checkState before: ' + this.state.checkState);
    	this.setState({ menuOpen: true, checkState: true });
    	console.log('this.state.checkState after: ' + this.state.checkState);
	}

	_selectRecommendedShops = () => {

		this.setState({ menuOpen: true, checkState: false });

		/* TO DO: Create Set ShopString Function
	   See Also Reducers */
	   	/*

	   	DX
		Banggood
		Geekbuying
		Gearbest
		Tomtop
		Lightinthebox

	   	*/

	   	var shopsString = "2-4-31-15-28-25";

		let location = history.getCurrentLocation()
		
		history.push({
		  pathname: location.pathname,
		  query: Object.assign({}, location.query, {shops: shopsString, page: 1}) 
		})

		store.dispatch(filterShop(shopsString));

	}

	_openFilterShopMenu = () => {
		console.log('this.state.menuOpen before: ' + this.state.menuOpen);
    	var menuState = !this.state.menuOpen;
    	this.setState({ menuOpen: menuState });
    	console.log('this.state.menuOpen after: ' + this.state.menuOpen);
	}


    render() {
		console.log("Render Shopfilter");

			return (
				<div>
                	<div id="attr-wrapper">
	                	 <h4><span className="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span> <strong>China Shops</strong></h4>
						 <Shopwrapper 	shopsString={this.props.shopsString} 
						 				checkState={this.state.checkState}
						 				menuOpen={this.state.menuOpen}
					 					_switchFilter={this._switchFilter} 
					 					_uncheckAll={this._uncheckAll}
					 					_selectAll={this._selectAll}
					 					_selectRecommendedShops={this._selectRecommendedShops}
					 					_openFilterShopMenu={this._openFilterShopMenu}
					 					
						 />
					</div> 	
				</div>
			);
	}
}


class Shopwrapper extends React.Component {
	constructor(props) {
		super(props);
	}

	componentWillReceiveProps (nextProps) {

	}




    render() {
		//console.log("IN SHOPSWRAPPER - this.props.shopsString: ", this.props.shopsString);

		if ( this.props.shopsString == "0" && this.props.checkState == false ) {
			var uncheck = true;
		} 
		else {
			var uncheck = this.props.checkState;
		}

		// Get the shops from the PHP page to Render the filtermenu with active shops 
		let shops = window.shops;
		var count = 0;


		return (
			
			<div>
				
				<ul className="row">
					<li>
						<a className="btn btn-link select-shops" onClick={this.props._selectAll.bind(this)}>Select All</a>
						<span>|</span> 
						<a className={"btn btn-link select-shops " + uncheck} onClick={this.props._uncheckAll.bind(this)}>Clear All</a> 
					</li>
					<li><a className="btn btn-link select-shops" onClick={this.props._selectRecommendedShops.bind(this)}><span className="ci-favicon"></span> Select Advised</a></li>
				</ul>

				<ul className={"list-group menu-open-" + this.props.menuOpen}>

					{Object.keys(shops).map((shop) => {
						{++count}
						//console.log(this.props.activeShops[shops[shop].id]);
						return <Shop 		key={shops[shop].id} 
											id={shops[shop].id} 
											shopname={shops[shop].name} 
											shopref={shops[shop].ref}
											shopsString={this.props.shopsString}
											checkState={this.props.checkState}
											_switchFilter={this.props._switchFilter} 
								/>
					})}	
				</ul>
				{count > 5 ? <a className="view-menu-button text-center" onClick={this.props._openFilterShopMenu.bind(this)}>View {this.props.menuOpen ? "Less" : "More"} <span className={"caret " + this.props.menuOpen}></span></a> : '' }

			</div> 	
		);
	}	
}


class Shop extends React.Component {
	constructor(props) {
		super(props);
	}

	render () {
		console.log('this.props.checkState: ' + this.props.checkState);

		var shopsString = this.props.shopsString.split("-");

		if ( this.props.checkState == true ) {
			var checked = "";

		} else {
			var checked = shopsString.includes(this.props.id) ? "checked" : ""; 
		}
		
		
		return (
			<li className="list-item">	
				<label id={this.props.shopref + "_favicon"} className="attribute-label" title={this.props.shopname}>
					<input
							className="attribute-checkbox"
							type="checkbox" 
							name={this.props.shopref} 
							value={this.props.id}
							checked={checked}
							onChange={this.props._switchFilter.bind(this, this.props.id)} 	
					/>

					
				<span>{this.props.shopname}</span></label>
			</li>
			);
	}
}

export default connect(mapStateToProps)(Shopfilter);


// <li> <a className="btn btn-default btn-sm btn-responsive pull-right " onClick={this.props._resetFilters.bind(this, this.props.activeShops)}><label className="attribute-label">Invert <span className="glyphicon glyphicon-sort" aria-hidden="true"></span></label></a></li>
							

	
import React from 'react';
import ReactDOM from 'react-dom';
import store from "../../store";

import { filterStock } from '../Actions';

import { createHistory, useQueries } from 'history'
const history = useQueries(createHistory)()


class Stockfilter extends React.Component {
	constructor (props) {
		super(props);
		this.state = {stock: this.props.stock};	
	}

	componentWillReceiveProps (nextProps) {
		this.setState({stock : nextProps.stock})
	}

	handler = (stock) =>  {
		console.log("stock handler clicked");

		if (this.state.stock == true) {
			stock = false;
		} else {
			stock = true; 
		}
		
		this.setState({stock: stock})
		
		// Change the main store State
		store.dispatch(filterStock(stock));

		// Change the URL
		let location = history.getCurrentLocation()
		history.push({
		  pathname: location.pathname,
		  query: Object.assign({}, location.query, {stock: stock, page: '1'}) 
		})
	} 


    render() {
    	console.log("Render Stock Component");
    	var checked = this.state.stock == true  ? "checked" : ""; 
    	return(
			<div id="stock-filter" className="col-md-3">
    			<label className="checkbox-inline">
    			<span>In Stock</span>
	    			<input 	type="checkbox"
	    					checked={checked}
	    					value={this.state.stock} 
	    					onChange={this.handler.bind(this, this.state.stock)}
	    			/>
    				
    			</label>
    		</div>
    		);
	}
}

export default Stockfilter;
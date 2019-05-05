import React from 'react';
import store from "../../store";
import { filterPrice } from '../Actions';

import { createHistory, useQueries } from 'history'
const history = useQueries(createHistory)()

import 'rc-slider/assets/index.css';
import 'rc-tooltip/assets/bootstrap.css'; 

import Tooltip from 'rc-tooltip';
import Slider from 'rc-slider';

/*
const Slider = require('rc-slider');
const createSliderWithTooltip = Slider.createSliderWithTooltip;
const Range = createSliderWithTooltip(Slider.Range);
*/

const createSliderWithTooltip = Slider.createSliderWithTooltip;
const Range = createSliderWithTooltip(Slider.Range);
const Handle = Slider.Handle;


const handle = (props) => {
  const { value, dragging, index, ...restProps } = props;
  return (
    <Tooltip
      prefixCls="rc-slider-tooltip"
      overlay={value}
      visible={dragging}
      placement="top"
      key={index}
    >
      <Handle value={value} {...restProps} />
    </Tooltip>
  );
};





class Pricefilter extends React.Component {
	constructor(props) {
		super(props);

	 	// Set the Price Ranger Values
	 	let priceRangeMin = parseInt(props.prices.priceRangeMin);
	 	let priceRangeMax = parseInt(props.prices.priceRangeMax);
		
		// Set the marks according to the priceRanges
		this.marks = {
		  0: <strong>${priceRangeMin}</strong>,
		  [priceRangeMax]: <strong>${priceRangeMax}</strong>
		};
		
 		// Define the Initial State of the PriceFilter
	 	this.state = { 	minRange: priceRangeMin, // Start Range
				 		maxRange: priceRangeMax, // End Range
				 		minValue: priceRangeMin, // Selected Min Value -> First Select Standard Range
				 		maxValue: priceRangeMax // Selected Max Value -> First Select Standard Range
				 	}; 	
	}

	componentWillMount() {
		
		// Use the URL prices for the filter values	
	 	let priceValueMin = parseInt(this.props.prices.priceValueMin);
	 	let priceValueMax = parseInt(this.props.prices.priceValueMax);


	 	// Change the state of the pricefilter values
		let stateValue = {minValue: priceValueMin, maxValue: priceValueMax} // Change Selected Value
		this.setState(stateValue);	
	}



	componentWillReceiveProps (nextProps) {
		//console.log("ReceiveProps: this.props: ", this.props);
		console.log("PriceFilter ReceiveProps nextProps: ", nextProps);	
		

	 	let priceRangeMin =  	parseInt(nextProps.prices.priceRangeMin);
	 	let priceRangeMax = 	parseInt(nextProps.prices.priceRangeMax);
	 	let priceValueMin =  	parseInt(nextProps.prices.priceValueMin);
	 	let priceValueMax = 	parseInt(nextProps.prices.priceValueMax);
		
		this.marks = {
		  0: <strong>${priceRangeMin}</strong>,
		  [priceRangeMax]: <strong>${priceRangeMax}</strong>
		};
		
		this.setState({		minRange: priceRangeMin, 
				 			maxRange: priceRangeMax,
							minValue: priceValueMin, 
							maxValue: priceValueMax 
					})
		
	}

	componentWillUpdate (nextProps, nextState) {

	}


	onSliderChange = (value) => {
	 	// Change the state of the pricefilter values
		var stateValue = {minValue: value[0], maxValue: value[1]} // Change Selected Value
		
		// Change the Actual internal State
		this.setState(stateValue);

		// Change the main store State
		store.dispatch(filterPrice(stateValue.maxValue, stateValue.minValue));

	   // CHange the URL
	    let location = history.getCurrentLocation()
	    history.push({
	      pathname: location.pathname,
	      query: Object.assign({}, location.query, {page: '1', minprice: value[0], maxprice: value[1]}) 
	    })

	}

    render() {
    	console.log("Render Price Component");
    	let priceValueMin = parseInt(this.props.prices.priceValueMin)
    	let priceValueMax = parseInt(this.props.prices.priceValueMax)
		return (
	            <div id="price-filter">
		      			<Range 
		      			min={this.state.minRange} 
		      			max={this.state.maxRange} 
		      			range marks={this.marks} 
		      			allowCross={false} 
		      			defaultValue={[priceValueMin, priceValueMax]} 
		      			Value={[priceValueMin, priceValueMax]}
		      			onAfterChange={this.onSliderChange} />
	            </div>
        );
	}
}

export default Pricefilter;
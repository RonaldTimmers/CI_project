import React from 'react';
import ReactDOM from 'react-dom';
import store from "../../store";

import { filterStars } from '../Actions';

import { createHistory, useQueries } from 'history'
const history = useQueries(createHistory)()


class Starfilter extends React.Component {
	constructor (props) {
		super(props);
		this.state = {selected: ''};	
	}

	componentWillReceiveProps (nextProps) {
		this.setState({selected : nextProps.stars})
	}

	handler = (stars) =>  {
		console.log("stars handler clicked");

		if (this.state.selected == stars) {
			stars = "0";
		}
		this.setState({selected: stars})
		
		// Change the main store State
		store.dispatch(filterStars(stars));

		// Change the URL
		let location = history.getCurrentLocation()
		history.push({
		  pathname: location.pathname,
		  query: Object.assign({}, location.query, {stars: stars, page: '1'}) 
		})
	} 

	isActive = (value) => {
		return 'btn btn-default btn-responsive '+((value===this.state.selected) ?'active' : '');
	}

	clearFilter = (value) => {
		return (value===this.state.selected) ? 'glyphicon glyphicon-remove' : '';
	}

    render() {
    	console.log("Render Stars Component");
    	return(
            <div id="star-filter" className="col-md-3">
          		
					Stars: <a className={this.isActive("4")}  	onClick={this.handler.bind(this, "4")}><span id="btn-stars"></span>4+<span className={this.clearFilter("4")} style={{top:'2px', left: '4px'}}  aria-hidden="true"></span></a>
          		
			</div>
    		);
	}
}

export default Starfilter;

/*
    	return(
            <div id="star-filter">
          		<ul className="list-inline"> 
					<li><a className={this.isActive("1")} 	onClick={this.handler.bind(this, "1")}>1 Star <span className={this.clearFilter("1")} style={{top:'2px', left: '4px'}} aria-hidden="true"></span></a></li>
					<li><a className={this.isActive("2")}  	onClick={this.handler.bind(this, "2")}>2 Stars <span className={this.clearFilter("2")} style={{top:'2px', left: '4px'}}  aria-hidden="true"></span></a></li>
					<li><a className={this.isActive("3")}  	onClick={this.handler.bind(this, "3")}>3 Stars <span className={this.clearFilter("3")} style={{top:'2px', left: '4px'}}  aria-hidden="true"></span></a></li>
					<li><a className={this.isActive("4")}  	onClick={this.handler.bind(this, "4")}>4 Stars + <span className={this.clearFilter("4")} style={{top:'2px', left: '4px'}}  aria-hidden="true"></span></a></li>
					<li><a className={this.isActive("5")}  	onClick={this.handler.bind(this, "5")}>5 Stars <span className={this.clearFilter("5")} style={{top:'2px', left: '4px'}}  aria-hidden="true"></span></a></li>
          		</ul>
			</div>
    		);


*/
import React from 'react';
import ReactDOM from 'react-dom';
import { connect } from 'react-redux';

import store from "../../store";
import { setFilterStatus, getURLQuery } from '../Actions';

import { createHistory, useQueries } from 'history'
const history = useQueries(createHistory)()

const mapStateToProps = (state) => {
    return {
    		filterString: state.filterReducer.filterString,
    		filterStatus: state.filterReducer.filterStatus.Status,
    		stockStatus: state.filterReducer.stock   
    }
}


class Attributefilter extends React.Component {
	constructor(props) {
		super(props);

		this.state = {status: this.props.filterStatus}
	}

	_switchFilter = (id, name, filtername) => {
		var active = !this.state.status[id].active;

		var newStatus = Object.assign({}, this.state.status, {[id]: {active: active, name: name, group: filtername}});

		this.setState({status: newStatus});
	}

	_closeFilter = (id, name) => {
		var active = !this.state.status[id].active;

		var newStatus = Object.assign({}, this.state.status, {[id]: {active: active, name: name}});
		
		this.setState({status: newStatus});	
	}

	_resetFilters = (activefilters) => {
		console.log("Reset Filters clicked");

		var newStatus = Object.assign({}, this.state.status);
		
		activefilters.map((id) => {
			newStatus = Object.assign({}, newStatus, {[id]: {active: false}});	
		})
		
		this.setState({status: newStatus});	
	}


	render () {
		console.log("Render Attributefilter");
		
		
		// This render method only gives the important stuff through
		// The initial Filters and the status of each filter
		if (window.attributeFilters == null || window.attributeFilters == "undefined" ) {
			return (<div></div>);
		} 
		else {
			let filters = window.attributeFilters.filters;
			return (
				<div >	
					<Attrfilter filters={filters} 
								status={this.state.status}
								filterString={this.props.filterString} 
								_switchFilter={this._switchFilter} 
								_closeFilter={this._closeFilter}
							 	_resetFilters={this._resetFilters}
					/>			
				</div>
			);
		}
	}

}


class Attrfilter extends React.Component {
	constructor(props) {
		super(props);
	}


	componentWillReceiveProps (nextProps) {

		var activeIDs;
		var activeIDs = Object.keys(nextProps.status).filter((key) => { return nextProps.status[key].active === true });

		var requestArray = [];

		activeIDs.map((id) => {
			var group = nextProps.status[id].group;

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
			
			Object.keys(nextProps.filters).map((group) => { 	
				theARRAY[group] = requestArray.filter((count) => { return count[0] == group });
				
			})
		
			console.log("theARRAY: ", theARRAY);

			var requestString = JSON.stringify(theARRAY);
		}


		console.log("requestString: ", requestString);

		var URLstring = activeIDs.join('-');

	 	store.dispatch(setFilterStatus(requestString, nextProps.status));

	 	// Get location to Push the new needed URL
	 	let location = history.getCurrentLocation()
		
		if (activeIDs.length == 0) {
			// Remove the filters query because active filters is 0
			delete location.query['filters'];
			history.push({
			  pathname: location.pathname,
			  query: Object.assign({}, location.query)	  
			})	
		} else {
			// Change the URL
			history.push({
			  pathname: location.pathname,
			  query: Object.assign({}, location.query, {filters: URLstring, page: 1})	  
			})	
		}
		// Render the active filters box beneath the mainfilter box
		const activefilterbox = document.getElementById("activefilter-box")
		ReactDOM.render(<Activefilter 	activefilters={activeIDs} 
										status={nextProps.status}
										_closeFilter={this.props._closeFilter} 
										_resetFilters={this.props._resetFilters} />, 
										activefilterbox);
	}

	componentDidMount () {
		var activeIDs;
		var activeIDs = Object.keys(this.props.status).filter((key) => { return this.props.status[key].active === true });

		// Render the active filters box beneath the mainfilter box
		const activefilterbox = document.getElementById("activefilter-box")
		ReactDOM.render(<Activefilter 	activefilters={activeIDs} 
										status={this.props.status}
										_closeFilter={this.props._closeFilter} 
										_resetFilters={this.props._resetFilters} />, 
										activefilterbox);
	}


    render() {
    	console.log("Render Attrfilter Component");

    	if (this.props.filters.length == 0) { 
    		return (<div></div>);
    	} else {
			return (
				<div>
	                <div id="attr-wrapper">
						{Object.keys(this.props.filters).map( (filter) => {
						 	return <AttrBox key={filter} 
										 	filtername={filter} 
										 	attributes={this.props.filters[filter]} 
										 	status={this.props.status} 
										 	_switchFilter={this.props._switchFilter} 
										 	/>; 
						})}
					</div>			      	
		      	</div>
	        );
		}
	}
}




class AttrBox extends React.Component {
	constructor(props) {
		super(props);
		this.state = {menuOpen: false};	
	}

	_changeFilterItems = () => {
		console.log(this.state.menuOpen);
    	var menuState = !this.state.menuOpen;
    	this.setState({ menuOpen: menuState });
	}

	render () {
    	console.log("****************THIS PROPS STATUS AttrBox", this.props.status );
    	console.log("****************THIS PROPS attributes AttrBox", this.props.attributes );

		var count = 0;


		return (
			<div>
			<h4><strong>{this.props.filtername}</strong></h4>
				<ul className={"list-group menu-open-" + this.state.menuOpen}>
					{Object.keys(this.props.attributes).map((attribute) => {
						{++count}
						return <Attribute 	key={attribute} 
											id={attribute} 
											attributename={this.props.attributes[attribute].name} 
											reference={this.props.attributes[attribute].ref}
											filtername={this.props.filtername}
											active={this.props.status[attribute].active}
											_switchFilter={this.props._switchFilter} 
								/>;
					})}
				</ul> 
				{count > 5 ? <a className="view-menu-button text-center" onClick={this._changeFilterItems.bind(this)}>View {this.state.menuOpen ? "Less" : "More"} <span className={"caret " + this.state.menuOpen}></span></a> : '' }
	
	      	</div>
        );
	}
}

class Attribute extends React.Component {
	constructor(props) {
		super(props);
	}

	render () {

		var checked = this.props.active ? "checked" : ""; 
		
		return (
			<li >	
				<h2>
					<label className="attribute-label" title={this.props.attributename}>
						<input 	className="attribute-checkbox"
								type="checkbox" 
								name={this.props.reference} 
								value={this.props.id} 	
								checked={checked}
								onChange={this.props._switchFilter.bind(this, this.props.id, this.props.attributename, this.props.filtername)} 
						/>
					<span>{this.props.attributename}</span></label>
				</h2>
				
			</li>
			);
	}

}

class Activefilter extends React.Component {
	constructor() {
		super();
	}


	render () {
		console.log("Render Active Filters");
		//console.log("this.props.status: ", this.props.status );
		//console.log("this.props.activefilters: ", this.props.activefilters );
		if (this.props.activefilters.length == 0) {
				return (<div></div>);
		} else {
		return (
				<div>
					<ul className="list-inline">
						<li onClick={this.props._resetFilters.bind(this, this.props.activefilters)}><a className="btn btn-warning btn-sm">Reset All<span className="glyphicon glyphicon-remove" style={{top:'2px', left: '4px'}} aria-hidden="true"></span></a></li>
						{this.props.activefilters.map( (filter) => {
							return <li key={filter} onClick={this.props._closeFilter.bind(this, filter, this.props.status[filter].name)}><a className="btn btn-success btn-sm">{this.props.status[filter].name}<span className="glyphicon glyphicon-remove" style={{top:'2px', left: '4px'}} aria-hidden="true"></span></a></li>;
						})}	
					</ul>
				</div>
			);
		}
	}
}

export default connect(mapStateToProps)(Attributefilter);
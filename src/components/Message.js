import React from 'react';
import { getURLQuery } from "../Actions";
import store from "../../store";

import { createHistory, useQueries } from 'history'
const history = useQueries(createHistory)()


class Message extends React.Component {
	constructor (props) {
		super(props);
	}

	handler = () => {
		console.log("Reset handler clicked");

		// Change the main store State
		store.dispatch(getURLQuery());

		// Change the URL 
		let location = history.getCurrentLocation();
	    history.push({
	      pathname: location.pathname
	    })
	} 

	render() {
		return(
			<div className="alert alert-warning" role="alert">
				You filtered to good.. You can set your filter friendlier, or just hit the button below for a fresh start.
				<br />
				<br />
				<br />
				<a onClick={this.handler.bind(this)} className="btn btn-default btn-sm"><strong>Reset all Filters</strong></a>
			</div>);
	}
}

export default Message;
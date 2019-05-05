//Imports for main libaries
import React from "react";
import ReactDOM from "react-dom";


import { Provider } from "react-redux";
 
//Custom made components to load
import store from "./store";

import Mainfilter from "./src/Mainfilter";
import Attributefilter from "./src/components/Attributefilter";
import Shopfilter from "./src/components/Shopfilter";
import Categoryfilter from "./src/components/Categoryfilter";


// Import External Libararies 
import Mark from 'mark.js';	// Needed and Called in index.php -> Specific: search and sc pages

if ( document.getElementById("render") ) {
	const render = document.getElementById("render")
	ReactDOM.render(
		<Provider store={store}>
			<Mainfilter />
		</Provider>, render);
}

if ( document.getElementById("shopfilter") ) {
	const shopfilter = document.getElementById("shopfilter")
	ReactDOM.render(
		<Provider store={store}>
			<Shopfilter />
		</Provider>, shopfilter);
	}

if ( document.getElementById("attrfilter" )) {
	const attrfilter = document.getElementById("attrfilter")
	ReactDOM.render(
		<Provider store={store}>
			<Attributefilter />
		</Provider>, attrfilter);
	}

if ( document.getElementById("categoryfilter" )) {
	const categoryfilter = document.getElementById("categoryfilter")
	ReactDOM.render(
		<Provider store={store}>
			<Categoryfilter />
		</Provider>, categoryfilter);
	}
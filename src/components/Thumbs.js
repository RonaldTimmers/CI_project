import React from 'react';
import ReactDOM from 'react-dom';
// import LazyLoad from 'react-lazy-load';
// import Thumbgallery from "./components/Thumbgallery";
import { createHistory, useQueries } from 'history'
const history = useQueries(createHistory)()

// var ReactGA = require('react-ga');
// ReactGA.initialize('UA-37081840-1');
 



class Product extends React.Component {
    constructor (props) {
        super(props);
        this.state = {hovering: "hidden"};	
    }

    hoverEnter = (id) => {
    	//console.log("hoverEnter ", id);
    	this.setState({hovering: ""})
    	//console.log(this.state);
    }

    hoverLeave = (id) => {
		//console.log("hoverLeave: ", id);
		this.setState({hovering: "hidden"})
		//console.log(this.state);
    }


	render () {
		var images = [];
		//console.log(this.props.product.thumbs_extra);
		for (var i = 1; i <= this.props.product.thumbs_extra; i++) {
				if (i == 1 ){
					images.push(this.props.baseElements.staticURL + this.props.product.thumb_path); 
				} 	
				if (i == 2 ){
					images.push(this.props.baseElements.staticURL + this.props.product.thumb_path_2);
				}	
				if (i == 3 ){
					images.push(this.props.baseElements.staticURL + this.props.product.thumb_path_3);
				}

				if (i > 3) {break;}
		}


        // Check if there is a discount and calculate it
        if (this.props.product.price < this.props.product.list) {
            var percent  = this.props.product.price / this.props.product.list;
            var off = Math.round(( 1 - percent ) * 100);
            var off = off  + '% OFF';
            var list = '$' + this.props.product.list;
        } else {
            var off = '';
            var list = '';
        }  

        if (window.deviceType != 'phone') {var modal = 'modal';} else { var modal = '';}
        if (window.deviceType != 'phone') {var link = 'modal-link';} else {var link = 'normal-link';}

        var self = this;

        // <span className="product-list-off label label-success pull-right">{off}</span>
        // <a href={this.props.baseElements.baseURL + "goto/" + this.props.product.id +"/"} rel="nofollow" target="_blank"> 
        
        /*
        <div className="product-hover-area row col-xs-12">
            <div className="product-CTA-button">
                <a data-toggle={modal}  data-pushstate="1" data-target="#product-modal" data-remote="false" data-id={this.props.product.id}
					href={this.props.baseElements.baseURL + "sku/" + this.props.product.id + "-" + this.props.product.title_url + "/"} title={this.props.product.title} 
					className={"btn btn-default btn-block " + link}>See Details <span className="glyphicon glyphicon-search" aria-hidden="true"></span></a>
            </div>
        </div>	

        */

    	return (
        		<div className="product-item col-lg-2 col-md-3 col-sm-3 col-ms-4 col-xs-6" onMouseEnter={this.hoverEnter.bind(this, this.props.product.id)} onMouseLeave={this.hoverLeave.bind(this, this.props.product.id)}>
			            
						<a data-toggle={modal}  data-pushstate="1" data-target="#product-modal" data-remote="false" data-id={this.props.product.id}
						href={this.props.baseElements.baseURL + "sku/" + this.props.product.id + "-" + this.props.product.title_url + "/"} title={this.props.product.title} 
						className={link}>
			            	<img className="product-thumb img-responsive img-rounded center-block hide-no-js" data-src={this.props.baseElements.staticURL + this.props.product.thumb_path} src="/img/spinner.gif" />  	
		            	</a>

		            		{/*
			                 * 9-10-17 Removed  -> Optimized AB Test - Search Page - Remove Thumbs
			                 * Users Stay longer and More page views if we remove the thumbs!

				            <div className="thumbs-list-container container-fluid">
				            	<ul className="thumbs-list">
									{images.map(function(image, index) { 
										return  	<li className="pull-left" key={index}>
								            			<img className="thumb hide-no-js" data-src={image} alt={self.props.product.URLtitle + '_' + index} src="/img/spinner.gif" />

								        			</li>;
									})}
					            </ul>
				            </div>
				            */}

					<div className="product-inner">

						
                        <a data-toggle={modal}  data-pushstate="1" data-target="#product-modal" data-remote="false" data-id={this.props.product.id}
						href={this.props.baseElements.baseURL + "sku/" + this.props.product.id + "-" + this.props.product.title_url + "/"} title={this.props.product.title} 
						className={link}>	

			    		<span className="product-title center-block">{this.props.product.title}</span>	
			    		<span className="product-price center-block">${ parseFloat(this.props.product.price).toFixed(2)}</span>
			    		{
			    			this.props.product.stock == 0 &&
    			                   <span className="product-stock in-stock center-block">
    			                   Stock: <span aria-hidden="true" className="glyphicon glyphicon-ok-sign"></span>
                   					</span>
			    		}		

			    		{
			    			this.props.product.stock == 1 &&
    			                   <span className="product-stock no-stock center-block">
    			                   Stock: <span aria-hidden="true" className="glyphicon glyphicon-remove-sign"></span>
                   					</span>
			    		}	
		    				

		                    <div className="row hidden-xs">
			                    <div className="product-stars col-xs-5">
				                    <span className="star0">
				                    	<span className={"star" + this.props.product.stars}></span>
				                    </span>
			                    </div>
		                    	
		                    </div>
	                    	<div className="product-source center-block row hidden-xs">
		                    	<div className={this.props.product.logo} ></div>
	                    	</div>	
	                    </a>


                    </div>
        		</div>
        
		);	
	}

	componentDidMount () {
 
	}
}



// Backup
// <button type="button" className={"btn btn-primary pull-right " + this.state.hovering} >Show More</button>
// <img className="img-responsive img-rounded center-block" src={this.props.baseElements.staticURL + this.props.product.thumb_path} alt={this.props.product.title} />

/*
					        <li className="pull-left">
								            <img className="thumb img-rounded" src={this.props.baseElements.staticURL + this.props.product.thumb_path} />
								        </li>
								        <li className="pull-left">
								            <img className="thumb img-rounded" src="https://static.compare-imports.com/thumbs/28/2016/10/1/pair-of-vintage-rhinestone-embellished-geometric-shape-earrings-for-women-129457801_1.jpg" />
								        </li>
					        	        <li className="pull-left">
								            <img className="thumb img-rounded" src={this.props.baseElements.staticURL + this.props.product.thumb_path} />
								        </li>

*/


class Thumbs extends React.Component {
    constructor (props) {
        super(props);
    }

    render() {
		var divStyle = {
			clear: "both"
		}

        const no_js_css = '.hide-no-js { display: none; }'

		//this.productComponents = this.createThumb(this.props.baseElements, this.hoverEnter, this.hoverLeave);
		let baseElements = this.props.baseElements;
		// onMouseEnter={hoverEnter.bind(this, product.id)} onMouseLeave={hoverLeave.bind(this, product.id)}
		return (
        		<div>
					{this.props.products.map(function(product) {
						return <Product key={product.id} product={product} baseElements={baseElements} />;
					})}
				</div>
			
        );
	}

	componentDidMount () {

	}

}

export default Thumbs;

/*
 <a href={baseElements.baseURL + "goto/" + product.id + "/"} rel="nofollow" target="_blank" title={"Go directly to the product at " + product.name}>
		                                <div className="product-source"><div className={product.logo}></div></div>
		                        </a>
		                        */
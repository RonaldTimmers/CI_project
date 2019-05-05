import React from 'react';
var Spinner = require('react-loader');

class Loader extends React.Component {
	constructor () {
		super();
	}

	render() {
		return(
		<Spinner loaded={false} lines={13} length={20} width={10} radius={30}
						    corners={1} rotate={0} direction={1} color="#0C4F59" speed={1}
						    trail={60} shadow={false} hwaccel={false} className="spinner"
						    zIndex={2e9} top="150px" left="45%" scale={1.00}
						    loadedClassName="loadedContent">
	    <div className="loading"></div>
	    </Spinner>);
	}
}

export default Loader;
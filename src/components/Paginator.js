import React from 'react';
import ReactPaginate from 'react-paginate';
import store from "../../store";
import { setPage } from '../Actions';
 
import { createHistory, useQueries } from 'history'
const history = useQueries(createHistory)()



class Paginator extends React.Component {
  constructor(props) {
    super(props);

    // Get the total products from the initial PHP script and define the total number of pages
    let totalProducts = parseInt(props.totalProducts); 
    let totalPages = Math.ceil(totalProducts / props.page_items); //Based on static 40 products per page.
    
    // Set the state of the internal  paginator 
    this.state = { totalPages: totalPages};
    
  }

  componentWillReceiveProps (nextProps) {
    console.log("Paginator Will Receive Props: Next = ", nextProps.totalProducts, "This = ", this.props.totalProducts);
    
    let totalProducts = parseInt(nextProps.totalProducts); 
    let totalPages = Math.ceil(totalProducts / nextProps.page_items); //Based on static 40 products per page.

    this.setState({totalPages: totalPages})
  }

  handlePageClick = (data) => {
    //Get the selected page from the paginator and change the State
    // + 1 because internal working of React-Paginate
    let selectedPage = data.selected + 1;
    
    scrollToTopProducts();
    //scrollToTop(); 

    // Change the main store State
    store.dispatch(setPage(selectedPage));

    // Change the URL
    let location = history.getCurrentLocation()
    console.log("Location.key pagination: ", location.key );
    
    history.push({
      pathname: location.pathname,
      query: Object.assign({}, location.query, {page: selectedPage}) 
    })    
                           
  };

 
  render() {
    console.log("Render Paginator Component");
    return (
      <div> 
          <ReactPaginate previousLabel={"< Prev"}
                         nextLabel={" Next >"}
                         breakLabel={<a href="">..</a>}
                         breakClassName={"break-me"}
                         pageNum={this.state.totalPages}
                         marginPagesDisplayed={1}
                         pageRangeDisplayed={3}
                         containerClassName={"pagination pagination-responsive "}
                         activeClassName={"active"}
                         forceSelected = {parseInt(this.props.page) - 1}
                          clickCallback={this.handlePageClick}
                          >        
          </ReactPaginate>
      </div>
    );
  }
}
 
export default Paginator;
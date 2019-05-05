import React from 'react';
import Helmet from "react-helmet";
 
import { createHistory, useQueries } from 'history'
const history = useQueries(createHistory)()



class Head extends React.Component {
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
    scrollToTop(); 

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
    console.log("Render Head Component");
    let location = history.getCurrentLocation()

    
    
    let page = parseInt(this.props.page)
    let nextpage = parseInt(this.props.page) + 1
    let prevpage = parseInt(this.props.page) - 1

    var links = new Array();
    var meta = new Array();

    console.log('Location.query: ', location.query);

    if(Object.keys(location.query).length) {
        console.log('de query bestaat');
        console.log(Object.keys(location.query).length == 1);

        if ("page" in location.query) {
              if (Object.keys(location.query).length == 1 ) {
                  if (page == 1) {
                      var canonical = window.location.protocol + "//" + window.location.hostname + location.pathname
                  } else {
                      var canonical = window.location.protocol + "//" + window.location.hostname + location.pathname + "?page=" + page
                      var prevurl = window.location.protocol + "//" + window.location.hostname + location.pathname + "?page=" + prevpage
                      var robots = true
                  }
                  
                  if (page != this.state.totalPages ) {
                      var nexturl = window.location.protocol + "//" + window.location.hostname + location.pathname + "?page=" + nextpage
                  } 
              } else {
                  var canonical = window.location.protocol + "//" + window.location.hostname + location.pathname
                  var nexturl = window.location.protocol + "//" + window.location.hostname + location.pathname + "?page=" + nextpage
                  var robots = true
              }

                              
        } else {
              var canonical = window.location.protocol + "//" + window.location.hostname + location.pathname
              var nexturl = window.location.protocol + "//" + window.location.hostname + location.pathname + "?page=" + nextpage
              var robots = true
        }
    } else {
        console.log('de query bestaat NIET');
        var canonical = window.location.protocol + "//" + window.location.hostname + location.pathname
        var nexturl = window.location.protocol + "//" + window.location.hostname + location.pathname + "?page=" + nextpage
    } 

    

    links.push({rel: "canonical", href: canonical});
    if (nexturl) { links.push({rel: "next", href: nexturl}); }
    if (prevurl) { links.push({rel: "prev", href: prevurl}); }
    if (robots)  { meta.push({name: "robots", content: "noindex, follow"}); }

    return (
      <div> 
          <Helmet link={links} meta={meta} />
      </div>
    );
  }
}
 
export default Head;
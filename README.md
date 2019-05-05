
# Compare Imports (CI)

The project started back in 2013 with a few friends. Since then we build, changed and improved the code.
The **trade-off between going forward (make the most out of your time as an entrepreneur) and keeping everything clean and maintainable always went to the first**.
Of course not a very sensible choice concerning the future. 

However, on that road, I learned a lot!

---- 

### Learnings

What are the main things I should have done differently if we start over again: 
1. Pick a Framework
2. Use GIT
3. Always try to keep SOLID maintainable code
4. Use a better IDE (or Visual Studio Code with extensions)
5. Don't forget Unit Tests 

*Some good lessons for the next project ;)* 

----

###  App Overview 
- Main App (**Current Github Repository**)
- Backend Services  (Category Mapping, Proxies, Edit Categories)
- Python Scraper with Scrapy Framework and Scrapy Deamon ([https://scrapy.org/] & [https://scrapyd.readthedocs.io/en/stable/])

----
<br/><br/>

## Walkthrough Main App

### Server Side PHP

- We did not use a specific router 
Using the *.htaccess* and *index.php* for that. 

- At first, we used a more procedural coding style.
Therefore there is still a */functions* folder with one main file. 

- Later, we tried to build in a more object-oriented structure
At that moment, we should have decided on an MVC framework.
That would have benefited our app structure bigtime. 

----

#### Main Files

- The main classes (/classes)
    - *Pageinfo.php* (get the right content, initiate in index)
    - *Pagecreator.php* (set main filter elements)
    - *Product.php* (create a single product)
    - *SearchQL.php* (set variables for using the SphinxServer)

Like mentioned above, we never made use of an MVC structure. 
It was until the last month, I tried to start the structure to an MVC layout. 
Which you can see in the */controllers* and */views* folders. 
Together with Twig (Counterpart of Laravel's Blade) /templates.

- The main views (/sites)
    - *c.php* (category overview)
    - *sc.php* (product overview)
    - *product.php* (single product page)
    - *search.php* (search overview)


#### SphinxSearch

To shorten the speed of retrieving the right products (according to search/category/filters). 
We used an open source search server Sphinx ([http://sphinxsearch.com/]).  

*A similar tool like the more famous, elasticsearch*


----

###  React Front-End Filter

We wanted to improve the search and filter front-end of our 2 million products. 
Therefore we rebuild a part of the website into a single page application. 
With the following libraries:

- react
- react-redux
- store
- axios

You can find the filter structure in the */src* folder. 

----

### Webpack

See *./webpack.prod.config.js*

Improve speed/delivery by compiling and bundling the assets.








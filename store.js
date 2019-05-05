import { applyMiddleware, createStore } from 'redux';


//import { createLogger } from 'redux-logger'
import thunk from "redux-thunk";
import reducers from "./src/reducers/Reducers";

const middleware = applyMiddleware(thunk)

export default createStore(reducers, middleware)


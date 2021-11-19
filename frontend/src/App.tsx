import React from 'react'
import {BrowserRouter, Route, Switch} from 'react-router-dom'
import Navbar from "./component/Navbar";
import Blog from './page/Blog'
import Home from "./page/Home";
import Contact from "./page/Contact";
import Login from "./page/Login";
import Signup from "./page/Signup";
import {logger} from "./util/log";
import {getPrefix} from "./util/url";
import {NotFound} from "./page/NotFound";
import Footer from "./component/Footer";
import {Post} from './page/Post'

/**
 * This is the main app function, it will spawn all the components required for the app to function
 *
 * @returns JSX.Element The app
 */
export default function App() {
    logger.debug('Starting application')
    logger.debug('Base URL is ' + getPrefix())

    return (
        <BrowserRouter>
            <Navbar/>
            <Switch>
                <Route exact path="/">
                    <Home/>
                </Route>

                <Route exact path="/~20006203">
                    <Home/>
                </Route>

                <Route path="/~20006203/blog">
                    <Blog/>
                </Route>

                <Route path="/~20006203/contact">
                    <Contact/>
                </Route>

                <Route path="/~20006203/login">
                    <Login/>
                </Route>

                <Route path="/~20006203/signup">
                    <Signup/>
                </Route>

                <Route path="/~20006203/post/:id">
                    <Post/>
                </Route>

                <Route>
                    <NotFound/>
                </Route>
            </Switch>
            <Footer/>
        </BrowserRouter>
    )
}



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
import {Paths} from "./util/paths";

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

                <Route exact path={Paths.HOME}>
                    <Home/>
                </Route>

                <Route path={Paths.BLOG}>
                    <Blog/>
                </Route>

                <Route path={Paths.CONTACT}>
                    <Contact/>
                </Route>

                <Route path={Paths.LOGIN}>
                    <Login/>
                </Route>

                <Route path={Paths.SIGNUP}>
                    <Signup/>
                </Route>

                <Route path={Paths.POST}>
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



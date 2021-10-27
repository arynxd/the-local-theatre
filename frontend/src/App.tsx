import React from 'react'
import {BrowserRouter, Route, Switch} from 'react-router-dom'
import Navbar from "./component/Navbar";
import Blog from './page/Blog'
import Home from "./page/Home";
import Contact from "./page/Contact";
import Login from "./page/Login";
import Signup from "./page/Signup";
import {BackendController} from "./backend/BackendController";
import {logger} from "./util/log";
import {getPrefix} from "./util/url";
import {initThemes} from "./util/theme";
import ThemeContext from "./component/ThemeContext";
import {NotFound} from "./page/NotFound";



function App() {
    const backend = new BackendController()
    logger.debug('Starting application')
    logger.debug('Base URL is ' + getPrefix())

    initThemes()
    return (
        <BrowserRouter>
            <ThemeContext>
                <Navbar/>
                <Switch>
                    <Route exact path="/">
                        <Home backend={backend}/>
                    </Route>

                     <Route exact path="/~20006203">
                        <Home backend={backend}/>
                     </Route>

                    <Route path="/blog">
                        <Blog/>
                    </Route>

                    <Route path="/contact">
                        <Contact/>
                    </Route>

                    <Route path="/login">
                        <Login/>
                    </Route>

                    <Route path="/signup">
                        <Signup/>
                    </Route>

                    <Route>
                        <NotFound />
                    </Route>
                </Switch>
            </ThemeContext>
        </BrowserRouter>
    )
}


export default App


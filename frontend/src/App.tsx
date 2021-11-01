import React, {useState} from 'react'
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
import {getTheme, loadTheme} from "./util/theme";
import {NotFound} from "./page/NotFound";
import Footer from "./component/Footer";
import {ThemeContext} from "./component/ThemeToggle";
import {Post} from './page/Post'


function App() {
    const backend = new BackendController()
    logger.debug('Starting application')
    logger.debug('Base URL is ' + getPrefix())

    loadTheme()

    // holds the global state for the theme
    const [theme, setTheme] = useState(getTheme())

    return (
        <BrowserRouter>
            <ThemeContext.Provider value={{theme, setTheme}}>
                <Navbar/>
                <Switch>
                    <Route exact path="/">
                        <Home backend={backend}/>
                    </Route>

                    <Route exact path="/~20006203">
                        <Home backend={backend}/>
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
            </ThemeContext.Provider>
        </BrowserRouter>
    )
}


export default App


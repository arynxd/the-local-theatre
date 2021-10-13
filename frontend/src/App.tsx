import React from 'react'
import {BrowserRouter, Route, Switch} from 'react-router-dom'
import Navbar from "./component/Navbar";
import Blog from './page/Blog'
import Home from "./page/Home";
import Contact from "./page/Contact";
import Login from "./page/Login";
import Signup from "./page/Signup";
import {BackendController} from "./backend/BackendController";
import {NotFound} from "./page/NotFound";

function App() {
    const backend = new BackendController()

    return (
        <BrowserRouter>
            <Navbar/>
            <Switch>
                <Route exact path="/">
                    <Home backend={backend}/>
                </Route>

                <Route path="/blog">
                    <Blog />
                </Route>

                <Route path="/contact">
                    <Contact />
                </Route>

                <Route path="/login">
                    <Login />
                </Route>

                <Route path="/signup">
                    <Signup />
                </Route>

                <Route path="*">
                    <NotFound />
                </Route>
            </Switch>
        </BrowserRouter>
    )
}

export default App


import { BrowserRouter, Route, Switch } from 'react-router-dom'
import Navbar from "./component/Navbar";
import Blog from './page/Blog'
import Home from "./page/Home";
import Contact from "./page/Contact";
import Login from "./page/Login";
import Signup from "./page/Signup";
import { logger } from "./util/log";
import { getPrefix } from "./util/url";
import { NotFound } from "./page/NotFound";
import Footer from "./component/Footer";
import { Post } from './page/Post'
import { Paths } from "./util/paths";
import UserSettings from "./page/UserSettings";
import Moderation from "./page/Moderation";


function Body() {
    return (
        <Switch>
            <Route exact path="/">
                <Home />
            </Route>

            <Route exact path={Paths.HOME}>
                <Home />
            </Route>

            <Route path={Paths.BLOG}>
                <Blog />
            </Route>

            <Route path={Paths.CONTACT}>
                <Contact />
            </Route>

            <Route path={Paths.LOGIN}>
                <Login />
            </Route>

            <Route path={Paths.SIGNUP}>
                <Signup />
            </Route>

            <Route path={Paths.POST}>
                <Post />
            </Route>

            <Route path={Paths.USER_SETTINGS}>
                <UserSettings />
            </Route>

            <Route path={Paths.MODERATION}>
                <Moderation />
            </Route>

            <Route>
                <NotFound />
            </Route>
        </Switch>
    )
}
export default function App() {
    logger.debug('Starting application')
    logger.debug('Base URL is ' + getPrefix())

    return (
        <BrowserRouter>
            <Navbar />
            <div className="h-full md:h-screen flex flex-col overflow-visible">
                <div className="flex-grow">
                    <Body />
                </div>
                <Footer />
            </div>
        </BrowserRouter>
    )
}

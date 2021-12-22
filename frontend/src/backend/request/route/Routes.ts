import {Route} from "./Route";

//TODO: check the validation keys here

/**
 * All of the Route(s) available on the backend API
 */
export default class Routes {
    public static readonly User = class {
        public static readonly FETCH = new Route('api/user', 'GET', ['id'], [], false)
        public static readonly FETCH_ALL = new Route('api/user/list', 'GET', [], [], false)
        public static readonly UPDATE = new Route('api/user', 'POST', [], [], true)
        public static readonly DELETE = new Route('api/user', 'DELETE', [], [], true)
        public static readonly AVATAR = new Route('api/avatar', 'GET', ['id'], [], false)
    }

    public static readonly Auth = class {
        public static readonly SIGNUP = new Route('api/signup', 'POST', [], ['name', 'username', 'email', 'password'], false)
        public static readonly LOGIN = new Route('api/login', 'POST', [], ['email', 'password'], false)
    }

    public static readonly Post = class {
        public static readonly FETCH = new Route('api/post', 'GET', ['id'], [], false)
        public static readonly LIST = new Route('api/post/list', 'GET', [], [], false)
        public static readonly ADD = new Route('api/post', 'POST', [], ['content', 'title'], true)
        public static readonly DELETE = new Route('api/post', 'DELETE', [], [], true)
        public static readonly UPDATE = new Route('api/post', 'POST', [], [], true)
    }

    public static readonly Comment = class {
        public static readonly LIST = new Route('api/comment/list', 'GET', ['id'], [], false)
        public static readonly DELETE = new Route('api/comment', 'DELETE', [], [], true)
        public static readonly ADD = new Route('api/comment', 'POST', [], ['content', 'postId'], true)
        public static readonly UPDATE = new Route('api/comment', 'POST', [], [], true)
    }

    public static readonly Show = class {
        public static readonly LIST = new Route('api/show/list', 'GET', [], [], false)
        public static readonly IMAGE = new Route('api/show/image', 'GET', ['id'], [], false)
    }

    public static readonly Self = class {
        public static readonly FETCH = new Route('api/user/@me', 'GET', [], [], true)
    }
}
import {Route} from "./Route";

export default class Routes {
    public static readonly User = class {
        public static readonly FETCH = new Route('api/user/', 'GET', ['id'], [], false)
        public static readonly FETCH_ALL = new Route('api/user/list/', 'GET', ['limit'], [], false)
        public static readonly UPDATE = new Route('api/user/', 'POST', [], [], true)
    }

    public static readonly Auth = class {
        public static readonly SIGNUP = new Route('api/signup/', 'POST', [], ['name', 'username', 'email', 'password'], false)
    }

    public static readonly Post = class {
        public static readonly FETCH = new Route('api/post/', 'GET', ['id'], [], false)
        public static readonly LIST = new Route('api/post/list/', 'GET', [''], [], false)
    }
}
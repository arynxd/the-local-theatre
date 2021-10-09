import {fetch} from "../util/url";
import CacheManager from "./CacheManager";

export default class HttpManager {
    constructor(public userCache: CacheManager<string, any> = new CacheManager()) { }

    async loadUsers() {
        const data = fetch("api/user/list").then(data => data.json())

        
    }
}
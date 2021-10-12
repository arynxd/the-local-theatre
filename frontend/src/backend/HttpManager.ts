
import CacheManager from "./CacheManager";
import User from "../model/User";
import {Guid} from "guid-typescript";

export default class HttpManager {
    public readonly userCache = new CacheManager<Guid, User>()

    async loadUsers() {


        
    }
}
<?php

class Constants {
    const URI_PREFIX = "~20006203";
    const API_PREFIX = "api";
    public static final function AVATAR_URL_PREFIX() {
        return "http://$_SERVER[HTTP_HOST]/api/avatar";
    }
}
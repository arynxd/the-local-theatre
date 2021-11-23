<?php

class Constants {
    const URI_PREFIX = "~20006203";
    const API_PREFIX = "api";

    public static final function AVATAR_URL_PREFIX() {
        return self ::SERVER_URL_PREFIX() . 'avatar';
    }

    public static final function SERVER_URL_PREFIX() {
        return "https://$_SERVER[HTTP_HOST]/~20006203/api/";
    }

    public static final function SHOW_IMAGE__URL_PREFIX() {
        return self ::SERVER_URL_PREFIX() . 'show/image';
    }
}
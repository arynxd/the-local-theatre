<?php

namespace TLT\Request\Module\Impl;

use TLT\Model\Impl\UserModel;
use TLT\Request\Module\Module;
use TLT\Util\Data\Map;
use TLT\Util\Enum\Constants;

class CacheModule extends Module {
    private $user;

    /**
     * Fetch the user that relates to the token used for auth
     *
     * If the token is not set, or the user is not found, this method returns null
     *
     * @return UserModel|null The model
     */
    public function user() {
        if (isset($this -> user)) {
            return $this -> user;
        }

        $auth = $this -> sess -> auth;

        if (!$auth -> isAuthenticated()) {
            return null;
        }

        $query = "SELECT u.* FROM credential c
                    LEFT JOIN user u on u.id = c.userId
                  WHERE token = :token";

        $selfUser = $this -> sess -> db -> query($query, ['token' => $auth -> token]) -> fetch();
        $selfUser = Map ::from($selfUser);

        $selfUser['avatar'] = Constants ::AVATAR_URL_PREFIX() . "?id=" . $selfUser['id'];
        $model = UserModel ::fromJSON($selfUser);
        $this -> user = $model;
        return $model;
    }
}
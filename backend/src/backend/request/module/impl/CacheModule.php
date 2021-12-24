<?php

namespace TLT\Request\Module\Impl;

use TLT\Model\Impl\SelfUserModel;
use TLT\Request\Module\BaseModule;
use TLT\Util\Data\Map;

class CacheModule extends BaseModule {
    private $user;

    /**
     * Fetch the user that relates to the token used for auth
     *
     * If the token is not set, or the user is not found, this method returns null
     *
     * @return SelfUserModel|null
     */
    public function user() {
        if (isset($this->user)) {
            return $this->user;
        }

        $auth = $this->sess->auth;

        if (!$auth->isAuthenticated()) {
            return null;
        }

        $query = "SELECT * FROM credential c
                    LEFT JOIN user u ON u.id = c.userId
                  WHERE token = :token";

        $selfUser = $this->sess->db
            ->query($query, ['token' => $auth->token])
            ->fetch();
        $model = SelfUserModel::fromJSON(Map::from($selfUser));
        $this->user = $model;
        return $model;
    }
}

<?php
require_once __DIR__ . "/../Middleware.php";
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . "/../../route/RouteValidationResult.php";

class AuthenticationMiddleware extends Middleware {
    public function apply($sess) {
        $token = $sess -> headers['Authorisation'];

        if (!isset($token)) {
            return Result(StatusCode::FORBIDDEN, "You are not permitted to perform this action.");
        }

         $query = "SELECT u.* FROM credential c
                    LEFT JOIN user u on u.id = c.userId
                  WHERE token = :token";

        $selfUser = $sess -> database -> query($query, ['token' => $token]) -> fetch();
        $selfUser = map($selfUser);

        if ($selfUser -> length() == 0) {
            return Result(StatusCode::FORBIDDEN, "Invalid or expired token provided.");
        }

       $selfUser['avatar'] = Constants::AVATAR_URL_PREFIX() . "?id=" . $selfUser['id'];
        $sess -> selfUser = UserModel::fromJSON($selfUser);

        return Ok();
    }
}
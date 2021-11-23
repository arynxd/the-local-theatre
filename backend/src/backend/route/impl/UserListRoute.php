<?php
require_once __DIR__ . '/../../util/constant/StatusCode.php';
require_once __DIR__ . '/../../util/constant/ContentType.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../util/Map.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../util/constant/Constants.php';
require_once __DIR__ . '/../../util/identifier.php';

class UserListRoute extends Route {
    public function __construct() {
        parent ::__construct("user/list", [RequestMethod::GET]);
    }

    public function handle($conn, $res) {

        $out = new Map();

        for ($i = 0; $i < 1; $i ++) {
            $model = new UserModel(
                createIdentifier(),
                'John',
                'Doe',
                0,
                0,
                0,
                'jdoe',
                Constants ::AVATAR_URL_PREFIX()
            );
            $out -> push($model -> toMap());
        }
        $res -> sendJSON(map([
            'users' => $out
        ]), StatusCode::OK);

    }

    public function validateRequest($conn, $res) {
        return Ok();
    }
}
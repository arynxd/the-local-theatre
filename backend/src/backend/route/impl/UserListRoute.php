<?php
require_once __DIR__ . '/../../util/constant/StatusCode.php';
require_once __DIR__ . '/../../util/constant/ContentType.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';

require_once __DIR__ . '/../../route/Route.php';

class UserListRoute extends Route {
    public function __construct() {
        parent ::__construct("user/list", [RequestMethod::GET]);
    }

    public function handle($conn, $res) {
        $limit = $conn -> queryParams()['limit'];

        $out = [];

        for ($i = 0; $i < $limit; $i++) {
            $model = new UserModel(
                $i,
                'John Doe',
                0,
                0,
                0,
                'jdoe',
                "http://$_SERVER[HTTP_HOST]/avatar"
            );
            array_push($out, $model -> toMap());
        }
        $res -> sendJSON($out, StatusCode::OK);

    }

    public function validateRequest($conn, $res) {
        $params = $conn -> queryParams();
        if (!$params -> exists('limit') || $params['limit'] <= 0) {
            return BadRequest("Limit Not Provided");
        }
        return Ok();
    }
}
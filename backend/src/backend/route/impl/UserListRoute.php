<?php
require_once __DIR__ . '/../../util/constant/StatusCode.php';
require_once __DIR__ . '/../../util/constant/ContentType.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';

require_once __DIR__ . '/../../route/Route.php';

class UserListRoute extends Route {
    public function __construct($database) {
        parent::__construct($database, "user/list", [RequestMethod::GET]);
    }

    public function handle($conn, $res) {
        $res -> sendJSON([
            [ "id" => 1, "name" => "John Doe" ],
            [ "id" => 2, "name" => "John Doe" ],
            [ "id" => 3, "name" => "John Doe" ],
            [ "id" => 4, "name" => "John Doe" ],
        ], StatusCode::OK);
        
    }

    public function validateRequest($conn, $res) {
        return [isset($conn -> queryParams()["limit"]), "Limit not passed"];
    }
}
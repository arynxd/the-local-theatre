<?php

namespace TLT\Routing\Impl;

use TLT\Middleware\Impl\AuthenticationMiddleware;
use TLT\Middleware\Impl\DatabaseMiddleware;
use TLT\Middleware\Impl\ModelValidatorMiddleware;
use TLT\Model\Impl\UserModel;
use TLT\Model\ModelKeys;
use TLT\Routing\BaseRoute;
use TLT\Util\Assert\Assertions;
use TLT\Util\Data\Map;
use TLT\Util\DBUtil;
use TLT\Util\Enum\Constants;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpResult;
use TLT\Util\Log\Logger;
use TLT\Util\StringUtil;

class UserRoute extends BaseRoute {
    public function __construct() {
        parent::__construct('user', [RequestMethod::GET, RequestMethod::POST]);
    }

    public function handle($sess, $res) {
        $method = $sess->http->method;

        if ($method == RequestMethod::GET) {
            $id = $sess->queryParams()['id'];
            Assertions::assertSet($id);

            $entity = $sess->data->user->get($id);

            if (!isset($entity)) {
                $res->status(404)
                    ->cors('all')
                    ->error('User not found');
            }

            $res->status(200)
                ->cors('all')
                ->json($entity->toMap());
        } elseif ($method == RequestMethod::POST) {
            $body = $sess->jsonParams();

            $selfUser = $sess->cache->user();
            Assertions::assertSet($selfUser);

            $isModifyingSelf = $selfUser->id == $body['id'];
            $isSelfAdmin = $selfUser->permissions == 2;
            $isEditingPerms = $selfUser->permissions != $body['permissions'];

            if (!$isModifyingSelf && !$isSelfAdmin) {
                $res->status(401)
                    ->cors('all')
                    ->error('You may only modify your own user account');
            }

            if ($isModifyingSelf && $isEditingPerms) {
                $res->status(401)
                    ->cors('all')
                    ->error('You cannot change your own permissions');
            }

            $user = $sess->data->user;

            if (isset($body['id'])) {
                // Update existing entity

                $user->start();
                $model = $user->get($body['id']);

                if (!isset($model)) {
                    $res->status(404)
                        ->cors('all')
                        ->error('User not found');
                }

                $newMap = Map::from([
                    'id' => $model->id,
                    'firstName' => $body->orDefault(
                        'firstName',
                        $model->firstName
                    ),
                    'lastName' => $body->orDefault(
                        'lastName',
                        $model->lastName
                    ),
                    'permissions' => $body->orDefault(
                        'permissions',
                        $model->permissions
                    ),
                    'dob' => $body->orDefault('dob', $model->dob),
                    'joinDate' => $model->joinDate,
                    'username' => $body->orDefault(
                        'username',
                        $model->username
                    ),
                ]);

                $newModel = UserModel::fromJSON($newMap);
                $user->edit($newModel);
                $user->commit();
            } else {
                // Insert new entity

                // Make sure we have enough data to create a user
                $sess->applyMiddleware(
                    new ModelValidatorMiddleware(
                        ModelKeys::USER_CREATE_MODEL(),
                        $body,
                        'Incomplete user data provided'
                    )
                );

                $id = StringUtil::newID();
                $joinDate = DBUtil::currentTime();

                $userData = Map::from([
                    'id' => $id,
                    'firstName' => $body['firstName'],
                    'lastName' => $body['lastName'],
                    'permissions' => DBUtil::DEFAULT_PERMISSIONS,
                    'dob' => $body['dob'],
                    'joinDate' => $joinDate,
                    'username' => $body['username'],
                ]);

                $userModel = UserModel::fromJSON($userData);

                $user->insert($userModel);
            }
        } else {
            Logger::getInstance()->fatal("Unhandled method $method");
        }
    }

    public function validateRequest($sess, $res) {
        $sess->applyMiddleware(new DatabaseMiddleware());

        if (
            $sess->http->method == RequestMethod::GET &&
            !isset($sess->queryParams()['id'])
        ) {
            return HttpResult::BadRequest('No ID provided');
        }

        if ($sess->http->method == RequestMethod::POST) {
            $data = $sess->jsonParams();

            if (!isset($data)) {
                return HttpResult::BadRequest('No data provided');
            }

            $sess->applyMiddleware(new AuthenticationMiddleware());
        }

        return HttpResult::Ok();
    }
}

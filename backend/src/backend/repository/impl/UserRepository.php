<?php

namespace TLT\Respository\Impl;

use TLT\Model\Impl\UserModel;
use TLT\Respository\BaseRepository;
use TLT\Util\Data\Map;

class UserRepository extends BaseRepository {
    /**
     * @inheritDoc
     * @return UserModel|null
     */
    public function get($id) {
        $query = 'SELECT * FROM user WHERE id = :id';

        $st = $this->sess->db->query($query, [
            'id' => $id,
        ]);

        $dbRes = Map::from($st->fetchAll())->toMapRecursive();

        if ($dbRes->length() == 0) {
            return null;
        }

        $dbRes = $dbRes->first();

        return UserModel::fromJSON($dbRes);
    }

    /**
     * @inheritDoc
     * @return Map<UserModel>
     */
    public function getAll() {
        $out = Map::none();

        $st = $this->sess->db->query('SELECT * FROM user');
        $dbRes = Map::from($st->fetchAll());

        foreach ($dbRes->raw() as $arr) {
            // convert to a model to get the right keys & validate
            $out->push(UserModel::fromJSON(Map::from($arr))->toMap());
        }
        return $out;
    }

    /**
     * @inheritDoc
     */
    public function delete($id) {
        $query = 'DELETE FROM user WHERE id = :id';

        $res = $this->sess->db->query($query, [
            'id' => $id,
        ]);

        return $res->rowCount() > 0;
    }

    /**
     * @inheritDoc
     * @param UserModel $model
     */
    public function insert($model) {
        $query = "INSERT INTO user (id, firstName, lastName, username, dob, joinDate, permissions)
          VALUES (
              :id,
              :firstName,
              :lastName,
              :username,
              :dob,
              :joinDate,
              :permissions
      )";

        $this->sess->db->query($query, [
            'firstName' => $model->firstName,
            'lastName' => $model->lastName,
            'username' => $model->username,
            'dob' => $model->dob,
            'permissions' => $model->permissions,
            'id' => $model->id,
            'joinDate' => $model->joinDate,
        ]);
        return true;
    }

    /**
     * @inheritDoc
     * @param UserModel $model
     */
    public function edit($model) {
        $query = "UPDATE user SET
                firstName = :firstName,
                lastName = :lastName,
                username = :username,
                dob = :dob,
                permissions = :permissions
            WHERE id = :id
    ";

        $res = $this->sess->db->query($query, [
            'firstName' => $model->firstName,
            'lastName' => $model->lastName,
            'username' => $model->username,
            'dob' => $model->dob,
            'permissions' => $model->permissions,
            'id' => $model->id,
        ]);

        return $res->rowCount() > 0;
    }

    public function exists($id) {
        $query = 'SELECT COUNT(*) FROM user WHERE id = :id';

        $res = $this->sess->db->query($query, [
            'id' => $id,
        ]);

        return $res->fetchColumn() > 0;
    }
}

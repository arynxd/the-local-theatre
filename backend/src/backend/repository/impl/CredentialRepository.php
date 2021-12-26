<?php

namespace TLT\Repository\Impl;

use TLT\Model\Impl\CredentialModel;
use TLT\Model\Impl\UserModel;
use TLT\Repository\BaseRepository;
use TLT\Util\Data\Map;

class CredentialRepository extends BaseRepository {
	/**
	 * @inheritDoc
	 * @return CredentialModel|null
	 */
	public function get($id) {
		$query = 'SELECT * FROM credential WHERE email = :email';

		$st = $this->sess->db->query($query, [
			'email' => $id,
		]);

		$dbRes = Map::from($st->fetchAll())->toMapRecursive();

		if ($dbRes->length() == 0) {
			return null;
		}

		$dbRes = $dbRes->first();

		return new CredentialModel(
			$dbRes['userId'],
			$dbRes['email'],
			$dbRes['password'],
			$dbRes['token']
		);
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
			$out->push(
				(new CredentialModel(
					$arr['userId'],
					$arr['email'],
					$arr['password'],
					$arr['token']
				))->toMap()
			);
		}
		return $out;
	}

	/**
	 * @inheritDoc
	 */
	public function delete($email) {
		$query = 'DELETE FROM credential WHERE email = :email';

		$res = $this->sess->db->query($query, [
			'email' => $email,
		]);

		return $res->rowCount() > 0;
	}

	/**
	 * @inheritDoc
	 * @param CredentialModel $model
	 */
	public function insert($model) {
		$query = "INSERT INTO credential (userId, email, password, token)
          VALUES (
              :userId,
              :email,
              :password,
              :token
        )";

		$this->sess->db->query($query, [
			'userId' => $model->userId,
			'email' => $model->email,
			'password' => $model->password,
			'token' => $model->token,
		]);

		return true;
	}

	/**
	 * @inheritDoc
	 * @param CredentialModel $model
	 */
	public function edit($model) {
		$query = "UPDATE credential SET
                email = :email,
                password = :password,
                token = :token
            WHERE userId = :userId
        ";

		$res = $this->sess->db->query($query, [
			'userId' => $model->userId,
			'email' => $model->email,
			'password' => $model->password,
			'token' => $model->token,
		]);

		return $res->rowCount() > 0;
	}

	public function exists($id) {
		$query = 'SELECT COUNT(*) FROM credential WHERE email = :email';

		$res = $this->sess->db->query($query, [
			'email' => $id,
		]);

		return $res->fetchColumn() > 0;
	}
}

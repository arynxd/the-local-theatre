<?php

namespace TLT\Repository\Impl;

use http\Exception\BadMethodCallException;
use PDO;
use TLT\Model\Impl\CommentModel;
use TLT\Model\Impl\UserModel;
use TLT\Respository\BaseRepository;

class CommentRepository extends BaseRepository {
	/**
	 * @inheritDoc
	 * @return CommentModel|null
	 */
	function get($id) {
		$st = $this->sess->db->query(
			"SELECT * FROM comment c 
                LEFT JOIN user u on u.id = c.authorId
            WHERE c.id = :id",
			['id' => $id]
		);

		$res = $st->fetch(PDO::FETCH_NAMED);

		if (!$res) {
			return null;
		}

		$ids = $res['id'];

		return new CommentModel(
			$ids[0],
			new UserModel(
				$ids[1],
				$res['firstName'],
				$res['lastName'],
				(int) $res['permissions'],
				(int) $res['dob'],
				(int) $res['joinDate'],
				$res['username']
			),
			$res['postId'],
			$res['content'],
			(int) $res['createdAt'],
			(int) $res['editedAt']
		);
	}

	/**
	 * @inheritDoc
	 * @return never
	 */
	function getAll() {
		throw new BadMethodCallException(
			'getAll is not supported by this repository'
		);
	}

	/**
	 * @inheritDoc
	 */
	function delete($id) {
		$query = 'DELETE FROM comment WHERE id = :id';

		$res = $this->sess->db->query($query, ['id' => $id]);

		return $res->rowCount() > 0; // true if it was modified, false otherwise
	}

	/**
	 * @inheritDoc
	 * @param CommentModel $model
	 */
	function insert($model) {
		$query = "INSERT INTO comment (
                id, authorId, postId, content, createdAt, editedAt
            ) 
            VALUES (
                :id, :authorId, :postId, :content, :createdAt, :editedAt
            )
        ";

		$this->sess->db->query($query, [
			'id' => $model->id,
			'authorId' => $model->author->id,
			'postId' => $model->postId,
			'content' => $model->content,
			'createdAt' => $model->createdAt,
			'editedAt' => $model->editedAt,
		]);

		return true;
	}

	/**
	 * @inheritDoc
	 * @param CommentModel $model
	 */
	function edit($model) {
		$query = "UPDATE comment SET
                content = :content,
                editedAt = :editedAt
            WHERE id = :id
        ";

		$res = $this->sess->db->query($query, [
			'id' => $model->id,
			'content' => $model->content,
			'editedAt' => $model->editedAt,
		]);

		return $res->rowCount() > 0;
	}

	/**
	 * @inheritDoc
	 */
	function exists($id) {
		$query = 'SELECT COUNT(*) FROM comment WHERE id = :id';

		$res = $this->sess->db->query($query, [
			'id' => $id,
		]);

		return $res->fetchColumn() > 0;
	}
}

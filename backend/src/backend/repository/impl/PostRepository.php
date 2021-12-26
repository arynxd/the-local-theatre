<?php

namespace TLT\Repository\Impl;

use PDO;
use TLT\Model\Impl\PostModel;
use TLT\Model\Impl\UserModel;
use TLT\Repository\BaseRepository;
use TLT\Util\Data\Map;

class PostRepository extends BaseRepository {
	/**
	 * @inheritDoc
	 * @return PostModel|null
	 */
	function get($id) {
		$st = $this->sess->db->query(
			"SELECT * FROM post p 
                LEFT JOIN user u on u.id = p.authorId
            WHERE p.id = :id",
			['id' => $id]
		);

		$res = $st->fetch(PDO::FETCH_NAMED);

		if (!$res) {
			return null;
		}

		$ids = $res['id'];

		return new PostModel(
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
			$res['content'],
			$res['title'],
			(int) $res['createdAt'],
			(int) $res['editedAt']
		);
	}

	/**
	 * @inheritDoc
	 * @return Map<PostModel>
	 */
	function getAll() {
		$st = $this->sess->db->query("SELECT * FROM post p 
                LEFT JOIN user u on u.id = p.authorId");

		$db = $st->fetchAll(PDO::FETCH_NAMED);
		$posts = Map::none();

		foreach ($db as $item) {
			$model = new PostModel(
				$item['id'][0],
				new UserModel(
					$item['id'][1],
					$item['firstName'],
					$item['lastName'],
					(int) $item['permissions'],
					(int) $item['dob'],
					(int) $item['joinDate'],
					$item['username']
				),
				$item['content'],
				$item['title'],
				(int) $item['createdAt'],
				(int) $item['editedAt']
			);
			$posts->push($model->toMap());
		}
		return $posts;
	}

	/**
	 * @inheritDoc
	 */
	function delete($id) {
		$query = 'DELETE FROM post WHERE id = :id';

		$res = $this->sess->db->query($query, ['id' => $id]);

		return $res->rowCount() > 0;
	}

	/**
	 * @inheritDoc
	 * @param PostModel $model
	 */
	function insert($model) {
		$query = "INSERT INTO post (id, content, title, authorId, createdAt, editedAt)
                VALUES (
                    :id, :content, :title, :authorId, :createdAt, :editedAt      
                );
        ";

		$this->sess->db->query($query, [
			'id' => $model->id,
			'title' => $model->title,
			'authorId' => $model->author->id,
			'content' => $model->content,
			'createdAt' => $model->createdAt,
			'editedAt' => $model->editedAt,
		]);
		return true;
	}

	/**
	 * @inheritDoc
	 * @param PostModel $model
	 */
	function edit($model) {
		$query = "UPDATE post SET
                id = :firstName,
                title = :lastName,
                content = :username
            WHERE id = :id
        ";

		$res = $this->sess->db->query($query, [
			'id' => $model->id,
			'title' => $model->title,
			'content' => $model->content,
		]);

		return $res->rowCount() > 0;
	}

	/**
	 * @inheritDoc
	 */
	function exists($id) {
		$query = 'SELECT COUNT(*) FROM post WHERE id = :id';

		$res = $this->sess->db->query($query, [
			'id' => $id,
		]);

		return $res->fetchColumn() > 0;
	}
}

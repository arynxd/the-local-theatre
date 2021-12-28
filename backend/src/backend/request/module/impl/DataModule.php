<?php

namespace TLT\Request\Module\Impl;

use TLT\Repository\Impl\CommentRepository;
use TLT\Repository\Impl\CredentialRepository;
use TLT\Repository\Impl\PostRepository;
use TLT\Request\Module\BaseModule;
use TLT\Repository\Impl\UserRepository;
use TLT\Util\Log\Logger;

class DataModule extends BaseModule {
	/**
	 * @var UserRepository $user
	 */
	public $user;

	/**
	 * @var CredentialRepository $credential
	 */
	public $credential;

	/**
	 * @var PostRepository $post
	 */
	public $post;

	/**
	 * @var CommentRepository $comment
	 */
	public $comment;

	private $transactionActive;

	/**
     * Starts the modification of data within this repository
     */
    final public function start() {
        $this->sess->db->startTransaction();
		$this -> transactionActive = true;
    }

    /**
     * Commits all pending modifications to this repository
     */
    final public function commit() {
        $this->sess->db->commit();
		$this -> transactionActive = false;
    }

	public function ensureCommitted() {
		if ($this -> transactionActive) {
			$this ->sess -> res -> internal("Uncommitted data found, ensure you call commit()");
		}
	}
	public function onEnable() {
		$this -> transactionActive = false;
		Logger::getInstance()->debug("Loading repositories..");
		$this->user = new UserRepository($this->sess);
		$this->credential = new CredentialRepository($this->sess);
		$this->post = new PostRepository($this->sess);
		$this->comment = new CommentRepository($this->sess);
		Logger::getInstance()->debug("Loaded all repositories");
	}
}

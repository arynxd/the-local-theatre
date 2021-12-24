<?php

namespace TLT\Request\Module\Impl;

use TLT\Repository\Impl\PostRepository;
use TLT\Request\Module\BaseModule;
use TLT\Respository\Impl\UserRepository;
use TLT\Respository\Impl\CredentialRepository;

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

	public function onEnable() {
		$this->user = new UserRepository($this->sess);
		$this->credential = new CredentialRepository($this->sess);
		$this->post = new PostRepository($this->sess);
	}
}

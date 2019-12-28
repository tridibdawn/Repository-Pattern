<?php
namespace App\Services;

use App\Interfaces\UserInterface;

class UserService {
	protected $user;

	public function __construct(UserInterface $user) {
		$this->user = $user;
	}

	public function index() {
		$users = $this->user->index();
		return $users;
	}

	public function store($data) {
		$user = $this->user->store($data);
		return $user;
	}

	public function show($id) {
		$user = $this->user->show($id);
		return $user;
	}

	public function update($data, $id) {
		$user = $this->user->update($data, $id);
		return $user;
	}

	public function destroy($id) {
		$user = $this->user->destroy($id);
		return $user;
	}
}
<?php
namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\User;

class UserRepository implements UserInterface {
	protected $user;

	public function __construct(User $user) {
		$this->user = $user;
	}

	public function index() {
		$users = $this->user::all();
		return $users;
	}

	public function store($data) {
		$user = $this->user::create($data);
		return $user;
	}

	public function show($id) {
		$user = $this->user::findOrFail($id);
		return $user;
	}

	public function update($data, $id) {
		$user = $this->user::findOrFail($id);
		$user->fill($data);
		$user->save();
		return $user;
	}

	public function destroy($id) {
		$user = $this->user::findOrFail($id);
		$user->delete();
		return $user;
	}
}
<?php
declare(strict_types=1);

namespace App\Model;


use App\Exception\BadPasswordException;
use App\Exception\UserNotFoundException;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;
use Nette\Security\SimpleIdentity;
use Tracy\Debugger;

class Authenticator implements \Nette\Security\Authenticator
{
	public function __construct(private readonly UsersModel $usersModel, private readonly Passwords $passwords)
	{
	}

	public function authenticate(string $user, string $password): IIdentity
	{
		Debugger::barDump(func_get_args(), 'auth args');
		$data = $this->usersModel->getUserByEmail($user);
		Debugger::barDump($data, 'db result');

		if (!$data) {
			throw new UserNotFoundException('User not found.');
		}
		if (!$this->passwords->verify($password, $data->password)) {
			throw new BadPasswordException('Invalid password.');
		}

		return new SimpleIdentity(
			id: $data->user_id,
			roles: [],
			data: ['name' => $data->name, 'email' => $data->email],
		);
	}
}
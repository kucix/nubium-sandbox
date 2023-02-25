<?php
declare(strict_types=1);

namespace App\Model;

use Nette\Database\Connection;
use Nette\Database\Row;

class UsersModel
{
	public function __construct(private Connection $connection)
	{
	}

	public function getUserByEmail(string $email): ?Row
	{
		return $this->connection->fetch('SELECT `user_id`, `name`, `email`, `password` FROM `users` WHERE `email`=?', trim($email));
	}

	public function addUser(string $email, string $password, string $name, ?string $ip): \Nette\Database\ResultSet
	{
		return $this->connection->query("INSERT INTO `users` (`email`, `name`, `password`, `ipaddr`, `reg_date`) VALUES (?, ?, ?, ?, NOW())",
			$email, $name, $password, $ip ?? 'unknown');
	}

	public function getUserPassword(int $id): string
	{
		return (string)$this->connection->fetchField('SELECT `password` FROM `users` WHERE `user_id`=?', $id);
	}

	public function changePassword(int $id, string $password): void
	{
		$this->connection->query("UPDATE `users` SET `password`=? WHERE `user_id`=?", $password, $id);
	}
}
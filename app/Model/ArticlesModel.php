<?php
declare(strict_types=1);

namespace App\Model;

use Nette\Database\Connection;
use Nette\Database\Row;
use Nette\Utils\Paginator;

class ArticlesModel
{
	public function __construct(private Connection $connection)
	{
	}

	/**
	 * @return Row[]
	 */
	public function getList(Paginator $paginator, string $sorting, string $order, int $user): array
	{
		$orderSort = match ($sorting) {
			'time' => 'A.published',
			'rating' => 'A.rating',
			default => 'A.title',
		};

		return $this->connection->fetchAll(
			'SELECT A.`article_id`, A.`title`, A.`perex`, A.`rating`, A.`published`, A.`user_id`, U.`name`, IF(AR.`rating` IS NULL, 1, 0) can_rate
			FROM `articles` A
			JOIN `users` U ON U.`user_id`=A.`user_id`
			LEFT JOIN `article_rating` AR ON AR.`article_id`=A.`article_id` AND AR.`user_id`=?
			WHERE A.`visible`=1
			  AND A.`published` <= NOW()
			  AND (A.`private`=0 OR ? > 0)
			ORDER BY ?order
			LIMIT ?
			OFFSET ?',
			$user,
			$user,
			[$orderSort => $order === 'asc'],
			$paginator->getLength(),
			$paginator->getOffset(),
		);
	}

	public function getCount(int $user): int
	{
		$result = $this->connection->fetch(
			'SELECT COUNT(A.`article_id`) AS count
			FROM `articles` A
			JOIN `users` U ON U.`user_id`=A.`user_id`
			WHERE A.`visible`=1
			  AND A.`published` <= NOW()
 			  AND (A.`private`=0 OR ? > 0)', $user
		);

		return (int)$result?->count;
	}

	public function getDetail(int $id, int $user): ?Row
	{
		return $this->connection->fetch(
			'SELECT A.`article_id`, A.`title`, A.`perex`, A.`text`, A.`rating`, A.`published`, A.`user_id`, U.`name`, IF(AR.`rating` IS NULL, 1, 0) can_rate
			FROM `articles` A
			JOIN `users` U ON U.`user_id`=A.`user_id`
			LEFT JOIN `article_rating` AR ON AR.`article_id`=A.`article_id` AND AR.`user_id`=?
			WHERE A.`visible`=1
			  AND A.`published` <= NOW()
			  AND A.`article_id`=?
			  AND (A.`private`=0 OR ? > 0)',
			$user,
			$id,
			$user
		);
	}

	public function addRating(int $article_id, int $user_id, int $rating, ?string $ip): void
	{
		$this->connection->query(
			"INSERT IGNORE INTO `article_rating` (`article_id`, `user_id`, `rating`, `date`, `ipaddr`) VALUES (?, ?, ?, NOW(), ?)",
			$article_id, $user_id, $rating, $ip ?? 'unknown'
		);
	}
}
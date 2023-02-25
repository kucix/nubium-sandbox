<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\ArticlesModel;
use Nette;
use Nette\Application\Attributes\Persistent;

final class HomepagePresenter extends BasePresenter
{

	#[Persistent]
	public string $sorting = 'time';

	#[Persistent]
	public string $order = 'asc';

	/**
	 * @var ArticlesModel
	 * @inject
	 */
	public ArticlesModel $articlesModel;

	public function renderDefault(int $id = 1): void
	{
		$paginator = new Nette\Utils\Paginator;
		$paginator->setPage($id);
		$paginator->setItemsPerPage(5);
		$paginator->setItemCount($this->articlesModel->getCount((int)$this->getUser()->getId()));

		$this->template->paginator = $paginator;
		$this->template->articles = $this->articlesModel->getList($paginator, $this->sorting, $this->order, (int)$this->getUser()->getId());
		$this->template->sorting = $this->sorting;
		$this->template->order = $this->order;
	}

}

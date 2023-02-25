<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Controls\Article\ArticleControl;
use App\Model\ArticlesModel;

final class ArticlePresenter extends BasePresenter
{

	/**
	 * @var ArticlesModel
	 * @inject
	 */
	public ArticlesModel $articlesModel;

	public function actionDefault(int $id): void
	{
		/** @var ArticleControl $this ['article'] */
		$this['article']->setArticleId($id);
	}

	public function renderDefault(int $id): void
	{
		$this->template->article = $this->articlesModel->getDetail($id, (int)$this->getUser()->getId());
	}
}

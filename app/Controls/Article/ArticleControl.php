<?php
declare(strict_types=1);

namespace App\Controls\Article;

use App\Model\ArticlesModel;
use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\DefaultTemplate;
use Nette\Database\Row;
use Nette\Http\Request;

/**
 * @property-read DefaultTemplate&\stdClass $template
 */
class ArticleControl extends Control
{

	public function __construct(private ArticlesModel $articlesModel, private Request $request)
	{
	}

	public function render(Row $article): void
	{
		$this->template->article = $article;
		$this->template->render(__DIR__ . '/template.latte');
	}

	public function renderDetail(Row $article): void
	{
		$this->template->article = $article;
		$this->template->render(__DIR__ . '/detail.latte');
	}

	public function handleRate(int $articleId, int $rate): void
	{
		$this->articlesModel->addRating($articleId, (int)$this->getPresenter()->getUser()->getId(), $rate, $this->request->getRemoteAddress());
		$this->getPresenter()->redrawControl('articledetail');
	}
}
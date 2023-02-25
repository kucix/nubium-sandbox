<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Factory\ArticleControlFactory;
use App\Factory\PasswordControlFactory;
use App\Factory\SignUpControlFactory;
use App\Factory\UserControlFactory;
use Nette;
use Nette\Bridges\ApplicationLatte\DefaultTemplate;

/**
 * @property-read DefaultTemplate&\stdClass $template
 */
class BasePresenter extends Nette\Application\UI\Presenter
{
	/**
	 * @var UserControlFactory
	 * @inject
	 */
	public UserControlFactory $userControlFactory;

	/**
	 * @var ArticleControlFactory
	 * @inject
	 */
	public ArticleControlFactory $articleControlFactory;

	/**
	 * @var SignUpControlFactory
	 * @inject
	 */
	public SignUpControlFactory $signUpControlFactory;

	/**
	 * @var PasswordControlFactory
	 * @inject
	 */
	public PasswordControlFactory $passwordControlFactory;

	public function createComponentUser(): ?Nette\ComponentModel\IComponent
	{
		return $this->userControlFactory->create();
	}

	public function createComponentArticle(): ?Nette\ComponentModel\IComponent
	{
		return $this->articleControlFactory->create();
	}

	public function createComponentSignup(): ?Nette\ComponentModel\IComponent
	{
		return $this->signUpControlFactory->create();
	}

	public function createComponentPassword(): ?Nette\ComponentModel\IComponent
	{
		return $this->passwordControlFactory->create();
	}

}

<?php
declare(strict_types=1);

namespace App\Presenters;

final class UserPresenter extends BasePresenter
{
	public function actionSignUp(): void
	{
		if ($this->getUser()->isLoggedIn()) {
			$this->redirect('Homepage:default');
		}
	}

	public function actionLogOut(): void
	{
		if ($this->getUser()->isLoggedIn()) {
			$this->getUser()->logout(true);
		}
		$this->redirect('Homepage:default');
	}

	public function renderChangePassword(): void
	{

	}
}

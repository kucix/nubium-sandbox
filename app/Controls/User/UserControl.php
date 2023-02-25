<?php
declare(strict_types=1);

namespace App\Controls\User;

use App\Exception\BadPasswordException;
use App\Exception\UserNotFoundException;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Bridges\ApplicationLatte\DefaultTemplate;
use Nette\Forms\Controls\TextInput;

/**
 * @property-read DefaultTemplate&\stdClass $template
 */
class UserControl extends Control
{

	public function render(): void
	{
		$this->template->render(__DIR__ . '/template.latte');
	}

	public function createComponentLogin(): Form
	{
		$form = new Form();

		$form->addText('email', 'E-mail')
			->setDefaultValue('admin@nubium-sandbox.test')
			->setRequired();

		$form->addPassword('password', 'Heslo')
			->setRequired();

		$form->addSubmit('submit', 'Přihlásit');

		$form->onSuccess[] = [$this, 'submitted'];

		return $form;
	}

	public function submitted(Form $form): void
	{
		$data = $form->getValues();
		try {
			$this->getPresenter()->getUser()->login((string)$data['email'], (string)$data['password']);
			$this->getPresenter()->redirect('this');
		} catch (UserNotFoundException $e) {
			/** @var array<TextInput> $form */
			$form['email']->addError('Uživatel nenalezen');
		} catch (BadPasswordException $e) {
			/** @var array<TextInput> $form */
			$form['password']->addError('Špatné heslo');
		}
	}
}
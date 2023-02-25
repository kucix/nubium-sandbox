<?php
declare(strict_types=1);

namespace App\Controls\Password;

use App\Model\UsersModel;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Bridges\ApplicationLatte\DefaultTemplate;
use Nette\Forms\Controls\TextInput;
use Nette\Forms\Rendering\DefaultFormRenderer;
use Nette\Security\Passwords;
use Nette\Security\User;

/**
 * @property-read DefaultTemplate&\stdClass $template
 */
class PasswordControl extends Control
{

	public function __construct(private UsersModel $usersModel, private Passwords $passwords, private User $user)
	{
	}

	public function render(): void
	{
		$this->template->render(__DIR__ . '/template.latte');
	}

	public function createComponentForm(): Form
	{
		$form = new Form();

		$form->addPassword('oldPassword', 'Původní heslo')
			->setRequired()
			->getControlPrototype()->class('form-control');

		$form->addPassword('password', 'nové heslo')
			->addRule(Form::MIN_LENGTH, 'Zadejte alespoň %s znaků dlouhé heslo', 6)
			->setRequired()
			->getControlPrototype()->class('form-control');

		$form->addPassword('password2', 'Nové heslo pro kontrolu')
			->setRequired()
			->addRule(Form::EQUAL, 'Hesla nejsou shodná', $form['password'])
			->getControlPrototype()->class('form-control');

		$form->addSubmit('submit', 'Změnit heslo');

		$form->onValidate[] = [$this, 'validate'];
		$form->onSuccess[] = [$this, 'submitted'];

		/** @var DefaultFormRenderer $renderer */
		$renderer = $form->getRenderer();
		$renderer->wrappers['controls']['container'] = null;
		$renderer->wrappers['pair']['container'] = 'div class="mb-3"';
		$renderer->wrappers['pair']['.error'] = 'is-invalid';
		$renderer->wrappers['control']['container'] = null;
		$renderer->wrappers['label']['container'] = null;
		$renderer->wrappers['control']['description'] = 'span class=form-text';
		$renderer->wrappers['control']['errorcontainer'] = 'span class=invalid-feedback';
		$renderer->wrappers['control']['.error'] = 'is-invalid';

		return $form;
	}

	public function validate(Form $form): void
	{
		$data = $form->getValues();
		$oldPwHash = $this->usersModel->getUserPassword((int)$this->user->getId());

		if (!$this->passwords->verify($data->oldPassword, $oldPwHash)) {
			/** @var array<TextInput> $form */
			$form['oldPassword']->addError('Špatné heslo');
		}

	}

	public function submitted(Form $form): void
	{
		$data = $form->getValues();
		$password = $this->passwords->hash($data->password);
		$this->usersModel->changePassword((int)$this->user->getId(), $password);
		$this->getPresenter()->flashMessage('Heslo změněno', 'success');
		$this->getPresenter()->redirect('Homepage:default');
	}
}
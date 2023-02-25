<?php
declare(strict_types=1);

namespace App\Controls\SignUp;

use App\Model\UsersModel;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Bridges\ApplicationLatte\DefaultTemplate;
use Nette\Forms\Controls\TextInput;
use Nette\Forms\Rendering\DefaultFormRenderer;
use Nette\Http\Request;
use Nette\Security\Passwords;

/**
 * @property-read DefaultTemplate&\stdClass $template
 */
class SignUpControl extends Control
{

	public function __construct(private UsersModel $usersModel, private Passwords $passwords, private Request $request)
	{
	}

	public function render(): void
	{
		$this->template->render(__DIR__ . '/template.latte');
	}

	public function createComponentForm(): Form
	{
		$form = new Form();

		$form->addText('email', 'E-mail')
			->addRule(Form::EMAIL, 'adejte platný e-mail')
			->setRequired()
			->getControlPrototype()->class('form-control');

		$form->addText('name', 'Jméno')
			->addRule(Form::MIN_LENGTH, 'Zadejte alespoň %s znaků', 6)
			->setRequired()
			->getControlPrototype()->class('form-control');

		$form->addPassword('password', 'Heslo')
			->addRule(Form::MIN_LENGTH, 'Zadejte alespoň %s znaků dlouhé heslo', 6)
			->setRequired()
			->getControlPrototype()->class('form-control');

		$form->addPassword('password2', 'Heslo pro kontrolu')
			->setRequired()
			->addRule(Form::EQUAL, 'Hesla nejsou shodná', $form['password'])
			->getControlPrototype()->class('form-control');

		$form->addSubmit('submit', 'Registrovat');

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
		$check = $this->usersModel->getUserByEmail($data->email);
		if ($check) {
			/** @var array<TextInput> $form */
			$form['email']->addError('E-mail je již registrován');
		}
	}

	public function submitted(Form $form): void
	{
		$data = $form->getValues();
		$password = $this->passwords->hash($data->password);
		$this->usersModel->addUser($data->email, $password, $data->name, $this->request->getRemoteAddress());
		$this->getPresenter()->flashMessage('Registrace dokončena', 'success');
		$this->getPresenter()->redirect('Homepage:default');
	}
}
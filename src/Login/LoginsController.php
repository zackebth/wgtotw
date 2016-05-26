<?php

namespace Anax\Login;

/**
 * A controller for users and admin related events.
 *
 */
class LoginsController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;


	/**
	 * Initialize the controller.
	 *
	 * @return void
	 */
	public function initialize()
	{
		$this->login = new \Anax\Login\Login();
		$this->login->setDI($this->di);
	}


	/**
	 * Login function.
	 *
	 * @return void
	 */
	public function loginAction($acronym = null)
	{

	$this->session = new \Anax\Session\CSession();

	if($this->session->has("user_loggedin"))	{

		$url = $this->di->url->create('users/id/' . $_SESSION["user_loggedin"]['id']);
		$this->di->response->redirect($url);


        } else {

		$form = new \Anax\HTMLForm\FormLogin();
		$form->setDI($this->di);
		$status = $form->check();

		$this->di->views->add('users/form', [
			'title' => "Logga in",
			'content' => $form->getHTML(),
            'link' => "<a href='users/add'>Registrera dig här</a>",

			]);
		}
	}

    public function easyLoginAction($title = 'Logga in')
	{

	$this->session = new \Anax\Session\CSession();

	if($this->session->has("user_loggedin"))	{
		$this->di->response->redirect();
        } else {

		$form = new \Anax\HTMLForm\FormLogin("#answer");
		$form->setDI($this->di);
		$status = $form->check();

		$this->di->views->add('users/form', [
			'title' => $title,
			'content' => $form->getHTML(),

			]);
		}
	}


	/**
	 * Logout function.
	 *
	 * @return void
	 */

	public function logoutAction() {
		$this->session = new \Anax\Session\CSession();
		unset($_SESSION["user_loggedin"]);

		$url = $this->di->url->create('login');
		$this->di->response->redirect($url);
	}
}

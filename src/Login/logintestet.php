<?php

namespace Anax\Login;

/**
 * A controller for users and admin related events.
 *
 */
class LoginController
{
    use \Anax\DI\TInjectable;

    private session;
	private users;

	/**
     * Construct session.
     *
     * @param array $options to configure options.
     */
    public function __construct($options = [])
    {
        $this->session = new \Anax\Session\CSession();
		$this->session->setDI($this->di);

		$this->users = new \Anax\Users\User();
		$this->users->setDI($this->di);
    }



	public function loginAction($username = null, $password = null, $hash = md5) {

		$hashpw = $hash($password);

		if(!isset($username) || !isset($password)) {
			die("Missing username or password");
		}

		$this->johndoe = $this->users->find($id);

		if($this->johndoe->getProperties()['password'] == $hashpw) {
			// $this->session->start();
			$this->session->name("user_loggin");
			$this->session->set("user_loggin", $this->johndoe);

			return true;
		}

		return false;
	}

}

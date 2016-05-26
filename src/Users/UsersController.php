<?php

namespace Anax\Users;

/**
 * A controller for users and admin related events.
 *
 */
class UsersController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    //private users;

		/**
	 * Initialize the controller.
	 *
	 * @return void
	 */
	public function initialize()
	{
		$this->users = new \Anax\Users\User();
		$this->users->setDI($this->di);
	}

     /**
     * List all users.
     *
     * @return void
     */
    public function listAction()
    {
        $all = $this->users->findAll();

        $this->theme->setTitle("List all users");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "Alla Användare",
        ]);
    }

	/**
     * List all users in trashcan.
     *
     * @return void
     */
    public function trashcanAction()
    {

        if(!$this->session->has('user_loggedin') || $this->session->get('user_loggedin')['rank'] < 99) {
            die("You dont have access to this page.");
        }

       $all = $this->users->query()
			->Where('deleted IS NOT NULL')
			->execute();

		$this->theme->setTitle("Trashcan");
		$this->views->add('users/list-trash', [
			'users' => $all,
			'title' => "Användare i papperskorgen.",
		]);
    }

    /**
     * List user with id.
     *
     * @param int $id of user to display
     *
     * @return void
     */
    public function idAction($id = null)
    {
        $user = $this->users->find($id);

        $this->theme->setTitle("View user with id");
		$title = "test";
        $this->views->add('users/view', [
			'title' => $title,
            'user' => $user,
        ]);
    }

    public function getMostActiveMembersAction() {
        $total = [];
        $all = [];

        $cu = $this->users->select("user_id, count(user_id) as total")
        ->from('comment group by user_id')
        ->orderby("total DESC")
        ->execute();


        $qu = $this->users->select("user_id,count(user_id) as total")
        ->from('question group by user_id')
        ->orderby("total DESC")
        ->execute();

        foreach ($cu as $c) {
            $c = get_object_vars($c);
            if(isset($total[$c['user_id']])) {
                $total[$c['user_id']] += (int)$c['total'];
            }else {
                $total[$c['user_id']] = (int)$c['total'];
            }
        }

        foreach ($qu as $q) {
            $q = get_object_vars($q);
            if(isset($total[$q['user_id']])) {
                $total[$q['user_id']] += (int)$q['total'];
            }else {
                $total[$q['user_id']] = (int)$q['total'];
            }
        }

        arsort($total);
        $total = count($total) < 5 ? $total : array_splice($total, 5);



        foreach ($total as $key => $value) {
            $key = (int)$key;
            $user = $this->users->query()
     			->Where('id = ?')
     			->execute([$key]);
            $user = get_object_vars($user[0]);
            $all[] = $user;
        }

        // var_dump($total);
        // var_dump($all);

        $this->di->views->add('users/widget', [
            'title' => "Mest aktiva användarna",
            'users' => $all,
            ]);
    }

	/**
	 * Add new user.
	 *
	 * @param string $acronym of user to add.
	 *
	 * @return void
	 */
	public function addAction($acronym = null)
	{


	$form = new \Anax\HTMLForm\FormAddUser();
    $form->setDI($this->di);
    $status = $form->check();

    $this->di->theme->setTitle("Registrera");
    $this->di->views->add('users/form', [
        'title' => "Skapa ditt konto här",
        'content' => $form->getHTML(),

        ]);
      	if($status === true) {
    		echo 'true';
    	    $url = $this->url->create('list');
    		$form->response->redirect($url);
    	}
	}


		/**
	 * Update an user.
	 *
	 * @param id of the add.
	 *
	 * @return void
	 */
	public function updateAction($id = null)
	{

		if (!isset($id)) {
			die("Missing id");
		}

        if(!$this->session->has('user_loggedin') || $this->session->get('user_loggedin')['id'] != $id)
        {
            die("You can't edit this profile");
        }

		$this->johndoe = $this->users->find($id);

		$acronym = $this->johndoe->getProperties()['acronym'];
		$email = $this->johndoe->getProperties()['email'];
		$name = $this->johndoe->getProperties()['name'];
		$active = ($this->johndoe->getProperties()['active'] == null) ? false : true;


		$form = new \Anax\HTMLForm\FormUpdateUser($id, $acronym, $name, $email, $active);
		$form->setDI($this->di);
		$status = $form->check();

		$this->di->views->add('users/form', [
			'title' => "Uppdatera användare",
			'content' => $form->getHTML(),
			]);

		if($status === true) {
			echo 'true';
			$url = $this->url->create('list');
			$form->response->redirect($url);
		}
	}


	/**
	 * Delete user.
	 *
	 * @param integer $id of user to delete.
	 *
	 * @return void
	 */
	public function deleteAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}

        if(!$this->session->has('user_loggedin') || $this->session->get('user_loggedin')['rank'] < 99) {
            die("You dont have access to this page.");
        }

		$res = $this->users->delete($id);

		$url = $this->url->create('users/list');
		$this->response->redirect($url);
	}

	/**
	 * Delete (soft) user.
	 *
	 * @param integer $id of user to delete.
	 *
	 * @return void
	 */
	public function softDeleteAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}

        if(!$this->session->has('user_loggedin') || $this->session->get('user_loggedin')['rank'] < 99) {
            die("You dont have access to this page.");
        }

		$now = gmdate('Y-m-d H:i:s');

		$user = $this->users->find($id);

		$user->deleted = $now;
		$user->save();

		$url = $this->url->create('users/list');
		$this->response->redirect($url);
	}

	/**
	 * List all active and not deleted users.
	 *
	 * @return void
	 */
	public function activeAction()
	{
		$all = $this->users->query()
			->where('active IS NOT NULL')
			->andWhere('deleted is NULL')
			->execute();


		$this->views->add('users/list-all', [
			'users' => $all,
			'title' => "Users that are active",
		]);
	}

	/**
	 * Delete (soft) user.
	 *
	 * @param integer $id of user to delete.
	 *
	 * @return void
	 */
	public function activateAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}
        if(!$this->session->has('user_loggedin') || $this->session->get('user_loggedin')['rank'] < 99) {
            die("You dont have access to this page.");
        }

		$now = gmdate('Y-m-d H:i:s');

		$user = $this->users->find($id);

		$user->active = $now;
		$user->deleted = null;
		$user->save();

		$url = $this->url->create('users/list/');
		$this->response->redirect($url);
	}

	/**
	 * Delete (soft) user.
	 *
	 * @param integer $id of user to delete.
	 *
	 * @return void
	 */
	public function deactivateAction($id = null)
	{
		if (!isset($id)) {
			die("Missing id");
		}

		$user = $this->users->find($id);

		$user->active = null;
		$user->save();

		$url = $this->url->create('users/list/');
		$this->response->redirect($url);
	}

    public function createDatabaseAction()
    {
        $this->db->setVerbose();

        $this->db->dropTableIfExists('user')->execute();

        $this->db->createTable(
            'user',
            [
                'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
                'acronym' => ['varchar(20)', 'unique', 'not null'],
                'email' => ['varchar(80)'],
                'name' => ['varchar(80)'],
                'password' => ['varchar(255)'],
                'rank' => ['integer'],
                'created' => ['datetime'],
                'updated' => ['datetime'],
                'deleted' => ['datetime'],
                'active' => ['datetime'],
            ]
        )->execute();


        $this->db->insert(
        'user',
        ['acronym', 'email', 'name', 'password', 'rank', 'created', 'active']
        );

        $now = gmdate('Y-m-d H:i:s');

        $this->db->execute([
            'admin',
            'admin@dbwebb.se',
            'Administrator',
            password_hash('admin', PASSWORD_DEFAULT),
            '110',
            $now,
            $now
        ]);

        $this->db->execute([
            'zackepacke',
            'zacke@packe.se',
            'Zacke Madsen',
            password_hash('zacke', PASSWORD_DEFAULT),
            '0',
            $now,
            $now
        ]);
    }

}

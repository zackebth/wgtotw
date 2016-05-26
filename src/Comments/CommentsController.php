<?php

namespace Anax\Comments;

/**
 * A controller for users and admin related events.
 *
 */
class CommentsController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

     private $pagekey;
     private $citeid = null;

		/**
	 * Initialize the controller.
	 *
	 * @return void
	 */
	public function initialize()
	{
		$this->comments = new \Anax\Comments\Comment();
		$this->comments->setDI($this->di);
	}



	public function getCommentSystemAction($pageid, $orderby = null) {

		$this->user = new \Anax\Users\User();
		$this->user->setDI($this->di);
		$this->pagekey = $pageid;

        if(is_numeric($orderby)) {
            $this->citeid = $orderby;
            $orderby = null;
        }

		// Kommentarer
        if(isset($orderby)) {
        $all = $this->comments->query()
            ->where('questionid = ?')
            ->andWhere('citeid is null')
            ->orderby($orderby . ' DESC')
            ->execute([$pageid]);
        } else {
    		$all = $this->comments->query()
            ->where('questionid = ?')
            ->andWhere('citeid is null')
            ->orderby('created DESC')
            ->execute([$pageid]);
        }
        // Kommentarer
		$cite = $this->comments->query()
        ->where('questionid = ?')
        ->andWhere('citeid is not null')
        ->orderby('created DESC')
        ->execute([$pageid]);


        $this->views->add('comment/list-all', [
            'comments' => $all,
            'cite' => $cite,
            'citeid' => $this->citeid,
            'pageid' => $pageid,
			'user' => $this->user,
            'orderby' => $orderby,
            'title' => "Kommentarer",
        ]);

        if($this->session->has("user_loggedin"))
        {   // Formulär
		          $this->addAction();
        } else {
            $this->dispatcher->forward([
                'controller' => 'logins',
                'action'     => 'easy-Login',
                'params'    => ['title' => 'Logga in för att kommentera'],
            ]);
        }
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
    	$form = new \Anax\HTMLForm\FormAddComment($this->pagekey);
        $form->setDI($this->di);
        $status = $form->check();

        $this->di->views->add('users/form', [
            'title' => "Lägg till kommentar",
            'content' => $form->getHTML(),

            ]);
      	if($status === true) {
    		echo 'true';
    	    $url = $this->url->create('list');
    		$form->response->redirect('hej');
    	}
	}

    /**
	 * Add new user.
	 *
	 * @param string $acronym of user to add.
	 *
	 * @return void
	 */
	public function answerAction($qid, $cid)
	{
        $this->user = new \Anax\Users\User();
		$this->user->setDI($this->di);

        // Kommentarer
        $q = $this->comments->find($qid);
		$all = $this->comments->query()
        ->where('id = ?')
        ->execute([$cid]);

     $this->theme->setTitle('Svara på en kommentar');

        $this->views->add('comment/list', [
            'comments' => $all,
			'user' => $this->user,
            'title' => 'Du vill svara på:',
        ]);



        if($this->session->has("user_loggedin"))
        {
            // SvarsFormulär
            $form = new \Anax\HTMLForm\FormAddComment($qid, $cid);
            $form->setDI($this->di);
            $status = $form->check();
            $this->di->views->add('users/form', [
            'title' => "Ditt svar",
            'content' => $form->getHTML(),
            ]);

             if($status === true) {
           		echo 'true';
           	    $url = $this->url->create('list');
           		$form->response->redirect($url);
           	}
        } else {
            $this->dispatcher->forward([
                'controller' => 'logins',
                'action'     => 'easy-Login',
                'params'    => ['title' => 'Logga in för att svara'],
            ]);
        }

	}

    public function userAction($id) {

		$this->user = new \Anax\Users\User();
		$this->user->setDI($this->di);

		// Kommentarer
		$all = $this->comments->query()
        ->where('user_id = ?')
        ->execute([$id]);

        $this->theme->setTitle('Kommentarer');

        $this->views->add('comment/simpellist', [
            'comments' => $all,
			'user' => $this->user,
            'title' => 'Kommentarer',
        ]);

    }

    public function updateAction($id = null)
    {

        $this->theme->setTitle('Uppdatera en kommentar');

        if (!isset($id)) {
            die("Missing id");
        }

        $tag = $this->comments->find($id);
        if(!$this->session->has('user_loggedin') || ($this->session->get('user_loggedin')['id'] !=  $tag->getProperties()['user_id']))
        {
            die("You can't edit this question");
        }

        $desc = $tag->getProperties()['comment'];
        $qid = $tag->getProperties()['questionid'];

        $form = new \Anax\HTMLForm\FormUpdateComment($id, $desc, $qid);
        $form->setDI($this->di);
        $status = $form->check();

        $this->di->views->add('users/form', [
            'title' => "Uppdatera din kommentar ",
            'content' => $form->getHTML(),
            ]);

        if($status === true) {
            echo 'true';
            $url = $this->url->create('list');
            $form->response->redirect($url);
        }
    }

    public function plusoneAction($id, $qid, $cid) {
        $question = $this->comments->find($cid);
        $rank = $question->getProperties()['rate'] + 1;
        //var_dump($rank);
        $save = $this->comments->save(array('id' => $cid, 'rate' => $rank));
        if($save) {
            $this->user = new \Anax\Users\User();
    		$this->user->setDI($this->di);
            $this->user->plusminus($id);
        }
        $url =  $this->url->create('questions/question/' . $qid . '#' . $cid);
        $this->response->redirect($url);
    }

    public function minusoneAction($id, $qid, $cid) {
        $question = $this->comments->find($cid);
        $rank = $question->getProperties()['rate'] - 1;
        //var_dump($rank);
        $save = $this->comments->save(array('id' => $cid, 'rate' => $rank));
        if($save) {
            $this->user = new \Anax\Users\User();
    		$this->user->setDI($this->di);
            $this->user->plusminus($id, true);
        }
        $url =  $this->url->create('questions/question/' . $qid . '#' . $cid);
        $this->response->redirect($url);
    }

    public function resetDatabaseAction() {
        $this->db->setVerbose();

        $this->db->dropTableIfExists('comment')->execute();

        $this->db->createTable(
            'comment',
            [
                'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
                'user_id' => ['integer', 'not null'],
                'comment' => ['text'],
                'questionid' => ['integer', 'not null'],
                'citeid' => ['integer'],
                'rate' => ['integer'],
                'created' => ['datetime'],
                'updated' => ['datetime'],
                'deleted' => ['datetime'],
            ]
        )->execute();
    }

    public function createDatabaseAction()
    {
        $this->db->setVerbose();

        $this->db->dropTableIfExists('comment')->execute();

        $this->db->createTable(
            'comment',
            [
                'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
                'user_id' => ['integer', 'not null'],
                'comment' => ['text'],
                'questionid' => ['integer', 'not null'],
                'citeid' => ['integer'],
                'rate' => ['integer'],
                'created' => ['datetime'],
                'updated' => ['datetime'],
                'deleted' => ['datetime'],
            ]
        )->execute();

         $this->db->insert(
        'comment',
        ['user_id', 'comment', 'questionid', 'citeid', 'rate','created']
        );

        $now = gmdate('Y-m-d H:i:s');


        $this->db->execute([
           1,
           'En test Kommentar',
           1,
           null,
           4,
           $now
       ]);

    }

}

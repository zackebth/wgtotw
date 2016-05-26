<?php

namespace Anax\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class FormUpdateUser extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;



	private $id;
	private $acronym;
	private $name;
	private $email;
	private $active = false;



    /**
     * Constructor
     *
     */
    public function __construct($id = null, $acronym = null, $name = null, $email = null, $active = true)
    {
		$this->id = $id;
		$this->acronym = $acronym;
		$this->name = $name;
		$this->email = $email;
		$this->active = $active;


        parent::__construct(
		[
			'class' => 'user-form',
		],
		[
           'acronym' => [
                'type'        => 'text',
                'label'       => 'Användarnamn:',
                'required'    => true,
                'validation'  => ['not_empty'],
			    'value'		  => $acronym
            ],
			'name' => [
                'type'        => 'text',
                'label'       => 'Namn:',
                'required'    => true,
                'validation'  => ['not_empty'],
				'value'		  => $name,
            ],
            'email' => [
                'type'        => 'email',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
				'value'		  => $email,
            ],
			'active' => [
                'type'        => 'checkbox',
                'required'    => false,
				'checked'	  => $active,
            ],
            'uppdatera' => [
                'type'      => 'submit',
                'callback'  => [$this, 'callbackSubmit'],
            ],
        ]);
    }



    /**
     * Customise the check() method.
     *
     * @param callable $callIfSuccess handler to call if function returns true.
     * @param callable $callIfFail    handler to call if function returns true.
     */
    public function check($callIfSuccess = null, $callIfFail = null)
    {
        return parent::check([$this, 'callbackSuccess'], [$this, 'callbackFail']);
    }



    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmit()
    {

		$date = gmdate('Y-m-d H:i:s');
        $active = !empty($_POST['active'])? $date : null;
		$this->user = new \Anax\Users\User();
        $this->user->setDI($this->di);
        $save = $this->user->save(
            array('acronym' => $this->Value('acronym'), 'email' => $this->Value('email'), 'name' => $this->Value('name'), 'password' => password_hash($this->Value('password'), PASSWORD_DEFAULT),
            'created' => $date, 'updated' => $date, 'deleted' => null, 'active' => $active, 'id' => $this->id));

       // $this->saveInSession = true;

        if($save)	{
        	return true;
        }
        else {
			return false;
		}
     }



    /**
     * Callback for submit-button.
     *
     */
    public function callbackSubmitFail()
    {
        $this->AddOutput("<p><i>DoSubmitFail(): Form was submitted but I failed to process/save/validate it</i></p>");
        return false;
    }



    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
	{
       $this->redirectTo('users/id/' . $this->user->getProperties()['id']);
    }



    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
        $this->AddOutput("<p><i>Form was submitted and the Check() method returned false.</i></p>");
        $this->redirectTo();
    }
}

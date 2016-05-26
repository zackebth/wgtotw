<?php
namespace Anax\HTMLForm;
/**
 * Anax base class for wrapping sessions.
 *
 */
class FormLogin extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;
	private $redi;
    /**
     * Constructor
     *
     */
    public function __construct($r = null)
    {
		$this->redi = $r;
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
            ],
			'password' => [
                'type'        => 'password',
                'label'       => 'Lösenord:',
                'required'    => true,
            ],
            'logga-in' => [
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
		$this->session = new \Anax\Session\CSession();
		$this->user = new \Anax\Users\User();
        $this->user->setDI($this->di);
		$this->johndoe = $this->user->findSpecific("acronym",$this->Value('acronym'));
		if($this->johndoe == null) {
			return false;
		}
		if(password_verify($this->Value('password'),$this->johndoe->getProperties()['password'])) {
			$this->session->name("user_loggedin");
			$this->session->set("user_loggedin", $this->user->getProperties());
			return true;
		}
        if($this->session->has("user_loggedin"))	{
            echo 'callback';
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
       $url = is_null($this->redi) ? 'users/id/' . $this->user->getProperties()['id'] : null;
       $this->redirectTo('profil');
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

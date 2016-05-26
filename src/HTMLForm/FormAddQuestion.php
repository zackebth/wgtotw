<?php
namespace Anax\HTMLForm;
/**
 * Anax base class for wrapping sessions.
 *
 */
class FormAddQuestion extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;
        private $ci;
        private $userid;
        private $userinfo;
        private $rank;
        private $useri;
        private $lid;
    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct([
			'class' => 'user-form',
		],
		[
			'rubrik' => [
                'type'        => 'text',
			    'label'       => 'Rubrik',
                'required'    => true,
            ],
            'tags' => [
                'type'        => 'text',
			    'label'		  => 'Tagar (sepererade med kommatecken)',
            ],
            'comment' => [
                'type'        => 'textarea',
				'label'       => 'Fråga',
                'required'    => true,
                'validation'  => ['not_empty'],
				'value' => '',
            ],
            'ställ-fråga' => [
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
		$this->user = new \Anax\Users\User();
		$this->user->setDI($this->di);
		$date = gmdate('Y-m-d H:i:s');
        $active = !empty($_POST['active'])? $date : null;
        $citeid = !empty($_POST['citeid'])? $this->Value('citeid') : null;
        $user_session = $this->di->session->get("user_loggedin");
        $this->userid = $user_session['id'];
        $this->userinfo = $this->user->find($this->userid);
        $tag = $this->value('tags');
        $search = array('å', 'ä', 'ö');
        $replace = array('a', 'a', 'o');
        $tag = str_replace($search, $replace, $tag);
        if(!empty($_POST['tags']))
        {
            $this->tags = new \Anax\Tags\Tag();
            $this->tags->setDI($this->di);
            $this->tags->add($tag);
        }
		$this->comment = new \Anax\Questions\Question();
        $this->comment->setDI($this->di);
        $save = $this->comment->save(
			array(
				'user_id' => $this->userid,
                'heading' => $this->value('rubrik'),
                'tags'  => $tag,
				'description' => $this->di->textFilter->doFilter($this->Value('comment'), 'shortcode, markdown'),
                'rate'  => '0',
				'created' => $date,
				'updated' => $date,
				'deleted' => null));
       if($save)	{
            $this->lid = $this->comment->lastInsertId();
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
        $this->saveInSession = true;
        $this->AddOutput("<p><i>DoSubmitFail(): Form was submitted but I failed to process/save/validate it</i></p>");
        return false;
    }
    /**
     * Callback What to do if the form was submitted?
     *
     */
    public function callbackSuccess()
	{
        $rank = $this->userinfo->getProperties()['rank'] + 1;
        $save = $this->user->save(array('id' => $this->userid, 'rank' => $rank));
        $this->redirectTo('questions/question/' . $this->lid);
    }
    /**
     * Callback What to do when form could not be processed?
     *
     */
    public function callbackFail()
    {
       $this->AddOutput("<p><i>Form was submitted and the Check() method returned false. Probably there is no user with that ID.</i></p>");
       $this->redirectTo();
	}
}

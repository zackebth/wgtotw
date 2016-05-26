<?php

namespace Anax\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class FormUpdateComment extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;



	private $id;
	private $com;
    private $qid;


    /**
     * Constructor
     *
     */
    public function __construct($id = null, $com = null, $qid = null)
    {
		$this->id = $id;
		$this->com = $com;
        $this->qid = $qid;



        parent::__construct(
		[
			'class' => 'user-form',
		],
		[
            'com' => [
                'type'        => 'textarea',
                'label'       => 'Kommentar',
                'required'    => true,
                'validation'  => ['not_empty'],
				'value'		  => $com,
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


		$this->quest = new \Anax\Comments\Comment();
        $this->quest->setDI($this->di);
        $save = $this->quest->save(
            array(
            'comment' => $this->Value('com'),
            'id' => $this->id));

       // $this->saveInSession = true;

        if($save)	{
        	return true;
        }
        else {
            var_dump($this->id);
			// return false;
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
       $this->redirectTo('questions/question/' . $this->qid . '#' . $this->id);
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

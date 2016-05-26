<?php

namespace Anax\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class FormUpdateTag extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;



	private $id;
	private $desc;
	private $name;


    /**
     * Constructor
     *
     */
    public function __construct($id = null, $desc = null, $name = null)
    {
		$this->id = $id;
		$this->desc = $desc;
		$this->name = $name;


        parent::__construct(
		[
			'class' => 'user-form',
		],
		[
           	'name' => [
                'type'        => 'text',
                'label'       => 'Namn:',
                'required'    => true,
                'validation'  => ['not_empty'],
				'value'		  => $name,
            ],
            'desc' => [
                 'type'        => 'textarea',
                 'label'       => 'Beskrivning:',
 			    'value'		  => $desc
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
		$this->tag = new \Anax\Tags\Tag();
        $this->tag->setDI($this->di);
        $save = $this->tag->save(
            array(
            'description' => $this->Value('desc'),
            'name' => $this->Value('name'),
            'id' => $this->id));

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
       $this->redirectTo('tags');
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

<?php

namespace Anax\HTMLForm;

/**
 * Anax base class for wrapping sessions.
 *
 */
class FormUpdateQuestion extends \Mos\HTMLForm\CForm
{
    use \Anax\DI\TInjectionaware,
        \Anax\MVC\TRedirectHelpers;



	private $id;
	private $heading;
	private $desc;
    private $tags;


    /**
     * Constructor
     *
     */
    public function __construct($id = null, $heading = null, $desc = null, $tags = null)
    {
		$this->id = $id;
		$this->heading = $heading;
		$this->desc = $desc;
        $this->tags = $tags;


        parent::__construct(
		[
			'class' => 'user-form',
		],
		[
			'heading' => [
                'type'        => 'text',
                'label'       => 'Rubrik',
                'required'    => true,
                'validation'  => ['not_empty'],
				'value'		  => $heading,
            ],
            'tags' => [
                'type'        => 'text',
                'label'       => 'Taggar:',
				'value'		  => $tags,
            ],
            'desc' => [
                'type'        => 'textarea',
                'label'       => 'Fråga',
                'required'    => true,
                'validation'  => ['not_empty'],
				'value'		  => $desc,
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


        $tag = $this->value('tags');
        $search = array('å', 'ä', 'ö');
        $replace = array('a', 'a', 'o');
        $tag = str_replace($search, $replace, $tag);
        $tag = explode(',', $tag);
        $oldtag = explode(',', $this->tags);
        $result = array_diff($tag, $oldtag);

        if(!empty($result))
        {
            $newtag = implode(',', $result);
            $this->tags = new \Anax\Tags\Tag();
            $this->tags->setDI($this->di);
            $this->tags->add($newtag);
        }

		$this->quest = new \Anax\Questions\Question();
        $this->quest->setDI($this->di);
        $save = $this->quest->save(
            array(
            'description' => $this->Value('desc'),
            'heading' => $this->Value('heading'),
            'tags' => $this->Value('tags'),
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
       $this->redirectTo('questions');
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

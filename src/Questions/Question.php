<?php

namespace Anax\Questions;

/**
 * Model for Users.
 *
 */
class Question extends \Anax\MVC\CDatabaseModel
{
    public function count_user_questions($id)
    {
        echo $id;
        $count = $this->comments->query("count(user_id)")
        ->where("user_id = ?")
        ->executeInto([$id]);

        return $count;
    }

    public function count_comments($id) {
        $this->c = new \Anax\Comments\Comment();
        $this->c->setDI($this->di);

        $comments = $this->c->query()
        ->where("questionid = ?")
        ->andWhere("citeid is null")
        ->execute([$id]);
        return count($comments);
    }



}

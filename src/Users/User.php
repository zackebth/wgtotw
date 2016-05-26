<?php

namespace Anax\Users;

/**
 * Model for Users.
 *
 */
class User extends \Anax\MVC\CDatabaseModel
{

    /**
* Get a gravatar based on the user's email.
*/
    public function get_gravatar($size=null) {
      return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim($this->getProperties()['email']))) . '.jpg?d=mm&' . ($size ? "s=$size" : null);
    }

    public function get_rank() {
        (int)$n = $this->getProperties()['rank'];
        if($n == 0 && $n < 10) {
            return 'Newbie';
        }
        else if(10 <= $n && $n < 25) {
            return 'Member';
        }
        else if(25 <=$n && $n < 50) {
            return 'Trusty';
        }
        else if(25 <=$n && $n < 50) {
            return 'Trusty';
        }
        else if(50 <=$n && $n < 100) {
            return 'Loyal';
        }
        else if(100 <=$n) {
            return 'Veteran';
        }
        else if($n < 0) {
            return 'Traitor';
        }

        return 'Member';
    }


    /*
    //  Methods reguarding questions.
    //  Count, Get all, Get etc.
    */
    public function count_user_questions($id)
    {
        $count = $this->select("count(user_id) as total")
        ->from('question')
        ->where("user_id = ?")
        ->execute([$id]);

        return $count;
    }

    public function get_questions() {

        $id = $this->getProperties()['id'];
        $this->q = new \Anax\Questions\Question();
        $this->q->setDI($this->di);

        $questions = $this->q->query()
        ->where("user_id = ?")
        ->orderby('created DESC')
        ->execute([$id]);

        return $questions;
    }

    public function get_latest_questions() {

        $id = $this->getProperties()['id'];
        $this->q = new \Anax\Questions\Question();
        $this->q->setDI($this->di);

        $questions = $this->q->query()
        ->where("user_id = ?")
        ->orderby('created DESC LIMIT 5')
        ->execute([$id]);

        return $questions;
    }


    /*
    //  Methods requarding Comments.
    //  Count, Get all, Get etc.
    */

    public function count_user_comments($id)
    {
        $count = $this->select("count(user_id) as total")
        ->from('comment')
        ->where("user_id = ?")
        ->execute([$id]);

        return $count;
    }

    public function get_comments() {

        $id = $this->getProperties()['id'];
        $this->c = new \Anax\Comments\Commentn();
        $this->c->setDI($this->di);

        $comments = $this->c->query()
        ->where("user_id = ?")
        ->orderby('created DESC')
        ->execute([$id]);

        return $comments;
    }

    public function get_latest_comments() {

        $id = $this->getProperties()['id'];
        $this->c = new \Anax\Comments\Comment();
        $this->c->setDI($this->di);

        $comments = $this->c->query()
        ->where("user_id = ?")
        ->orderby('created DESC LIMIT 5')
        ->execute([$id]);

        return $comments;
    }

    public function plusminus($id, $minus = false) {
        $user = $this->find($id);
        if($minus) {
            $rank = $this->getProperties()['rank'] - 1;
        } else {
            $rank = $this->getProperties()['rank'] + 1;
        }
        $save = $this->save(array('id' => $id, 'rank' => $rank));
    }

    public function get_information($id) {
        $user = $this->query()
        ->where("id = ?")
        ->execute([$id]);

        return get_object_vars($user[0]);
    }


}

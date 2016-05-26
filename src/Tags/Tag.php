<?php

namespace Anax\Tags;

/**
 * Model for Users.
 *
 */
class Tag extends \Anax\MVC\CDatabaseModel
{

    public function add($tags)
    {
        $date = gmdate('Y-m-d H:i:s');
        $tag = explode(',', $tags);
        $keys = array('name','description','rate','created');
        $values = array();

        foreach ($tag as $t) {
            //var_dump($t);
            $search = array('å', 'ä', 'ö');
            $replace = array('a', 'a', 'o');
            $t = str_replace($search, $replace, $t);

            $all = $this->query()
            ->where("name like '%" . $t . "%'" )
            ->execute();

             if(empty($all)) {
                 $this->create(
                 array(
                     'name' => $t,
                     'description' => $t . ' - Ingen info...',
                     'rate' => '1',
                     'created' => $date,
                 )
                );
            } else if(!empty($all)) {
                $name = get_object_vars($all[0]);
                $n = $name['name'];
                $rate = (int)$name['rate'] + 1;
                if(strtolower($n) == strtolower($t)) {
                    $this->save(
                        array(
                            'id' => $name['id'],
                            'rate' => $rate,
                        )
                    );
                }

            }
        }
        // $values = substr($values,0,-1);
    }

}

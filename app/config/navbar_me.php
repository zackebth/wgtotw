<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'navbar',

    // Here comes the menu strcture
    'items' => [

        // This is a menu item
        'home'  => [
            'text'  => '<i class="fa fa-home"></i> Startsida',
            'url'   => $this->di->get('url')->create(''),
            'title' => ''
        ],

        // This is a menu item
        'tags' => [
            'text'  =>'<i class="fa fa-tags"></i> Taggar',
            'url'   => $this->di->get('url')->create('tags'),
            'title' => '',
        ],

        // This is a menu item
        'questions' => [
            'text'  =>'<i class="fa fa-commenting"></i> Frågor',
            'url'   => $this->di->get('url')->create('questions'),
            'title' => '',
            'mark-if-parent-of' => 'questions',
        ],
		// This is a menu item
        'users' => [
            'text'  =>'<i class="fa fa-users"></i> Användare',
            'url'   => $this->di->get('url')->create('users'),
            'title' => '',
            'mark-if-parent' => true,
        ],
       // This is a menu item
        'Login' => [
            'text'  => ($this->di->session->get('user_loggedin') ? '<i class="fa fa-user"></i> Profil' : '<i class="fa fa-key"></i> Logga in'),
            'url'   =>  $this->di->session->get('user_loggedin') ? $this->di->get('url')->create('profil') : $this->di->get('url')->create('login'),
            'title' => '',
        ],
		 // This is a menu item
        'About' => [
            'text'  =>'<i class="fa fa-info"></i> Om Oss',
            'url'   => $this->di->get('url')->create('about'),
            'title' => ''
        ],


    ],



    /**
     * Callback tracing the current selected menu item base on scriptname
     * Work good if your routes the same as the controllers.
     */
    'callback' => function ($url) {

        $route = $this->di->get('request')->getRoute();

        if(empty($route)) {
                if ($url == $this->di->get('request')->getCurrentUrl(false)) {
                    return true;
                }
            }
        else {
                $ra = explode('/', $route);
                $ua = explode('/', $url);
                $i = count($ua) - 1;
                if($ra[0] == $ua[$i]) {
                    return true;
                }

        }
    },



    /**
     * Callback to check if current page is a decendant of the menuitem, this check applies for those
     * menuitems that has the setting 'mark-if-parent' set to true.
     *
     */
    'is_parent' => function ($parent) {
        $route = $this->di->get('request')->getRoute();
        return !substr_compare($parent, $route, 0, strlen($parent));
    },



   /**
     * Callback to create the url, if needed, else comment out.
     *
     */
   /*
    'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },
    */
];

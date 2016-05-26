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
        'start'  => [
            'text'  => '<i class="fa fa-home"></i>',
            'url'   => $this->di->get('url')->create('../'),
            'title' => ''
        ],
		
		 // This is a menu item
        'home'  => [
            'text'  => ' <i class="fa fa-list"></i> Lista',
            'url'   => $this->di->get('url')->create(''),
            'title' => ''
        ],
 
       // This is a menu item
        'add' => [
            'text'  =>'<i class="fa fa-plus"></i> Add',
            'url'   => $this->di->get('url')->create('users/add'),
            'title' => ''
        ],

         // This is a menu item
        'trash' => [
            'text'  =>'<i class="fa fa-trash"></i> Papperskorgen',
            'url'   => $this->di->get('url')->create('users/trashcan'),
            'title' => ''
        ],
		 // This is a menu item
        'setup' => [
            'text'  =>'<i class="fa fa-recycle"></i> Återställ Databasen',
            'url'   => $this->di->get('url')->create('setup'),
            'title' => '',
            ],
      
    ],
 


    /**
     * Callback tracing the current selected menu item base on scriptname
     *
     */
    'callback' => function ($url) {
        if ($url == $this->di->get('request')->getCurrentUrl(false)) {
            return true;
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

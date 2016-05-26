<?php
require __DIR__.'/config_with_app.php';

$app->theme->configure(ANAX_APP_PATH . 'config/theme_me.php');
$app->url->setUrlType(\Anax\Url\CUrl::URL_APPEND);
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_users.php');




// CONTROLLERS


$di->set('form', '\Mos\HTMLForm\CForm');


$di->set('CommentController', function() use ($di) {
    $controller = new Phpmvc\Comment\CommentController();
    $controller->setDI($di);
    return $controller;
});

$di->setShared('db', function() {
    $db = new \Mos\Database\CDatabaseBasic();
    $db->setOptions(require ANAX_APP_PATH . 'config/config_mysql.php');
    $db->connect();
    return $db;
});

$di->set('UsersController', function() use ($di) {
    $controller = new \Anax\Users\UsersController();
    $controller->setDI($di);
    return $controller;
});

$di->set('FormController', function () use ($di) {
    $controller = new \Anax\HTMLForm\FormController();
    $controller->setDI($di);
    return $controller;
});

// END CONTROLLERS


// ROUTES
$app->router->add('', function() use ($app) {
 	$app->theme->setTitle("Min Me sida");

   $users = $app->dispatcher->forward([
		'controller' => 'users',
		'action'     => 'list',
	]);

});


$app->router->add('list', function() use ($app) {

	$users = $app->dispatcher->forward([
		'controller' => 'users',
		'action'     => 'list',
	]);

});


$app->router->add('add', function() use ($app) {

   $users = $app->dispatcher->forward([
		'controller' => 'Form',
		'action'     => 'index',
	]);

});


$app->router->add('setup', function() use ($app) {

    $app->db->setVerbose();

    $app->db->dropTableIfExists('user')->execute();

    $app->db->createTable(
        'user',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'acronym' => ['varchar(20)', 'unique', 'not null'],
            'email' => ['varchar(80)'],
            'name' => ['varchar(80)'],
            'password' => ['varchar(255)'],
            'rank' => ['integer'],
            'created' => ['datetime'],
            'updated' => ['datetime'],
            'deleted' => ['datetime'],
            'active' => ['datetime'],
        ]
    )->execute();


    $app->db->insert(
    'user',
    ['acronym', 'email', 'name', 'password', 'rank', 'created', 'active']
    );

    $now = gmdate('Y-m-d H:i:s');

    $app->db->execute([
        'admin',
        'admin@dbwebb.se',
        'Administrator',
        password_hash('admin', PASSWORD_DEFAULT),
        '0',
        $now,
        $now
    ]);

    $app->db->execute([
        'zackepacke',
        'zacke@packe.se',
        'Zacke Madsen',
        password_hash('zacke', PASSWORD_DEFAULT),
        '0',
        $now,
        $now
    ]);
 });


$app->router->handle();
$app->theme->render();

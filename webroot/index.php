<?php
require __DIR__.'/config_with_app.php';

$app->theme->configure(ANAX_APP_PATH . 'config/theme_me.php');
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_me.php');


// CONTROLLERS
$di->set('CommentsController', function() use ($di) {
    $controller = new Anax\Comments\CommentsController();
    $controller->setDI($di);
    return $controller;
});

$di->set('QuestionsController', function() use ($di) {
    $controller = new Anax\Questions\QuestionsController();
    $controller->setDI($di);
    return $controller;
});

$di->set('TagsController', function() use ($di) {
    $controller = new Anax\Tags\TagsController();
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

$di->set('LoginsController', function() use ($di) {
    $controller = new \Anax\Login\LoginsController();
    $controller->setDI($di);
    return $controller;
});


// END CONTROLLERS


// ROUTES
$app->router->add('', function() use ($app) {
 	$app->theme->setTitle("Min Me sida");

     $content = $app->fileContent->get('home.md');
     $content = $app->textFilter->doFilter($content, 'shortcode, markdown');

    $app->views->add('me/page', [
        'content' => $content,
        'questions' => "hej",
    ]);

    $app->dispatcher->forward([
        'controller' => 'questions',
        'action'     => 'get-Latest-Questions',
    ]);

    $app->dispatcher->forward([
        'controller' => 'tags',
        'action'     => 'get-Latest-Tags',
    ]);

    $app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'get-Most-Active-Members',
    ]);

});

$app->router->add('about', function() use ($app) {

    $app->theme->setTitle("About us");

    $content = $app->fileContent->get('about.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');

    $app->views->add('me/page', [
        'content' => $content,
    ]);

});


$app->router->add('source', function() use ($app) {

    $app->theme->addStylesheet('css/source.css');
    $app->theme->setTitle("Källkod");

    $source = new \Mos\Source\CSource([
        'secure_dir' => '..',
        'base_dir' => '..',
        'add_ignore' => ['.htaccess'],
    ]);

    $app->views->add('me/source', [
        'content' => $source->View(),
    ]);

});

$app->router->add('login', function() use ($app) {

	$app->session();
    $app->theme->setTitle("Login Page");

    $app->dispatcher->forward([
        'controller' => 'logins',
        'action'     => 'login',
    ]);
});

$app->router->add('users', function() use ($app) {

	$app->session();
    $app->theme->setTitle("Alla användare");

    $app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'list',
    ]);
});

$app->router->add('profil', function() use ($app) {

	$app->session();
    $app->theme->setTitle("Login Page");
    $id = $app->session->get('user_loggedin')['id'];

    $app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'id',
        'params'    => [
            'id' => $id],
    ]);
});

$app->router->add('logout', function() use ($app) {

	$app->session();
    $app->theme->setTitle("Login Page");

    $app->dispatcher->forward([
        'controller' => 'logins',
        'action'     => 'logout',
    ]);


});

$app->router->add('qdbc', function() use ($app) {

    $app->dispatcher->forward([
        'controller' => 'questions',
        'action'     => 'create-Database',
    ]);
});

$app->router->add('qdbtc', function() use ($app) {

    $app->dispatcher->forward([
        'controller' => 'tags',
        'action'     => 'create-Database',
    ]);
});

$app->router->add('questions', function() use ($app) {

    $app->session();
    $app->theme->setTitle("Questions");

    $app->dispatcher->forward([
        'controller' => 'questions',
        'action'     => 'question',
    ]);

});

$app->router->add('tags', function() use ($app) {

    $app->theme->setTitle("Tags");

    $app->dispatcher->forward([
        'controller' => 'tags',
        'action'     => 'tag',
    ]);

});

$app->router->add('cdbc', function() use ($app) {
    $app->dispatcher->forward([
        'controller' => 'Comments',
        'action'     => 'create-Database',
    ]);
});

$app->router->add('cdbr', function() use ($app) {
    $app->dispatcher->forward([
        'controller' => 'Comments',
        'action'     => 'reset-Database',
    ]);
});




$app->router->handle();
$app->theme->render();

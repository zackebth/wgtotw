<?php
require __DIR__.'/config_with_app.php';

 $app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');
 $app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
 $app->navbar->configure(ANAX_APP_PATH . 'config/navbar_me.php');


$app->router->add('', function() use ($app) {

   // $app->theme->addStylesheet('css/anax-grid/regions_demo.css');
    $app->theme->setTitle("Regioner");

    $app->views->addString('flash111', 'flash')
               ->addString('featured-1', 'featured-1')
               ->addString('featured-2', 'featured-2')
               ->addString('featured-3', 'featured-3')
               ->addString('triptych-1', 'triptych-1')
               ->addString('triptych-2', 'triptych-2')
               ->addString('triptych-3', 'triptych-3')
               ->addString('Built with MVC  <a href="http://validator.w3.org/check/referer">HTML5</a> &amp;
<a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">CSS3</a>
Validate: <a href="http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance">Unicorn</a>', 'copy-col-1')
               ->addString('&copy; Zackarias Madsen - 2015. ', 'copy-col-2')
               ->addString('footer-col-3', 'copy-col-3')
               ->addString('footer-col-1', 'footer-col-1')
               ->addString('footer-col-2', 'footer-col-2')
               ->addString('footer-col-3', 'footer-col-3')
               ->addString('footer-col-4', 'footer-col-4');

  $content = $app->fileContent->get('typography.md');
  $content = $app->textFilter->doFilter($content, 'shortcode, markdown');


 $app->views->add('theme-grid/page', [
        'content' => $content,

    ]);

  $app->views->add('theme-grid/page', [
        'content' => $content,

    ],
    'sidebar');

});

$app->router->add('font-awesome', function() use ($app) {

    $app->theme->setTitle("Font Awesome");

    $content = $app->fileContent->get('fa-sidebar.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');

    $sidebar = $app->fileContent->get('fa-sidebar.md');
    $sidebar = $app->textFilter->doFilter($sidebar, 'shortcode, markdown');


   $app->views->add('theme-grid/page', [
          'content' => $content,

      ]);

    $app->views->add('theme-grid/page', [
          'content' => $sidebar,
      ],
      'sidebar');

  });

$app->router->handle();
$app->theme->render();

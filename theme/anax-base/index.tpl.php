<!doctype html>
<html class='no-js' lang='<?=$lang?>'>
<head>
<meta charset='utf-8'/>
<title><?=$title . $title_append?></title>
<?php if(isset($favicon)): ?><link rel='icon' href='<?=$this->url->asset($favicon)?>'/><?php endif; ?>
<?php foreach($stylesheets as $stylesheet): ?>
<link rel='stylesheet' type='text/css' href='<?=$this->url->asset($stylesheet)?>'/>
<link href='http://fonts.googleapis.com/css?family=Courgette' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Roboto+Slab' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<?php endforeach; ?>
<?php if(isset($style)): ?><style><?=$style?></style><?php endif; ?>
<script src='<?=$this->url->asset($modernizr)?>'></script>
</head>

<body>

<div id='nav-wrap'>
<div class='clear'></div>
<?php if ($this->views->hasContent('navbar')) : ?>

<?php $this->views->render('navbar')?>

<?php endif; ?>
<div class='clear'></div>
</div>

<?php if ($this->views->hasContent('sidebar')) : ?>
<div id='main-section-split'>
<?php if(isset($main)) echo $main?>
<?php $this->views->render('main')?>
<?php if(isset($sidebar)) echo $sidebar?>
<?php $this->views->render('sidebar')?>
</div>
<?php else: ?>
<div id='main-section'>
<?php if(isset($main)) echo $main?>
<?php $this->views->render('main')?>
</div>
<?php endif; ?>

<div id='footer-section'>
<?php if(isset($footer)) echo $footer?>
<?php $this->views->render('footer')?>
</div>



<?php if(isset($jquery)):?><script src='<?=$this->url->asset($jquery)?>'></script><?php endif; ?>

<?php if(isset($javascript_include)): foreach($javascript_include as $val): ?>
<script src='<?=$this->url->asset($val)?>'></script>
<?php endforeach; endif; ?>

<?php if(isset($google_analytics)): ?>
<script>
  var _gaq=[['_setAccount','<?=$google_analytics?>'],['_trackPageview']];
  (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
  g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
  s.parentNode.insertBefore(g,s)}(document,'script'));
</script>
<?php endif; ?>

</body>
</html>

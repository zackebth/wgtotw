<!doctype html>
<html class='no-js' lang='<?=$lang?>'>
<head>
<meta charset='utf-8'/>
<title><?=$title . $title_append?></title>
<?php if(isset($favicon)): ?><link rel='icon' href='<?=$this->url->asset($favicon)?>'/><?php endif; ?>
<?php foreach($stylesheets as $stylesheet): ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel='stylesheet' type='text/css' href='<?=$this->url->asset($stylesheet)?>'/>
<link href='http://fonts.googleapis.com/css?family=Courgette' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Roboto+Slab' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<?php endforeach; ?>
<?php if(isset($style)): ?><style><?=$style?></style><?php endif; ?>
<script src='<?=$this->url->asset($modernizr)?>'></script>
</head>

<body>

<div id='grid-wrapper'>

<?php if ($this->views->hasContent('header')) : ?>
<?php if(isset($header)) echo $header?>
<div id='header'><?php $this->views->render('header')?></div>
<?php endif; ?>


<?php if ($this->views->hasContent('navbar')) : ?>
<?php $this->views->render('navbar')?>
<?php endif; ?>


<?php if ($this->views->hasContent('flash')) : ?>
<div id='flash'><?php $this->views->render('flash')?></div>
<?php endif; ?>

<?php if ($this->views->hasContent('triptych-1', 'triptych-2', 'triptych-3')) : ?>
<div id='wrap-triptych'>
    <div id='triptych-1'><?php $this->views->render('triptych-1')?></div>
    <div id='triptych-2'><?php $this->views->render('triptych-2')?></div>
    <div id='triptych-3'><?php $this->views->render('triptych-3')?></div>
</div>
<?php endif; ?>

<?php if ($this->views->hasContent('main','sidebar')) : ?>
<div id='wrap-main'>
<div id='main'>  <?php $this->views->render('main')?></div>
<div id='sidebar'><?php $this->views->render('sidebar')?></div>
</div>
<?php endif; ?>


<?php if ($this->views->hasContent('footer-col-1', 'footer-col-2', 'footer-col-3', 'footer-col-4')) : ?>
<div id='wrap-footer-col'>
    <div id='footer-col-1'><?php $this->views->render('footer-col-1')?></div>
    <div id='footer-col-2'><?php $this->views->render('footer-col-2')?></div>
    <div id='footer-col-3'><?php $this->views->render('footer-col-3')?></div>
    <div id='footer-col-4'><?php $this->views->render('footer-col-4')?></div>
</div>
<?php endif; ?>

<?php if ($this->views->hasContent('copy-col-1', 'copy-col-2', 'copy-col-3')) : ?>
<footer id='copy-wrap'>
<div id='copy-col-1'><?php $this->views->render('copy-col-1')?></div>
<div id='copy-col-2'><?php $this->views->render('copy-col-2')?></div>
<div id='copy-col-3'><?php $this->views->render('copy-col-3')?></div>
</footer>
<?php endif; ?>




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

</div>
</body>
</html>

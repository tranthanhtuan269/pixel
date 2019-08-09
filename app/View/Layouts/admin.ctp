<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('Workman', 'Hệ thống quản lý');
$cakeVersion = __d('Workman', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
    <?php echo $this->Html->charset(); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>
        <?php echo $cakeDescription ?>:
        <?php echo $this->fetch('title'); ?>
    </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">
    <?php echo $this->Html->css('bootstrap.min.css'); ?>
    <?php echo $this->Html->css('bootstrap-responsive.min.css'); ?>
    <?php echo $this->Html->css('font-awesome.min.css'); ?>
    <?php echo $this->Html->css('normalize.css'); ?>
    <?php echo $this->Html->css('DT_bootstrap.css'); ?>
    <?php echo $this->Html->css('bootstrap-fileupload.css'); ?>
    <?php echo $this->Html->css('datepicker.css'); ?>
    <?php echo $this->Html->css('daterangepicker.css'); ?>
    <?php echo $this->Html->css('flaty'); ?>
    <?php echo $this->Html->css('flaty-responsive'); ?>
    <?php echo $this->Html->script('modernizr-2.6.2.min.js'); ?>
</head>
<body>
<!--[if lt IE 7]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to
    improve your experience.</p>
<![endif]-->
<!-- BEGIN Theme Setting -->
<?php echo $this->element('theme_skill'); ?>
<?php echo $this->element('header'); ?>
<div class="container-fluid" id="main-container">
    <?php echo $this->element('menu'); ?>
    <?php echo $this->Session->flash(); ?>
    <?php echo $this->fetch('content'); ?>
</div>
<?php echo $this->Html->script('jquery-1.10.1.min.js'); ?>
<?php echo $this->Html->script('bootstrap.min.js'); ?>
<?php echo $this->Html->script('jquery.nicescroll.min.js'); ?>
<?php echo $this->Html->script('jquery.dataTables.js'); ?>
<?php echo $this->Html->script('DT_bootstrap.js'); ?>
<?php echo $this->Html->script('bootstrap-fileupload.min.js'); ?>
<?php echo $this->Html->script('bootstrap-datepicker.js'); ?>
<?php echo $this->Html->script('date.js'); ?>
<?php echo $this->Html->script('daterangepicker.js'); ?>
<?php echo $this->Html->script('jquery.validate.min.js'); ?>
<?php echo $this->Html->script('additional-methods.min.js'); ?>
<!--page specific plugin scripts-->
<?php echo $this->Html->script('jquery.flot.js'); ?>
<?php echo $this->Html->script('jquery.flot.resize.js'); ?>
<?php echo $this->Html->script('jquery.flot.pie.js'); ?>
<?php echo $this->Html->script('jquery.flot.stack.js'); ?>
<?php echo $this->Html->script('jquery.flot.crosshair.js'); ?>
<?php echo $this->Html->script('jquery.flot.tooltip.min.js'); ?>
<?php echo $this->Html->script('jquery.sparkline.min.js'); ?>
<?php echo $this->Html->script('ckeditor/ckeditor'); ?>
<?php echo $this->Html->script('flaty.js'); ?>
<?php echo $this->element('sql_dump'); ?>
<!--<script type="text/javascript">-->
<!--    $(document).ready(function () {-->
<!---->
<!--        $("#menu li").click(function () {-->
<!--            $("a").parent().removeClass("active");-->
<!--            $(this).parent().addClass("active");-->
<!--        });-->
<!--    });-->
<!--</script>-->
</body>
</html>

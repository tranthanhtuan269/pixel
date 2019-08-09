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
<!--    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>-->
    <?php echo $this->Html->css(array('ui.fancytree','bootstrap.min','jquery-ui','font-awesome.min','normalize','DT_bootstrap','prettyPhoto','flaty','bootstrap-fileupload','jquery.gritter','flipclock','tipped')); ?>
    <?php echo $this->Html->script(array('jquery','jquery-ui.min','bootstrap.min','jquery.nicescroll.min','jquery.flot','jquery.cookie','bootstrap-fileupload.min','jquery.prettyPhoto','flaty','main','jquery.validate.min','ckeditor/ckeditor','jquery.gritter','highcharts','flipclock','tipped','jquery.fancytree')); ?>
    <?php
        if(strtolower($this->params['controller'])=='projects'){
            echo $this->Html->script('jquery.datetimepicker');
            echo $this->Html->css('jquery.datetimepicker');
    ?>
        <script>
            $(function(){
                $('.datetimepicker').datetimepicker({
                    format:'d/m/Y H:i',
                    lang:'vi',
                    step: 30
                });
            });
        </script>
     <?php } ?>
</head>
<body>
<!--[if lt IE 7]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to
    improve your experience.</p>
<![endif]-->
<!-- BEGIN Theme Setting -->

<?php echo $this->element('theme_skin'); ?>
<?php echo $this->element('header'); ?>
<div class="container-fluid" id="main-container">
    <?php echo $this->element('menu'); ?>
    <div id="main-content">
        <?php echo $this->fetch('content'); ?>
    </div>
</div>
<?php echo $this->element('task_manager'); ?>
<?php echo $this->element('fixed_bottom'); ?>

<div style="display: none">
    <?php echo $this->element('sql_dump'); ?>

</div>

</body>
<script>
//        $(window).bind('beforeunload', function(eventObject) {
//               return confirm("Press a button");
//        });

</script>
</html>


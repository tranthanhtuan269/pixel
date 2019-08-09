
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
    <?php echo $this->Html->css(array('bootstrap.min', 'bootstrap-responsive.min', 'font-awesome.min', 'normalize', 'flaty', 'flaty-responsive')); ?>
    <?php echo $this->Html->script(array('jquery', 'bootstrap.min')); ?>
</head>
<style>
    .login-page:before, .error-page:before, #main-content{
        background: <?php  echo array_key_exists('LOGIN_BG',$CF)? "url('".$CF['LOGIN_BG']."')" :  "blue"    ?>;
    }
</style>
<body class="login-page">
<!--[if lt IE 7]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to
    improve your experience.</p>
<![endif]-->

<!-- BEGIN Main Content -->
<div class="login-wrapper">
<?php
echo $this->Form->create('User', array('action' => 'login','id'=>'form-login'));
?>
<h3><?php echo __('Đăng nhập'); ?></h3>
<hr/>
<div class="control-group">
    <div class="controls">
        <?php
        echo $this->Form->input('username', array('div' => 'clearfix',
            'value' => isset($username)?$username:"",
            'before' => '<label>' . __('Username') . '</label><div class="input">',
            'after' => $this->Form->error('username', array(), array('wrap' => 'span', 'class' => 'help-inline')) . '</div>',
            'error' => array('attributes' => array('style' => 'display:none')),
            'label' => false, 'class' => 'xlarge','type'=>'text'));
        ?>
    </div>
</div>
<div class="control-group">
    <div class="controls">
        <?php
        echo $this->Form->input('password', array('div' => 'clearfix',
            'value' => isset($password)?$password:"",
            'before' => '<label>' . __('Password') . '</label><div class="input">',
            'after' => $this->Form->error('password', array(), array('wrap' => 'span', 'class' => 'help-inline')) . '</div>',
            'error' => array('attributes' => array('style' => 'display:none')),
            'label' => false, 'class' => 'xlarge'));
        ?>
    </div>
</div>
<div class="control-group">
    <div class="controls">
<!--        <label class="checkbox">-->
            <?php echo $this->Form->input('remember',array(
                'type' => 'checkbox',
                'label' => 'Lưu mật khẩu',
                'checked'  => isset($email)?1:0,
            ));?>
<!--        </label>-->
    </div>
</div>
<div class="control-group">
    <div class="controls">
        <button type="submit" class="btn btn-primary input-block-level"><?php echo __('Đăng nhập'); ?></button>
    </div>
</div>
<hr/>
<p class="clearfix">
    <?php echo $this->Session->flash(); ?>
</p>
<!--        <div class="actions">-->
<!--            --><?php //echo $this->Form->submit(__('Submit'), array('class' => 'btn primary', 'div' => false)); ?>
<!--            --><?php //echo $this->Form->reset(__('Cancel'), array('class' => 'btn', 'div' => false)); ?>
<!--        </div>-->
        <?php
        echo $this->Form->end();
        ?>
</div>
<script type="text/javascript">
    function goToForm(form) {
        $('.login-wrapper > form:visible').fadeOut(500, function () {
            $('#form-' + form).fadeIn(500);
        });
    }
</script>
</body>
</html>

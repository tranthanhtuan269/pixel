<style type="text/css">
    /* Override some defaults */
    html, body {
        background-color: #eee;
    }

    /*body {*/
    /*padding-top: 40px; *//* 40px to make the container go all the way to the bottom of the topbar */
    /*}*/

    .container > footer p {
        text-align: center; /* center align it with the container */
    }

    .container {
        width: 100%; /* downsize our container to make the content feel a bit tighter and more cohesive. NOTE: this removes two full columns from the grid, meaning you only go to 14 columns and not 16. */
        background-color: #fff;
    }

    /* The white background content wrapper */
    .container > .content {
        background-color: #fff;
        padding: 20px;
        margin: 0 -20px; /* negative indent the amount of the padding to maintain the grid system */
        -webkit-border-radius: 0 0 6px 6px;
        -moz-border-radius: 0 0 6px 6px;
        border-radius: 0 0 6px 6px;
        -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, .15);
        -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, .15);
        box-shadow: 0 1px 2px rgba(0, 0, 0, .15);
    }

    /* Page header tweaks */
    .page-header {
        background-color: #f5f5f5;
        padding: 20px 20px 10px;
        margin: -20px -20px 20px;
    }

    /* Styles you shouldn't keep as they are for displaying this base example only */
    .content .span16,
    .content .span7,
    .content .span7,
    .content .span10,
    .content .span4 {
        min-height: 500px;
    }

    .span7 {
        padding: 20px !important;
        width: 40% !important;
    }

    /* Give a quick and non-cross-browser friendly divider */
    .content .span7,
    .content .span4 {
        margin-left: 0 !important;
        padding-left: 19px !important;
        border-left: 1px solid #eee !important;
        /*margin-top: 40px;*/
    }

    .topbar .btn {
        border: 0;
    }

    .breadcrumb {
        padding: 7px 14px;
        margin: 0 0 18px;
        background-color: #f5f5f5;
        background-repeat: repeat-x;
        background-image: -khtml-gradient(linear, left top, left bottom, from(#ffffff), to(#f5f5f5));
        background-image: -moz-linear-gradient(top, #ffffff, #f5f5f5);
        background-image: -ms-linear-gradient(top, #ffffff, #f5f5f5);
        background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ffffff), color-stop(100%, #f5f5f5));
        background-image: -webkit-linear-gradient(top, #ffffff, #f5f5f5);
        background-image: -o-linear-gradient(top, #ffffff, #f5f5f5);
        background-image: linear-gradient(top, #ffffff, #f5f5f5);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#f5f5f5', GradientType=0);
        border: 1px solid #ddd;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        -webkit-box-shadow: inset 0 1px 0 #ffffff;
        -moz-box-shadow: inset 0 1px 0 #ffffff;
        box-shadow: inset 0 1px 0 #ffffff;
    }

    .breadcrumb li {
        display: inline;
        text-shadow: 0 1px 0 #ffffff;
    }

    table {
        width: 100%;
        margin-bottom: 18px;
        padding: 0;
        font-size: 13px;
        border-collapse: collapse;
    }

    table th, table td {
        padding: 10px 10px 9px;
        line-height: 18px;
        text-align: left;
    }

    table th {
        padding-top: 9px;
        font-weight: bold;
        vertical-align: middle;
    }

    table td {
        vertical-align: top;
        border-top: 1px solid #ddd;
    }

    table tbody th {
        border-top: 1px solid #ddd;
        vertical-align: top;
    }

    .condensed-table th, .condensed-table td {
        padding: 5px 5px 4px;
    }

</style>
<!--end them-->
<?php echo $this->Html->css(array('/acl_management/css/treeview')); ?>
<?php echo $this->Html->script(array(
    '/acl_management/js/jquery.cookie',
    '/acl_management/js/treeview',
    '/acl_management/js/acos',
    '/acl_management/js/twitter/bootstrap-buttons',
));

?>
<?php
echo $this->element('top_page', array(
    'page_title' => 'Phân quyền người dùng'
));
?>
<!--<div class="container">-->
<div id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/"><?php echo __('Trang chủ'); ?></a>
            <span class="divider"><i class="icon-angle-right"></i></span>
        </li>
        <li>
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><?php echo __('Phân quyền người dùng'); ?></a>
        </li>
    </ul>
</div>
<?php echo $this->Session->flash(); ?>
<div class="row-fluid">
    <div class="container">
        <div class="span7">
            <div class="">
                <button class="btn danger" data-loading-text="loading...">Generate</button>
            </div>
            <div id="acos">
                <?php echo $this->Tree->generate($results, array('alias' => 'alias', 'plugin' => 'acl_management', 'model' => 'Aco', 'id' => 'acos-ul', 'element' => '/permission-node')); ?>
            </div>
        </div>
        <div class="span7">
            <div id="aco-edit"></div>
        </div>
    </div>
</div>
<footer>
    <?php
    echo $this->element('footer');
    ?>
</footer>
</div>
<!-- END Main Content -->
<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
<script type="text/javascript">
    $(document).ready(function () {
        $("#acos").treeview({collapsed: true});
    });
    $(function () {
        var btn = $('.btn').click(function () {
            btn.button('loading');
            $.get('<?php echo $this->Html->url('/acl_management/user_permissions/sync');?>', {},
                function (data) {
                    btn.button('reset');
                    $("#acos").html(data);
                }
            );
        })
    });
</script>

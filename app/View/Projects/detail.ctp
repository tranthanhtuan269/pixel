<?php echo $this->element('top_page', array(
    'page_title' => 'Quản lý đơn hàng'
));
?>
<?php echo $this->Session->flash(); ?>
<div id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/"><?php echo __('Trang chủ'); ?></a>
            <span class="divider"><i class="icon-angle-right"></i></span>
        </li>
        <li>
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><?php echo __('Quản lý đơn hàng'); ?></a>
            <span class="divider"><i class="icon-angle-right"></i></span>
        </li>
        <li class="active">
            <?php echo __('Chi tiết đơn hàng'); ?>
        </li>
    </ul>
</div>
<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i><?php echo 'Chi tiết đơn hàng'; ?></h3>

                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <div class="row-fluid">
                    <div class="span4">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h4 class="panel-title">Thông tin đơn hàng</h4>
                            </div>
                            <div class="panel-body">
                                <div class="row-fluid">
                                    <label class="span5"><b><?php echo __('Tên đơn hàng'); ?>:</b></label>

                                    <div class="span7">
                                        <?php echo $project['Project']['Name']; ?>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <label class="span5"><b><?php echo __('Mã khách hàng'); ?>:</b></label>

                                    <div class="span7">
                                        <?php echo $project['Customer']['customer_code']; ?>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <label class="span5"><b><?php echo __('Nhóm khách hàng'); ?>:</b></label>

                                    <div class="span7">
                                        <?php echo $project['CustomerGroup']['name']; ?>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <label class="span5"><b><?php echo __('Người tạo'); ?>:</b></label>

                                    <div class="span7">
                                        <?php echo $project['User']['name']; ?>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <label class="span5"><b><?php echo __('Nhân viên tham gia'); ?>:</b></label>

                                    <div class="span7">
                                        <a data-toggle="modal" class="btn" role="button" href="#view-joiners">Xem chi
                                            tiết</a>
                                    </div>
                                </div>
<!--                                <div class="row-fluid">-->
<!--                                    <a data-toggle="modal" id="reject_project" data-rate="4" class="btn btn-danger"-->
<!--                                       data-id-project="--><?php //echo $project['Project']['ID']; ?><!--" role="button"-->
<!--                                       href="#reject">Reject-->
<!--                                        đơn hàng</a>-->
<!--                                </div>-->
                            </div>
                        </div>
                    </div>
                    <div class="span8">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h4 class="panel-title">Thông tin sản phẩm</h4>
                            </div>
                            <div class="panel-body">
                                <div class="span6">
                                    <div class="row-fluid">
                                        <label class="span6"><b><?php echo __('Số lượng'); ?>:</b></label>

                                        <div class="span6">
                                            <?php echo $project['Project']['Quantity']; ?>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <label class="span6"><b><?php echo __('Hoàn thành'); ?>:</b></label>

                                        <div class="span6">
                                            <?php echo $count_pd; ?>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <label class="span6"><b><?php echo __('Dung lượng ban đầu'); ?>:</b></label>

                                        <div class="span6">
                                            <?php echo round($project['Project']['InitSize']/1024/1024,2).' MB'; ?>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <label class="span6"><b><?php echo __('Dung lượng hoàn thành'); ?>:</b></label>

                                        <div class="span6">
                                            <?php echo round($project['Project']['CompSize']/1024/1024,2).' MB'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="row-fluid">
                                        <label class="span12"><b><?php echo __('Lưu ý ca trưởng/VP'); ?>:</b></label>

                                        <div class="span12">
                                            <?php echo $project['Project']['RequireDevide']; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row-fluid">
                                    <label class="span3"><b><?php echo __('Đường dẫn thư mục'); ?>:</b></label>

                                    <div class="span9">
                                        <?php echo str_replace('@', '/', $dir . $project['Project']['UrlFolder']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h4 class="panel-title">Chi tiết chia hàng <span
                                        style="float: right"> Đã chia: <?php echo(isset($products['total']['Delivered']) ? $products['total']['Delivered'] : 0) ?>
                                        Files   |   Tổng số: <?php echo count($project['Product']) ?> Files</span></h4>
                            </div>
                            <div class="panel-body">
                                <div class="">
                                    <?php
                                    if (count($products) > 1) {
                                        foreach ($products['data'] as $name => $product) {
                                            $user_info = $this->requestAction(Router::url(array('controller' => 'projects', 'action' => 'user_info', $name)));
                                            ?>
                                            <div class="span4" style="margin-left:0">
                                                <div class="span6" style="text-align:right;"><b><?php echo get(get($user_info,'User'),'name'); ?>
                                                        :</b></div>

                                                <div class="span6" style="text-align:left; ">
                                                    <?php echo 'Done ' . (isset($product['Done']) ? $product['Done'] : 0) . '/ ' . (isset($product['Delivered']) ? $product['Delivered'] : 0) . ' Files'; ?>
                                                    <?php
                                                    if ((isset($product['Done']) ? $product['Done'] : 0) < (isset($product['Delivered']) ? $product['Delivered'] : 0)) {
                                                        ?>
                                                        <button class="btn btn-danger cancel_product"
                                                                data-product="<?php echo $product['product_id']; ?>">
                                                            <i class="icon-remove"></i><?php echo __(' Hủy'); ?>
                                                        </button>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                    }?>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="row-fluid text-center">
                    <div class="span12">
                        <?php if ($project['Project']['Status_id'] < 3) { ?>
                            <button class="btn btn-danger" onclick="status(<?php echo $project['Project']['ID'] ?>)">
                                Kích hoạt
                            </button>
                        <?php }
                        if($project['Project']['Status_id'] != 6){?>
                        <button class="btn btn-danger">Đọc excel</button>
                        <?php }?>
                        <button class="btn btn-danger"
                                onclick="location.href='<?php echo $this->html->url(array('controller' => 'Projects', 'action' => 'bring', $project['Project']['ID']), true); ?>'">
                            Gom hàng - Giao hàng
                        </button>
                        <button class="btn btn-danger">Sản phẩm hoàn thành</button>
                        <button class="btn btn-danger"
                                onclick="location.href='<?php echo $this->html->url(array('controller' => 'Projects', 'action' => 'index'), true); ?>'">
                            Trờ về quản lý đơn hàng
                        </button>
                    </div>
                </div>
                <div class="row-fluid" id="show-products">
                        <img src="<?php echo $this->webroot; ?>loading.gif">
                </div>
            </div>
        </div>
    </div>
    <footer>
        <?php
        echo $this->element('footer');
        ?>
    </footer>
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="view-joiners"
     style="display: none;">
    <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
        <h3 id="myModalLabel">Danh sách nhân viên tham gia đơn hàng</h3>
    </div>
    <div class="modal-body">
        <div class="box">
            <div class="box-content">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Họ tên</th>
                        <th>Loại xử lý</th>
                        <?php if($group_id_user == 1){?>
                        <th>Điểm</th>
                        <?php }?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($userpoints as $k => $userpoint) { ?>
                        <tr>
                            <td><?php echo $k + 1; ?></td>
                            <td><?php echo $userpoint['User']['name'] ?></td>
                            <td><?php echo $userpoint['Action']['Name'] ?></td>
                            <?php if($group_id_user == 1){?>
                            <td><?php echo $userpoint['ProjectAction']['Point'] ?></td>
                            <?php }?>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button aria-hidden="true" data-dismiss="modal" class="btn">Close</button>
    </div>
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="reject"
     style="display: none;">
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="modal-1"
     style="display: none;">

</div>
<!-- END Main Content -->
<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
<style>
    .grid-product-img {
        max-height: 128px;
        overflow: hidden;
        border: 1px solid #d9d9d9;
        margin-bottom: 10px;
        max-width: 128px;
    }

    .grid-product-img .checkbox {
        width: 100%;
        height: 100px;
        padding-left: 30px;
    }
</style>
<script>
    $('.cancel_product').click(function () {
        var product_ids = $(this).attr('data-product');
//        alert(product_ids);
        $.post("<?php echo Router::url(array('controller' => 'projects','action' => 'cancel_deliver')); ?>", { 'product_ids': product_ids })
            .done(function (data) {
                if (data) {
                    alert('Sản phẩm đã được lấy lại!');
                    window.location.reload();
                }
            });
    });
    $(function () {
        <?php
        $exten = '';$rs = 3;
        foreach ($project['Product'] as $pd){
        $arr = explode('.',$pd['name_file_product']);
        $exten = $arr[count($arr)-1];

        $rs = $this->requestAction(Router::url(array('controller'=>'projects', 'action'=>'view_check', $exten)));
        } ?>
        <?php if(count($project['Product'])>20 || $rs == 1){?>
        getproducts(1);
        <?php }else{?>
        getproducts();
        <?php }?>
//        $('.bt-assignment').click(function(){
//            if($("input.cbimg:checked").length > 0){
//                $('#ProductDetailForm').submit();
//            }else{
//                alert('Bạn phải chọn sản phẩm muốn giao!');
//            }
//        });
    });
    function getproducts(type) {
        if (type == 1) {
            $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'ListviewDetail',$project['Project']['ID']),true)?>", function (data) {
                $("#show-products").html(data);
            });
        } else {
            $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'GridviewDetail',$project['Project']['ID']),true)?>", function (data) {
                $("#show-products").html(data);
            });
        }
    }
    function status(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array('controller'=>'Projects','action' => 'ActiveProject')); ?>/" + id,
            success: function (data) {
                if (data.returnCode == 1) {
                    $.gritter.add({title: "Kích hoạt thành công!", text: '<a href="#" style="color:#ccc">Bạn đã thay đổi trạng thái đơn hàng</a>.', image: "<?php echo $this->webroot. 'img/notification.png';?>", sticky: false, time: ""});
                    window.location.reload();
                }
            }
        });
    }
</script>
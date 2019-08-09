<?php echo $this->element('top_page', array(
    'page_title' => 'Quản lý đơn hàng'
));  ?>
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
                                    <label class="span5"><b><?php echo __('Khách hàng'); ?>:</b></label>
                                    <div class="span7">
                                        <?php echo $project['Customer']['name']; ?>
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
                                        <a data-toggle="modal" class="btn" role="button" href="#view-joiners">Xem chi tiết</a>
                                    </div>
                                </div>
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
                                <div class="span6 panel">
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
                                        <?php echo str_replace('@','/',$dir.$project['Project']['UrlFolder']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-fluid text-center" >
                    <div class="span12">
                        <?php
                        $pd = "";
                         foreach ($list['products'] as $product) {
                            if(isset($product['Product']['done_round']) && $product['Product']['done_round'] > 1){
                                $pd .= $product['Product']['id'].',';
                            }

                        }
//                        debug($pd);die; ?>
                        <a id="okcheck" class="btn btn-danger" href="<?php echo $this->html->url(array('controller'=>'Projects','action'=>'okcheck',5,$pd),true)?>">Ấn để hoàn tất kiểm tra</a>
                        <button class="btn btn-danger" onclick="location.href='<?php echo $this->html->url(array('controller'=>'Projects','action'=>'bring',$project['Project']['ID']),true)?>'">Trờ về chi tiết đơn hàng</button>
                    </div>
                </div>
                <div class="row-fluid" id="show-products">
                    <div class="box box-gray">
                        <div class="box-title">
                            <h3><i class="icon-puzzle-piece"></i>Danh sách Folder</h3>
                            <div class="box-tool">
                                <div class="btn-group">

                                </div>
                            </div>
                        </div>
                        <div class="box-content" >
                            <div class="row-fluid">
                                <div class="box">
                                    <div class="box-content">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tên sản phẩm</th>
                                                    <th>Trạng thái</th>
                                                    <th>Người làm</th>
                                                    <th>
                                                        <a class="btn btn-primary btn-sm" href="<?php echo Router::url(array('controller'=>'Projects','action' => 'download_all',$project['Project']['ID'],$list['dirname'])); ?>">
                                                            <i class="fa fa-download"></i> Download all</a>
                                                        <a class="btn btn-primary btn-sm" ><i class="fa fa-download"></i> Upload all</a>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i=1;
//                                                if(isset($project['Project']['ID']) && $project['Project']['ID']==860) {
//                                                    debug($list['products']);
//                                                    die;
//                                                }
                                                foreach ($list['products'] as $product) {
//                                                    debug($product);die;
                                                     if(isset($product['Product']['done_round']) && $product['Product']['done_round'] > 1){
                                                    $done_pd = $this->requestAction(Router::url(array('controller' => 'products', 'action' => 'get_done_product',  $product['Product']['id'])));
                                                    if($product['Product']['Check']['CheckerProduct']['done']==0) {
                                                        ?>
                                                        <tr>
<!--                                                            --><?php //debug($done_pd);?>
                                                            <td>&nbsp;<?php echo $i?></td>
                                                            <td>
                                                                &nbsp;<?php echo $product['Product']['name_file_product']?></td>
                                                            <td>
                                                                &nbsp;<?php if ($product['Product']['Check']['CheckerProduct']['check'] == 1) {
                                                                    echo('<span style="color: orange">Đã check</span>');
                                                                } else {
                                                                    echo('<span style="color: red">Đang check</span>');
                                                                } ?></td>
                                                            <td>&nbsp;<?php echo get(get($product, 'Deliver'), 'name')?></td>
                                                            <td>
                                                                <span
                                                                    data="<?php echo str_replace('@', '/', $domain . $dir . get($product['Product'],'url') .get($product['Product'],'sub_folder') .'@' . $product['Product']['name_file_product'])?>"
                                                                    class="btn btn-primary btn-sm download-file"><i
                                                                        class="fa fa-download"></i> Download file gốc
                                                                </span>
                                                                <span
                                                                    data="<?php echo str_replace('@', '/', $domain.$dir . get($product['Project'],'UrlFolder') . '@DONE@'.get($product['Project'],'Code').'-'.get(get($product,'Performer'), 'username').get($product['Product'],'sub_folder').'@'. $done_pd['DoneProduct']['name_file_done'])?>"
                                                                    class="btn btn-primary btn-sm download-file"><i
                                                                        class="fa fa-download"></i> Download file đã làm
                                                                </span>
                                                                <a data-toggle="modal" class="btn btn-primary btn-sm "
                                                                   onclick="changefile(<?php echo $product['Product']['id']?>)"
                                                                   role="button" href="#modal-2"><i
                                                                        class="fa fa-mail-forward"></i> Thay thế</a>

                                                                <a data-toggle="modal" id="bt-reject<?php echo $product['Product']['id'] ?>"
                                                                   class="btn btn-danger reject-product reject_product" data-rate="7" data-project-id="<?php echo $product['Project']['ID'];?>"
                                                                   data-id-product="<?php echo $product['Product']['id'] ?>"
                                                                   data-file="<?php echo $product['Product']['name_file_product'] ?>"
                                                                   role="button" href="#reject">Reject</a>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }else{?>
                                                        <tr>
                                                            <td>&nbsp;<?php echo $i?></td>
                                                            <td>
                                                                &nbsp;<?php echo $product['Product']['name_file_product']?></td>
                                                            <td>
                                                                &nbsp;<?php echo('<span style="color: green">Đã done</span>');?>
                                                            </td>
                                                            <td>&nbsp;<?php echo isset($product['Deliver']) ? get($product['Deliver'], 'name') : '' ?></td>
                                                            <td>
                                                                <span
                                                                    data="<?php echo str_replace('@', '/', $domain . $dir . $product['Product']['url'] . '@' . $product['Product']['name_file_product'])?>"
                                                                    class="btn btn-primary btn-sm download-file"><i
                                                                        class="fa fa-download"></i> Download</span>
                                                                <a data-toggle="modal" id="bt-reject<?php echo $product['Product']['id'] ?>"
                                                                   class="btn btn-danger reject-product reject_product" data-rate="7" data-project-id="<?php echo $product['Project']['ID'];?>"
                                                                   data-id-product="<?php echo $product['Product']['id'] ?>"
                                                                   data-file="<?php echo $product['Product']['name_file_product'] ?>"
                                                                   role="button" href="#reject">Reject</a>

                                                            </td>
                                                        </tr>
                                                    <?php }
                                                    $i++;
                                                }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <?php echo $this->element('footer'); ?>
    </footer>

</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="modal-1"
     style="display: none; z-index: 9999">

</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="reject"
     style="display: none;">
</div>
<div class="modal fade" id="modal-2" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" id="content-popup">

    </div>
</div>
<div class="modal fade" id="modal-3" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" id="content-reject">

    </div>
</div>
<!-- END Main Content -->
<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
<style>
    .grid-product-img {
        max-height: 100px;
        overflow: hidden;
        border: 1px solid #d9d9d9;
    }
    .grid-product-img .checkbox {
        width: 100%;
        height: 100px;
        padding-left: 30px;
    }
</style>
<script>
     function changefile(product_id){
         $.get( "<?php echo $this->html->url(array('controller'=>'Projects','action'=>'changefile'))?>/"+product_id, function( data ) {
              $('#content-popup').html(data);
         });
     }
     $('.reject_product').click(function () {
         var id = $(this).attr('data-project-id');
         var id_product = $(this).attr('data-id-product');
         var rate = $(this).attr('data-rate');
         $.post("<?php echo Router::url(array('controller' => 'projects','action' => 'form_reject')); ?>", { 'id': id,rate:rate,id_product: id_product})
             .done(function (data) {
                 if (data) {
                     $('#reject').html(data);
                     $('#link').val('<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"?>');
                 }
             });
     });

     $('.download-file').click(function(){
         var a = document.createElement('a');
         a.download = 'myImage.png';
         a.href = $(this).attr('data');
         a.click();
     });
//    $('#okcheck').click(function(){
//            alert('aaa');
//        }
//    );
</script>
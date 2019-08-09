<?php
echo $this->element('top_page', array(
    'page_title' => 'Quản lý các sản phẩm trả lại và feedback',
));
?>
<div id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/"><?php echo __('Trang chủ'); ?></a>
            <span class="divider"><i class="icon-angle-right"></i></span>
        </li>
        <li>
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><b><?php echo __('Quản lý các sản phẩm trả lại và feedback'); ?></b></a>
        </li>
    </ul>
</div>
<?php echo $this->Session->flash(); ?>
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div>
                <h2><?php echo __('Danh sách các sản phẩm feedback'); ?></h2>
            </div>
            <table class="table table-advance" id="table1">
                <thead>
                <tr>
                    <th style="width:18px"><input type="checkbox"/></th>
                    <th><?php echo __('Tên sản phẩm'); ?></th>
                    <th class="text-center"><?php echo __('Ngày feedback'); ?></th>
                    <th class="text-center"><?php echo __('Người feedback'); ?></th>
                    <th class="text-center"><?php echo __('Lý do'); ?></th>
                    <th class="text-center"><?php echo __('Loại sử lý yêu cầu'); ?></th>
                    <th class="text-center"><?php echo __('Định dang trả về'); ?></th>
                    <th style="width:100px"><?php echo __('Hành động'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
//                                debug($list_product_feedback);die;
                foreach ($list_product_feedback as $item):
                    ?>
                    <tr class="table-flag">
                        <td><input value="<?php echo $item['Productaction']['id']; ?>" type="checkbox" name="ck[]"/>
                        </td>
                        <td><?php echo $item['Product']['name_file_product']; ?></td>
                        <td class="text-center"><?php
                            if ($item['Productaction']['date_feedback'] != null) {
                                $date = explode(' ', $item['Productaction']['date_feedback']);
                                $new_date = explode('-', $date[0]);
                                echo $new_date[2] . '/' . $new_date[1] . '/' . $new_date[0];
                            }
                            ?></td>
                        <td class="text-center"><?php echo $item['User']['name'] ?></td>
                        <td class="text-center">
                            <?php echo $item['Note']['note'] ?>
                        </td>
                        <td class="text-center">
                            <?php echo ($item['Processtype']['name'] != null) ? $item['Processtype']['name'] : ''; ?>
                        </td>
                        <td class="text-center">
                            <?php echo ($item['Extension']['name'] != null) ? $item['Extension']['name'] : ''; ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a data-product="<?php echo $item['Productaction']['product_id']; ?>" data-process="<?php echo $item['Productaction']['process_type_id'];  ?>" data-extension="<?php echo $item['Extension']['id'];?>"
                                   class="btn btn-small btn-primary show-tooltip save_feedback" title="Đồng ý" href="#">
                                    <i class="icon-ok"></i>
                                </a>
                                <a data-product="<?php echo $item['Productaction']['product_id']; ?>" class="btn btn-small btn-danger show-tooltip cancel_feedback" title="Hủy" href="#"><i
                                        class="icon-remove"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div>
                <h2><?php echo __('Danh sách các sản phẩm trả lại'); ?></h2>
            </div>
            <table class="table table-advance" id="table1">
                <thead>
                <tr>
                    <th style="width:18px"><input type="checkbox"/></th>
                    <th><?php echo __('Tên sản phẩm'); ?></th>
                    <th class="text-center"><?php echo __('Ngày trả'); ?></th>
                    <th class="text-center"><?php echo __('Người trả'); ?></th>
                    <th  class="text-center"  style="width:500px"><?php echo __('Lý do'); ?></th>
                    <th style="width:100px"><?php echo __('Hành động'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
//                debug($list_product_return);die;
                foreach ($list_product_return as $item):
                    ?>
                    <tr class="table-flag">
                        <td><input value="<?php echo $item['ProductReturn']['id']; ?>" type="checkbox" name="ck[]"/>
                        </td>
                        <td><?php echo $item['Product']['name_file_product']; ?></td>
                        <td class="text-center"><?php
                            if ($item['ProductReturn']['date_return'] != null) {
                                $date = explode(' ', $item['ProductReturn']['date_return']);
                                $new_date = explode('-', $date[0]);
                                echo $new_date[2] . '/' . $new_date[1] . '/' . $new_date[0];
                            }
                            ?></td>
                        <td class="text-center"><?php echo $item['User']['name'] ?></td>
                        <td class="text-center"><?php echo get($item['ProductReturn'],'note') ?></td>
                        <td>
                            <div class="btn-group">
                                <?php if ($item['ProductReturn']['status'] == ProductReturn::STATUS_WANT_RETURN) { ?>
                                    <a class="btn btn-small btn-primary show-tooltip save_return" data-product-return="<?php echo $item['ProductReturn']['id'];?>" title="Đồng ý" href="#">
                                    <i class="icon-ok"></i>
                                </a>
                                <a  data-product-return="<?php echo $item['ProductReturn']['id'];?>"  class="btn btn-small btn-danger show-tooltip cancel_return" title="Hủy" href="#"><i
                                        class="icon-remove"></i>
                                <?php }?>
                                
                                
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
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
<script>
    $(document).ready(function () {
        $('.save_feedback').click(function () {
            $('.fixed_bottom').show();
            var product_id = $(this).attr('data-product');
            var process_type_id = $(this).attr('data-process');
            var product_extension_id = $(this).attr('data-extension');
            $.post("<?php echo Router::url(array('action' => 'save_feedback')); ?>", { 'product_id': product_id,'process_type_id' :process_type_id,'product_extension_id':product_extension_id })
                .done(function (data) {
                    if (data) {
                        $('.fixed_bottom').hide();
                        window.location.reload();
                    } else {

                    }
                });
        });
        $('.cancel_feedback').click(function () {
            $('.fixed_bottom').show();
            var product_id = $(this).attr('data-product');
            $.post("<?php echo Router::url(array('action' => 'cancel_feedback')); ?>", { 'product_id': product_id})
                .done(function (data) {
                    if (data) {
                        $('.fixed_bottom').hide();
                        window.location.reload();
                    } else {

                    }
                });
        });
        $('.save_return').click(function () {
            $('.fixed_bottom').show();
            var product_return_id = $(this).attr('data-product-return');
            $.post("<?php echo Router::url(array('action' => 'save_return')); ?>", { 'product_return_id': product_return_id, 'state' : 1})
                .done(function (data) {
                    if (data) {
                        $('.fixed_bottom').hide();
                        alert('Thành công! Sản phẩm đã hoàn tất trả lại.')
                        window.location.reload();
                    } else {

                    }
                });
        });
        
        $('.cancel_return').click(function () {
            $('.fixed_bottom').show();
            var product_return_id = $(this).attr('data-product-return');
            $.post("<?php echo Router::url(array('action' => 'save_return')); ?>", { 'product_return_id': product_return_id, 'state' : 0})
                .done(function (data) {
                    if (data) {
                        $('.fixed_bottom').hide();
                        alert('Thành công! ')
                        window.location.reload();
                    } else {

                    }
                });
        });
    });

</script>
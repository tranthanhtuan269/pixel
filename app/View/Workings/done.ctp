<?php
echo $this->element('top_page', array(
    'page_title' => 'Trả hàng',
));
$file_size = floor($done_product['Product']['file_size'] / (1024));

?>
<div id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/"><?php echo __('Trang chủ'); ?></a>
            <span class="divider"><i class="icon-angle-right"></i></span>
        </li>
        <li>
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><?php echo __('Danh sách sản phẩm được giao'); ?></a>
            <span class="divider"><i class="icon-angle-right"></i></span>
        </li>
        <li class="active">
            <?php echo __('Trả hàng'); ?>
        </li>
    </ul>
</div>
<?php echo $this->Session->flash(); ?>
<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i><?php echo __('Trả hàng'); ?></h3>

                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <div class="span12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title"><?php echo __('Thông tin sản phẩm:'); ?></h4>
                        </div>
                        <div class="panel-body">
                            <div class="span4">
                                <?php
                                $file_name = explode('.', $done_product['Product']['name_file_product']);
                                if ($file_size < 10 && isset($PE[$file_name[count($file_name) - 1]])) {
                                    ?>
                                    <img style="width: 250px;height: 200px;border: 1px solid #808080;padding: 5px"
                                         src="<?php echo $domain . (str_replace('@', '/', $dir . $done_product['Product']['url'] . '@' . $done_product['Product']['name_file_product'])) ?>">

                                    <?php
                                } else {
                                    ?>
                                    <img style="width: 250px;height: 200px;border: 1px solid #808080;padding: 5px"
                                         src="http://placehold.it/200x200">

                                    <?php
                                }
                                ?>
                            </div>
                            <div class="span4">
                                <div>
                                    <lable
                                        class="name"><b><?php echo __('Quốc gia: '); ?></b>
                                    </lable><?php
                                    echo $country; ?>
                                    <div style="clear: both"></div>
                                </div>
                                <hr>
                                <div>
                                    <lable
                                        class="name"><b><?php echo __('Dự án: '); ?></b>
                                    </lable><?php echo $done_product['Project']['Name']; ?>
                                    <div style="clear: both"></div>
                                </div>
                                <hr>
                                <div>
                                    <lable
                                        class="name"><b><?php echo __('Định dạng trả về: '); ?></b>
                                    </lable><?php echo $done_product['Productextension']['name']; ?>
                                    <div style="clear: both"></div>
                                </div>
                            </div>
                            <div class="span4">
                                <div>
                                    <lable
                                        class="name"><b><?php echo __('File: '); ?></b>
                                    </lable><?php echo $done_product['Product']['name_file_product']; ?>
                                    <div style="clear: both"></div>
                                </div>
                                <hr>
                                <div>
                                    <lable
                                        class="name"><b><?php echo __('Dung lượng: '); ?></b>
                                    </lable><?php
                                    echo round($done_product['Product']['file_size'] / (1024 * 1024), 2) . ' MB';
                                    ?>
                                    <div style="clear: both"></div>
                                </div>
                                <hr>
                                <div>
                                    <lable
                                        class="name"><b><?php echo __('Ngày nhận: '); ?></b>
                                    </lable><?php echo $done_product['Product']['deliver_date']; ?>
                                    <div style="clear: both"></div>
                                </div>
                            </div>
                            <div style="clear: both;"></div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="span12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h4 class="panel-title"><?php echo __('Trả hàng:'); ?></h4>
                            </div>
                            <div class="panel-body">
                                <?php echo $this->Form->create('Working', array(
                                    'action' => 'done',
                                    'onsubmit' => 'return check_file_name();',
                                    'type' => 'file'
                                )); ?>
                                <div class="control-group">
                                    <label class="control-label" for="name"><?php echo __('Sản phẩm hoàn thiện'); ?>
                                        :</label>
                                    <div style="display: none">
                                        <input type="radio" value="1" name="done_type" checked="checked"> Upload file
                                        trên web
                                        <br>
                                        <input type="radio" value="2" name="done_type"> Upload file qua FTP
                                    </div>
                                    <div class="controls">
                                        <div class="span12" id="type_1">
                                            <?php echo $this->Form->input('name_file_product', array(
                                                'type' => 'file',
                                                'class' => 'input-xlarge',
                                                'label' => false,
                                                'data-rule-required' => 'true'
                                            )) ?>
                                        </div>
                                        <div class="span12" id="type_2" style="display: none">
                                            <b style="color: green">Bạn hãy upload file qua FTP vào thư mục:
                                                /<?php echo $done_product['Project']['Name']; ?>/</b>
                                            <br>
                                            <b style="color: royalblue">Sau khi upload hoàn thành thì click xác nhận để
                                                tiếp tục</b>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="name"><?php echo __('Xử lý'); ?>:</label>

                                    <div class="controls">
                                        <div class="span12">
											<span
                                                style="color: red;font-weight: bold;position: absolute;margin-top: 5px;"><?php echo $done_product['Processtype']['name']; ?></span>
                                            <?php echo $this->Form->input('process_type_id', array(
                                                'type' => 'hidden',
                                                'value' => $done_product['Processtype']['id'],
                                                'label' => false
                                            )) ?>
                                            <?php echo $this->Form->input('url', array(
                                                'type' => 'hidden',
                                                'value' => $done_product['Project']['UrlFolder'],
                                                'label' => false
                                            )) ?>
                                            <?php echo $this->Form->input('project_id', array(
                                                'type' => 'hidden',
                                                'value' => $done_product['Project']['ID'],
                                                'label' => false
                                            )) ?>
                                            <?php echo $this->Form->input('product_id', array(
                                                'type' => 'hidden',
                                                'value' => $done_product['Product']['id'],
                                                'label' => false
                                            )) ?>
                                            <?php echo $this->Form->input('done', array(
                                                'type' => 'hidden',
                                                'value' => $done,
                                                'label' => false
                                            )) ?>
                                            <?php echo $this->Form->input('format_return', array(
                                                'type' => 'hidden',
                                                'value' => $done_product['Productextension']['name'],
                                                'label' => false
                                            )) ?>
                                            <?php echo $this->Form->input('product_extension_id', array(
                                                'type' => 'hidden',
                                                'value' => $done_product['Productextension']['id'],
                                                'label' => false
                                            )) ?>
                                            <?php echo $this->Form->input('name_file_old', array(
                                                'type' => 'hidden',
                                                'value' => $done_product['Product']['name_file_product'],
                                                'label' => false
                                            )) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="name"><?php echo __('Số xử lý'); ?>:</label>

                                    <div class="controls">
                                        <div class="span12">
                                            <?php echo $this->Form->input('number_process', array(
                                                'type' => 'text',
                                                'value' => '1',
                                                'class' => 'input-xlarge',
                                                'label' => false,
                                                'data-rule-required' => 'true'
                                            )) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Loại danh mục'); ?></label>

                                    <div class="controls">
                                        <?php
                                        echo $this->Form->input('product_category_id', array(
                                            'type' => 'select',
                                            'label' => false,
                                            'class' => 'select2-choice select2-default',
                                            'options' => $list_product_category,
                                            'data-rule-required' => 'true'
                                        ));
                                        ?>

                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Loại sản phẩm'); ?></label>

                                    <div class="controls">
                                        <?php
                                        echo $this->Form->input('product_type_id', array(
                                            'type' => 'select',
                                            'label' => false,
                                            'class' => 'select2-choice select2-default',
                                            'options' => $list_product_type,
                                            'data-rule-required' => 'true'
                                        ));
                                        ?>

                                    </div>
                                </div>
                                <div class="form-actions">
                                    <input type="submit" class="btn btn-primary" value="<?php echo __('Xác nhận') ?>">
                                    <a href="<?php echo Router::url(array(
                                        'controller' => 'workings',
                                        'action' => 'index'
                                    )); ?>">
                                        <button type="button" class="btn"><?php echo __('Quay lại') ?></button>
                                    </a>
                                </div>
                                <?php echo $this->Form->end(); ?>

                            </div>
                        </div>
                    </div>
                </div>
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
<!-- END Main Content -->

<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
<script>
    function check_file_name() {
        var filename = $('input[name="data[Working][name_file_product]"]').val();
        var ol_file = $('input[name="data[Working][name_file_old]"]').val();
        var return_ext = $('input[name="data[Working][format_return]"]').val();
        var err = "";
        if ($('input[name=done_type]:checked').val() == 2) {

        } else {
            if (filename != "") {
                var file_paths = filename.split("\\");
                var old_file_paths = ol_file.split(".");
                var new_file_name = file_paths[file_paths.length - 1];
                var file_ext = new_file_name.split(".");
                var old_file_name_only = "";
                for (var i = 0; i < old_file_paths.length - 1; i++) {
                    old_file_name_only += old_file_paths[i] + '.';
                }

                var new_file_name_only = "";

                for (var i = 0; i < file_ext.length - 1; i++) {
                    new_file_name_only += file_ext[i] + '.';
                }
                if ($('input[name="data[Working][product_extension_id]"]').val() != 11) {
                    if (file_ext[file_ext.length - 1].toUpperCase() != return_ext.toUpperCase()) {
                        err = "file trả không đúng định dạng";
                    }
                }

                if ((new_file_name_only) != (old_file_name_only)) {
                    err += "File trả hàng không đúng tên"
                }

                if (err != "") {
                    alert(err);
                    return false;
                }
            } else {

                alert("Bạn phải chọn sản phẩm để done");
                return false;
            }
        }
        return true;
    }

    $(document).ready(function () {
        $('input[name=done_type]').click(function () {
            if ($(this).val() == 2) {
                $('#type_' + $(this).val()).show();
                $('#type_1').hide();
            } else {
                $('#type_2').hide();
                $('#type_1').show();
            }
        });
    });
</script>
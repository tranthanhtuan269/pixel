<?php
echo $this->element('top_page', array(
    'page_title' => 'Chuyển người làm',
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
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><?php echo __('Danh sách sản phẩm được giao'); ?></a>
            <span class="divider"><i class="icon-angle-right"></i></span>
        </li>
        <li class="active">
            <?php echo __('Chuyển người làm'); ?>
        </li>
    </ul>
</div>
<?php //debug($done_product);die;?>
<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i><?php echo __('Thông tin cần chuyển'); ?></h3>

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
                                <label><?php echo __('<b>Sản phẩm cần chuyển:</b>');?></label>
                                <img style="width: 250px;height: 200px;border: 1px solid #808080;padding: 5px"
                                     src="<?php echo '/' . (str_replace('@', '/', $dir . $done_product['Product']['url'] . '@' . $done_product['Product']['name_file_product'])) ?>">
                            </div>
                            <?php echo $this->Form->create('Working', array('onsubmit' => 'return check_file_name();','action'=> 'change/'.$done_product['Product']['id'],'class' => 'form-horizontal','type' => 'file')); ?>
                            <div class="span8">
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Người cần chuyển'); ?>:</label>

                                    <div class="controls">
                                        <div class="span12">
                                            <textarea name="NV_ten" id="NV_ten" readonly="true" class="input-xlarge" rows="2"></textarea>
                                            <input type="hidden" value="" name="NV_ID" id="NV_ID" data-rule-required="true"/>
                                        </div>
                                        <div class="span12">
                                            <a data-toggle="modal" class="btn select-checkbox-user" onclick="getNVS()"
                                               role="button" href="#modal-1"><?php echo __('Chọn nhân viên'); ?></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="name"><?php echo __('File chuyển'); ?>
                                        :</label>
                                    <div class="controls">
                                        <div class="span12">
                                            <?php echo $this->Form->input('name_file_product', array('type' => 'file', 'class' => 'input-xlarge', 'label' => false,'data-rule-required' => 'true')) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"><?php echo __('Ghi chú:'); ?>:</label>
                                    <div class="controls">
                                        <?php echo $this->Form->textarea('note', array('class' => 'input-xlarge', 'div' => false, 'label' => false)); ?>
                                        <?php echo $this->Form->input('product_id',array('type'=>'hidden','value'=>$done_product['Product']['id'] ));?>
                                        <?php echo $this->Form->input('url',array('type'=>'hidden','value'=>$done_product['Product']['url'] ));?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions text-center">
                                <input type="submit" class="btn btn-primary" value="<?php echo __('Xác nhận') ?>">
                                <button type="button" class="btn"><?php echo __('Hủy') ?></button>
                            </div>
                            <?php echo $this->Form->end(); ?>
                            <div style="clear: both;"></div>
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
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="modal-1" style="display: none;">
</div>
<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
<script>
    function getNVS() {
        $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'SelectUsersSetpd'),true)?>/"+<?php echo $done_product['Project']['ID']?>, function (data) {
            $("#modal-1").html(data);
        });
    }
    function check_file_name() {
        var filename = $('input[name="data[Working][name_file_product]"]').val();
        var ol_file = "<?php echo($done_product['Product']['name_file_product'])?>";
        var return_ext = "<?php echo($done_product['Productextension']['name'])?>";
        var err = "";
        if (filename != "") {
            var file_paths = filename.split("\\");
//            var old_file_paths = ol_file.split(".");
            var new_file_name = file_paths[file_paths.length - 1];

            if(new_file_name != ol_file){
                err = " File tranfer không đúng tên."
            }


            if(err!=""){
                alert(err);
                return false;
            }
        }else{
            alert('Chọn file cần chuyển');
            return false;
        }
    }

</script>
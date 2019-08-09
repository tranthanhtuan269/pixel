<?php echo $this->Form->create('Project', array('url' => array('controller' => 'Projects', 'action' => 'changefile', $product['Product']['id']), 'class' => 'form-horizontal form-bordered', 'type' => 'file')) ?>
<div class="modal-content">
    <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
        <h3 id="myModalLabel1">Upload file thay thế</h3>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label" for="textfield4">Tên sản phẩm</label>

            <div class="col-sm-9 col-lg-10 controls">
                <p class="form-control-static"><?php echo $done_pd['DoneProduct']['name_file_done'] ?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label" for="textfield4">File thay thế</label>

            <div class="col-sm-9 col-lg-10 controls">
                <?php echo $this->Form->input('File', array('type' => 'file', 'div' => false, 'label' => false, 'class' => 'form-control', 'id' => 'image_file')); ?>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button aria-hidden="true" data-dismiss="modal" class="btn">Close</button>
        <button class="btn btn-primary submitchange">Thay thế</button>
    </div>
</div>
<?php echo $this->Form->end(); ?>

<script>
    $(function () {
        $('.submitchange').click(function () {
            var filename = $('#image_file').val();
            var res = filename.split("\\");

//            alert(res[res.length-1]);
            if(filename == ''){
                alert('Bạn phải chọn file thay thế');
                return false;
            }
            if (res[res.length - 1] == '<?php echo $done_pd['DoneProduct']['name_file_done']?>') {
                $("#ProjectChangefileForm").submit();
            } else {
                alert('Tên file thay thế phải trùng với tên sản phẩm');
                return false;
            }
        });
    });
</script>
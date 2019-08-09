<div class="row-fluid">
    <div class="span6" style="background-image: url('<?php echo $domain.str_replace('@', '/', $dir . $product['Product']['url'] . '@' . $product['Product']['name_file_product']); ?>');background-repeat: no-repeat;background-size: 100% auto"></div>
    <div class="span6" id="form-update">
        <?php echo $this->Form->create('Product',array('url'=>array('controller'=>'Products','action'=>'updateProduct',$product['Product']['id']),'type'=>'file'))?>
        <table class="table">
            <tr>
                <td>Ngày giao</td>
                <td><?php echo $product['Product']['deliver_date']?></td>
            </tr>
            <tr>
                <td>Ngày hoàn thành</td>
                <td><?php echo $product['Product']['date_of_completion']?></td>
            </tr>
            <tr>
                <td>Ngưới giao</td>
                <td><?php echo $product['Deliver']['name']?></td>
            </tr>
            <tr>
                <td>Ngưới làm</td>
                <td><?php echo $product['Performer']['name']?></td>
            </tr>
            <tr>
                <td>Loại xử lý</td>
                <td><?php echo $product['Processtype']['name']?></td>
            </tr>
            <tr>
                <td>Thông tin xử lý</td>
                <td><?php echo $this->Form->input('processinfo',array('div'=>false,'label'=>false,'rows'=>3,'value'=>$product['Product']['note_product']));?></td>
            </tr>
<!--            <tr>-->
<!--                <td>Thông tin chuyển</td>-->
<!--            </tr>-->
            <tr>
                <td>Đính kèm</td>
                <td><?php echo $this->Form->input('file',array('div'=>false,'label'=>false,'type'=>'file'));?></td>
            </tr>
<!--            <tr>-->
<!--                <td>Reject</td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td>Lý do</td>-->
<!--            </tr>-->
            <tr>
                <td colspan="2">
                    <button type="submit"  class="btn btn-primary" id="submit-form" >Lưu lại</button>
                </td>
            </tr>
        </table>
    </div>
</div>
<?php $this->Form->end()?>
<?php echo $this->Html->script(array('jquery.form'));?>
<script>
    $(function(){
        $('form#ProductGetproductinfoForm').submit(function () {
//            return false;
            $(this).ajaxSubmit({
                url: '<?php echo $this->Html->url(array('controller' => 'Products', 'action' => 'updateProduct',$product['Product']['id']), true);?>',
                type: 'post',
                dataType: 'json',
                cache: false,
                success: function (data) {
                    if(data.resultCode==1 || data.resultCode=='1'){
                        $('#form-update').html(data.resultMsg);
                    }else{
                        alert(data.resultMsg);
                    }
                },
                error: function () {
                    alert('<?php echo ('Không thể thực hiện được yêu cầu đến máy chủ. Hãy thử lại.'); ?>');
                },
                complete: function () {
                }
            });
            return false;
        });
    });
</script>
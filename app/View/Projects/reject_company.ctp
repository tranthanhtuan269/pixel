<?php echo $this->Form->create('Project',array('url'=>array('controller'=>'Projects','action'=>'rejectCompany',$product['Product']['id']),'class'=>'form-horizontal form-bordered','type'=>'file'))?>
<div class="modal-content">
    <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
        <h3 id="myModalLabel1">Reject sản phẩm cấp công ty</h3>
    </div>
    <div class="modal-body">
        <div class="row-fluid">
            <div class="span4">
                <img src="<?php echo $domain.str_replace('@', '/', $dir . $product['Product']['url'] . '@' . $product['Product']['name_file_product'])?>" />
            </div>
            <div class="span8">
                <div class="row-fluid">
                    <div class="span5">Tên sản phẩm</div>
                    <div class="span7"><?php echo $product['Product']['name_file_product']?></div>
                </div>
                <div class="row-fluid">
                    <div class="span5">Dự án</div>
                    <div class="span7"><?php echo $product['Project']['Name']?></div>
                </div>
                <div class="row-fluid">
                    <div class="span5">Ngày giao</div>
                    <div class="span7"><?php echo $product['Product']['deliver_date']?></div>
                </div>
                <div class="row-fluid">
                    <div class="span5">Ngày hoàn thành</div>
                    <div class="span7"><?php echo $product['Product']['date_of_completion']?></div>
                </div>
                <div class="row-fluid">
                    <div class="span5">Người làm</div>
                    <div class="span7"><?php echo $product['Deliver']['name']?></div>
                </div>
                <div class="row-fluid">
                    <div class="span5">Thông tin trả hàng</div>
                    <div class="span7">
                        <?php echo $this->Form->input('Note',array('div'=>false,'label'=>false,'type'=>'textarea','row'=>2,'class'=>'span12'));?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button aria-hidden="true" data-dismiss="modal" class="btn">Close</button>
        <button class="btn btn-primary submitreject">Reject</button>
    </div>
</div>
<?php echo $this->Form->end();?>

<script>
    $(function(){
        $('.submitreject').click(function(){
            $("#ProjectRejectCompanyForm").submit();
        });
    });
</script>
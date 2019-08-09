<div class="modal-header">
    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
    <h3 id="myModalLabel">Chọn người dùng trong danh sách bên dưới</h3>
</div>
<div class="modal-body">

        <table width="100%" border="0">
            <?php foreach ($departments as $department) {
                $countNV = isset($dp[$department['Department']['id']])?count($dp[$department['Department']['id']]):0;
                ?>
                <tr>
                    <td height="25" class="Text_12_black" style="border-top: #CCCCCC 1px dotted;">
                        <?php if ($countNV > 0) { ?>
                            <a onclick="togg1(<?php echo $department['Department']['id'] ?>)">
                                <?php echo $this->html->image('add-1.png',array('id'=>"treePB1{$department['Department']['id']}",'width'=>"16", 'height'=>"16",'border'=>"0"))?>
                            </a>
                        <?php } else {?>
                            <a>
                                <?php echo $this->html->image('sub.png',array('id'=>"treePB1{$department['Department']['id']}",'width'=>"16", 'height'=>"16",'border'=>"0"))?>
                            </a>
                        <?php } ?>
                        <input type="checkbox" name="PBID1" id="PBID1<?php echo $department['Department']['id'] ?>" title="<?php echo $department['Department']['name'] ?>" value="<?php echo $department['Department']['id'] ?>" onclick="checkall1(<?php echo $department['Department']['id'] ?>);" />
                        <span class="department_name"> <?php echo $department['Department']['name'] ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table id="listNS1<?php echo $department['Department']['id'] ?>" width="100%" border="0" cellpadding="0" cellspacing="0" style="display: none;">
                            <?php $i = 0;
                            if(isset($dp[$department['Department']['id']])){
                                foreach ($dp[$department['Department']['id']] as $user) {
                                    //if($user['Department']['ID'] == $department['Department']['ID']){
                                    $i++; ?>
                                    <tr>
                                        <td  style="padding-left: 25px" height="20px"> &nbsp;&nbsp;&nbsp;<input name="NSID1" height="20" class="cl1_<?php echo $department['Department']['id'] ?>" id="NSID1" title="<?php echo $user['name'] ?>" type="checkbox" value="<?php echo $user['id'] ?>" />
                                            <b class="user-name"><?php echo $user['name'] ?></b>
                                        </td>
                                    </tr>
                                <?php }} ?>
                        </table>
                    </td>
                </tr>
            <?php } ?>
        </table>

</div>
<div class="modal-footer">
    <button aria-hidden="true" data-dismiss="modal" class="btn">Hủy</button>
    <button data-dismiss="modal"  onclick="GetNS1();document.getElementById('frm-dial1').reset();" class="btn btn-primary">Chọn</button>
</div>

    <script type="text/javascript">
        var type = 0;
        function setType(val){
            type = val;
        }
        function GetNS1() {
            var NSvals1 = [];
            var NSnames1 = [];
            var PBvals1 = [];
            var PBnames1 = [];
            i = 0;
            $("input[name='NSID1'][type='checkbox']:checked").each(function() {
                NSname1 = jQuery(this).attr('title');
                NSval1 = jQuery(this).val();
                NSnames1[i] = jQuery(this).attr('title');
                NSvals1[i] = jQuery(this).val();
                i++;
            });
            i=0;
            $("input[name='PBID1'][type='checkbox']:checked").each(function() {
                PBnames1[i] = jQuery(this).attr('title');
                PBvals1[i] = jQuery(this).val();
                i++;
            });
            // Truong hop chon nhom nguoi nguoi phoi hop xu ly
            // dùng number để dùng cho chọn nhiều nv trong 1 trang
            <?php if($number == null){
                ?>
            $('#NV_ID').val(NSvals1);
            $('#NV_ten').val(NSnames1);
            <?php
            }?>
            <?php if($number == 1){
               ?>
            $('#NV_ID_1').val(NSvals1);
            $('#NV_ten_1').val(NSnames1);
            <?php
            }?>
            <?php if($number == 2){
               ?>
            $('#NV_ID_2').val(NSvals1);
            $('#NV_ten_2').val(NSnames1);
            <?php
            }?>
            <?php if($number == 3){
               ?>
            $('#NV_ID_3').val(NSvals1);
            $('#NV_ten_3').val(NSnames1);
            <?php
            }?>
        }

        function togg1(id){

            st = $('#listNS1' + id).css('display');
            //alert(id);
            if(st == "none"){
                $('#listNS1' + id).css('display', '');
                $('#treePB1' + id).attr('src', '../img/sub.png' );

            }else{
                $('#listNS1' + id).css('display', 'none');
                $('#treePB1' + id).attr('src', '../img/add-1.png' );
            }
        }

        function checkall1(id){
            if($('#PBID1' + id).attr('checked')){
                chk = 'true';
            }else{
                chk = '';
            }
            //alert(chk);
            $("input[class='cl1_" + id + "'][type='checkbox']").each(function() {
                jQuery(this).attr('checked',chk);
            });
        }
    </script>
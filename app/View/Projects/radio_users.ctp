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
                                        <td  style="padding-left: 25px" height="20px"> &nbsp;&nbsp;&nbsp;
                                            <input name="NSID1" height="20" class="cl1_<?php echo $department['Department']['id'] ?>" id="NSID1" title="<?php echo $user['name'] ?>" type="radio" value="<?php echo $user['id'] ?>" />
                                            <b class="user-name"><?php echo $user['name'].'('.$user['dg'].'/'.$user['dl'].')'; ?></b>
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

<div style="display: none">
    <?php echo $this->element('sql_dump'); ?>

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
            $("input[name='NSID1'][type='radio']:checked").each(function() {
                NSname1 = jQuery(this).attr('title');
                NSval1 = jQuery(this).val();
                NSnames1[i] = jQuery(this).attr('title');
                NSvals1[i] = jQuery(this).val();
                i++;
            });
            i=0;
            $("input[name='PBID1'][type='radio']:checked").each(function() {
                PBnames1[i] = jQuery(this).attr('title');
                PBvals1[i] = jQuery(this).val();
                i++;
            });
            // Truong hop chon nhom nguoi nguoi phoi hop xu ly
            for(var k = 1; k<10;k++){
               if(k == <?php echo $number;?>){
                   $('#id_user-'+k).val(NSvals1);
                   $('#name_user-'+k).html(NSnames1);
               }
            }
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
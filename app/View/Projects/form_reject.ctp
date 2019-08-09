<div class="modal-header">
    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
    <h3 id="myModalLabel">Thông tin Reject </h3>
</div>
<?php echo $this->Form->create('Reject', array('class' => 'form-horizontal validation-form', 'type' => 'file', 'action' => 'add')); ?>
<div class="modal-body">
    <div class="box">
        <div class="box-content">
            <div class="control-group">
                <label class="control-label"><b><?php echo __('Tên đơn hàng'); ?>:</b></label>

                <div class="controls">
                    <div class="span12" id="info_project">
                        <?php echo $project['Project']['Name']; ?>
                        <?php echo $this->Form->input('project_id', array('type' => 'hidden', 'value' => $project['Project']['ID'], 'div' => false, 'label' => false)); ?>
                        <?php echo $this->Form->input('link', array('type' => 'hidden', 'value' => "", 'div' => false, 'label' => false,'id'=>'link')); ?>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <?php if (isset($product)){?>
                <label class="control-label"><b><?php echo __('Tên file'); ?>:</b></label>

                <div class="controls">
                    <div class="span12">
                        <?php echo $this->Form->input('product_id', array('type' => 'hidden', 'id' => 'product_id_reject', 'value' => $product['Product']['id'], 'div' => false, 'label' => false)); ?>
                        <div id="product_name_file"><?php if (isset($product)) {
                                echo $product['Product']['name_file_product'];
                            }?></div>
                    </div>
                </div>
                <?php }?>
            </div>
            <div class="control-group">
                <label class="control-label"><?php echo __('Loại reject'); ?></label>

                <div class="controls">
                    <div class="span12">
                        <?php
                            echo $this->Form->input('rate_point_id', array(
                                    'type' => 'select',
                                    'options' => $ratePoint,
                                    'empty' => '--Chọn loại reject--',
                                    'label' => false
                                )
                            );


                        ?>

                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><?php echo __('Nhóm nghiệp vụ'); ?></label>

                <div class="controls">
                    <div class="span12">
                        <?php
                        echo $this->Form->input('large_group_id', array(
                                'type' => 'select',
                                'options' => $LargeGroup,
                                'empty' => '--Chọn nhóm nghiệp vụ--',
                                'onchange' => 'getLarge(this.value)',
                                'label' => false
                            )
                        );

                        ?>

                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><?php echo __('Nhóm làm việc'); ?></label>

                <div class="controls" id="working_group">
                    <div class="span12">
                        <?php
                        echo $this->Form->input('working_group_id', array(
                                'type' => 'select',
                                'empty' => '--Chọn nhóm làm việc--',
                                'label' => false
                            )
                        );

                        ?>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><?php echo __('Nội dung Reject'); ?>:</label>

                <div class="controls">
                    <div class="span4">
                        <?php echo $this->Form->textarea('desc', array('style' => 'height:150px', 'class' => 'span4'));
                        ?>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><?php echo __('File đính kèm'); ?>:</label>

                <div class="controls">
                    <?php echo $this->Form->input('file', array('type' => 'file', 'class' => 'span12', 'div' => false, 'label' => false)); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="span8"><?php echo __('Danh sách nhân viên liên quan'); ?>:</label>
                <table class="table table-advance" id="table1">
                    <thead>
                    <tr>
                        <th class="text-center"><?php echo __('Tên nhân viên'); ?></th>
                        <th class="text-center"><?php echo __('Công việc'); ?></th>
                        <th class="text-center"><?php echo __('Trách nhiệm'); ?></th>
                        <th class="text-center"><?php echo __('Số lần bị reject'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $stt = 0;
//                    debug($userpoints);die;
                    foreach ($userpoints as $k => $userpoint) {
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $userpoint['User']['name']; ?><input type="hidden" value="<?php echo $userpoint['User']['id']; ?>" name="user[<?php echo $stt;?>]"/></td>
                            <td class="text-center"><?php echo $userpoint['Action']['Name'] ;?><input type="hidden" value="<?php echo $userpoint['Action']['ID'] ;?>" name="action[<?php echo $stt;?>]"/></td>
                            <td class="text-center"><?php

                                $percent = array('-1' => '-10', '-2' => '-20', '-3' => '-30', '-4' => '-40', '-5' => '-50', '-6' => '-60', '-7' => '-70', '-8' => '-80', '-9' => '-90', '-10' => '-100','0'=>'0','0' => '0', '1' => '10', '2' => '20', '3' => '30', '4' => '40', '5' => '50', '6' => '60', '7' => '70', '8' => '80', '9' => '90', '10' => '100');
                                echo $this->Form->input('percent', array(
                                        'type' => 'select',
                                        'options' => $percent,
                                        'name' => 'percent[' . $stt . ']',
                                        'label' => false,
                                        'class' => 'span1 percent',
                                        'empty' => '-Chọn-'
                                    )
                                );
                                ?></td>
                            <td class="text-center">
                                <?php
//                                if (isset($product)){
//                                    $rs = $this->requestAction(Router::url(array('controller' => 'projects', 'action' => 'reject_check',$project['Project']['ID'], $userpoint['User']['id'],$userpoint['Action']['ID'],$product['Product']['id'])));
//                                }else{
                                    $rs = $this->requestAction(Router::url(array('controller' => 'projects', 'action' => 'reject_check',$project['Project']['ID'], $userpoint['User']['id'],$userpoint['Action']['ID'])));
//                                }
                                if (count($rs) > 0) {
//                                        if ($key == $userpoint['User']['id']) {
                                            echo count($rs) . ' lần';
                                            ?>
                                            <?php foreach ($rs as $a => $item) { ?>
                                                <div>
                                                    Lần <?php echo ($a + 1) . ':  ' . ($item['Reject']['percent'] * 10) . '% - ' . $item['Reject']['datetime']; ?></div>
                                            <?php
//                                            }
//                                        }
                                    }
                                } else {
                                    echo 'Chưa bị reject lần nào!';
                                }
                                ?>

                            </td>
                        </tr>
                        <?php $stt++;
                    }
                    ?>
<!--                    --><?php //debug($workings);die;?>
                    <?php foreach ($workings as $working){?>
                        <tr>
                            <td class="text-center"><?php echo $working['User']['name']; ?><input type="hidden" value="<?php echo $working['User']['id']; ?>" name="user[<?php echo $stt;?>]"/></td>
                            <td class="text-center"><?php echo ('Làm hàng') ;?><input type="hidden" value="100<?php //echo $working['Action']['ID'] ;?>" name="action[<?php echo $stt;?>]"/></td>
                            <td class="text-center"><?php

                                $percent = array('-1' => '-10', '-2' => '-20', '-3' => '-30', '-4' => '-40', '-5' => '-50', '-6' => '-60', '-7' => '-70', '-8' => '-80', '-9' => '-90', '-10' => '-100','0'=>'0', '1' => '10', '2' => '20', '3' => '30', '4' => '40', '5' => '50', '6' => '60', '7' => '70', '8' => '80', '9' => '90', '10' => '100');
                                echo $this->Form->input('percent', array(
                                        'type' => 'select',
                                        'options' => $percent,
                                        'name' => 'percent[' . $stt . ']',
                                        'label' => false,
                                        'class' => 'span1 percent',
                                        'empty' => '-Chọn-'
                                    )
                                );
                                ?></td>
                            <td class="text-center">
                                <?php
                                if (isset($product)){
                                    $rs = $this->requestAction(Router::url(array('controller' => 'projects', 'action' => 'reject_check',$project['Project']['ID'], $working['User']['id'],100,$product['Product']['id'])));
                                }else{
                                    $rs = $this->requestAction(Router::url(array('controller' => 'projects', 'action' => 'reject_check',$project['Project']['ID'], $working['User']['id'],100)));
                                }
                                if (count($rs) > 0) {
//                                        if ($key == $userpoint['User']['id']) {
                                    echo count($rs) . ' lần';
                                    ?>
                                    <?php foreach ($rs as $a => $item) { ?>
                                        <div>
                                            Lần <?php echo ($a + 1) . ':  ' . ($item['Reject']['percent'] * 10) . '% - ' . $item['Reject']['datetime']; ?></div>
                                        <?php
//                                            }
//                                        }
                                    }
                                } else {
                                    echo 'Chưa bị reject lần nào!';
                                }
                                ?>

                            </td>
                        </tr>
                    <?php $stt++;
                    }?>
                    <tr id="row-1">
                         <td class="text-center" id="name_user-1"></td>
                        <td class="text-center">Nhân viên bổ xung</td>
                        <td class="text-center"><?php $percent = array('-1' => '-10', '-2' => '-20', '-3' => '-30', '-4' => '-40', '-5' => '-50', '-6' => '-60', '-7' => '-70', '-8' => '-80', '-9' => '-90', '-10' => '-100','0' => '0', '1' => '10', '2' => '20', '3' => '30', '4' => '40', '5' => '50', '6' => '60', '7' => '70', '8' => '80', '9' => '90', '10' => '100');  echo $this->Form->input('percent', array('type' => 'select','options' => $percent, 'name' => 'percent_add[1]','label' => false,'class' => 'span1 percent','empty' => '-Chọn-' ) ); ?></td>
                        <td class="text-center">
                            <input type="hidden" value="" name="user_add[1]" id="id_user-1"/>
                            <a data-toggle="modal" id="click_1" onclick="click_NV(1)" class="btn select-checkbox-user"
                               role="button" href="#modal-1">Chọn nhân viên</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="control-group">
                <label class="control-label"><?php echo __('Nhân viên nhận thông báo bổ xung'); ?>:</label>

                <div class="controls">
                    <div class="span4">
                        <textarea name="NV_ten_2" id="NV_ten_2" readonly="true" class="span4" rows="2"></textarea>
                        <input type="hidden" value="" name="NV_ID_2" id="NV_ID_2"/>
                    </div>
                    <div class="span6">
                        <a data-toggle="modal" class="btn select-checkbox-user" onclick="getNVS(2)"
                           role="button" href="#modal-1">Chọn nhân viên</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="submit" value="Reject" class="btn btn-primary">
    <button aria-hidden="true" data-dismiss="modal" class="btn">Close</button>
</div>
<?php echo $this->Form->end(); ?>
<script>
    var sm = 0;
    $('#RejectAddForm').submit(function() {
        $('.percent').each(function() {
            if($(this).val()!=""){
                sm=1;
            }
        });
        if(
            $('#RejectRatePointId').val()==''
//            || $('#RejectWorkingGroupId').val()==''
        ){
            alert('Chọn loại reject');
            return false;
        }else{
            if(sm == 0){
                alert('Chọn trách nhiệm');
                return false;
            }else{
                return true;
            }

        }
    });
    function click_NV(row){
     var   number = row + 1;

        getNVR(row);
        $('#row-'+row).after('<tr id="row-'+ number +'"><td class="text-center" id="name_user-'+ number+'"></td><td class="text-center">Nhân viên bổ xung</td><td class="text-center"><select name="percent_add['+number+']" class="span1 percent"><option value="">-Chọn-</option><option value="-1">-10</option><option value="-2">-20</option><option value="-3">-30</option><option value="-4">-40</option><option value="-5">-50</option><option value="-6">-60</option><option value="-7">-70</option><option value="-8">-80</option><option value="-9">-90</option><option value="-10">-100</option><option value="0">0</option><option value="1">10</option><option value="2">20</option><option value="3">30</option><option value="4">40</option><option value="5>50</option><option value="6">60</option><option value="7">70</option><option value="8">80</option><option value="9">90</option><option value="10">100</option></select></td><td class="text-center"><a data-toggle="modal" id="click_'+number+'" onclick="click_NV('+ number +')" class="btn select-checkbox-user" role="button" href="#modal-1">Chọn nhân viên</a><input type="hidden" value="" name="user_add['+number+']" id="id_user-'+ number +'"/></td></tr>')

    }


    function getLarge(id) {
        $.get("<?php echo $this->html->url(array('controller'=>'workinggroups','action'=>'getWorking'),true)?>/" + id, function (data) {
            $("#working_group").html(data);
        });
    }
    function getNVS(i) {
        $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'SelectUsers'),true)?>" + "/" + i, function (data) {
            $("#modal-1").html(data);
        });
    }
    function getNVR(i) {
        $("#modal-1").html('<div style="height: 500px;font-size: 18px;color: blue">loading...</div>');
        $('#click_'+i).html('Đang xử lý..');
        $.ajax({
            method: "GET",
            async:true,
            url: "<?php echo $this->html->url(array('controller'=>'Projects','action'=>'RadioUsers'),true)?>" + "/" + i
        })
            .done(function( data ) {
                $("#modal-1").html(data);
                $('#click_'+i).html('Chọn nhân viên');
            });
<!---->
<!--        $.get("--><?php //echo $this->html->url(array('controller'=>'Projects','action'=>'RadioUsers'),true)?><!--" + "/" + i, function (data) {-->
<!--            $("#modal-1").html(data);-->
<!--        });-->
    }
</script>
<?php
echo $this->element('top_page', array(
    'page_title' => 'Danh sách báo cáo',
));
?>
<div id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/"><?php echo __('Trang chủ'); ?></a>
            <span class="divider"><i class="icon-angle-right"></i></span>
        </li>
        <li class="active">
            <?php echo __('Báo cáo'); ?>
        </li>
    </ul>
</div>

<!-- BEGIN Main Content -->
<!--báo cáo hàng ngày của nhân viên-->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-bar-chart"></i> Báo cáo hàng ngày của nhân viên</h3>

                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-down"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <div class="box-content">
                    <?php echo $this->Form->create('Report', array('class' => 'form-horizontal validation-form ', 'action' => 'everyDay')); ?>
                    <div class="row-fluid">
                        <div class="span5">
                            <div class="control-group">
                                <label class="control-label">
                                    <?php echo __('Phòng ban'); ?>:</label>

                                <div class="controls">
                                    <?php echo $this->Form->input('Departments_id', array('class' => 'span12', 'options' => $departments, 'onchange' => 'getUsers(this.value)', 'empty' => '--Chọn phòng ban--', 'div' => false, 'label' => false)); ?>
                                </div>
                            </div>
                            <div class="control-group usersxxx">
                                <label class="control-label">
                                    <?php echo __('Nhân viên'); ?>:</label>

                                <div class="controls">
                                    <?php echo $this->Form->input('user_id', array('class' => 'span12', 'empty' => '--Chọn nhân viên--', 'div' => false, 'label' => false)); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="name"><?php echo __('Từ ngày'); ?>:</label>

                                <div class="controls">
                                    <?php echo $this->Form->input('from_date', array('placeholder' => '', 'type' => 'text', 'class' => 'input-small Date', 'label' => false, 'div' => false, 'data-rule-required' => 'true', 'id'=> 'daily_fromdate')) ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="name"><?php echo __('Đến ngày'); ?>:</label>

                                <div class="controls">
                                    <?php echo $this->Form->input('to_date', array('placeholder' => '', 'type' => 'text', 'class' => 'input-small Date', 'label' => false, 'div' => false, 'data-rule-required' => 'true', 'id'=> 'daily_todate')) ?>
                                </div>
                            </div>
                        </div>
                        <div class="span5">
                            <div class="control-group">
                                <label class="control-label">
                                    <?php echo __('Quốc gia'); ?>:</label>

                                <div class="controls">
                                    <?php echo $this->Form->input('Country_id', array('class' => 'span12', 'options' => $countries, 'onchange' => 'getCustomers(this.value)', 'empty' => '--Chọn quốc gia--', 'div' => false, 'label' => false)); ?>
                                </div>
                            </div>
                            <div class="control-group customer">
                                <label class="control-label">
                                    <?php echo __('Khách hàng'); ?>:</label>

                                <div class="controls">
                                    <?php echo $this->Form->input('Customer_id', array('class' => 'span12', 'options' => array('empty' => '--Chọn khách hàng--'), 'div' => false, 'label' => false)); ?>
                                </div>
                            </div>
                            <div class="control-group customer_group">
                                <label class="control-label">
                                    <?php echo __('Nhóm khách hàng'); ?>:</label>

                                <div class="controls">
                                    <?php echo $this->Form->input('CustomerGroup_id', array('class' => 'span12', 'options' => array('empty' => '--Chọn nhóm khách hàng--'), 'div' => false, 'label' => false)); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">
                                    <?php echo __('Loại xử lý'); ?>:</label>

                                <div class="controls">
                                    <?php echo $this->Form->input('ProcessType_id', array('class' => 'span12', 'options' => $processTypes, 'empty' => '--Chọn loại xử lý--', 'div' => false, 'label' => false)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <input type="submit" class="btn btn-primary" value="<?php echo __('In báo cáo') ?>"
                               onclick="submit('ReportEveryDayForm')">
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row-fluid">
    <!--        Báo cáo tổng hơp nhân viên-->
    <div class="span6">
        <div class="box box-black">
            <div class="box-title">
                <h3><i class="icon-bar-chart"></i> Báo cáo tổng hơp nhân viên</h3>

                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-down"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php echo $this->Form->create('Report', array('class' => 'form-horizontal validation-form', 'action' => 'employee')); ?>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Từ ngày'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('from_date', array('placeholder' => '', 'type' => 'text', 'class' => 'input-small Date', 'label' => false, 'div' => false, 'data-rule-required' => 'true', 'id'=>'fromdate_staff' )) ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Đến ngày'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('to_date', array('placeholder' => '', 'type' => 'text', 'class' => 'input-small Date', 'label' => false, 'div' => false, 'data-rule-required' => 'true',  'id'=>'todate_staff' )) ?>
                    </div>
                </div> 
                <div class="control-group">
                    <label class="control-label">
                        <?php echo __('Quốc gia'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('Country_id', array('class' => 'span12', 'options' => $countries, 'onchange' => 'getCustomers(this.value)', 'empty' => '--Chọn quốc gia--', 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="control-group customer">
                    <label class="control-label">
                        <?php echo __('Khách hàng'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('Customer_id', array('class' => 'span12', 'options' => array('empty' => '--Chọn khách hàng--'), 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
<!--                <div class="control-group">-->
<!--                    <label class="control-label" for="name">--><?php //echo __('Chọn nhân viên'); ?><!--:<i-->
<!--                            class="icon-star red icon_fix"></i></label>-->
<!---->
<!--                    <div class="controls">-->
<!--                        <div class="span12">-->
<!--                            <textarea name="NV_ten" id="NV_ten" readonly="true" class="col span_12_of_12"-->
<!--                                      rows="2"></textarea>-->
<!--                            <input type="hidden" name="NV_ID" id="NV_ID" value="49"/>-->
<!--                        </div>-->
<!--                        <div class="span9">-->
<!--                            <a data-toggle="modal" class="btn select-checkbox-user" onclick="getNVS()" role="button"-->
<!--                               href=".modal-1">Chọn nhân viên</a>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
                <div class="control-group">
                    <label class="control-label"><?php echo __('Chọn nhân viên'); ?>:</label>

                    <div class="controls">
                        <div class="span12">
                            <textarea name="NV_ten" id="NV_ten_1" readonly="true" class="span12" rows="2"></textarea>
                            <input type="hidden" value="" name="NV_ID" id="NV_ID_1"/>
                        </div>
                        <div class="span12">
                            <a data-toggle="modal" class="btn select-checkbox-user" onclick="getNVS(1)"
                               role="button" href="#modal-1">Chọn nhân viên</a>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="<?php echo __('In báo cáo') ?>"
                           onclick="submit('ReportEmployeeForm')">
                </div>
                <?php echo $this->Form->end(); ?>

            </div>
        </div>
    </div>
    <!--Báo cáo tổng hợp ngày nghỉ của nhân viên-->
    <div class="span6">
        <div class="box box-orange">
            <div class="box-title">
                <h3><i class="icon-bar-chart"></i> Báo cáo tổng hợp ngày nghỉ của nhân viên</h3>

                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-down"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php echo $this->Form->create('Report', array('class' => 'form-horizontal validation-form', 'action' => 'vacation')); ?>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Chọn tháng / năm'); ?>:</label>

                    <div class="controls">
                        <?php
                        echo $this->Form->dateTime('datetime', 'MY', '', array(
                                'label' => false,
                                'minYear' => date('Y') - 3,
                                'maxYear' => date('Y'),
                                'class' => 'span4',
                                'empty' => array(
                                    'month' => 'Chọn tháng',
                                    'year' => 'Chọn năm'
                                )
                            )
                        );

                        ?>
                    </div>
                    <div class=" alert-info">
                        <!--                        <button class="close" data-dismiss="alert">×</button>-->
                        <strong>Lưu ý!</strong> Chọn tháng chỉ áp dụng cho báo cáo toàn công ty
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">
                        <?php echo __('Phòng ban'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('Departments_id', array('options' => $departments, 'onchange' => 'getUsers(this.value)', 'empty' => '--Chọn phòng ban--', 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="control-group usersxxx">
                    <label class="control-label">
                        <?php echo __('Nhân viên'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('Departments_id', array('options' => $departments, 'onchange' => 'getCustomers(this.value)', 'empty' => '--Chọn nhân viên--', 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="<?php echo __('In báo cáo') ?>"
                           onclick="submit('ReportVacationForm')">
                </div>
                <?php echo $this->Form->end(); ?>

            </div>
        </div>
    </div>
</div>

<div class="row-fluid">
    <!--        Báo cáo tổng hơp đơn hàng-->
    <div class="span6">
        <div class="box box-magenta">
            <div class="box-title">
                <h3><i class="icon-bar-chart"></i> Báo cáo tổng hơp đơn hàng</h3>

                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-down"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php echo $this->Form->create('Report', array('class' => 'form-horizontal validation-form', 'action' => 'project_summary')); ?>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Từ ngày'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('from_date', array('placeholder' => '', 'type' => 'text', 'class' => 'input-small Date', 'label' => false, 'div' => false, 'data-rule-required' => 'true', 'id'=>'fromdate_1' )) ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Đến ngày'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('to_date', array('placeholder' => '', 'type' => 'text', 'class' => 'input-small Date', 'label' => false, 'div' => false, 'data-rule-required' => 'true',  'id'=>'todate_1' )) ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">
                        <?php echo __('Quốc gia'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('Country_id', array('options' => $countries, 'onchange' => 'getCustomers(this.value)', 'empty' => '--Chọn quốc gia--', 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="control-group customer">
                    <label class="control-label">
                        <?php echo __('Khách hàng'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('Customer_id', array('options' => array('empty' => '--Chọn khách hàng--'), 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="control-group customer_group">
                    <label class="control-label">
                        <?php echo __('Nhóm khách hàng'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('CustomerGroup_id', array('options' => array('empty' => '--Chọn nhóm khách hàng--'), 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">
                        <?php echo __('Trạng thái đơn hàng hàng'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('Status_id', array('options' => $statuses, 'empty' => '--Chọn trạng thái--', 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">
                        <?php echo __('Nhóm Com'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('GroupCom', array('class' => 'span12', 'options' => $GroupCom, 'empty' => '--Chọn nhóm Comp--', 'onchange' => 'getCom(this.value,"ReportProjectSummaryForm")', 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">
                        <?php echo __('Loại Com'); ?>:</label>

                    <div class="controls"id="com">
                        <?php echo $this->Form->input('Com_id', array('class' => 'span12', 'empty' => '--Chọn Comp--', 'div' => false, 'label' => false)); ?>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label">
                        <?php echo __('Chế độ báo cáo'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('type', array('options' => array('1'=>'Full', 2 => 'advanced', 3 => 'basic'), 'empty' => '--Chọn kiểu báo cáo--', 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="<?php echo __('In báo cáo') ?>"
                           onclick="submit('ReportProjectsForm')">
                </div>
                <?php echo $this->Form->end(); ?>

            </div>
        </div>
    </div>
    <!--Báo cáo tổng hợp sản phẩm-->
    <div class="span6">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-bar-chart"></i> Báo cáo tổng hợp sản phẩm</h3>

                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-down"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php echo $this->Form->create('Report', array('class' => 'form-horizontal validation-form', 'action' => 'products')); ?>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Từ ngày'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('from_date', array('placeholder' => '', 'type' => 'text', 'class' => 'input-small Date', 'label' => false, 'div' => false, 'data-rule-required' => 'true', 'id'=>'fromdate_3' )) ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Đến ngày'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('to_date', array('placeholder' => '', 'type' => 'text', 'class' => 'input-small Date', 'label' => false, 'div' => false, 'data-rule-required' => 'true',  'id'=>'todate_3' )) ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">
                        <?php echo __('Quốc gia'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('Country_id', array('options' => $countries, 'onchange' => 'getCustomers(this.value)', 'empty' => '--Chọn quốc gia--', 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="control-group customer">
                    <label class="control-label">
                        <?php echo __('Khách hàng'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('Customer_id', array('options' => array('empty' => '--Chọn khách hàng--'), 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="control-group customer_group">
                    <label class="control-label">
                        <?php echo __('Nhóm khách hàng'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('CustomerGroup_id', array('options' => array('empty' => '--Chọn nhóm khách hàng--'), 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">
                        <?php echo __('Trạng thái đơn hàng hàng'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('Status_id', array('options' => $statuses, 'empty' => '--Chọn trạng thái--', 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">
                        <?php echo __('Nhóm Com'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('GroupCom', array('class' => 'span12', 'options' => $GroupCom, 'empty' => '--Chọn nhóm Comp--', 'onchange' => 'getCom(this.value,"ReportProjectSummaryForm")', 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">
                        <?php echo __('Loại Com'); ?>:</label>

                    <div class="controls"id="com">
                        <?php echo $this->Form->input('Com_id', array('class' => 'span12', 'empty' => '--Chọn Comp--', 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="<?php echo __('In báo cáo') ?>"
                           onclick="submit('ReportProjectsForm')">
                </div>
                <?php echo $this->Form->end(); ?>

            </div>
        </div>
    </div>
</div>

<div class="row-fluid">
    <!--        Báo cáo tổng hơp hoạt động-->
    <div class="span6">
        <div class="box box-pink">
            <div class="box-title">
                <h3><i class="icon-bar-chart"></i> Báo cáo tổng hơp hoạt động</h3>

                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-down"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php echo $this->Form->create('Report', array('class' => 'form-horizontal validation-form', 'action' => 'activity')); ?>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Chọn ngày'); ?>:</label>

                    <div class="controls">
                        <?php
                        echo $this->Form->dateTime('datetime', 'MY', '', array(
                                'label' => false,
                                'minYear' => date('Y') - 3,
                                'maxYear' => date('Y'),
                                'class' => 'span4',
                                'empty' => array(
                                    'month' => 'Chọn tháng',
                                    'year' => 'Chọn năm'
                                )
                            )
                        );

                        ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo __('Chọn nhân viên'); ?>:</label>

                    <div class="controls">
                        <div class="span12">
                            <textarea name="NV_ten" id="NV_ten2" readonly="true" class="span12" rows="2"></textarea>
                            <input type="hidden" value="" name="NV_ID" id="NV_ID2"/>
                        </div>
                        <div class="span12">
                            <a data-toggle="modal" class="btn select-checkbox-user" onclick="getNVS(2)"
                               role="button" href="#modal-1">Chọn nhân viên</a>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="<?php echo __('In báo cáo') ?>"
                           onclick="submit('ReportActivityForm')">
                </div>
                <?php echo $this->Form->end(); ?>

            </div>
        </div>
    </div>
    <!--Báo cáo tổng hợp File-->
    <div class="span6">
        <div class="box box-green">
            <div class="box-title">
                <h3><i class="icon-bar-chart"></i> Báo cáo tổng hơp File</h3>

                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-down"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php echo $this->Form->create('Report', array('class' => 'form-horizontal validation-form', 'action' => 'files')); ?>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Chọn ngày'); ?>:</label>

                    <div class="controls">
                        <?php
                        echo $this->Form->dateTime('datetime', 'MY', '', array(
                                'label' => false,
                                'minYear' => date('Y') - 3,
                                'maxYear' => date('Y'),
                                'class' => 'span4',
                                'empty' => array(
                                    'month' => 'Chọn tháng',
                                    'year' => 'Chọn năm'
                                )
                            )
                        );

                        ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><?php echo __('Chọn nhân viên'); ?>:</label>

                    <div class="controls">
                        <div class="span12">
                            <textarea name="NV_ten" id="NV_ten3" readonly="true" class="span12" rows="2"></textarea>
                            <input type="hidden" value="" name="NV_ID" id="NV_ID3"/>
                        </div>
                        <div class="span12">
                            <a data-toggle="modal" class="btn select-checkbox-user" onclick="getNVS(3)"
                               role="button" href="#modal-1">Chọn nhân viên</a>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="<?php echo __('In báo cáo') ?>"
                           onclick="submit('ReportFilesForm')">
                </div>
                <?php echo $this->Form->end(); ?>

            </div>
        </div>
    </div>
</div>

<div class="row-fluid">
    <!--        Báo cáo tổng hơp Danh Mục Sản Phẩm(năm)-->
    <div class="span6">
        <div class="box box-orange">
            <div class="box-title">
                <h3><i class="icon-bar-chart"></i> Báo cáo tổng hợp D.Mục Sản Phẩm(năm)</h3>

                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-down"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php echo $this->Form->create('Report', array('class' => 'form-horizontal validation-form', 'action' => 'productCategories')); ?>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Chọn năm'); ?>:</label>

                    <div class="controls">
                        <?php
                        echo $this->Form->dateTime('datetime', 'Y', '', array(
                                'label' => false,
                                'minYear' => date('Y') - 3,
                                'maxYear' => date('Y'),
                                'class' => 'span4',
                                'empty' => array(
                                    'year' => 'Chọn năm'
                                )
                            )
                        );

                        ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">
                        <?php echo __('Danh mục'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('product_category_id', array('options' => $productCategories, 'empty' => '--Tất cả--', 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="<?php echo __('In báo cáo') ?>"
                           onclick="submit('ReportProductCategoriesForm')">
                </div>
                <?php echo $this->Form->end(); ?>

            </div>
        </div>
    </div>
    <!--Báo cáo kết quả kỳ thi-->
    <div class="span6">
        <div class="box box-red">
            <div class="box-title">
                <h3><i class="icon-bar-chart"></i> Báo cáo kết quả kỳ thi</h3>

                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-down"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php echo $this->Form->create('ReportEveryDay', array('class' => 'form-horizontal validation-form')); ?>
                <div class="control-group">
                    <label class="control-label">
                        <?php echo __('Kỳ thi'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('CustomerGroup_id', array('options' => array('empty' => '--Chọn kỳ thi--'), 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="<?php echo __('In báo cáo') ?>">
                </div>
                <?php echo $this->Form->end(); ?>

            </div>
        </div>
    </div>
</div>

<div class="row-fluid">
    <!--        Báo cáo tổng hơp Khách hàng(năm)-->
    <div class="span6">
        <div class="box box-green">
            <div class="box-title">
                <h3><i class="icon-bar-chart"></i> Báo cáo tổng hợp Khách hàng(năm)</h3>

                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-down"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php echo $this->Form->create('Report', array('class' => 'form-horizontal validation-form', 'action' => 'customers')); ?>
                <div class="control-group">
                    <!--                    <label class="control-label" for="name">-->
                    <?php //echo __('Chọn năm'); ?><!--:</label>-->
                    <div class="controls">
                        <?php
                        echo $this->Form->dateTime('datetime', 'Y', '', array(
                                'label' => false,
                                'minYear' => date('Y') - 3,
                                'maxYear' => date('Y'),
                                'class' => 'span4',
                                'empty' => array(
                                    'year' => 'Chọn năm'
                                )
                            )
                        );

                        ?>
                    </div>
                </div>
                <div class="control-group">
                    <!--                    <label class="control-label">-->
                    <!--                        --><?php //echo __('Quốc gia'); ?><!--:</label>-->

                    <div class="controls">
                        <?php echo $this->Form->input('Country_id', array('class' => 'span12', 'options' => $countries, 'onchange' => 'getCustomers(this.value)', 'empty' => '--Tất cả--', 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="control-group customer">
                    <!--                    <label class="control-label">-->
                    <!--                        --><?php //echo __('Khách hàng'); ?><!--:</label>-->

                    <div class="controls">
                        <?php echo $this->Form->input('Customer_id', array('class' => 'span12', 'options' => array('empty' => '--Không có khách hàng--'), 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="<?php echo __('In báo cáo') ?>"
                           onclick="submit('ReportCustomersForm')">
                </div>
                <?php echo $this->Form->end(); ?>

            </div>
        </div>
    </div>
    <!--Báo cáo lương tháng cho nhân viên-->
    <div class="span6">
        <div class="box box-back">
            <div class="box-title">
                <h3><i class="icon-bar-chart"></i> Báo cáo lương tháng nhân viên</h3>

                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-down"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php echo $this->Form->create('ReportEveryDay', array('class' => 'form-horizontal validation-form')); ?>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Chọn tháng/năm'); ?>:</label>

                    <div class="controls">
                        <?php
                        echo $this->Form->dateTime('datetime', 'MY', '', array(
                                'label' => false,
                                'minYear' => date('Y') - 3,
                                'maxYear' => date('Y'),
                                'class' => 'span4',
                                'empty' => array(
                                    'month' => 'Chọn tháng',
                                    'year' => 'Chọn năm'
                                )
                            )
                        );

                        ?>
                    </div>
                </div>
                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="<?php echo __('In báo cáo') ?>">
                </div>
                <?php echo $this->Form->end(); ?>

            </div>
        </div>
    </div>
</div>

<div class="row-fluid">
    <!--        Báo cáo tổng hơp Nhân viên(năm)-->
    <div class="span6">
        <div class="box box-black">
            <div class="box-title">
                <h3><i class="icon-bar-chart"></i> Báo cáo tổng hợp Nhân viên(năm)</h3>

                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-down"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php echo $this->Form->create('Report', array('class' => 'form-horizontal validation-form','action'=>'collectionEmployee')); ?>
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo __('Chọn năm'); ?>:</label>

                    <div class="controls">
                        <?php
                        echo $this->Form->dateTime('datetime', 'Y', '', array(
                                'label' => false,
                                'minYear' => date('Y') - 3,
                                'maxYear' => date('Y'),
                                'class' => 'span4',
                                'empty' => array(
                                    'year' => 'Chọn năm'
                                )
                            )
                        );

                        ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">
                        <?php echo __('Phòng ban'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('Departments_id', array('class' => 'span12', 'options' => $departments, 'onchange' => 'getUsers(this.value)', 'empty' => '--Chọn phòng ban--', 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="control-group usersxxx">
                    <label class="control-label">
                        <?php echo __('Nhân viên'); ?>:</label>

                    <div class="controls">
                        <?php echo $this->Form->input('user_id', array('class' => 'span12', 'empty' => '--Chọn nhân viên--', 'div' => false, 'label' => false)); ?>
                    </div>
                </div>
                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" value="<?php echo __('In báo cáo') ?>" onclick="submit('ReportCollectionEmployeeForm')">
                </div>
                <?php echo $this->Form->end(); ?>

            </div>
        </div>
    </div>
</div>

<!-- END Main Content -->

<footer>
    <?php
    echo $this->element('footer');
    ?>
</footer>

<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-down"></i></a>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="modal-1"
     style="display: none;"></div>
<script>
    function getCustomers(country_id) {
        $('.fixed_bottom').show();
        $.get("<?php echo $this->html->url(array('controller'=>'Customers','action'=>'getCustomers'),true)?>/" + country_id, function (data) {
            $(".customer").html(data);
            $('.fixed_bottom').hide();
        });
    }

    function getCustomer_Groups(country_id) {
        $('.fixed_bottom').show();
        $.get("<?php echo $this->html->url(array('controller'=>'Customergroups','action'=>'getCustomerGroups'),true)?>/" + country_id, function (data) {
            $(".customer_group").html(data);
            $('.fixed_bottom').hide();
        });
    }
    function getUsers(country_id) {
        $('.fixed_bottom').show();
        $.get("<?php echo $this->html->url(array('controller'=>'departments','action'=>'getUsers'),true)?>/" + country_id, function (data) {
            $(".usersxxx").html(data);
            $('.fixed_bottom').hide();
        });
    }
    $(".Date").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        onClose: function (selectedDate) {
            $("#VacationToDate").datepicker("option", "minDate", selectedDate);
        }
    });
    function getNVS(stt) {
        $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'SelectUsers'),true)?>/"+stt, function (data) {
            $("#modal-1").html(data);
        });
    }

    function submit(formId) {
        alert(formId);
        document.getElementById(formId+"").submit();
    }

    function getCom(val,formId) {
        $.get("<?php echo $this->html->url(array('controller'=>'Coms','action'=>'getCom'),true)?>/" + val, function (data) {
            $("#"+formId+" #com").html(data);
        });
    }
</script>
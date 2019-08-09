 <div class="projects view">
    <h2><?php echo __('Chi tiết dự án') ?></h2>

    <div class="span10">
        <div class="span5">
            <dl>
                <dt><?php echo __('Mã dự án'); ?></dt>
                <dd>
                    <?php echo h($project['Project']['Code']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Tên dự án'); ?></dt>
                <dd>
                    <?php echo h($project['Project']['Name']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Yêu cầu đơn hàng'); ?></dt>
                <dd>
                    <?php echo h($project['Project']['Require']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Yêu cầu chia hàng'); ?></dt>
                <dd>
                    <?php echo h($project['Project']['RequireDevide']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Ngày nhập dự án'); ?></dt>
                <dd>
                    <?php echo h($project['Project']['InputDate']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Ngày hoàn thành'); ?></dt>
                <dd>
                    <?php echo h($project['Project']['CompTime']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Số file của dự án'); ?></dt>
                <dd>
                    <?php echo h($project['Project']['Quantity']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('InitSize'); ?></dt>
                <dd>
                    <?php echo h($project['Project']['InitSize']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('CompSize'); ?></dt>
                <dd>
                    <?php echo h($project['Project']['CompSize']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('ExamTime'); ?></dt>
                <dd>
                    <?php echo h($project['Project']['ExamTime']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('CheckExamTime'); ?></dt>
                <dd>
                    <?php echo h($project['Project']['CheckExamTime']); ?>
                    &nbsp;
                </dd>
            </dl>
        </div>
        <div class="span4">
            <dl>
                <!--		<dt>--><?php //echo __('ID'); ?><!--</dt>-->
                <!--		<dd>-->
                <!--			--><?php //echo h($project['Project']['ID']); ?>
                <!--			&nbsp;-->
                <!--		</dd>-->

                <dt><?php echo __('UpTime'); ?></dt>
                <dd>
                    <?php echo h($project['Project']['UpTime']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Khách hàng'); ?></dt>
                <dd>
                    <?php echo $this->Html->link($project['Customer']['name'], array('controller' => 'customers', 'action' => 'view', $project['Customer']['id'])); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Đường dẫn'); ?></dt>
                <dd>
                    <?php echo h($project['Project']['UrlFolder']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('File'); ?></dt>
                <dd>
                    <?php echo h($project['Project']['File']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('IsActivated'); ?></dt>
                <dd>
                    <?php echo h($project['Project']['IsActivated']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('IsSpecial'); ?></dt>
                <dd>
                    <?php echo h($project['Project']['IsSpecial']); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Nhân viên'); ?></dt>
                <dd>
                    <?php echo $this->Html->link($project['User']['name'], array('controller' => 'users', 'action' => 'view', $project['User']['id'])); ?>
                    &nbsp;
                </dd>
                <!--		<dt>--><?php //echo __('HasCheck'); ?><!--</dt>-->
                <!--		<dd>-->
                <!--			--><?php //echo h($project['Project']['HasCheck']); ?>
                <!--			&nbsp;-->
                <!--		</dd>-->
                <!--		<dt>--><?php //echo __('SpentTime'); ?><!--</dt>-->
                <!--		<dd>-->
                <!--			--><?php //echo h($project['Project']['SpentTime']); ?>
                <!--			&nbsp;-->
                <!--		</dd>-->
                <dt><?php echo __('Loại sản phẩm'); ?></dt>
                <dd>
                    <?php echo $this->Html->link($project['ProductType']['name'], array('controller' => 'product_types', 'action' => 'view', $project['ProductType']['id'])); ?>
                    &nbsp;
                </dd>
                <dt><?php echo __('Loại xử lý'); ?></dt>
                <dd>
                    <?php echo $this->Html->link($project['ProcessType']['name'], array('controller' => 'process_types', 'action' => 'view', $project['ProcessType']['id'])); ?>
                    &nbsp;
                </dd>
                <!--		<dt>--><?php //echo __('Project Type'); ?><!--</dt>-->
                <!--		<dd>-->
                <!--			--><?php //echo $this->Html->link($project['ProjectType'][''], array('controller' => 'project_types', 'action' => 'view', $project['ProjectType']['id'])); ?>
                <!--			&nbsp;-->
                <!--		</dd>-->
                <dt><?php echo __('Nhóm khách hàng'); ?></dt>
                <dd>
                    <?php echo h($project['CustomerGroup']['name']); ?>
                    &nbsp;
                </dd>
            </dl>
        </div>
    </div>
    <div style="clear: both"></div>
    <div><h5><a href="<?php echo Router::url(array('controller'=>'pages','action' => 'index'))?>"><i class="icon-arrow-left"></i><?php echo __(' Quay lại') ?></a></h5></div>
</div>
<!--<div class="actions">-->
<!--	<h3>--><?php //echo __('Actions'); ?><!--</h3>-->
<!--	<ul>-->
<!--		<li>--><?php //echo $this->Html->link(__('Edit Project'), array('action' => 'edit', $project['Project']['ID'])); ?><!-- </li>-->
<!--		<li>--><?php //echo $this->Form->postLink(__('Delete Project'), array('action' => 'delete', $project['Project']['ID']), array(), __('Are you sure you want to delete # %s?', $project['Project']['ID'])); ?><!-- </li>-->
<!--		<li>--><?php //echo $this->Html->link(__('List Projects'), array('action' => 'index')); ?><!-- </li>-->
<!--		<li>--><?php //echo $this->Html->link(__('New Project'), array('action' => 'add')); ?><!-- </li>-->
<!--		<li>--><?php //echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?><!-- </li>-->
<!--		<li>--><?php //echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?><!-- </li>-->
<!--		<li>--><?php //echo $this->Html->link(__('List Statuses'), array('controller' => 'statuses', 'action' => 'index')); ?><!-- </li>-->
<!--		<li>--><?php //echo $this->Html->link(__('New Status'), array('controller' => 'statuses', 'action' => 'add')); ?><!-- </li>-->
<!--		<li>--><?php //echo $this->Html->link(__('List Customers'), array('controller' => 'customers', 'action' => 'index')); ?><!-- </li>-->
<!--		<li>--><?php //echo $this->Html->link(__('New Customer'), array('controller' => 'customers', 'action' => 'add')); ?><!-- </li>-->
<!--		<li>--><?php //echo $this->Html->link(__('List Project Types'), array('controller' => 'project_types', 'action' => 'index')); ?><!-- </li>-->
<!--		<li>--><?php //echo $this->Html->link(__('New Project Type'), array('controller' => 'project_types', 'action' => 'add')); ?><!-- </li>-->
<!--		<li>--><?php //echo $this->Html->link(__('List Process Types'), array('controller' => 'process_types', 'action' => 'index')); ?><!-- </li>-->
<!--		<li>--><?php //echo $this->Html->link(__('New Process Type'), array('controller' => 'process_types', 'action' => 'add')); ?><!-- </li>-->
<!--		<li>--><?php //echo $this->Html->link(__('List Product Types'), array('controller' => 'product_types', 'action' => 'index')); ?><!-- </li>-->
<!--		<li>--><?php //echo $this->Html->link(__('New Product Type'), array('controller' => 'product_types', 'action' => 'add')); ?><!-- </li>-->
<!--	</ul>-->
<!--</div>-->

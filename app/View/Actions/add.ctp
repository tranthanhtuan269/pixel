<?php
echo $this->element('top_page', array(
	'page_title' => 'Quản lý điểm số nghiệp vụ',
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
			<a href="<?php echo Router::url(array('action' => 'index')); ?>"><?php echo __('Quản lý điểm'); ?></a>
			<span class="divider"><i class="icon-angle-right"></i></span>
		</li>
		<li class="active">
			<?php echo __('Thêm điểm'); ?>
		</li>
	</ul>
</div>
<!-- BEGIN Main Content -->
<div class="row-fluid">
	<div class="span12">
		<div class="box">
			<div class="box-title">
				<h3><i class="icon-reorder"></i><?php echo __('Thêm thang điểm'); ?></h3>

				<div class="box-tool">
					<a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
					<a data-action="close" href="#"><i class="icon-remove"></i></a>
				</div>
			</div>
			<div class="box-content">
				<?php echo $this->Form->create('Action', array('class' => 'form-horizontal validation-form')); ?>
				<div class="control-group">
					<label class="control-label" for="name"><?php echo __('Tên thang điểm'); ?>:</label>

					<div class="controls">
						<div class="span12">
							<?php echo $this->Form->input('Name', array('placeholder' => '', 'class' => 'input-xlarge', 'label' => false, 'data-rule-required' => 'true', 'data-rule-minlength' => '3')) ?>
						</div>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="name"><?php echo __('Điểm'); ?>:</label>

					<div class="controls">
						<div class="span12">
							<?php echo $this->Form->input('Point', array('placeholder' => '', 'type' => 'text', 'class' => 'input-xlarge', 'label' => false, 'data-rule-required' => 'true', 'data-rule-number' => 'true')) ?>
						</div>
					</div>
				</div>


				<div class="form-actions">
					<!--                        <input type="submit" name="save" value="save">-->
					<input type="submit" class="btn btn-primary" value="<?php echo __('Lưu') ?>">
					<button type="button" class="btn"><?php echo __('Hủy') ?></button>
				</div>
				<?php echo $this->Form->end(); ?>
				</form>
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
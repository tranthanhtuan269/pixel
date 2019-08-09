<div class="box box-gray">
    <div class="box-title">
        <h3><i class="icon-puzzle-piece"></i>Danh sách sản phẩm</h3>

        <div class="box-tool">
<!--            --><?php //debug($project);die;?>
            <?php if($project['Project']['Status_id']!= 6){?>
            <button class="btn disabled btn-primary bt-createproject">Tạo dự án</button>
            <button class="btn disabled btn-primary bt-assignment">Giao việc</button>
            <button class="btn disabled btn-primary bt-assignment fixed-window">Giao việc</button>
            <?php }?>
            <div class="btn-group">
                <a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="icon-th-large"></i> Gridview <span class="caret"></span>
                </a>
                <ul class="dropdown-menu dropdown-warning">
                    <li><a onclick="getproducts(2)"><i class="icon-th-large"></i> Gridview</a></li>
                    <li><a onclick="getproducts(1)"><i class="icon-th-list"></i> Listview</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="box-content">
        <div class="clearfix">
            <div class="pull-left span4">
                <div class="btn-toolbar btn-toolbar-gallery">
                    <input type="text" id="filter" placeholder="Nhập tên ảnh">
                </div>
            </div>
            <div class="pull-left span4">
                <div id="countchecked"></div>
            </div>
        </div>
        <hr>
        <?php echo $this->Form->create('Product', array('url' => array('controller' => 'Projects', 'action' => 'setUserProducts'), 'id' => 'ProductDetailForm')) ?>
        <?php echo $this->Form->input('Project_id', array('type' => 'hidden', 'value' => $project_id)); ?>
        <?php echo $this->Form->input('Product_id.', array('type' => 'checkbox', 'value' => 0, 'div' => false, 'label' => false, 'id' => 'selectall')) . '  Chọn tất cả.'; ?>
        <div class="row-fluid">
            <?php
            foreach ($products as $product) {
                $bgsize = '100% auto';
                if ($product['Product']['url'] != '') {
//                    if(file_exists($domain.str_replace('@', '/', $dir . $product['Product']['url'] . '@' . $product['Product']['name_file_product']))){
//                        $size = getimagesize($domain.str_replace('@', '/', $dir . $product['Product']['url'] . '@' . $product['Product']['name_file_product']));
//                        if($size[0]<$size[1]){
//                            $bgsize = 'auto 100% ';
//                        }
//                    }
                    ?>
                    <div id="lable<?php echo $product['Product']['id'] ?>"
                         class="show-btn controls span2   grid-product-img"
                         data="<?php echo $product['Product']['name_file_product'] ?>">
                        <?php
                        //                        $img_file = get_headers($domain . (str_replace('@', '/', rawurlencode($dir . $product['Product']['url'] . '@' . rawurlencode($product['Product']['name_file_product'])))), 1);
                        //                        $file_size = floor($img_file["Content-Length"] / (1024 * 1024));
                        $file_size = floor($product['Product']['file_size'] / (1024 * 1024));
                        $file_name = explode('.', $product['Product']['name_file_product']);
                        $checker = $this->requestAction(Router::url(array('controller' => 'projects', 'action' => 'check_product', $product['Product']['id'])));
                        if (isset($PE[$file_name[count($file_name) - 1]])) {
                            ?>
                            <label class="checkbox span12"
                                   style="background-image: url('<?php echo $this->webroot . 'img/oQ1R0t0.png'; ?>');background-repeat: no-repeat;background-size:<?php echo $bgsize ?>;background-position: center">
                                <?php  if ($product['Product']['deliver_user_id'] == '' || $product['Product']['deliver_user_id'] == null || $product['Product']['deliver_user_id'] == 0) {
                                    echo $this->Form->input('Product_id.', array('type' => 'checkbox', 'value' => $product['Product']['id'], 'data' => $product['Product']['id'], 'div' => false, 'label' => false, 'class' => 'cbimg'));
                                }?>
                                &nbsp<?php echo $product['Product']['name_file_product'] ?>
                                <br><br>

                                <div
                                    style="font-size: 25px;font-weight: bold;color: #ffffff"><?php echo $file_name[count($file_name) - 1]; ?></div>
                            </label>
                        <?php
                        } else {
                            if ($file_size <= 20) {
                                ?>
                                <label class="checkbox span12"
                                       style="background-image: url('<?php echo $domain . (str_replace('@', '/', $dir . $product['Product']['url'] .$product['Product']['sub_folder']. '@' . $product['Product']['name_file_product'])); ?>');background-repeat: no-repeat;background-size:<?php echo $bgsize ?>;background-position: center">
                                    <?php  if ($product['Product']['deliver_user_id'] == '' || $product['Product']['deliver_user_id'] == null || $product['Product']['deliver_user_id'] == 0) {
                                        echo $this->Form->input('Product_id.', array('type' => 'checkbox', 'value' => $product['Product']['id'], 'data' => $product['Product']['id'], 'div' => false, 'label' => false, 'class' => 'cbimg'));
                                    }?>
                                    &nbsp<?php echo $product['Product']['name_file_product'] ?>
                                </label>
                            <?php
                            } else {
                                ?>
                                <label class="checkbox span12"
                                       style="background-image: url('<?php echo $this->webroot . 'img/oQ1R0t0.png'; ?>');background-repeat: no-repeat;background-size:<?php echo $bgsize ?>;background-position: center">
                                    <?php  if ($product['Product']['deliver_user_id'] == '' || $product['Product']['deliver_user_id'] == null || $product['Product']['deliver_user_id'] == 0) {
                                        echo $this->Form->input('Product_id.', array('type' => 'checkbox', 'value' => $product['Product']['id'], 'data' => $product['Product']['id'], 'div' => false, 'label' => false, 'class' => 'cbimg'));
                                    }?>
                                    &nbsp<?php echo $product['Product']['name_file_product'] ?>
                                    <br><br>

                                    <div
                                        style="font-size: 25px;font-weight: bold;color: #ffffff"><?php echo $file_name[count($file_name) - 1]; ?></div>
                                </label>
                            <?php
                            }
                        }
                        ?>
                        <?php if ($product['Product']['done_round'] < 2) { ?>
                            <a data-toggle="modal" id="bt-lable<?php echo $product['Product']['id'] ?>"
                               style="position: absolute; right: 0px; bottom: 0px; cursor: pointer;" class="btn hide"
                               onclick="getinfo(<?php echo $product['Product']['id'] ?>)" role="button"
                               href="#product-detail">Chi tiết</a>
                        <?php } ?>
                        <?php if ($product['Product']['done_round'] < 2) { ?>
                            <a data-toggle="modal" id="bt-reject<?php echo $product['Product']['id'] ?>"
                               style="position: absolute; left: 0px; bottom: 0px; cursor: pointer;"
                               class="btn btn-danger reject-product reject_product" data-rate="5" data-project-id="<?php echo $project_id;?>"
                               data-id-product="<?php echo $product['Product']['id'] ?>"
                               data-file="<?php echo $product['Product']['name_file_product'] ?>"
                               role="button" href="#reject">Reject</a>
                        <?php
                        }
                        if ($product['Product']['done_round'] == 2) {
                            ?>
                            <a data-toggle="modal" id="bt-reject<?php echo $product['Product']['id'] ?>"
                               style="position: absolute; left: 0px; bottom: 0px; cursor: pointer;"
                               class="btn btn-danger reject-product reject_product" data-rate="6" data-project-id="<?php echo $project_id;?>"
                               data-id-product="<?php echo $product['Product']['id'] ?>"
                               data-file="<?php echo $product['Product']['name_file_product'] ?>"
                               role="button" href="#reject">Reject</a>
                        <?php
                        } ?>
                        <?php
                        if ($checker == 1) {
                            ?>
                            <a data-toggle="modal" id="bt-reject<?php echo $product['Product']['id'] ?>"
                               style="position: absolute; left: 0px; bottom: 0px; cursor: pointer;"
                               class="btn btn-danger reject-product reject_product" data-rate="7" data-project-id="<?php echo $project_id;?>"
                               data-id-product="<?php echo $product['Product']['id'] ?>"
                               data-file="<?php echo $product['Product']['name_file_product'] ?>"
                               role="button" href="#reject">Reject</a>
                        <?php
                        } ?>
                        <?php  if ($product['Product']['deliver_user_id'] == '' || $product['Product']['deliver_user_id'] == null || $product['Product']['deliver_user_id'] == 0) {?>
                            <a style="position: absolute; top: 0px; right: 0px;" class="btn hide"
                           id="bt-del-lable<?php echo $product['Product']['id'] ?>"
                           onclick="deleteproduct(<?php echo $product['Product']['id'] ?>)">Xóa</a>
                        <?php }?>
                        <div style="clear: both;"></div>
                    </div>
                <?php
                }
            } ?>
        </div>
        <?php echo $this->form->end() ?>
    </div>
</div>
<div class="row-fluid">
    <div class="span6">
        <div class="dataTables_info"
             id="table1_info"><?php echo $this->Paginator->counter('Tổng số bản ghi {:current}/{:count}'); ?></div>
    </div>
    <div class="span6">
                            <div class="dataTables_paginate paging_bootstrap pagination">
                                <ul>
                                    <li class = "<?php if($page_num - 1 <= 0){ ?>   disabled <?php } ?> paging" data="<?php echo ($page_num - 1)?($page_num - 1):1 ?> "><a>prev</a></li>
                                    <?php for ($i=1; $i <= ceil($this->Paginator->counter('{:count}')/$limit); $i++) { 
                                        # code...
                                        ?>
                                             <li class="<?php if($page_num == $i){ ?> active <?php } ?> paging page_<?php echo $i ?>" data="<?php echo $i ?>"><a><?php echo $i ?></a></li>

                                        <?php
                                    } ?>
                                     <li class="next <?php if($page_num + 1 >= ceil($this->Paginator->counter('{:count}')/20)){ ?>  disabled <?php } ?> paging" <?php echo ($page_num + 1 <= $page_num)?($page_num + 1):$page_num ?>  ><a   currentclass="disabled" rel="next">next</a></li>
                                     </ul>
                            </div>
                        </div>
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide"
     id="product-detail" style="display: none;">
    <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
        <h3 id="myModalLabel">Thông tin sản phẩm</h3>
    </div>
    <div class="modal-body">
        <div class="box">
            <div class="box-content content-product">

            </div>
        </div>
    </div>
</div>
<?php echo $this->html->script('jquery.shiftcheckbox'); ?>
<script>
    $('.reject_product').click(function () {
        var id = $(this).attr('data-project-id');
        var id_product = $(this).attr('data-id-product');
        var rate = $(this).attr('data-rate');
        $.post("<?php echo Router::url(array('controller' => 'projects','action' => 'form_reject')); ?>", { 'id': id,rate:rate,id_product: id_product})
            .done(function (data) {
                if (data) {
                    $('#reject').html(data);
                }
            });
    });
    function getinfo(product_id) {
        $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'getproductinfo'),true)?>/" + product_id, function (data) {
            $(".content-product").html(data);
        });
    }
    $(function () {
        $('.reject-product').click(function () {
            var product_id = $(this).attr('data-reject');
            var product_name = $(this).attr('data-file');
            $('#product_id_reject').val(product_id);
            $('#product_name_file').html(product_name);
        });
        $("#selectall").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
            $('#countchecked').html('<b>Có ' + $("input.cbimg:checked").length + " sản phẩm được chọn</b>");
        });
        $('.bt-assignment').click(function () {
            if ($("input.cbimg:checked").length > 0) {
                $('#ProductDetailForm').submit();
            } else {
                alert('Bạn phải chọn sản phẩm muốn giao!');
            }
        });

        $('.bt-createproject').click(function () {
            if ($("input.cbimg:checked").length > 0) {
                var ids = new Array();
                var idp = $('#ProductProjectId').val();
                $("input.cbimg:checked").each(function () {
                    ids.push($(this).val());
                });

                $.post("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'createprojectfirst'),true)?>", {"product_id[]": ids, project_id: idp}, function (data) {

                    if (data.resultCode == 1 || data.resultCode == '1') {
                        location.href = "<?php echo $this->html->url(array('controller'=>'Projects','action'=>'edit'),true)?>/" + data.Project_id;
                    } else {
                        alert(data.resultMsg);
                    }
                });
            } else {
                alert('Bạn phải chọn tạo dự án mới!');
            }
        });

        $('input.cbimg').click(function () {
            $('.grid-product-img').removeClass('checked');
            $("input.cbimg:checked").each(function () {
                $('#lable' + $(this).val()).addClass('checked');
            });
            $('#countchecked').html('<b>Có ' + $("input.cbimg:checked").length + " sản phẩm được chọn</b>");
        });


        $('.show-btn').hover(
            function () {
                $('#bt-' + $(this).attr('id')).show();
                $('#bt-del-' + $(this).attr('id')).show();
            },
            function () {
                $('#bt-' + $(this).attr('id')).hide();
                $('#bt-del-' + $(this).attr('id')).hide();
            });

        $('#ProductDetailForm .checkbox').shiftcheckbox({
            checkboxSelector: ':checkbox'
        });

        $("#filter").keyup(function () {
            // Retrieve the input field text and reset the count to zero
            var filter = $(this).val();
            // Loop through the comment list
            $(".grid-product-img").each(function () {
//                alert($(this).attr('data'));
                // If the list item does not contain the text phrase fade it out
                if ($(this).attr('data').search(new RegExp(filter, "i")) < 0) {
                    $(this).fadeOut();
                    // Show the list item if the phrase matches and increase the count by 1
                } else {
                    $(this).show();
                }
            });
        });

    });

    function deleteproduct(id) {
        var d = confirm("Chắc chắn xoá?");
        if(d) {
        $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'deleteProduct'),true)?>/" + id, function (data) {
            alert(data.resultMsg);
            if (data.resultCode == 1) {
                $('#lable' + id).remove();
            }
        });
            return true;
        }else{
            return false;
        }
    }
	
	$(document).ready(function(){
		$('.paging').click(function(){
            var page_num = $(this).attr('data');
            $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'GridviewDetail'),true)?>/<?php echo $project_id ?>/" + page_num + '/<?php echo $limit ?>', function (data) {
                $("#show-products").html(data);
            });
        });
	});
</script>
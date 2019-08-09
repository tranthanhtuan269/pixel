
<div class="box box-gray">
    <div class="box-title">
        <h3><i class="icon-puzzle-piece"></i>Danh sách sản phẩm</h3>
        <div class="box-tool">
<!--            <button class="btn disabled btn-primary bt-createproject">Tạo dự án</button>-->
            <?php if($project['Project']['Status_id']!= 6){?>
            <button class="btn disabled btn-primary bt-assignment">Giao việc</button>
            <button class="btn disabled btn-primary bt-assignment fixed-window">Giao việc</button>
            <?php }?>
            <div class="btn-group">
                <a class="btn btn-warning dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="icon-th-list"></i> Listview <span class="caret"></span>
                </a>
                <ul class="dropdown-menu dropdown-warning">
                    <li><a onclick="getproducts(2)"><i class="icon-th-large"></i> Gridview</a></li>
                    <li><a onclick="getproducts(1)"><i class="icon-th-list"></i> Listview</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="box-content" >
        <div class="clearfix">
        <div class="span4">
        <div id="table1_length" class="dataTables_length"><label>
                <select name="DataTables_Table_0_length" id="pagination" aria-controls="table1" size="1" onchange="Pagination()">
                    <option value="10" <?php if ($limit==10): ?>
                         selected="selected"
                    <?php endif ?>> 10</option>
                    <option value="25" <?php if ($limit==25): ?>
                         selected="selected"
                    <?php endif ?>> 25</option>
                    <option value="50" <?php if ($limit==50): ?>
                         selected="selected"
                    <?php endif ?> > 50</option>
                    <option value="100" <?php if ($limit==100): ?>
                         selected="selected"
                    <?php endif ?>> 100</option>
                    <option value="1000000" <?php if ($limit==1000000): ?>
                         selected="selected"
                    <?php endif ?>> All</option>
                </select>
                Dòng/trang</label>
        </div>
    </div>
            <div class="span6">
<!--                <div id="table1_length" class="dataTables_length"><label>-->
<!--                        <select name="DataTables_Table_0_length" id="pagination"-->
<!--                                aria-controls="table1" size="1"-->
<!--                                onchange="Pagination()">-->
<!---->
<!--                            <option value="10"-->
<!--                                    --><?php //if ($row == 10){ ?><!--selected="selected" --><?php //} ?><!-->
<!--                                10-->
<!--                            </option>-->
<!--                            <option value="25"-->
<!--                                    --><?php //if ($row == 25){ ?><!--selected="selected" --><?php //} ?><!-->
<!--                                25-->
<!--                            </option>-->
<!--                            <option value="50"-->
<!--                                    --><?php //if ($row == 50){ ?><!--selected="selected" --><?php //} ?><!-->
<!--                                50-->
<!--                            </option>-->
<!--                            <option value="100"-->
<!--                                    --><?php //if ($row == 100){ ?><!--selected="selected" --><?php //} ?><!-->
<!--                                100-->
<!--                            </option>-->
<!---->
<!--                        </select>-->
<!--                        --><?php //echo __('Dòng/trang'); ?><!--</label></div>-->
            </div>
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
        <?php echo $this->Form->create('Product',array('url'=>array('controller'=>'Projects','action'=>'setUserProducts'),'id'=>'ProductDetailForm'))?>
        <?php echo $this->Form->input('Project_id',array('type'=>'hidden','value'=>$project_id));?>
        <div class="row-fluid">
            <div class="box">
                <div class="box-content">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo $this->Form->input('Product_id.',array('type'=>'checkbox','value'=>0,'div'=>false,'label'=>false,'id'=>'selectall'));?></th>
                            <th>Tên sản phẩm</th>
                            <th>Loại xử lý</th>
                            <th>Ngày giao</th>
                            <th>Người làm</th>
                            <th>Ngày hoàn thành</th>
                            <th>Thời gian xử lý</th>
                            <th>Thời gian xử lý tiêu chuẩn</th>
                            <th>Quản lý</th>
                        </tr>
                        </thead>
                        <tbody>
<!--                        --><?php //debug($products);die;?>
                        <?php $i = ($page_num-1)*20; foreach ($products as $product) {
//                            pr($product);
                            $i++;$ext = '';
                            $pro = explode('.',$product['Product']['name_file_product']);
                                if ($product['Product']['url'] != '') {  ?>
                                    <tr class="grid-product-img" id= "row<?php echo $product['Product']['id'] ?>" data="<?php echo $product['Product']['name_file_product'] ?>">
                                        <td><?php echo $i;?></td>
                                        <td><?php  if ($product['Product']['deliver_user_id'] == '' || $product['Product']['deliver_user_id'] == null || $product['Product']['deliver_user_id'] == 0) {
                                                echo $this->Form->input('Product_id.', array('type' => 'checkbox', 'value' => $product['Product']['id'],'class'=>'cbimg', 'div' => false, 'label' => false));
                                            } ?>
                                        </td>
                                        <td>
                                            <?php echo $product['Product']['name_file_product']?>
                                        </td>
<!--                                        <td>-->
<!--                                            --><?php
////                                            $img_file = get_headers($domain . (str_replace('@', '/', rawurlencode($dir . $product['Product']['url'] . '@' . rawurlencode($product['Product']['name_file_product'])))), 1);
////                                            $file_size = floor($img_file["Content-Length"] / (1024 * 1024));
//                                            $file_size = floor($product['Product']['file_size'] / (1024 * 1024));
//                                            $file_name = explode('.', $product['Product']['name_file_product']);
//                                            if (isset($PE[$file_name[count($file_name) - 1]])) {
//                                                ?>
<!--                                                <img style="width: 50px;" src="--><?php //echo $this->webroot . 'img/oQ1R0t0.png';  ?><!--">-->
<!--                                            --><?php
//                                            } else {
//                                                if ($file_size <= 20) {
//                                                    ?>
<!--                                                    <img style="width: 50px;" src="--><?php //echo  $domain . (str_replace('@', '/', rawurlencode($dir . $product['Product']['url'] . '@' . rawurlencode($product['Product']['name_file_product']))));  ?><!--">-->
<!--                                                --><?php
//                                                } else {
//                                                    ?>
<!--                                                    <img style="width: 50px;" src="--><?php //echo $this->webroot . 'img/oQ1R0t0.png';  ?><!--">-->
<!--                                                --><?php
//                                                }
//                                            }
//                                            ?>
<!--                                        </td>-->
                                        <td><?php echo $product['Processtype']['name']?></td>
                                        <td><?php echo $product['Product']['deliver_date']?></td>
                                        <td><?php echo $product['Performer']['name']?></td>
                                        <td><?php echo $product['Product']['date_of_completion']?></td>
                                        <td><?php
//                                            if($product['Product']['date_of_completion']) {
//                                                $tgxl = strtotime($product['Product']['date_of_completion']) - strtotime($product['Product']['deliver_date']);
//                                                echo date('i \p\h\ú\t s \g\i\â\y', $tgxl);
//                                            }
                                            $rs = $this->requestAction(Router::url(array('controller' => 'workings', 'action' => 'working_time', $product['Product']['id'])));
                                            if(!empty($rs['Working']['process_time'])) {
                                                echo gmdate("H:i:s",$rs['Working']['process_time']);
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $product['Processtype']['time_counting'] . ' (phút)'; ?></td>
                                        <td>
                                            <a data-toggle="modal" id="bt-reject<?php echo $product['Product']['id'] ?>"
                                               class=" reject_product" data-rate="7" data-project-id="<?php echo $product['Project']['ID'];?>"
                                               data-id-product="<?php echo $product['Product']['id'] ?>"
                                               data-file="<?php echo $product['Product']['name_file_product'] ?>"
                                               role="button" href="#reject">Reject</a>
                                            &nbsp;
                                            <?php if($product['Product']['perform_user_id'] == 0){?>
                                            <a style="color: red" onclick="deleteproduct(<?php echo $product['Product']['id']?>)"> Xóa </a><?php }?>
                                        </td>
                                    </tr>
                            <?php }}?>
                        </tbody>
                    </table>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="dataTables_info"
                                 id="table1_info"><?php echo $this->Paginator->counter('Tổng số bản ghi {:current}/{:count}'); ?></div>
                        </div>
                        <!-- <div class="span6">
                            <div class="dataTables_paginate paging_bootstrap pagination">
                                <ul>
                                    <?php
                                    echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
                                    echo $this->Paginator->numbers(array('separator' => '', 'currentTag' => 'a', 'currentClass' => 'active', 'tag' => 'li', 'first' => 1));
                                    echo $this->Paginator->next(__('next'), array('tag' => 'li', 'currentClass' => 'disabled'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
                                    ?>
                                </ul>
                            </div>
                        </div> -->
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
                </div>
            </div>
        </div>
        <?php echo $this->form->end()?>
    </div>
</div>
<script>
    $(function(){
        $("#selectall").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
            $('#countchecked').html('<b>Có '+$("input.cbimg:checked").length + " sản phẩm được chọn</b>");
        });
        $('.bt-assignment').click(function(){
            if($("input.cbimg:checked").length > 0){
                $('#ProductDetailForm').submit();
            }else{
                alert('Bạn phải chọn sản phẩm muốn giao!');
            }
        });
        $('input.cbimg').click(function(){
            $('#countchecked').html('<b>Có '+$("input.cbimg:checked").length + " sản phẩm được chọn</b>");
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

        $('.paging').click(function(){
            var page_num = $(this).attr('data');
            $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'ListviewDetail'),true)?>/<?php echo $project_id ?>/" + page_num + '/<?php echo $limit ?>', function (data) {
                $("#show-products").html(data);
            });
        });
    });

    function deleteproduct(id){
        var d = confirm("Chắc chắn xoá?");
        if(d) {
            $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'deleteProduct'),true)?>/" + id, function (data) {
                alert(data.resultMsg);
                if (data.resultCode == 1) {
                    $('#row' + id).remove();
                }
            });
            return true;
        }else{
            return false;
        }
    }
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
    function Pagination() {
        $('.fixed_bottom').show();
        $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'ListviewDetail'),true)?>/<?php echo $project_id ?>/1/" + $('#pagination').val(), function (data) {
                $("#show-products").html(data);
                $('.fixed_bottom').hide();

            });
        
     }
</script>
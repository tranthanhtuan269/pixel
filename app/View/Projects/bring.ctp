<?php echo $this->element('top_page', array(
    'page_title' => 'Quản lý đơn hàng'
));  ?>
<div id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/"><?php echo __('Trang chủ'); ?></a>
            <span class="divider"><i class="icon-angle-right"></i></span>
        </li>
        <li>
            <a href="<?php echo Router::url(array('action' => 'index')); ?>"><?php echo __('Quản lý đơn hàng'); ?></a>
            <span class="divider"><i class="icon-angle-right"></i></span>
        </li>
        <li class="active">
            <?php echo __('Chi tiết đơn hàng'); ?>
        </li>
    </ul>
</div>
<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i><?php echo 'Chi tiết đơn hàng'; ?></h3>

                <div class="box-tool">
                    <a data-action="collapse" href="#"><i class="icon-chevron-up"></i></a>
                    <a data-action="close" href="#"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <div class="row-fluid">
                    <div class="span4">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h4 class="panel-title">Thông tin đơn hàng</h4>
                            </div>
                            <div class="panel-body">
                                <div class="row-fluid">
                                    <label class="span5"><b><?php echo __('Tên đơn hàng'); ?>:</b></label>

                                    <div class="span7">
                                        <?php echo $project['Project']['Name']; ?>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <label class="span5"><b><?php echo __('Mã Khách hàng'); ?>:</b></label>

                                    <div class="span7">
                                        <?php echo $project['Customer']['customer_code']; ?>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <label class="span5"><b><?php echo __('Nhóm khách hàng'); ?>:</b></label>

                                    <div class="span7">
                                        <?php echo $project['CustomerGroup']['name']; ?>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <label class="span5"><b><?php echo __('Người tạo'); ?>:</b></label>

                                    <div class="span7">
                                        <?php echo $project['User']['name']; ?>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <label class="span5"><b><?php echo __('Nhân viên tham gia'); ?>:</b></label>

                                    <div class="span7">
                                        <a data-toggle="modal" class="btn" role="button" href="#view-joiners">Xem chi
                                            tiết</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="span8">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h4 class="panel-title">Thông tin sản phẩm</h4>
                            </div>
                            <div class="panel-body">
                                <div class="span6">
                                    <div class="row-fluid">
                                        <label class="span6"><b><?php echo __('Số lượng'); ?>:</b></label>

                                        <div class="span6">
                                            <?php echo $project['Project']['Quantity']; ?>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <label class="span6"><b><?php echo __('Hoàn thành'); ?>:</b></label>

                                        <div class="span6">
                                            <?php echo $count_pd; ?>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <label class="span6"><b><?php echo __('Dung lượng ban đầu'); ?>:</b></label>

                                        <div class="span6">
                                            <?php echo round($project['Project']['InitSize']/1024/1024,2).' MB'; ?>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <label class="span6"><b><?php echo __('Dung lượng hoàn thành'); ?>:</b></label>

                                        <div class="span6">
                                            <?php echo round($project['Project']['CompSize']/1024/1024,2).' MB'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="row-fluid">
                                        <label class="span12"><b><?php echo __('Lưu ý ca trưởng/VP'); ?>:</b></label>

                                        <div class="span12">
                                            <?php echo $project['Project']['RequireDevide']; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row-fluid">
                                    <label class="span3"><b><?php echo __('Đường dẫn thư mục'); ?>:</b></label>

                                    <div class="span9">
                                        <?php echo str_replace('@', '/', $dir . $project['Project']['UrlFolder']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-fluid text-center">
                    <div class="span12">
                        <?php pr($filedone); ?>
                        <?php if ($filedone >= $project['Project']['Quantity'] && $project['Project']['Status_id']!=6) { ?>
                            <!--                             <button class="btn btn-danger" onclick="upload(<?php echo $project['Project']['ID']?>)">Start upload</button>
 -->                             <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal"
                                    data-target="#upload-modal">
                                Start Upload
                            </button>
                        <?php } ?>

                        <!-- Modal -->
                        <form id="upload_form">
                        <div class="modal fade" id="upload-modal" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel"><b>Bổ sung thông tin dự án</b></h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="span6">Số lượng file thực tế:</div>
                                        <div class="span4"><input name="real_quality" value="<?php echo $filedone; ?>"
                                                                  id="real_quanlity" type="text"></div>


                                        <div class="span6">Gửi đến email của khách hàng:</div>
                                        <div class="span4" style="text-align: left">
                                            <input type="checkbox" value="1" name="client_mail_check"
                                                   id="client_mail_check"/>
                                            <input name="client_email" id="client_email"
                                                   value="<?php echo $project['Customer']['email']; ?>" type="hidden">
<!--                                            <span style="color: blue">--><?php //echo $project['Customer']['email']; ?><!--</span>-->
                                        </div>
                                        <div class="span6">Gửi đến Email:</div>
                                        <div class="span4" style="text-align: left">
                                            <input name="send_email" id="send_email" value="" type="text"></div>
                                        <div class="span6">CC:</div>
                                        <div class="span4" style="text-align: left">
                                            <input name="cc_email" id="cc_email" value="" type="text"></div>
                                        <div class="span6 projectlink" style="display: none">Đường link dự án:</div>
                                        <div class="span4 projectlink" style="text-align: left;display: none">
                                            <input name="pj_link" id="pj_link" value="" type="text"></div>
                                        <div class="span6"></div>
                                        <div class="span4">
                                            <button type="button" class="btn btn-primary upload2" style="display: none"
                                                    onclick="upload2(<?php echo $project['Project']['ID'] ?>)">Hoàn thành dự án
                                            </button>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng lại
                                        </button>
                                        <button type="button" class="btn btn-default upload1" onclick="upload1()">Thực hiện upload
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                        <button class="btn btn-danger"
                                onclick="location.href='<?php echo $this->html->url(array('controller' => 'Projects', 'action' => 'detail', $project['Project']['ID']), true); ?>'">
                            Trờ về chi tiết đơn hàng
                        </button>
                    </div>
                </div>
                <div class="row-fluid" id="show-products">
                    <div class="box box-gray">
                        <div class="box-title span12">
                            <div class="span7"><h3><i class="icon-puzzle-piece"></i>Danh sách Folder</h3></div>

                            <div class="box-tool span5">
                                <div class="btn-group">
                                    <?php
                                    $check_count = true;
                                    $done_count = true;
                                    $has_check = 0;
                                    if (isset($list)) {
                                        foreach ($list as $k => $item) {
                                            $xk = 0;
                                                    foreach ($item['products'] as $_key => $_value) {
                                                        # code...
                                                        $xk = $_key;
                                                    }
                                            if($item['check'] != count($item['products'])){
                                                $check_count = false;
                                                $has_check = $item['products'][$xk]['Project']['HasCheck'];
                                            }
                                            if($item['done'] != count($item['products'])){
                                                $done_count = false;
                                            }
                                        }
                                    }
                                    if(($check_count==true || $has_check ==1) && $done_count == false){
                                    ?>
                                    <button class="btn btn-warning" onclick="done_all()">Done all</button>
                                    <?php }?>
                                </div>
                            </div>


                        </div>
                        <div style="background: yellow; text-align: right; padding: 5px; color: red; font-size: 18px; font-weight: bold">
                            Chú ý: Không check hàng ở nhiều tab khác nhau (Chỉ mở 1 tab)
                        </div>
                        <div class="box-content">

                                             <?php if (isset($list)) {
                                                if($project['Project']['ID']==2138){
//                                                    debug($list);die;
                                                }
//
                                                $check_mem =0;

                                                foreach ($list as $k => $item) {
                                                    if(isset($item['products']) && count($item['products'])){
                                                    $check_mem++;
                                                   // debug($k);die;
                                                    $xk = 0;
                                                    foreach ($item['products'] as $_key => $_value) {
                                                        # code...
                                                        $xk = $_key;
                                                    }


                                                    $rs = $this->requestAction(Router::url(array('controller' => 'projects', 'action' => 'checker_check', $item['products'][$xk]['Product']['id'])));

                                                    ?>
                                                    <div >
                                                        <div class="span7">
                                                            <i class="icon-folder-open"></i>
                                                            <?php echo $item['dirname'] . " <b>(" . $item['done'] . '/' . count($item['products']) . " Files)</b>" ?>
                                                        </div>
                                                        <div class="span5">
                                                        <?php if($rs == 1){?>
                                                            <?php if($item['done']!= count($item['products'])){ ?>

                                                                <?php if($item['check']!= count($item['products'])){ ?>
                                                                    <button class="btn btn-primary"
                                                                            onclick="check(<?php echo $k . ',' . $project['Project']['ID'] . ',' . $item['deliver_id'] ?>)">
                                                                        Check
                                                                    </button>
                                                                <?php }else{
                                                                    ?>
                                                                    <span style="font-size: 18px; font-weight: bold; color: darkgreen"><i style="" class="icon-ok-circle"></i> đã check </span>  -
                                                                <?php }
                                                                ?>
                                                                <button class="btn btn-warning"
                                                                        onclick="<?php if ($item['products'][$xk]['Project']['HasCheck'] == 1||$item['check']== count($item['products'])) { ?>done(<?php echo $k . ',' . $project['Project']['ID'] . ',' . $item['deliver_id'] ?>) <?php } else { ?>alert('Bạn cần check sản phẩm trước khi trả hàng!');<?php } ?>">
                                                                    Done
                                                                </button>
                                                                <?php

                                                                }else{
                                                                    ?>
                                                                    <span style="font-size: 18px; font-weight: bold; color: darkgreen"><i style="" class="icon-ok-circle"></i> đã done </span>  - -<?php echo $item['doner']; ?>
                                                               <?php }?>

                                                        <?php }elseif($item['done']!= count($item['products'])){
                                                             echo count($item['done']) .".".count($item['check']);

                                                            if($item['check']!= count($item['products'])){?>
                                                                        <span style="font-size: 18px; font-weight: bold; color: darkgreen"><i style="" class="icon-ok-circle"></i> Sản phẩm đang chờ check </span><button class="btn btn-primary" onclick=" check(<?php echo $k . ',' . $project['Project']['ID'] . ',' . $item['deliver_id'] ?>)">Check
                                                                    </button> -
                                                                    <?php }else{?>
                                                                    <span style="font-size: 18px; font-weight: bold; color: darkgreen"><i style="" class="icon-ok-circle"></i> đã check </span>  -
                                                                    <?php }?>
                                                            <button class="btn btn-warning"

                                                                    onclick="<?php if ($item['products'][$xk]['Project']['HasCheck'] == 1||$item['check']== count($item['products'])) { ?>done(<?php echo $k . ',' . $project['Project']['ID'] . ',' . $item['deliver_id'] ?>) <?php } else { ?>alert('Bạn cần check sản phẩm trước khi trả hàng!');<?php } ?>">
                                                                Done
                                                            </button>
                                                        <?php }else{?>
                                                            <span style="font-size: 18px; font-weight: bold; color: darkgreen"><i style="" class="icon-ok-circle"></i> đã done </span>  - -<?php echo $item['doner']; ?>
                                                        <?php }?>

                                                            <span><i class="icon-check"></i>Nhân viên check: </span>
                                                            <span id="name_user-<?php echo $check_mem ?>"
                                                                  readonly="true"><?php echo $item['checker'] ?> </span>

                                                            <input type="hidden" value="" name="NV_ID" id="id_user-<?php echo $check_mem ?>"
                                                                   onchange="addChecker(this.value,<?php echo $item['deliver_id'] . ',' . $project['Project']['ID'] . ',\'' . $item['id_products'] ?>')"/>
                                                            <?php if ($item['check'] == 0 && $item['done'] == 0) { ?>
                                                                <a
                                                                    data-toggle="modal" class="btn select-checkbox-user"
                                                                    onclick="getNVS(<?php echo $check_mem ?>)" role="button"
                                                                    href="#modal-1">Chọn</a>
                                                                    <span class = "ok-check" stt="<?php echo $check_mem ?>"
                                                                    id="ok_check-<?php echo $check_mem ?>"></span>
                                                            <?php } ?>
                                                            </div>
                                                            </div>
                                                 <?php }
                                                }
                                            } ?>


                        </div>
                    </div>
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
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="view-joiners"
     style="display: none;">
    <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
        <h3 id="myModalLabel">Danh sách nhân viên tham gia đơn hàng</h3>
    </div>
    <div class="modal-body">
        <div class="box">
            <div class="box-content">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Họ tên</th>
                        <th>Loại xử lý</th>
                        <?php if($group_id_user == 1){?>
                            <th>Điểm</th>
                        <?php }?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($userpoints as $k => $userpoint) { ?>
                        <tr>
                            <td><?php echo $k + 1; ?></td>
                            <td><?php echo $userpoint['User']['name'] ?></td>
                            <td><?php echo $userpoint['Action']['Name'] ?></td>
                            <?php if($group_id_user == 1){?>
                                <td><?php echo $userpoint['ProjectAction']['Point'] ?></td>
                            <?php }?>

                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button aria-hidden="true" data-dismiss="modal" class="btn">Close</button>
    </div>
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide" id="modal-1"
     style="display: none;">
</div>
<!-- END Main Content -->
<a id="btn-scrollup" class="btn btn-circle btn-large" href="#"><i class="icon-chevron-up"></i></a>
<style>
    .grid-product-img {
        max-height: 100px;
        overflow: hidden;
        border: 1px solid #d9d9d9;
    }

    .grid-product-img .checkbox {
        width: 100%;
        height: 100px;
        padding-left: 30px;
    }
</style>
<script>
    function getNVS(stt) {
        $('.fixed_bottom').show();
        $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'RadioUsers'),true)?>" + "/" + stt, function (data) {
            $("#modal-1").html(data);
            $('#ok_check-' + stt).html('<a class="btn btn-primary" role="button">OK</a>')
            $('.fixed_bottom').hide();
        });
    }

    function addChecker(checker_id, deliver_id, project_id, products) {
        $.post("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'addChecker'),true)?>/", {checker: checker_id, deliver: deliver_id, project: project_id, products: products})
            .done(function (data) {
                if (data == 0) {
                    alert('Thay đổi người kiểm tra lỗi! Hãy thử lại.');
                } else {
                    alert('Đã thay đổi nhân viên check');
                    location.reload();
                }
            });
    }

    function check(k, project_id, deliver_id) {
        location.href = "<?php echo $this->html->url(array('controller'=>'Projects','action'=>'check'),true);?>/" + project_id + '/' + deliver_id + '/' + k;
    }

    function done(k, project_id, deliver_id) {
        $('.fixed_bottom').css('display', 'block');

        $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'done'),true)?>/" + project_id + '/' + deliver_id + '/' + k, function (data) {
                if (data == 0) {
                    alert('Có lỗi xảy ra! Hãy thử lại.');
                } else {
                    if (data.result == 1) {
                        location.reload();
                    } else {
                        alert('Có lỗi xảy ra khi copy file vào thư mục Done! Hãy thử lại.');
                        $('.fixed_bottom').css('display', 'none');
                    }
                }
            }
        );


    }
    function done_all(){
        $('.fixed_bottom').css('display', 'block');
        var result_done =1;
        <?php
            if (isset($list)) {
                foreach ($list as $k => $item) {
                    if(isset($item['products']) && count($item['products'])){
        ?>
        $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'done'),true)?>/" + <?php echo $project['Project']['ID']?> + '/' + <?php echo $item['deliver_id']?> + '/' + <?php echo $k?>, function (data) {
            if (data == 0) {
                alert('Có lỗi xảy ra! Hãy thử lại.');
            } else {
                if (data.result == 1) {
//                    location.reload();
                } else {
                    result_done = 0;
                    alert('Có lỗi xảy ra khi copy file vào thư mục Done! Hãy thử lại.');
                    $('.fixed_bottom').css('display', 'none');
                }
            }
        });
        <?php
                }
                }
            }
        ?>
        if(result_done != 0){
            alert('Thành công');
            location.reload();
        }
    }
    function upload1(){
        $('.fixed_bottom').show();
        $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'userupload',$project['Project']['ID']),true)?>",function(){
            $('.projectlink').css('display', 'block');
            $('.upload1').css('display', 'none');
            $('.upload2').css('display', 'block');
            $('.fixed_bottom').hide();
        });

    }

    function upload2(project_id) {
        $('.fixed_bottom').show();
        $('button.upload2').attr('onclick','alert("Hệ thống đang thực hiện");');
        var real_quality = "";
        if ($('#real_quality').val()) {
            real_quality = "/" + $('#real_quality').val();
        }

        var email = "";
        if ($('#client_mail_check').prop("checked")) {
            email = "/" + $('#client_email').val();

        } else {
            email = "/" + $('#send_email').val();
        }
        var cc = "";
        if ($('#cc_email').val()) {
            cc = "/" + $('#cc_email').val();

        }
        var link = "";
        link = $('#pj_link').val();

        $.ajax( {
            type: "POST",
            url: '<?php echo $this->html->url(array('controller'=>'Projects','action'=>'upload_done'),true)?>/'+project_id+'<?php if(isset ($_GET['abc']) && $_GET['abc']){ ?>?debug=2<?php } ?>',
            data: $('#upload_form').serialize(),
            success: function( data ) {
//                console.log( response );
//                alert(data.resultMsg);
            if (data.resultCode == 1) {
                $.get("<?php echo $this->html->url(array('controller'=>'Projects','action'=>'changestatusproject'),true)?>/" + project_id + '/' + 6, function (data) {
                    location.href = "<?php echo $this->html->url(array('controller'=>'Projects','action'=>'index'),true)?>";
                });
            }
            $('.fixed_bottom').hide();
            }
        } );
//        $.get("<?php //echo $this->html->url(array('controller'=>'Projects','action'=>'upload_done'),true)?>///" + project_id + real_quality + email + cc, function (data) {
//            alert(data.resultMsg);
//            if (data.resultCode == 1) {
//                $.get("<?php //echo $this->html->url(array('controller'=>'Projects','action'=>'changestatusproject'),true)?>///" + project_id + '/' + 6, function (data) {
//                    location.href = "<?php //echo $this->html->url(array('controller'=>'Projects','action'=>'index'),true)?>//";
//                });
//            }
//            $('.fixed_bottom').hide();
//
//        })

    }

    $(document).ready(function () {
        $('#client_mail_check').click(function () {
            if (this.checked) {
                $('#send_email').val('');
                $('#cc_email').val('');
                $('#send_email').hide();
                $('#cc_email').hide();
            } else {
                $('#send_email').show();
                $('#cc_email').show();
            }
        });


        $('.ok-check' ).click(function(){
            $("#id_user-" + $(this).attr('stt')).trigger('change');
        })

    });

</script>
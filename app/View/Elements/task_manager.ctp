<?php if($group_id_user == 1 || $group_id_user == 3 || $group_id_user == 6 || $group_id_user == 7){?>
    <div class="span3" id="message">
        <div class="box box-green">
            <div class="box-title">
                <h3><i class="icon-check"></i> Task Manager</h3>
                <div class="box-tool">
                    <a  data-action="collapse" ><i id="check_manager"  class="icon-chevron-down"></i></a>
                </div>
            </div>
            <div id="content_manager" class="box-content" style="display: none">

            </div>
        </div>
    </div>
    <script>
        $('#check_manager').click(function () {
            $.post("<?php echo Router::url(array('plugin' => false, 'controller' => 'TaskManagers', 'action' => 'add_row')); ?>")
                .done(function (data) {
                    if (data) {
                        $('#content_manager').html(data);
                    }
                });
        });
    </script>
<?php }?>
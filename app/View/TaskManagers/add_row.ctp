<ul class="todo-list">
    <?php
    foreach ($task_manager as $item) {
        ?>
        <li>
            <div class="todo-desc">
                <p><?php echo $item['TaskManager']['message'].'('.$item['User']['name'].')';?></p>
            </div>
            <div class="todo-actions">
                <span><?php
                   $today = date('Y-m-d');
                    if($today > $item['TaskManager']['time']){
                        echo date_format(date_create($item['TaskManager']['time']),'d/m');
                    }else{
                        echo date_format(date_create($item['TaskManager']['time']),'H:i');
                    }
                    ?></span>
                <a class="show-tooltip" href="#" title="" data-original-title="It's done"><i class="icon-check"></i></a>
                <a class="show-tooltip" href="#" title="" data-original-title="Remove"><i class="icon-remove"></i></a>
            </div>
        </li>
    <?php
    }
    ?>
</ul>
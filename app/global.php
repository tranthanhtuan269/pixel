<?php
/**
 * Created by PhpStorm.
 * User: luckymancvp
 * Date: 5/31/17
 * Time: 1:27 PM
 */

function get($item, $key){
    return isset($item[$key]) ? $item[$key] : '';
}
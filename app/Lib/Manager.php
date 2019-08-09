<?php
/**
 * Created by PhpStorm.
 * User: luckymancvp
 * Date: 7/2/17
 * Time: 10:29 AM
 */


class Manager {
    public static $realFolder = '/Store/Giao_nhan_hang/';


    public static function listFolder($dir)
    {
        /*if (!is_dir($dir))
            return array();*/
        $folders = scandir(self::$realFolder . $dir);
        $res = array();
        foreach ($folders as $folder)
            if (($folder!='.') && ($folder != '..'))
                $res[] = $dir . DIRECTORY_SEPARATOR . $folder;
        return $res;
    }
    
    public function createUser($username)
    {
        die($username);
    }
}
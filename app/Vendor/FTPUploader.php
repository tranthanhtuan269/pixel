<?php

class FTPUploader
{

    public $host = 'localhost';
    public $user = 'pixel_vp';
    public $pass = ')oKmNji9';
    public $mode = FTP_BINARY;
    public $ftp_stream = 'a';
    public $projectFolder = "Giao_nhan_hang";
    public $domain = "http://pixel-files.pixelvn.com:21/";
    // public $domain = "http://pixelvn.luckymancvp.com:10/";

    public static $realFolder = '/Store/Giao_nhan_hang/';

    public function __construct($host, $user, $pass, $mode = FTP_BINARY)
    {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->mode = $mode;
        $this->ftp_stream = $this->connect($this->host, $this->user, $this->pass);
//        pr($this->ftp_stream);die;
//        return $this->ftp_stream;
    }

    public function change($host, $user, $pass, $mode = FTP_BINARY)
    {
//        return ($pass);
        if ($host == '' || $user == '' || $pass == '') {
            return false;
        }
        if (count(explode('.', $host)) < 2) {
            return false;
        }
        $this->host = trim($host);
        $this->user = trim($user);
        $this->pass = trim($pass);
        $this->mode = trim($mode);
        $this->ftp_stream = $this->connect($this->host, $this->user, $this->pass);
        return $this->ftp_stream;
    }

    public static function connect($server_address, $username, $password)
    {
        try {
            $ftp_conn = ftp_connect($server_address);
            ftp_login($ftp_conn, $username, $password);
        } catch (Exception $e) {
            return FALSE;
        }
        return $ftp_conn;
    }

    /**
     * luckymancvp
     * Use when create a new project
     * @param $dir
     * @return bool|string
     */
    public function make_directory($dir)
    {
//        if (strpos($dir, $this->projectFolder) === false)
//            $dir = $this->projectFolder . DIRECTORY_SEPARATOR . $dir;
// if directory already exists or can be immediately created return true
//        die(var_dump(FTPUploader::ftp_is_dir($this->projectFolder)));

        if (FTPUploader::ftp_is_dir($dir) || @ftp_mkdir($this->ftp_stream, $dir)) {
            ftp_chmod($this->ftp_stream, 0777, $dir);
            return true;
        }
// otherwise recursively try to make the directory
        if (!FTPUploader::make_directory(dirname($dir))) return false;
// final step to create the directory
        $result = ftp_mkdir($this->ftp_stream, $dir);

        ftp_chmod($this->ftp_stream, 0777, $dir);

        $data = (ftp_rawlist($this->ftp_stream, dirname($dir)));

        return $result;
    }

    public function ftp_is_dir($dir)
    {
// get current directory
        $original_directory = ftp_pwd($this->ftp_stream);
// test if you can change directory to $dir
// suppress errors in case $dir is not a file or not a directory
        if (@ftp_chdir($this->ftp_stream, $dir)) {
// If it is a directory, then change the directory back to the original directory
            ftp_chdir($this->ftp_stream, $original_directory);
            return true;
        } else {
            return false;
        }
    }

    public static function write_remote_file($file_content, $remote_path, $remote_url, $server_address, $username, $password)
    {

// writing content to a temporary file
        $tmp_file = 'tmp.' . date('YmdHis'); /* timestamp to prevent overwriting existing file */
        $tmp = fopen($tmp_file, 'w+');
        fwrite($tmp, $file_content);
        fclose($tmp);
// upload the temporary file
        $upload = FTPUploader::upload($tmp_file, $remote_path, $remote_url, $server_address, $username, $password, FTP_ASCII);
// delete temporary file
        unlink($tmp_file);
        $result = $upload;
        return $result;
    }

    public function upload($local_file_location, $remote_path, $remote_url = '')
    {
        $error_msg = '';
// try to connect

        $output['resultCode'] = 1;
        $output['resultMsg'] = 'Success!';
        $ftp_conn = $this->ftp_stream;
        if (!$ftp_conn) {
            $output['resultCode'] = 3;
            $output['resultMsg'] = 'Could not connect to server';
        }
// create directories if don't exist
        $path = explode('/', $remote_path);
        unset($path[count($path) - 1]);
        $path = implode('/', $path);
        $mkdir = FTPUploader::make_directory($path);
        if (!$mkdir) {
            $output['resultCode'] = 2;
            $output['resultMsg'] = 'Could not create folder';
        }
// create backup file
//        if(! is_dir('.ftpbackup')) mkdir('.ftpbackup', 0777);
        //  $segments = explode('/', $local_file_location);
        // $filename = $segments[count($segments) - 1];
        // $chk_uploaded = copy($remote_path, $local_file_location.'/'.$filename);
        //$chk_uploaded = move_uploaded_file($remote_path,)
// upload the file


        //      echo $local_file_location;
        //       echo $remote_path."<br>";

        $chk_uploaded = ftp_put($ftp_conn, $remote_path, $local_file_location, $this->mode);
//		die('xxx');
//        ftp_chmod($this->ftp_stream,0755, $remote_path);
        if (!$chk_uploaded) {
            $output['resultCode'] = 0;
            $output['resultMsg'] = 'Could not upload file';
        }

// checking remote_url if file exist
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $remote_url);
//        curl_setopt($ch, CURLOPT_HEADER, TRUE);
//        curl_setopt($ch, CURLOPT_NOBODY, TRUE);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//        $head = curl_exec($ch);
//        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//        curl_close($ch);
//        $chk_verify = ($http_code < 400);
//        if (! $chk_verify)
//        {
//            $error_msg = 'Could not verify file was uploaded via URL';
//        }
        return $output;
    }

    public function listfiles($dir = '.')
    {
        putenv('TMPDIR=/tmp/');
        $list = ftp_nlist($this->ftp_stream, $dir);
        return $list;
    }

    public function file_size($file)
    {
        $size = ftp_size($this->ftp_stream, $file);
        return $size;
    }

    public function copy($urlsource, $dirsource, $dest)
    {
        if (ftp_size($this->ftp_stream, $dirsource) != -1) {
            $tmp = explode("/", $dest);
            $dest_folder = "";
            for ($i = 0; $i < count($tmp) - 2; $i++) {
                $dest_folder .= $tmp[$i] . "/";
            }
            $this->make_directory($dest_folder);
//            ftp_chmod($this->ftp_stream,777, $dest);

//	        $tmp =explode("/",$dest);
//	        $dest_new = "";
//	        for($i=0; $i<count($tmp)-1; $i++){
//		        $dest_new .= $tmp[$i]. "/";
//	        }
//	        $dest_new .= $this->refresh_filename($tmp[count($tmp)-1]);
//
//	        $tmp =explode("/",$urlsource);
//	        $source_new = "";
//	        for($i=0; $i<count($tmp)-1; $i++){
//		        $source_new .= $tmp[$i]. "/";
//	        }
//	        $source_new .= $this->refresh_filename($tmp[count($tmp)-1]);
//
//	        $tmp =explode("/",$dirsource);
//	        $dirsource_new = "";
//	        for($i=0; $i<count($tmp)-1; $i++){
//		        $dirsource_new .= $tmp[$i]. "/";
//	        }
//	        $dirsource_new .= $this->refresh_filename($tmp[count($tmp)-1]);
//
//	        ftp_rename($this->ftp_stream,$dirsource, $dirsource_new);

//	        $upload = ftp_put($this->ftp_stream,$dest_new,$source_new,$this->mode);
//            echo $this->projectFolder."/".$dest;
            $urlsource = str_replace('http://pixel-files.pixelvn.com/', $this->projectFolder, $urlsource);

            $urlsource = str_replace('Giao_nhan_hang', self::$realFolder, $urlsource);
            $urldest = str_replace('Giao_nhan_hang', self::$realFolder, $this->projectFolder . $dest);

//	        $upload = copy($urlsource,$this->projectFolder."/".$dest);
//            $upload = exec("copy \"". str_replace("/","\\",$urlsource ) ."\" \"". $this->projectFolder.$dest. "\"");
            $str = "cp \"" . $urlsource . "\" \"" . $urldest . "\"";

            $command = $str;
            $command = $command . ' 2>&1';
            $fileupload = exec($command, $out);
            $upload = 'x';

            if ($upload == '') {
                $output['resultCode'] = 0;
                $output['resultMsg'] = 'Could not upload file';
            } else {
                $output['resultCode'] = 1;
                $output['resultMsg'] = 'Success!';
//	            ftp_rename($this->ftp_stream,$dest_new,$dest);

            }
        } else {
            $output['resultCode'] = 2;
            $output['resultMsg'] = 'File not exists!';
        }
        return $output;

    }

    public function refresh_filename($content)
    {
        $tmp = explode(".", $content);
        return date("YMDHIS" . $tmp[count($tmp) - 1]);

    }

    public function move($urlsource, $dirsource, $dest)
    {
        $base = $dirsource;
        $dirsource = self::$realFolder . $dirsource;
        if (!file_exists($dirsource))
            return array('resultCode' => 2,
                'resultMsg' => 'File not exists!');

        $res = rename($dirsource, self::$realFolder . $dest);
        /*if (!$res){
            ftp_chmod($this->ftp_stream, 0777, ($base));
            ftp_chmod($this->ftp_stream, 0777, dirname($base));
            return $this->move($urlsource, $base, $dest);
        }*/


        if (!$res)
            return array('resultCode' => 0,
                'resultMsg' => 'Could not upload file');

        return array('resultCode' => 1,
            'resultMsg' => 'Success!');
//	    die();
//        return $urlsource;
        // debug($dirsource);
        if (ftp_size($this->ftp_stream, $dirsource) != -1) {
//            $this->make_directory($dest);

//	        $tmp =explode("/",$dest);
//	        $dest_new = "";
//	        for($i=0; $i<count($tmp)-1; $i++){
//		        $dest_new .= $tmp[$i]. "/";
//	        }
//	        $dest_new .= $this->refresh_filename($tmp[count($tmp)-1]);
//
//	        $tmp =explode("/",$urlsource);
//	        $source_new = "";
//	        for($i=0; $i<count($tmp)-1; $i++){
//		        $source_new .= $tmp[$i]. "/";
//	        }
//	        $source_new .= $this->refresh_filename($tmp[count($tmp)-1]);
//
//	        $tmp =explode("/",$dirsource);
//	        $dirsource_new = "";
//	        for($i=0; $i<count($tmp)-1; $i++){
//		        $dirsource_new .= $tmp[$i]. "/";
//	        }
//	        $dirsource_new .= $this->refresh_filename($tmp[count($tmp)-1]);
            //ftp_rename($this->ftp_stream,$dirsource, $dirsource_new);
            //$upload = ftp_put($this->ftp_stream,$dest_new,$source_new,$this->mode);
            $str = "move \"" . str_replace("/", "\\", $this->projectFolder . $dirsource) . "\" \"" . str_replace("/", "\\", $this->projectFolder . $dest) . "\"";
            $str = "chcp 65001 \r\n" . mb_convert_encoding($str, "UTF-8", mb_detect_encoding($str, "UTF-8, ISO-8859-1, ISO-8859-15", true));
            $filename = str_replace("/", "\\", $this->projectFolder) . 'command.bat';
            $handle = fopen($filename, "w+");
            fwrite($handle, str_replace('%', '%%', $str));
            fclose($handle);
            $upload = exec($filename);
            $del_cmd = exec("del /q " . $filename);

            if ($upload != '        1 file(s) moved.') {
                $output['resultCode'] = 0;
                $output['resultMsg'] = 'Could not upload file';
            } else {
                //unlink($urlsource);
                $output['resultCode'] = 1;
                $output['resultMsg'] = 'Success!';
//	            ftp_rename($this->ftp_stream,$dest_new,$dest);

            }

        } else {
            $output['resultCode'] = 2;
            $output['resultMsg'] = 'File not exists!';
        }
        return $output;
    }

    public function delete($dirsource)
    {
        $dest = self::$realFolder . $dirsource;
        if (ftp_size($this->ftp_stream, $dirsource) != -1) {
            $upload = '1';
            $output = array();
            $del_cmd = unlink($dest);
            if ($upload == '') {
                $output['resultCode'] = 0;
                $output['resultMsg'] = 'Could not delete file';
            } else {
                $output['resultCode'] = 1;
                $output['resultMsg'] = 'Success!';
            }
        } else {
            $output['resultCode'] = 2;
            $output['resultMsg'] = 'File not exists!';
        }
        return $output;
    }

    public function recursiveDelete($directory)
    {
        if (strlen($directory) < 7)
            return;
        $command = "rm -rf ". self::$realFolder . $directory;
        exec($command);
        return ;



        # here we attempt to delete the file/directory
        if (!(@ftp_rmdir($this->ftp_stream, $directory) || @ftp_delete($this->ftp_stream, $directory))) {
            # if the attempt to delete fails, get the file listing
            $filelist = @ftp_nlist($this->ftp_stream, $directory);

            # loop through the file list and recursively delete the FILE in the list
            foreach ($filelist as $file) {
                FTPUploader::recursiveDelete($file);
            }

            #if the file list is empty, delete the DIRECTORY we passed
            FTPUploader::recursiveDelete($directory);
        }
    }

    public function delete_folder($dirsource)
    {
        if (ftp_rmdir($this->ftp_stream, $dirsource)) {
            $files = ftp_nlist($this->ftp_stream, ".");
            foreach ($files as $file) {
                ftp_delete($this->ftp_stream, $file);
            }
            $output['resultCode'] = 1;
            $output['resultMsg'] = 'Success!';
        } else {

            $output['resultCode'] = 0;
            $output['resultMsg'] = 'Could not delete file';
        }
        return $output;
    }

    public function close()
    {
        ftp_close($this->ftp_stream);
    }

}

?>

<?php

class test {
    
    public function getAllId($data, $modelName, $idField = 'id') {
        $res = array();
        foreach ($data as $item) {
            $res[] = $item[$modelName][$idField];
        }
        
        return $res;
    }
    
}
?>
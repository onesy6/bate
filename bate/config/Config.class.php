<?php

class Config {
    
    public $config = array();
    
    public function getConfig($path = 'config.inc.php') {
        if ($path) {
            $conInc = include_once __DIR__ . DIRECTORY_SEPARATOR . $path;
        } else {
            throw new LogicException('路径不能为空.');
        }
        
        $commonInc = include_once __DIR__ . DIRECTORY_SEPARATOR . 'config_common.inc.php';
        
        $this->config = array_merge($commonInc, $conInc);
        
        return json_decode(json_encode($this->config));
    }
}

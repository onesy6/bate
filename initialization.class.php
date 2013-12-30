<?php
class Initialization {
    
    public function getDomainFromHost($host) {
        $slices = explode(".", $host);
        $domain = $slices[1] . '.' .  $slices[2];
        return $domain;
    }

    public function getDispatchRoute($config) {
        $route = $config['app_route'][DOMAIN];
        include $route;
    }
}
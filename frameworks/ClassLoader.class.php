<?php

class ClassLoader {
    
    public static $UserClassPreffix = array('controller', 'module', 'collection', 'model');
    
    public static $SeparetorInName = '_';
    
    public static $supportedSuffix = array('.class.php', '.php');
    
    /**
     * ClassPath => array('controller' => ''.....);
     * 
     * @var array $ClassPath
     */
    public static $ClassPath = array();
    
    public static function AppendPath2Loader($pathes) {
        if (is_array($pathes)) {
            foreach ($pathes as $key => $value) {
                self::$ClassPath[$key] = $value;
            }
        } else {
            throw new ArgumentsException('路径参数错误，请传入路径数组');
        }
    }
    
    /**
     * 加载用户类的规则.
     * 
     * @param array $classname
     */
    public static function UserClassLoader($classname_parts) {
        $class_name = implode(self::$SeparetorInName, $classname_parts);
        // 必须是用户类的前缀且用户类的路径中要有描述.
        $flg = in_array($classname_parts[0], self::$UserClassPreffix, true) && in_array($classname_parts[0], self::$ClassPath, true);
        if (!$flg) {
            // 前缀未找到路径定义.
            $flg = false;
            return $flg;
        }
        $path = self::getUserClassPathByNameParts($classname_parts);
        $file_path = self::$ClassPath[$classname_parts[0]] . DIRECTORY_SEPARATOR . $path . '.class.php';
        if (!file_exists($file_path)) {
            // 定义的文件不存在.
            $flg = false;
            return $flg;
        }
        include_once $file_path;
        if ( ! class_exists($class_name, false)) {
            // 指定文件内并不存在这么一个类.加载失败.
            $flg = false;
            return $flg;
        } else {
            $flg = true;
            return $flg;
        }
    }
    
    /**
     * 系统插件类的规则.插件通过一个合乎路径的引导文件进行加载.
     * 
     * @param array $classname
     */
    public static function PluginClassLoader($classname_parts) {
        return self::ClassLoading($classname_parts, 'plugin');
    }
    
    /**
     * 系统类加载规则.
     * 
     * @param array $classname
     */
    public static function SystemClassLoader($classname_parts) {
        return self::ClassLoading($classname_parts, 'system');
    }
    
    public static function ClassLoading($classname_parts, $kind) {
        $flg = in_array($kind, self::$ClassPath, true);
        if (! $flg) {
            // plugin前缀未找到定义或者未启用.
            return false;
        }
        $class_name = implode(self::$SeparetorInName, $classname_parts);
        $path = self::getUserClassPathByNameParts($classname_parts, true);
        foreach (self::$supportedSuffix as $suffix) {
            $pathes[] = self::$ClassPath[$kind]  . DIRECTORY_SEPARATOR. $path . $suffix;
        }
        $flg = false;
        foreach ($pathes as $paths) {
            $flg = file_exists($paths) || $paths;
        }
        if (! $flg) {
            // 文件不存在.
            return false;
        }
        
        foreach ($pathes as $paths) {
            include_once $paths;
        }
        if ( ! class_exists($class_name, false)) {
            // 加载失败
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * 综合处理这些加载规则.
     * 
     * @param type $classname
     */
    public static function UnionClassLoader($classname) {
        $name_parts = explode(self::$SeparetorInName, $classname);
        
        if (self::UserClassLoader($name_parts)) {
            return ;
        }
        if (self::SystemClassLoader($name_parts)) {
            return ;
        }
        if (self::PluginClassLoader($name_parts)) {
            return ;
        }
    }
    
    public static function getUserClassPathByNameParts($classname_parts, $is_keep_first = false) {
        if (! $is_keep_first) {
            $classname_parts[count($classname_parts) - 1] = $classname_parts[count($classname_parts) - 1];
            $pathes = array_slice($classname_parts, 1);
            $rtn = implode(DIRECTORY_SEPARATOR, $pathes);
            return $rtn;
        } else {
            $pathes = array_slice($classname_parts, 0);
            $rtn = implode(DIRECTORY_SEPARATOR, $pathes);
            return $rtn;
        }
    }
    
    public static function InitLoader() {
        $cfgObject = new Config();
        $config = $cfgObject->getConfig()->loader_config;
    }
    
}

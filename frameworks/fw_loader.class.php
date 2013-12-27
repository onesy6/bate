<?php

include 'GlobalVarsRegister.class.php';
include 'ProjectException.class.php';
include 'SiteEngine.class.php';
include 'System.class.php';
include 'medoo.class.php';

spl_autoload_register("ClassLoader::UnionClassLoader");
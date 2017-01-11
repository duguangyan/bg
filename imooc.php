<?php
	header("Content-Type: text/html; charset=UTF-8");
	//基于tp(ThinkPHP)框架
	define('APP_DEBUG', true);
	//1.定义项目的名称
	define('APP_NAME', 'Imooc');
	//2.定义项目路径
	define('APP_PATH', './Imooc/');
	//3.映入tp的核心文件	
	require('./ThinkPHP/ThinkPHP.php');   //include

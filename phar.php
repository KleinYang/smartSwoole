<?php

/**
 * 目的 ：将PHP框架项目，打包成一个 .phar 文件
 * 常用 ：这个打包，一般是用PHP语言来做 linux 系统软件的，如composer.phar
 *
 * 
 * 方法：
 * 
 * 压缩 test_phar 目录下的(php,可选)文件
 *
 * 1、将 php.ini 的 `;phar.readonly = On` 改为 `phar.readonly = Off`
 *
 * 2、运行 phar.php（本文件），会在当前目录下生成 test.phar
 *
 * 3、在git-bash里，运行 php ./test.phar
 *
 * 4、在git-bash里（网页也可以），运行php ./test_phar/index.php。输出结果  等同于 第3步输出结果
 */


## 压缩的包名
$phar = new Phar('test.phar');

## 压缩的路径 【可选参数，压缩文件类型】
$phar->buildFromDirectory(__DIR__);

## 压缩方式，这里用 gzip 方式
$phar->compressFiles(Phar::GZ);

$phar->stopBuffering();

## 默认自动加载 test_phar 目录下的哪个文件。|  等同于 nginx 配置 sever {listen 80; …… index index.php; }
$phar->setStub($phar->createDefaultStub('server.php'));

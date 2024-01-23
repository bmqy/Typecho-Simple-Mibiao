<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <?php if($this->options->favicon): ?>
    <link rel="icon" href="<?php $this->options->favicon() ?>" type="image/x-icon" />
    <?php endif; ?>
    <title><?php $this->options->title(); ?></title>
    <!-- 使用url函数转换相关路径 -->
    <link rel="stylesheet" href="<?php $this->options->themeUrl('style.css'); ?>">
    <!-- 通过自有函数输出HTML头部信息 -->
    <?php $this->header(); ?>
    <?php if($this->options->statisticalCode !=''): ?>
        <div style="display: none;"><?php $this->options->statisticalCode(); ?></div>
    <?php endif;?>
</head>
<body>
<div class="invoice">
    <div class="header">
        <?php if ($this->options->logo): ?>
        <img src="<? $this->options->logo() ?>" style="height: 100px">
        <?php endif; ?>
        <h1><? $this->options->title() ?></h1>
    </div><!-- end #header -->

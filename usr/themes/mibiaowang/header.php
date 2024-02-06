<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <?php if($this->options->favicon): ?>
        <link rel="icon" href="<?php $this->options->favicon() ?>" type="image/x-icon" />
    <?php endif; ?>
    <title><?php $this->options->title(); ?></title>
    <!-- 使用url函数转换相关路径 -->

    <link rel="stylesheet" type="text/css" href="<?php $this->options->themeUrl('static/css/layer.css') ?>" />
    <link rel="stylesheet" type="text/css" href="<?php $this->options->themeUrl('static/css/layui.css') ?>" />
    <link rel="stylesheet" href="<?php $this->options->themeUrl('static/css/iconfont.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php $this->options->themeUrl('static/css/animate.css') ?>" />
    <!-- build:css styles/main.css -->
    <link rel="stylesheet" href="<?php $this->options->themeUrl('static/css/common.css') ?>">
    <!-- endbuild -->
    <script type="text/javascript" src="<?php $this->options->themeUrl('static/js/jquery-2.0.3.js') ?>"></script>
    <!-- 通过自有函数输出HTML头部信息 -->
    <?php $this->header(); ?>
    <?php if($this->options->statisticalCode !=''): ?>
        <div style="display: none;"><?php $this->options->statisticalCode(); ?></div>
    <?php endif;?>
</head>
<body>

<header id="header" class="layout" style="background: #fff">
    <!--头部-->
    <div class="container">
        <div id="jm-head">
            <div class="head-box">
                <div id="logo">
                    <a href="<?php $this->options->siteUrl(); ?>" title="<?php $this->options->title() ?>">
                        <img style="max-width:200px;max-height:60px;" src="<?php $this->options->logo() ?>" alt="logo" title="<?php $this->options->title() ?>" class="fl">
                    </a>
                </div>
                </div>
            </div>
        </div>
    </div>
</header><!-- end #header -->

<!--导航-->
<div id="nav">
    <div class="navbar">
        <ul>
            <li><a href="/" class="on"><?php $this->options->title() ?></a></li>
            <li><a href="#sale"  target="_self">在售域名</a></li>
            <li><a href="#jylc"  target="_self">交易流程</a></li>
            <li><a href="#sold"  target="_self">最近成交</a></li>
            <li><a href="#not"  target="_self">非卖品</a></li>
            <div class="clear"></div>
        </ul>
    </div>
</div>
    

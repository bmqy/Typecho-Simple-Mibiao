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
    <script src="https://cdn.tailwindcss.com/3.4.1"></script>
    <link rel="stylesheet" href="<?php $this->options->themeUrl('style.css'); ?>">
    <!-- 通过自有函数输出HTML头部信息 -->
    <?php $this->header(); ?>
    <?php if($this->options->statisticalCode !=''): ?>
        <div style="display: none;"><?php $this->options->statisticalCode(); ?></div>
    <?php endif;?>
    <?php if($this->options->customCss !=''): ?>
        <style>
            <?php $this->options->customCss(); ?>
        </style>
    <?php endif;?>
</head>
<body>

<header id="header" class="container mx-auto">
    <div class="site-name p-3.5 text-center md:box-content">
        <?php if ($this->options->logo): ?>
        <div id="logo"><a href="<?php $this->options->siteUrl(); ?>">
            <img class="shadow-lg" src="<?php $this->options->logo() ?>" alt="<?php $this->options->title() ?>"/>
        </a></div>
        <?php endif; ?>
        <div><a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title() ?></a></div>
        <p class="description"><?php $this->options->description() ?></p>
    </div>
    <div class="socials flex flex-row justify-center gap-x-1.5">
        <?php if($this->options->qq): ?>
        <div class="social-item">
            <a href="https://wpa.qq.com/msgrd?V=1&Uin=<?php $this->options->qq() ?>&Site=<?php $this->options->siteUrl() ?>&Menu=yes" target="_blank" title="在线QQ"><svg t="1705734180784" class="icon fill-sky-500" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="4208" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24"><path d="M162.797568 576.497664c-30.287872 75.442176-35.29728 147.37408-10.903552 160.73728 16.87552 9.275392 43.149312-11.943936 67.883008-50.542592 9.814016 42.274816 34.000896 80.203776 68.589568 110.866432-36.21888 14.116864-59.94496 37.175296-59.94496 63.24224 0 42.944512 64.079872 77.613056 143.112192 77.613056 71.305216 0 130.373632-28.153856 141.273088-65.247232 2.885632 0 14.209024 0 16.961536 0 11.114496 37.093376 70.053888 65.247232 141.441024 65.247232 79.120384 0 143.11424-34.670592 143.11424-77.613056 0-26.066944-23.683072-48.955392-59.98592-63.24224 34.463744-30.662656 58.81856-68.591616 68.548608-110.866432 24.727552 38.598656 50.880512 59.817984 67.84 50.542592 24.518656-13.3632 19.632128-85.295104-10.94656-160.73728-23.891968-59.068416-56.266752-102.67648-80.953344-112.449536 0.333824-3.592192 0.626688-7.563264 0.626688-11.364352 0-22.892544-6.098944-44.027904-16.498688-61.239296 0.210944-1.376256 0.210944-2.67264 0.210944-4.050944 0-10.569728-2.381824-20.385792-6.475776-28.86656-6.223872-153.76384-101.339136-276.02944-255.35488-276.02944-153.974784 0-249.217024 122.267648-255.440896 276.02944-4.009984 8.605696-6.473728 18.466816-6.473728 28.993536 0 1.378304 0 2.67264 0.167936 4.052992-10.190848 17.084416-16.29184 38.219776-16.29184 61.19424 0 3.844096 0.206848 7.686144 0.4608 11.446272C219.148288 473.905152 186.650624 517.431296 162.797568 576.497664L162.797568 576.497664z" p-id="4209"></path></svg></a>
        </div>
        <?php endif; ?>
        <?php if($this->options->email): ?>
        <div class="social-item"><a href="mailto:<?php $this->options->email() ?>" target="_blank" title="发送邮件"><svg t="1705735548830" class="icon fill-sky-500" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="15474" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24"><path d="M428.864 619.712 0 355.136l0 416.576C0 840.192 57.152 896 127.616 896l768.768 0c70.4 0 127.552-55.616 127.552-124.224L1023.936 362.048 622.656 618.24C567.872 653.12 484.096 653.632 428.864 619.712L428.864 619.712z" p-id="15475"></path><path d="M896.384 128 127.616 128C57.216 128 0 183.616 0 252.224l0 0.96 482.24 297.344c22.656 14.016 63.36 13.696 85.824-0.64L1024 258.88 1024 252.224C1023.936 183.872 966.72 128 896.384 128L896.384 128z" p-id="15476"></path></svg></a></div>
        <?php endif; ?>
        <?php if($this->options->tgUsername): ?>
        <div class="social-item"><a href="https://t.me/<?php $this->options->tgUsername() ?>" target="_blank" title="电报沟通"><svg t="1705736426395" class="icon fill-sky-500" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="16570" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24"><path d="M512 512m-464 0a464 464 0 1 0 928 0 464 464 0 1 0-928 0Z" p-id="16571"></path><path d="M750.56 336l-77.28 364.8c-5.76 25.76-20.96 32-42.56 20l-117.76-86.72L456.16 688a29.76 29.76 0 0 1-23.68 11.52l8.48-119.84L659.04 384c9.6-8.48-1.92-13.12-14.72-4.8L374.56 548.32 258.56 512c-25.28-8-25.76-25.28 5.12-37.44l454.24-175.04c21.12-7.84 39.52 4.48 32.64 36.48z" fill="#FFFFFF" p-id="16572"></path></svg></a></div>
        <?php endif; ?>
    </div>
</header><!-- end #header -->
    
    

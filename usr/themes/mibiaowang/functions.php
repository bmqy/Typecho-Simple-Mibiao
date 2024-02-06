<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

function themeConfig($form)
{
    $email = new \Typecho\Widget\Helper\Form\Element\Text(
        'email',
        null,
        null,
        _t('电子邮箱'),
        _t('在这里填入email电子邮箱')
    );
    $form->addInput($email);

    $qq = new \Typecho\Widget\Helper\Form\Element\Text(
        'qq',
        null,
        null,
        _t('QQ号码'),
        _t('在这里填入QQ号码')
    );
    $form->addInput($qq);

    $qrcodeUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'qrcodeUrl',
        null,
        null,
        _t('微信二维码'),
        _t('在这里填入一个二维码 URL 地址, 以在网站上供扫码查看，例如：https://www.bmqy.net/images/wechat_channel.jpg')
    );
    $form->addInput($qrcodeUrl);

    $tgUsername = new \Typecho\Widget\Helper\Form\Element\Text(
        'tgUsername',
        null,
        null,
        _t('Telegram用户名'),
        _t('在这里填入Telegram用户名')
    );
    $form->addInput($tgUsername);

    $beiAnHao = new \Typecho\Widget\Helper\Form\Element\Text(
        'beiAnHao',
        null,
        null,
        _t('备案号'),
        _t('在这里填入备案号')
    );
    $form->addInput($beiAnHao);

    $banner1 = new \Typecho\Widget\Helper\Form\Element\Text(
        'banner1',
        null,
        'http://oss.mb.cn/upload/ossfile/3/20200102/1524595e0d9acbd1483xNDVO0.png',
        _t('广告图1'),
        _t('在这里填入一个图片 URL 地址, 以在网站上显示广告图片，例如：http://oss.mb.cn/upload/ossfile/3/20200102/1524595e0d9acbd1483xNDVO0.png')
    );
    $form->addInput($banner1);

    $banner2 = new \Typecho\Widget\Helper\Form\Element\Text(
        'banner2',
        null,
        'http://oss.mb.cn/upload/ossfile/3/20200102/1524595e0d9acbd1483xNDVO0.png',
        _t('广告图2'),
        _t('在这里填入一个图片 URL 地址, 以在网站上显示广告图片，例如：http://oss.mb.cn/upload/ossfile/3/20200102/1524595e0d9acbd1483xNDVO0.png')
    );
    $form->addInput($banner2);
}
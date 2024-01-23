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

    $tgUsername = new \Typecho\Widget\Helper\Form\Element\Text(
        'tgUsername',
        null,
        null,
        _t('Telegram用户名'),
        _t('在这里填入Telegram用户名')
    );
    $form->addInput($tgUsername);
}

/*
function themeFields($layout)
{
    $logoUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'logoUrl',
        null,
        null,
        _t('站点LOGO地址'),
        _t('在这里填入一个图片URL地址, 以在网站标题前加上一个LOGO')
    );
    $layout->addItem($logoUrl);
}
*/
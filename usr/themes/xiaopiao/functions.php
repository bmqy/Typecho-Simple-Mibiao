<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

function themeConfig($form)
{
    $orderInfo = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'orderInfo',
        null,
        '<p>订单：1234567890</p>
    <p>日期：2024年1月23日</p>
    <p>客户：bmqy</p>',
        _t('订单信息'),
        _t('在这里填入订单信息，例如：&lt;p&gt;订单：1234567890&lt;/p&gt;
    &lt;p&gt;日期：2024年1月23日&lt;/p&gt;
    &lt;p&gt;客户：bmqy&lt;/p&gt;')
    );
    $form->addInput($orderInfo);

    $storeInfo = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'storeInfo',
        null,
        '<p>简单米表</p>
    <p>QQ：88268459</p>
    <p>邮箱：notice@bmqy.net</p>',
        _t('商店信息'),
        _t('在这里填入商店信息，例如：&lt;p&gt;简单米表&lt;/p&gt;
    &lt;p&gt;QQ：88268459&lt;/p&gt;
    &lt;p&gt;邮箱：notice@bmqy.net&lt;/p&gt;')
    );
    $form->addInput($storeInfo);

    $qrcodeUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'qrcodeUrl',
        null,
        'https://www.bmqy.net/images/wechat_channel.jpg',
        _t('二维码'),
        _t('在这里填入一个二维码 URL 地址, 以在网站上供扫码查看，例如：https://www.bmqy.net/images/wechat_channel.jpg')
    );
    $form->addInput($qrcodeUrl);

    // 购物小票提示
    $shoppingTips = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'shoppingTips',
        null,
        '<p>1. 请保留好您的小票，作为商品保修和退换货的凭证。</p>
    <p>2. 如需退换货，请在购买后的7天内前往本店办理，超时恕不受理。</p>
    <p>3. 商品出售后，请妥善保管好发票和商品，避免损坏影响售后服务。</p>
    <p>4. 如有任何问题，请及时联系我们的客服，我们将竭诚为您服务。</p>
    <p>5. 以上提示，仅供观赏。</p>',
        _t('购物提示'),
        _t('在这里填入一段文字, 以提示购物小票的用途，例如：&lt;p&gt;1. 请保留好您的小票，作为商品保修和退换货的凭证。&lt;/p&gt;
    &lt;p&gt;2. 如需退换货，请在购买后的7天内前往本店办理，超时恕不受理。&lt;/p&gt;
    &lt;p&gt;3. 商品出售后，请妥善保管好发票和商品，避免损坏影响售后服务。&lt;/p&gt;
    &lt;p&gt;4. 如有任何问题，请及时联系我们的客服，我们将竭诚为您服务。&lt;/p&gt;
    &lt;p&gt;5. 以上提示，仅供观赏。&lt;/p&gt;')
    );

    $form->addInput($shoppingTips);
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
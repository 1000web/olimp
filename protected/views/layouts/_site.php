<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru-RU">

<head profile="http://gmpg.org/xfn/11">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title><?php echo $this->meta_title; ?></title>

    <?php
    if ($this->meta_description !== null) echo CHtml::metaTag($this->meta_description, 'description').PHP_EOL;
    else if($this->default_description !== null) echo CHtml::metaTag($this->default_description, 'description').PHP_EOL;

    if ($this->meta_keywords !== null) echo CHtml::metaTag($this->meta_keywords, 'keywords').PHP_EOL;
    else if ($this->default_keywords !== null) echo CHtml::metaTag($this->default_keywords, 'keywords').PHP_EOL;

    if ($this->meta_prev !== null) echo "<link rel='prev' href='" . $this->meta_prev . "' />\n";
    if ($this->meta_next !== null) echo "<link rel='next' href='" . $this->meta_next . "' />\n";

    $request = Yii::app()->getRequest();
    $url = $request->getUrl();
    //$this->meta_canonical = $this->getAbsoluteUrl();
    // Make sure that we do not create a recursive canonical redirect.
    if ($this->meta_canonical !== $url && $this->meta_canonical !== $request->getHostInfo().$url)
        echo '<link rel="canonical" href="'.$this->meta_canonical.'" />'.PHP_EOL;

    ?>

    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/style.css" type="text/css" media="screen"/>
    <!--link rel="alternate" type="application/rss+xml" title="Зимние Олимпийские игры 2014 в Сочи RSS Feed post" href="http://www.olimp2014.net/feed/post"/-->

    <meta name="generator" content="CMS.1000web"/>
    <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>

</head>
<body>
<div id="page">
    <div id="header">
        <div id="headerimg">
            <h1><a href="/" title="На главную - <?php echo Yii::app()->name; ?>"><?php echo Yii::app()->name; ?></a></h1>

            <div class="description">
                Расписание олимпийских игр<br/>
                виды спорта, страны-участницы<br/>
                рейтинги, результаты, медали
            </div>
        </div>
    </div>

    <?php
    if(Yii::app()->user->isAdmin()) {
        $this->widget('bootstrap.widgets.TbNavbar', array(
            //'htmlOptions' => array('class'=>'nav-collapse collapse'),
            'items' => array(
                array(
                    'class' => 'bootstrap.widgets.TbMenu',
                    'encodeLabel' => false,
                    'items' => MenuItem::model()->getItemsArray('top_menu'),
                ),
            ),
        ));
    }
    ?>

    <div id="menu">
        <ul>
            <li class="page_item"><a href="/sport">Виды спорта</a></li>
            <li class="page_item"><a href="/place">Место проведения</a></li>
            <li class="page_item"><a href="/countries">Страны-участницы</a></li>
            <li class="page_item"><a href="/raspisanie">Расписание игр</a></li>
        </ul>
    </div>
    <br clear="all"/>

    <div id="content" class="narrowcolumn">

        <?php
        if ($this->breadcrumbs) {
            $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
                'encodeLabel' => false,
                'links' => $this->breadcrumbs,
            ));
        }
        echo $content;
        ?>
<?php
/**
    <div class="navigation">
        <div class="alignleft"><a href="http://www.olimp2014.net/page/2/">&laquo; Старые записи</a></div>
        <div class="alignright"></div>
    </div>
/**/
?>

    </div>

    <div id="sidebar">
        <ul>
            <?php foreach ($this->widgets as $widget) { ?>
                <li class='widget'>
                    <h2 class='widgettitle'><?php echo $widget['title']; ?></h2>
                    <?php echo $widget['content']; ?>
                </li>
            <?php } ?>
        </ul>
        <?php
        echo MyHelper::insertImage(Yii::app()->theme->baseUrl . '/images/sidebar-bottom.gif', '', array('style' => 'margin-left:-15px;'))
        ?>
    </div>

    <br clear="all"/>
</div>
<hr/>
<div id="footer">
    <p>
        <?php echo Yii::app()->name; ?>
        <a href="/feed/post">Записи (RSS)</a>
        <a href="/feed/comment">Комментарии (RSS)</a>.
    </p>
</div>
</div>
<script language="javascript" type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#countdowntimer-3-dashboard').countDown({
            targetDate: {
                'day': 07,
                'month': 03,
                'year': 2014,
                'hour': 12,
                'min': 12,
                'sec': 20,
                'localtime': '10/13/2013 11:13:21',
                'mysqltime': '2013-10-13 11:13:21'
            },
            style: 'hoth',
            launchtarget: '',
            omitWeeks: true        });
    });
</script>

<div style="display:none;" class="nav_up" id="nav_up">
    <img alt="&uarr;" width="32" height="32"
         src="http://www.olimp2014.net/wp-content/plugins/scroll-top-and-bottom/icon/2_u.ico"></div>
<div style="display:none;" class="nav_down" id="nav_down">
    <img alt="&darr;" width="32" height="32"
         src="http://www.olimp2014.net/wp-content/plugins/scroll-top-and-bottom/icon/2_d.ico"></div>

<script>
    $(function () {
        var $elem = $('body');

        $('#nav_up').fadeIn('slow');
        $('#nav_down').fadeIn('slow');

        $(window).bind('scrollstart', function () {
            $('#nav_up,#nav_down').stop().animate({'opacity': '0.2'});
        });
        $(window).bind('scrollstop', function () {
            $('#nav_up,#nav_down').stop().animate({'opacity': '1'});
        });

        $('#nav_down').click(
            function (e) {
                $('html, body').animate({scrollTop: $elem.height()}, 800);
            }
        );
        $('#nav_up').click(
            function (e) {
                $('html, body').animate({scrollTop: '0px'}, 800);
            }
        );
    });
</script>
<link rel='stylesheet' id='countdown-hoth-css-css'
      href='http://www.olimp2014.net/wp-content/plugins/jquery-t-countdown-widget/css/hoth/style.css?ver=1.2'
      type='text/css' media='all'/>
</body>
</html>
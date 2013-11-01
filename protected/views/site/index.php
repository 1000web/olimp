<?php
/* @var $this SiteController */
/* @var $posts array */

$this->buildMetaTitle('Главная страница');

foreach($posts as $post) {

?>
<hr/>
<div class="post" id="post-<?php echo $post['id']; ?>">
    <h2><a href="/post/<?php echo $post['id'] . '/' . $post['url']; ?>/" rel="bookmark"
           title="Постоянная ссылка: <?php echo $post['title']; ?>"><?php echo $post['title']; ?></a></h2>
    <small><?php echo $post['date']; ?></small>

    <div class="entry">
        <table>
            <tr>
                <td><a href="http://static.olimp2014.net/wp-content/uploads/2013/10/0110.jpg"><img
                            src="http://static.olimp2014.net/wp-content/uploads/2013/10/0110-300x168.jpg"
                            alt="Мутко считает, что самое главное – не промахнуться с выходом на пик формы перед Играми в Сочи "
                            width="300" height="168" class="aligncenter size-medium wp-image-418"/></a></td>
                <td><strong>Министр спорта РФ Виталий Мутко поделился мнением о подготовке сборных России к
                        зимней Олимпиаде в Сочи.</strong></td>
            </tr>
        </table>
        <p>«Зимний сезон уже стартовал – по некоторым вида спорта уже вовсю проходят как внутрироссийские, так и
            международные соревнования. В частности, сборная России по шорт-треку уже выступила на двух
            стартовых этапах Кубка мира, принесла нашей стране важные медали!<br/>
            <a href="/post/<?php echo $post['id'] . '/' . $post['url']; ?>/#more" class="more-link">Читать полностью &raquo;</a></p>
    </div>

    <p class="postmetadata"> Размещено в
    <?php
        $n = 0;
        foreach($post['categories'] as $cat) :
            if($n++) echo ', ';
    ?>
        <a href="/category/<?php echo $cat['id'] . '/' . $cat['url']; ?>/" title="Просмотреть все записи в рубрике &laquo;<?php echo $cat['value']; ?>&raquo;"
           rel="category tag"><?php echo $cat['value']; ?></a>
    <?php ENDFOREACH; ?>
    | <a href="/post/<?php echo $post['id'] . '/' . $post['url']; ?>/#comment"
           title="Прокомментировать запись &laquo;<?php echo $post['title']; ?>&raquo;">Нет комментариев &#187;</a></p>

</div>

<?
}
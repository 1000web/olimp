<?php
/* @var $this TaskController */
/* @var $model Model */
/* @var $cols array */
/* @var $avail_cols array */

$param = $this->id . '_columns';
$avail_cols = $model->getAvailableAttributes();
$cols = $this->getColumns($this->id, $avail_cols);

echo '<table>';
$i = 0;
// отображаемые элементы
foreach ($cols as $key => $col) {
    // удаляем такой элемент из возможных вариантов
    unset($avail_cols[array_search($col, $avail_cols)]);

    echo '<tr>';
    echo '<td>' . (++$i) . '</td>';
    echo '<td>';
    echo '<strong>' . $model->getLabel($col) . '</strong>';
    echo '</td>';
    // не показывать столбец
    $del = $cols;
    unset($del[array_search($col, $del)]);
    echo '<td>';
    echo CHtml::link('Удалить', array('setparam', 'param' => $param, 'value' => implode(',', $del)));
    echo '</td>';
    // первый элемент
    echo '<td>';
    if ($i > 1) {
        $up = $cols;
        $swap = $up[$i - 2];
        $up[$i - 2] = $up[$i - 1];
        $up[$i - 1] = $swap;
        echo CHtml::link('Вверх', array('setparam', 'param' => $param, 'value' => implode(',', $up)));
    }
    echo '</td>';
    // последний элемент
    echo '<td>';
    if ($i < count($cols)) {
        $down = $cols;
        $swap = $down[$i];
        $down[$i] = $down[$i - 1];
        $down[$i - 1] = $swap;
        echo CHtml::link('Вниз', array('setparam', 'param' => $param, 'value' => implode(',', $down)));
    }
    echo '</td>';
    echo '</tr>';


}
// оставшиеся элементы
foreach ($avail_cols as $col) {
    echo '<tr>';
    echo '<td>' . (++$i) . '</td>';
    echo '<td>';
    echo $model->getLabel($col);
    echo '</td>';
    // показывать столбец
    $add = $cols;
    $add[] = $col;
    echo '<td>';
    echo CHtml::link('Добавить', array('setparam', 'param' => $param, 'value' => implode(',', $add)));
    echo '</td>';
    echo '</tr>';
}
echo '</table>';


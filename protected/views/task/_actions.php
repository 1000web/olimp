<div class="span11">
    <?php

    switch ($this->_model->task_stage_id) {
        case TaskStage::$STAGE_NEW:
            $label_type = 'muted';
            break;
        case TaskStage::$STAGE_ACTIVE:
            $label_type = 'text-info';
            break;
        case TaskStage::$STAGE_FROZEN:
            $label_type = 'text-warning';
            break;
        case TaskStage::$STAGE_FAILED:
            $label_type = 'text-error';
            break;
        case TaskStage::$STAGE_CANCELLED:
            $label_type = 'muted';
            break;
        case TaskStage::$STAGE_DONE:
            $label_type = 'text-success';
            break;
        case TaskStage::$STAGE_CONFIRMED:
            $label_type = 'text-success';
            break;
        default:
            $label_type = '';
    }
    echo '<h3 class="' . $label_type . '">ЗАДАЧА ' . $this->_model->task_stage->value . ' ';

    $task_active = $this->widget('bootstrap.widgets.TbButton', array(
            'label' => 'Принять в работу',
            'type' => 'info', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'url' => array('view', 'id' => $this->_model->id, 'stage' => TaskStage::$STAGE_ACTIVE),
        ), true) . ' ';
    $task_frozen = $this->widget('bootstrap.widgets.TbButton', array(
            'label' => 'Отложить',
            'type' => 'warning', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'url' => array('view', 'id' => $this->_model->id, 'stage' => TaskStage::$STAGE_FROZEN),
        ), true) . ' ';
    $task_failed = $this->widget('bootstrap.widgets.TbButton', array(
            'label' => 'Провалить',
            'type' => 'danger', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'url' => array('view', 'id' => $this->_model->id, 'stage' => TaskStage::$STAGE_FAILED),
        ), true) . ' ';
    $task_done = $this->widget('bootstrap.widgets.TbButton', array(
            'label' => 'Завершить',
            'type' => 'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'url' => array('view', 'id' => $this->_model->id, 'stage' => TaskStage::$STAGE_DONE),
        ), true) . ' ';
    $task_cancel = $this->widget('bootstrap.widgets.TbButton', array(
            'label' => 'Отменить',
            'type' => 'inverse', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'url' => array('view', 'id' => $this->_model->id, 'stage' => TaskStage::$STAGE_CANCELLED),
        ), true) . ' ';
    $task_refuse = $this->widget('bootstrap.widgets.TbButton', array(
            'label' => 'Отказать',
            'type' => 'danger', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'url' => array('view', 'id' => $this->_model->id, 'stage' => TaskStage::$STAGE_ACTIVE),
        ), true) . ' ';
    $task_confirm = $this->widget('bootstrap.widgets.TbButton', array(
            'label' => 'Подтвердить',
            'type' => 'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
            'url' => array('view', 'id' => $this->_model->id, 'stage' => TaskStage::$STAGE_CONFIRMED),
        ), true) . ' ';

    if ($this->_model->owner_id == Yii::app()->user->id) {
        // я - владелец и исполнитель задачи
        if ($this->_model->user_id == Yii::app()->user->id) {
            switch ($this->_model->task_stage_id) {
                case TaskStage::$STAGE_NEW: // новая
                    echo $task_active;
                    break;
                case TaskStage::$STAGE_ACTIVE: // в работе
                    echo $task_cancel;
                    echo $task_failed;
                    echo $task_confirm;
                    break;
                case TaskStage::$STAGE_FAILED: // провалена
                    echo $task_active;
                    break;
            }
        } else {
            // я - владелец, но не исполнитель
            switch ($this->_model->task_stage_id) {
                case TaskStage::$STAGE_NEW: // задача не начата
                case TaskStage::$STAGE_FROZEN: // отложена
                case TaskStage::$STAGE_ACTIVE: // в работе
                    echo $task_cancel;
                    break;
                case TaskStage::$STAGE_DONE: // завершена
                    echo $task_confirm;
                    echo $task_refuse;
                    break;
                case TaskStage::$STAGE_FAILED: // провалена
                    echo $task_cancel;
                    echo $task_active;
                    break;
            }
        }
    } else if ($this->_model->user_id == Yii::app()->user->id) {
        // я - только исполнитель задачи
        switch ($this->_model->task_stage_id) {
            case TaskStage::$STAGE_NEW: // задача не начата
                echo $task_active;
                break;
            case TaskStage::$STAGE_FROZEN: // отложена
                echo $task_active;
                break;
            case TaskStage::$STAGE_ACTIVE: // в работе
                echo $task_frozen;
                echo $task_failed;
                echo $task_done;
                break;
            case TaskStage::$STAGE_DONE: // завершена
                echo $task_active;
                break;
            case TaskStage::$STAGE_FAILED: // провалена
                echo $task_active;
                break;
        }
    }
    ?>
</h3>
</div>
<br/><br/>

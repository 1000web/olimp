<?php

class MyHelper
{
    public static function action_icon($action)
    {
        $icons = array(
            'create' => 'icon-plus',
            'index' => 'icon-list',
            'update' => 'icon-pencil',
            'view' => 'icon-eye-open',
            'delete' => 'icon-trash',
            'column' => 'icon-list',
            'favorite_add' => 'icon-star-empty',
            'favorite_del' => 'icon-star',
            'favorite' => 'icon-star',
        );
        return $icons[$action];
    }

    public static function checkAccess($param1, $param2, $param3 = NULL)
    {
        $param = '';
        if ($param3 !== NULL) {
            // прилетело 3 параметра
            // param1 = module, $param2 = controller, param3 = action
            // первый параметр непустой
            if (!empty($param1)) $param = $param1 . '.';
            // приклеиваем два оставшихся параметра
            $param .= $param2 . '.' . $param3;
        } else {
            // прилетело 2 параметра
            // $param1 = controller, param2 = action
            $param = $param1 . '.' . $param2;
        }
        return Yii::app()->user->checkAccess($param);
    }

    public static function createURL($param1, $param2, $param3 = NULL)
    {
        $url = '';
        if ($param3 !== NULL) {
            // прилетело 3 параметра
            // param1 = module, $param2 = controller, param3 = action
            // первый параметр непустой
            if (!empty($param1)) $url = '/' . $param1;
            // приклеиваем два оставшихся параметра
            $url .= '/' . $param2 . '/' . $param3;
        } else {
            // прилетело 2 параметра
            // $param1 = controller, param2 = action
            $url = '/' . $param1 . '/' . $param2;
        }
        return array($url);
    }

    public static function number_format($amount){
        if($amount == 0) return '&ndash;';
        if($amount == floor($amount)) $dec = 0; else $dec = 2;
        return number_format($amount , $dec, ',', ' ');
    }

    public static function datetime_format($timestamp){
        if(! $timestamp) return NULL;

        $cur_time = time();
        $date = '';
        // будущее время
        if($cur_time < $timestamp) {
            if(date('Y', $cur_time) == date('Y', $timestamp)) $date = date('d M', $timestamp);
            else $date = date('d-m-Y', $timestamp);

            if(($timestamp%60) != 0) $time = 'в ' . date('H:i:s', $timestamp);
            else $time = 'в ' . date('H:i', $timestamp);
        } else {
            // прошлое
            if(($cur_time - $timestamp) < 60) $time = ' менее минуты назад';
            else {
                if(($cur_time - $timestamp) < (60 * 60)) {
                    $n = floor(($cur_time - $timestamp)/60);
                    $v = 'минут';
                    if($n < 5 OR $n > 20) {
                        $i = $n%10;
                        /*if($i == 0) $v = 'минут';
                        else /**/ if($i == 1) $v = 'минуту';
                        else if($i >= 2 AND $i <= 4) $v = 'минуты';
                        //else if($i >= 5 AND $i <= 9) $v = 'минут';
                    }
                    $time = $n . ' ' . $v . ' назад';
                }
                else {
                    $time = 'в ' . date('H:i', $timestamp);
                    if(date('d-m-Y', $timestamp) == date('d-m-Y', $cur_time)) $date = 'сегодня';
                    else {
                        if(date('d-m-Y', $timestamp) == date('d-m-Y', $cur_time - (24 * 60 * 60))) $date = 'вчера';
                        else {
                            if(date('Y', $timestamp) == date('Y', $cur_time)) $date = date('d M', $timestamp);
                            else $date = date('d-m-Y', $timestamp);
                        }
                    }
                }
            }
        }


        return $date . ' ' . $time;
    }

    public static function labels($param = NULL)
    {
        $array = array(
            'id' => '#',
            'organization_name' => 'Полное наименование организации',
            'value' => 'Значение',
            'comment' => 'Комментарий',
            'organization_type_id' => 'Тип',
            'organization_group_id' => 'Группа',
            'organization_region_id' => 'Регион',
            'description' => 'Описание',

            'activkey' => 'Activkey',
            'create_at' => 'Дата создания',
            'create_time' => 'Дата создания',
            'close_date' => 'Дата закрытия',

            'date' => 'Дата',
            'time' => 'Время',
            'create_datetime' => 'Создано',
            'datetime' => 'Срок',

            'num' => 'Кол-во',

            'deleted' => 'Удалено',
            'email' => 'Email',
            'favadd' => 'Добавить в Избранное',
            'favdel' => 'Удалить из Избранного',
            'first_name' => 'Имя',
            'state' => 'Статус',

            'item_id' => 'Пункт',
            'item_parent_id' => 'Родитель',
            'item_module' => 'Модуль',
            'item_controller' => 'Контроллер',
            'item_action' => 'Действие',

            'icon' => 'Иконка',
            'last_name' => 'Фамилия',
            'lastvisit_at' => 'Lastvisit At',
            'menu' => 'Меню #',
            'menu_id' => 'Меню #',

            'owner_id' => 'Менеджер',

            'organization' => 'Организация',

            'type' => 'Тип',
            'group' => 'Группа',
            'region' => 'Регион',
            'menu_parent_id' => 'Родитель',
            'parent_id' => 'Родитель',
            'password' => 'Пароль',
            'position' => 'Должность',
            'status' => 'Статус',
            'superuser' => 'Суперпользователь',

            'task_type_id' => 'Тип задачи',
            'task_stage_id' => 'Этап',
            'task_prior_id' => 'Приоритет',
            'task_owner_id' => 'Владелец',
            'task_user_id' => 'Исполнитель',
            'task_status' => 'Активность',

            'user_id' => 'Пользователь',
            'username' => 'Имя пользователя',
            'question' => 'Вопрос',

            'prior' => 'Приоритет',
            'visible' => 'Видимость',
            'title' => 'Заголовок',
            'h1' => 'H1',
            'module' => 'Модуль',
            'controller' => 'Контроллер',
            'action' => 'Действие',
            'url' => 'Url',
            'guest_only' => 'Только гость',

            'placegroup_id' => 'Группа мест проведения',

        );
        switch ($param) {
            case 'organization':
                $array['value'] = 'Название организации';
                break;
            case 'task':
                $array['value'] = 'Суть задачи';
                $array['user_id'] = 'Исполнитель';
                break;
            case 'taskstage':
                $array['value'] = 'Название этапа';
                break;
            case 'place':
                $array['value'] = 'Название места проведения';
                break;
        }
        return $array;
    }

    public static function getValue($name, $myvar = '$data')
    {
        $datetime_format = 'Y-m-d H:i:s';
        switch ($name) {
            case 'organization_id':
                if (!self::checkAccess('organization', 'view')) $value = '$data->organization->value';
                else $value = 'CHtml::link(CHtml::encode($data->organization->value),array("/organization/view","id"=>$data->organization_id))';
                break;
            case 'user_id':
                $value = '$data->user?$data->user->username:""';
                //$value = '($data->user_id == Yii::app()->user->id)?"Я":$data->user->username';
                //$value = '$data->user->profiles->last_name $data->user->profiles->first_name ($data->user->username)';
                break;
            case 'num':
                $value = 'MyHelper::number_format($data->num)';
                break;
            case 'organization_type_id':
                $value = '$data->organization_type->value';
                break;
            case 'organization_region_id':
                $value = '$data->organization_region->value';
                break;
            case 'organization_group_id':
                $value = '$data->organization_group->value';
                break;
            case 'datetime':
                $value = 'MyHelper::datetime_format($data->datetime)';
                break;
            case 'task_type_id':
                $value = '$data->task_type->value';
                break;
            case 'task_stage_id':
                $value = '$data->task_stage->value';
                break;
            case 'task_prior_id':
                $value = '$data->task_prior->value';
                break;
            case 'placegroup_id':
                $value = '$data->placegroup->value';
                break;
            case 'state':
                $value = '$data->getStateName($data->state)';
                break;
            default:
                $value = NULL;
        }
        if ($myvar != '$data') $value = str_replace('$data', $myvar, $value);
        return $value;
    }


}
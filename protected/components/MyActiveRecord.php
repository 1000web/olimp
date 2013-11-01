<?php

class MyActiveRecord extends CActiveRecord
{
    public function getByCriteria($criteria, $pagesize = 20) {
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => $pagesize,
            ),
        ));
    }

    public function getOptions($id = 'id', $value = 'value', $order = NULL, $params = NULL, $optional = false)
    {
        $criteria = new CDbCriteria;

        if ($order === NULL) {
            if (!is_array($value)) $order = $value;
            else $order = $id;
        }
        $criteria->order = 't.' . $order;
        //$criteria->group = $id;
        $criteria->distinct = true;

        if ($params !== NULL) {
            $i = 0;
            foreach ($params as $key => $val) {
                if (in_array($val, $this->getAllowedRange($key))) {
                    $criteria->addCondition($key . '=:param' . $i);
                    $criteria->params[':param' . $i] = $val;
                }
                $i++;
            }
        }
        $items = $this->findAll($criteria);

        $ret = array();
        if($optional) $ret[] = '- = Пусто = -';
        foreach ($items as $item) {
            if (is_array($value)) $ret[$item[$id]] = $item[$value['key']][$value['val']];
            else $ret[$item[$id]] = $item[$value];
        }
        return $ret;
    }

    public function getValue($id)
    {
        $item = $this->findByPk($id);
        return $item->value;
    }

    public function getAllowedRange($id = 'id')
    {
        $items = $this->findAll();
        $ret = array();
        foreach ($items as $item) {
            $ret[] = $item[$id];
        }
        return $ret;
    }

    public function getAvailableAttributes()
    {
        return array('id', 'value', 'description');
    }

    public function getStateOptions()
    {
        return array(
            2 => $this->getStateName(2),
            1 => $this->getStateName(1),
        );
    }

    public function getStateName($state)
    {
        switch ($state) {
            case 1:
                return 'Неактивна';
                break;
            case 2:
                return 'Активна';
                break;
        }
        return 'Неизвестно';
    }

    public function getAllowedState()
    {
        return array(1, 2);
    }

    public function attributeLabels()
    {
        return MyHelper::labels();
    }

    public function getLabel($key)
    {
        $labels = $this->attributeLabels();
        if (isset($labels[$key])) return $labels[$key];
        return 'НЕИЗВЕСТНО';
    }

    public function getAttributeValue($attribute)
    {
        $value = MyHelper::getValue($attribute, '$this');
        return $this->evaluateExpression($value);
    }

    public function getAll($pagesize = 20)
    {
        $criteria = new CDbCriteria;
        return $this->getByCriteria($criteria, $pagesize);
    }

    public function getByUrl($url)
    {
        $criteria = new CDbCriteria;
        $criteria->addCondition('t.url=:url');
        $criteria->params = array(':url'=> $url);
        $criteria->addCondition('t.visible=1');
        return $this->getByCriteria($criteria, 1);
    }

}
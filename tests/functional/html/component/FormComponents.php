<?php

abstract class FormComponents extends Components
{
    const INPUT_TEXT = 'text';
    const INPUT_SELECT = 'select';
    const INPUT_CHECKBOX = 'checkbox';
    const INPUT_RADIO = 'radio';
   
    public function setAllFields($formData)
    {
        foreach ($formData as $field => $value) {
            $this->setField($field, $value);
        }
    }

    public function setField($field, $value)
    {
        $fieldConf = $this->getFieldConfiguration($field);
        $element = $this->getField($field);

        switch ($fieldConf['input']) {
            case self::INPUT_TEXT:
                $this->selenium->clearAndSetValue($element, $value);
                break;
            case self::INPUT_SELECT:
                $select = $this->selenium->select($element);

                if ($fieldConf['by'] === 'value') {
                    $select->selectOptionByValue($value);
                } else if ($fieldConf['by'] === 'label') {
                    $select->selectOptionByLabel($value);
                }
                break;
            case self::INPUT_CHECKBOX:
                $checked = $element->attribute('checked');
                if (($value && !$checked) || (!$value && $checked)) {
                    $element->click();
                }
                break;
        }
    }

    public function getFieldConfiguration($field)
    {
        $configurations = $this->fieldConfiguration();
        return $configurations[$field];
    }

    protected function _getElement($type)
    {
        $type = $this->selenium->using($type[0])->value($type[1]);
        
        return $this->selenium->element($type);
    }

    public function submit()
    {
        $this->_getElement($this->submitElement())->click();
    }

    public function getField($field)
    {
        $field = $this->getFieldConfiguration($field);
        $type = $this->selenium->using($field['type'][0])->value($field['type'][1]);

        return $this->selenium->element($type);
    }
    
    abstract public function fieldConfiguration();
    abstract public function submitElement();
}

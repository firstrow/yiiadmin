<?php

class YiiadminModelBehavior extends CActiveRecordBehavior {

    public $attributes = array();
    
    public function beforeValidate($on) {
        $this->Owner->created = time();
        if ($this->Owner->isNewRecord) {            
            $this->Owner->editorId = Yii::app()->user->id;
        }
        return true;
    }

    public function getChoicesFor($attribute)
    {        
        $data = array();
        $methodName = 'get'.ucfirst($attribute).'Choices';

        if (isset($this->attributes[$attribute]['choices']))
        {
            return $this->attributes[$attribute]['choices'];
        } elseif (method_exists($this, $methodName))
        {
            $data = $this->$methodName();
        } elseif (method_exists($this->owner, $methodName))
        {
            $data = $this->owner->$methodName();
        } else {
            $propertyName = $attribute.'Choices';
            if (isset($this->owner->$propertyName) && is_array($this->owner->$propertyName))
            {
                $data = $this->owner->$propertyName;
            }
        }
        return $data;
    }
}
// vim: set filetype=php expandtab tabstop=4 shiftwidth=4 autoindent smartindent:

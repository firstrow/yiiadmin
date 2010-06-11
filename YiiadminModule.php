<?php

/**
 * YiiadminModule 
 * 
 * @uses CWebModule
 * @package YiiAdmin
 * @version $id$
 * @copyright 2010 
 * @author Firstrow <firstrow@gmail.com> 
 * @license BSD
 */
class YiiadminModule extends CWebModule
{
    private $_assetsUrl;
    protected $model;
    public $attributesWidgets=null;
    public $_modelsList=array();
    public static $fileExt='.php';
    private $controller;
    public $password;   
    public $registerModels=array();
    public $excludeModels=array();

	public function init()
	{
        error_reporting(E_ALL ^ E_NOTICE);
        Yii::app()->clientScript->registerCoreScript('jquery');

        Yii::app()->setComponents(array(
			'errorHandler'=>array(
				'errorAction'=>'yiiadmin/default/error',
			),
			'user'=>array(
				'class'=>'CWebUser',
				'stateKeyPrefix'=>'yiiadmin',
				'loginUrl'=>Yii::app()->createUrl('yiiadmin/default/login'),
			),
		));

		$this->setImport(array(
			'yiiadmin.models.*',
			'yiiadmin.components.*',
            'zii.widgets.grid.CGridColumn',
		));
	}

    /**
     * Получение списка моделей
     * 
     * @access public
     * @return void
     */
    public function getModelsList()
    {
        $models=$this->registerModels;
    
        if (!empty($models))
        {
            foreach($models as $model)
            {
                // Импорт всех моделей(модели)
                Yii::import($model);

                if (substr($model, -1)=='*')
                {
                    // Если импортируем директорию с моделями,
                    // Получим список моделей
                    $files=CFileHelper::findFiles(Yii::getPathOfAlias($model));
                    if ($files)
                    {
                        foreach($files as $file)
                        {
                           $class_name=str_replace(self::$fileExt,'',substr(strrchr($file,DIRECTORY_SEPARATOR), 1));
                           $this->addModel($class_name);
                        }
                    }
                }
                else
                {
                    $class_name=substr(strrchr($model, "."), 1);
                    $this->addModel($class_name); 
                }
            }
        }

        return array_unique($this->_modelsList);
    }

    /**
     * Добавление модели в список.
     * 
     * @param mixed $name 
     * @access protected
     * @return void
     */
    protected function addModel($name)
    {
        if (!in_array($name,$this->excludeModels))
            $this->_modelsList[]=$name;
    }

    /**
     * Загрузка модели
     * 
     * @param string $name 
     * @access public
     * @return object
     */
    public function loadModel($name)
    {
        $model=(string)$_GET['model_name'];
        $this->model=new $model;
        return $this->model;
    }

    public function createWidget($form,$model,$attribute)
    {
        $dbType=$model->tableSchema->columns[$attribute]->dbType;
        
        $widget=$this->getAttributeWidget($attribute); 
        
        switch ($widget)
        {
            case 'textArea';
                return $form->textArea($model,$attribute,array('class'=>'vTextField'));
            break;
            
            case 'textField';
                return $form->textField($model,$attribute,array('class'=>'vTextField'));
            break;

            case 'dropDownList':
                return $form->dropDownList($model,$attribute,$this->getAttributeChoices($attribute),array('empty'=>'- select -'));
            break;

            case 'calendar': 
                $widgetData=array_slice($this->getAttributeData($attribute),2);
                
                $data=array(
                    'name'=>get_class($model).'['.$attribute.']',
                    'value'=>$model->$attribute,
                    'language'=>'ru',
                    'options'=>array(
                        'showAnim'=>'fold',
                        'dateFormat'=>'yy-mm-dd',
                    ),
                );
                
                if ($widgetData)
                    $data=array_merge($data,$widgetData);

                $this->controller->widget('zii.widgets.jui.CJuiDatePicker', $data);
            break;

            case 'boolean':
                return $form->checkBox($model,$attribute); 
            break;

            default: 
                return $form->textField($model,$attribute,array('class'=>'vTextField')); 
            break;
        }
    }

    protected function getAttributeWidget($name)
    {
        if ($this->attributesWidgets!==null)
        {
            if (isset($this->attributesWidgets->$name))
                return $this->attributesWidgets->$name;
            else
            {
                $dbType=$this->model->tableSchema->columns[$name]->dbType; 
                if ($dbType=='text')
                    return 'textArea';
                else
                    return 'textField';
            }
        }

        if (method_exists($this->model,'attributeWidgets'))
            $attributeWidgets=$this->model->attributeWidgets();
        else
            return null;

        $temp=array();

        if (!empty($attributeWidgets))
        {
            foreach($attributeWidgets as $key=>$val)
            {
                if (isset($val[0]) && isset($val[1]))
                {
                    $temp[$val[0]]=$val[1];
                    $temp[$val[0].'Data']=$val;
                }
            }
        }

        $this->attributesWidgets=(object)$temp;

        return $this->getAttributeWidget($name);
    }

    protected function getAttributeData($attribute)
    {
        $attribute.='Data';
        if (isset($this->attributesWidgets->$attribute))
            return $this->attributesWidgets->$attribute;
        else
            return null;
    }

    /**
     * Получение массива значений атрибута.
     * Имя переменной массива с значениями должно быть: attributeNameChoices. 
     * Например categoryChoices.
     * 
     * @param mixed $attribute 
     * @access private
     * @return array
     */
    private function getAttributeChoices($attribute)
    {
        $data=array();
        $choicesName=(string)$attribute.'Choices';
        if (isset($this->model->$choicesName) && is_array($this->model->$choicesName))
            $data=$this->model->$choicesName;
        
        return $data;
    }

    public function getModelNamePlural($model)
    {
        if (is_string($model))
            $model=new $model;

        if (isset($model->adminName))
            return $model->adminName;
        else
            return get_class($model);
    }

    public function getObjectPluralName($model, $pos=0)
    {
        if (is_string($model))
            $model=new $model; 

        if (!isset($model->pluralNames))
            return get_class($model);
        else
            return $model->pluralNames[$pos];
    }

	/**
	 * @return string the base URL that contains all published asset files.
	 */
	public function getAssetsUrl()
	{
		if($this->_assetsUrl===null)
			$this->_assetsUrl=Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.yiiadmin.assets'));
		return $this->_assetsUrl;
	}

	/**
	 * @param string the base URL that contains all published asset files.
	 */
	public function setAssetsUrl($value)
	{
		$this->_assetsUrl=$value;
	}

    public static function createActionUrl($action,$pk)
    {
        $a=new CController;
        return $a->createUrl('manageModel',$data->primaryKey);
    }

    public static function t($message)
    {
        return Yii::t('YiiadminModule.yiiadmin',$message);
    }

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
            $this->controller=$controller;
			$route=$controller->id.'/'.$action->id;

			$publicPages=array(
				'default/login',
				'default/error',
			);
			if($this->password!==false && Yii::app()->user->isGuest && !in_array($route,$publicPages))
				Yii::app()->user->loginRequired();
			else
				return true;
		}
		return false;
	}
}

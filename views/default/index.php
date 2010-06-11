<?php
$this->pageTitle=$title;
?>
<div class="column span-12">
    <div class="module" id="app_auth"> 
        <h2><a href="#" class="section"><?php echo YiiadminModule::t('Список Моделей'); ?></a></h2> 
        <?php foreach($models as $m): ?> 
            <div class="row"> 
                <a href="#"><?php echo CHtml::link($this->module->getModelNamePlural($m),$this->createUrl('manageModel/list',array('model_name'=>$m))); ?></a> 
                <ul class="actions"> 
                    <li class="add-link">
                    <?php echo CHtml::link(YiiadminModule::t('Создать'),$this->createUrl('manageModel/create',array('model_name'=>$m))); ?>
                    <li> 
                    <li class="change-link">
                    <?php echo CHtml::link(YiiadminModule::t('Изменить'),$this->createUrl('manageModel/list',array('model_name'=>$m))); ?> 
                    </li> 
                </ul> 
            </div>
        <?php endforeach; ?>
    </div>
</div> 

<!--
<div class="column span-6 last"> 
    <div class="module actions" id="recent-actions-module"> 
        <h2>Последние действия</h2>
        <div class="module"> 
                <ul>
                    <li>action text</li>
                </ul>
        </div>
    </div> 
</div> 
-->

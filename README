<h3>Instalation</h3>
<ul>
    <li>Clone this repo in protected/modules/yiiadmin.(git clone git@github.com:firstrow/yiiadmin.git)</li>
    <li>
        Edit yii main config file protected/config/main.php. 
        <br/>Enable yiimodule, set password and enter models you want to manage.

        <pre>
        'modules'=>array(
         ...
            'yiiadmin'=>array(
                'password'=>'YOU PASS HERE',
                'registerModels'=>array(
                    //'application.models.Contests',
                    //'application.models.BlogPosts',
                    //'application.models.*',
                ),
                //'excludeModels'=>array(),
            ),
        ),
        ...
        </pre>

    </li>
    <li>Open in browser http://your-project/index.php/yiiadmin and enter your password.</li>
</ul>

Example model config from my testing project:
<pre>
    // ./application/models/Contests.php
    // Contests model file

    // Model plural names
    public $adminName='Contests'; // will be displayed in main list
    public $pluralNames=array('Contest','Contests');   

    // Config for attribute widgets
    public function attributeWidgets()
    {
        return array(
            array('proffesion_id', 'dropDownList'), // For choices create variable name proffesion_idChoices
            array('date_start','calendar', 'language'=>'ru','options'=>array('dateFormat'=>'yy-mm-dd')),
            array('date_stop','calendar'),
            array('active','boolean'),
        );
    }

    // Config for CGridView class
    public function adminSearch()
    {
        return array(
                // Data provider, by default is "search()"
                //'dataProvider'=>$this->search(),
                'columns'=>array(
                'id',
                'name',
                'date_start',
                'date_stop',
                array(
                    'name'=>'active',
                    'value'=>'$data->active==1 ? CHtml::encode("Yes") : CHtml::encode("No")',
                    'filter'=>array(1=>'Да',0=>'Нет'),
                ),
                array(
                    'name'=>'proffesion_id',
                    'value'=>'User_profile::getProfName($data->proffesion_id)',
                    'filter'=>$this->proffesion_idChoices,
                ),
            ),
        );
    }
</pre>

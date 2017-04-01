<?php
return [
    'class'=>'yii\web\UrlManager',
    'enablePrettyUrl'=>env('LINK_ASSETS'),
    'showScriptName'=>false,
    'rules'=>[
        // url rules
        ['pattern'=>'site/doc', 'route'=>'site/doc'],
    ]
];

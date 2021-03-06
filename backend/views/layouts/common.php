<?php
/**
 * @var $this yii\web\View
 */
use backend\assets\BackendAsset;
use backend\widgets\Menu;
use common\models\TimelineEvent;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
$bundle = BackendAsset::register($this);

$avatar = '';
if(!Yii::$app->user->isGuest){
    $avatar = Yii::$app->user->identity->userProfile->getAvatar($this->assetManager->getAssetUrl($bundle, 'img/anonymous.jpg'));
}else{
    $avatar = $this->assetManager->getAssetUrl($bundle, 'img/anonymous.jpg');
}

$avatar .= '?imageView2/3/w/215/h/215';
?>
<?php $this->beginContent('@backend/views/layouts/base.php'); ?>
    <div class="wrapper">
        <!-- header logo: style can be found in header.less -->
        <header class="main-header">
            <a href="<?php echo Yii::getAlias('@frontendUrl') ?>" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <?php echo Yii::$app->name ?>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only"><?php echo Yii::t('backend', 'Toggle navigation') ?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                    	<li id="timeline-notifications" class="notifications-menu">
                            <a href="<?php echo Yii::getAlias('@frontendUrl') ?>">
                                前台
                            </a>
                        </li>
                        <li id="timeline-notifications" class="notifications-menu">
                            <a href="<?php echo Yii::getAlias('@backendUrl') ?>">
                                后台
                            </a>
                        </li>
                        <li id="timeline-notifications" class="notifications-menu">
                            <a href="<?php echo Url::to(['/timeline-event/index']) ?>">
                                <i class="fa fa-bell"></i>
                                <span class="label label-success">
                                    <?php echo TimelineEvent::find()->today()->count() ?>
                                </span>
                            </a>
                        </li>
                        <!-- Notifications: style can be found in dropdown.less -->
                        <li id="log-dropdown" class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-warning"></i>
                            <span class="label label-danger">
                                <?php echo \backend\models\SystemLog::find()->count() ?>
                            </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header"><?php echo Yii::t('backend', 'You have {num} log items', ['num'=>\backend\models\SystemLog::find()->count()]) ?></li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
                                        <?php foreach(\backend\models\SystemLog::find()->orderBy(['log_time'=>SORT_DESC])->limit(5)->all() as $logEntry): ?>
                                            <li>
                                                <a href="<?php echo Yii::$app->urlManager->createUrl(['/log/view', 'id'=>$logEntry->id]) ?>">
                                                    <i class="fa fa-warning <?php echo $logEntry->level == \yii\log\Logger::LEVEL_ERROR ? 'text-red' : 'text-yellow' ?>"></i>
                                                    <?php echo $logEntry->category ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                                <li class="footer">
                                    <?php echo Html::a(Yii::t('backend', 'View all'), ['/log/index']) ?>
                                </li>
                            </ul>
                        </li>
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?php echo $avatar; ?>" class="user-image">
                                <span><?php echo Yii::$app->user->identity->username ?> <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header light-blue">
                                    <img src="<?php echo $avatar; ?>" class="img-circle" alt="User Image" />
                                    <p>
                                        <?php echo Yii::$app->user->identity->username ?>
                                        <small>
                                            <?php echo Yii::t('backend', 'Member since {0, date, short}', Yii::$app->user->identity->created_at) ?>
                                        </small>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <?php echo Html::a(Yii::t('backend', 'Profile'), ['/sign-in/profile'], ['class'=>'btn btn-default btn-flat']) ?>
                                    </div>
                                    <div class="pull-left">
                                        <?php echo Html::a(Yii::t('backend', 'Account'), ['/sign-in/account'], ['class'=>'btn btn-default btn-flat']) ?>
                                    </div>
                                    <div class="pull-right">
                                        <?php echo Html::a(Yii::t('backend', 'Logout'), ['/sign-in/logout'], ['class'=>'btn btn-default btn-flat', 'data-method' => 'post']) ?>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <?php echo Html::a('<i class="fa fa-cogs"></i>', ['/site/settings'])?>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="<?php echo $avatar; ?>" class="img-circle" />
                    </div>
                    <div class="pull-left info">
                        <p><?php echo Yii::t('backend', 'Hello, {username}', ['username'=>Yii::$app->user->identity->getPublicIdentity()]) ?></p>
                        <a href="<?php echo Url::to(['/sign-in/profile']) ?>">
                            <i class="fa fa-circle text-success"></i>
                            <?php echo Yii::$app->formatter->asDatetime(time()) ?>
                        </a>
                    </div>
                </div>
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <?php echo Menu::widget([
                    'options'=>['class'=>'sidebar-menu'],
                    'linkTemplate' => '<a href="{url}">{icon}<span>{label}</span>{right-icon}{badge}</a>',
                    'submenuTemplate'=>"\n<ul class=\"treeview-menu\">\n{items}\n</ul>\n",
                    'activateParents'=>true,
                    'items'=>[
                        [
                            'label'=>Yii::t('backend', 'Main'),
                            'options' => ['class' => 'header']
                        ],
                        [
                            'label'=>Yii::t('backend', 'Timeline'),
                            'icon'=>'<i class="fa fa-bar-chart-o"></i>',
                            'url'=>['/timeline-event/index'],
                            'badge'=> TimelineEvent::find()->today()->count(),
                            'badgeBgClass'=>'label-success',
                        ],
                        [
                            'label'=>Yii::t('backend', '课件管理（授课）'),
                            'url' => '#',
                            'icon'=>'<i class="fa fa-edit"></i>',
                            'options'=>['class'=>'treeview'],
                            'items'=>[
                                ['label'=>Yii::t('backend', '课件列表'), 'url'=>['/campus/courseware/index'], 'icon'=>'<i class="fa  fa-file-text"></i>'
                                ], 
                                ['label'=>Yii::t('backend', '课件附件'), 'url'=>['/campus/courseware-to-file/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'
                                ],
                                ['label'=>Yii::t('backend', '课件分类'), 'url'=>['/campus/courseware-category/index'], 'icon'=>'<i class=" fa  fa-file-text"></i>'
                                ],
                                ['label'=>Yii::t('backend', '课件关系'), 'url'=>['/campus/courseware-to-courseware/index'], 'icon'=>'<i class=" fa  fa-file-text"></i>'
                                ],
                                ['label'=>Yii::t('backend', '附件管理'), 'url'=>['/campus/file-storage-item/index'], 'icon'=>'<i class=" fa  fa-file-text"></i>'
                                ],
                            ]   
                        ],
                        
                        /*
                        [
                            'label'=>Yii::t('backend', '班级管理'),
                            'url' => '#',
                            'icon'=>'<i class="fa fa-edit"></i>',
                            'options'=>['class'=>'treeview'],
                            'items'=>[
                                ['label'=>Yii::t('backend', '学生管理'), 'url'=>['/campus/apply-to-play/index'], 'icon'=>'<i class="fa  fa-file-text"></i>'
                                ], 
                                ['label'=>Yii::t('backend', '学员档案管理'), 'url'=>['/campus/contact/index'], 'icon'=>'<i class=" fa  fa-file-text"></i>'
                                ],

                            ]   
                        ], 
                        */
                        [
                            'label'=>Yii::t('backend', '课程管理（学生）'),
                            'url' => '#',
                            'icon'=>'<i class="fa fa-mortar-board"></i>',
                            'options'=>['class'=>'treeview'],
                            'items'=>[
                                ['label'=>Yii::t('backend', '课程设置'), 'url'=>['/campus/course/index'], 'icon'=>'<i class="fa  fa-file-text"></i>'
                                ], 
                                ['label'=>Yii::t('backend', '签到管理'), 'url'=>['/campus/sign-in/index'], 'icon'=>'<i class=" fa  fa-file-text"></i>'
                                ],

                            ]   
                        ],
                       /*
                        [
                            'label'=>Yii::t('backend', '通知管理'),
                            'url' => '#',
                            'icon'=>'<i class="fa fa-edit"></i>',
                            'options'=>['class'=>'treeview'],
                            'items'=>[
                                ['label'=>Yii::t('backend', '通知列表'), 'url'=>['/campus/apply-to-play/index'], 'icon'=>'<i class="fa  fa-file-text"></i>'
                                ],
                            ]   
                        ],
                        [
                            'label'=>Yii::t('backend', '微信管理'),
                            'url' => '#',
                            'icon'=>'<i class="fa fa-edit"></i>',
                            'options'=>['class'=>'treeview'],
                            'items'=>[
                                ['label'=>Yii::t('backend', '粉丝列表'), 'url'=>['/campus/apply-to-play/index'], 'icon'=>'<i class="fa  fa-file-text"></i>'
                                ],
                            ]   
                        ],
                        */
                        [
                            'label'=>Yii::t('backend', 'Content'),
                            'url' => '#',
                            'icon'=>'<i class="fa fa-edit"></i>',
                            'options'=>['class'=>'treeview'],
                            'items'=>[
                                ['label'=>Yii::t('backend', '静态页面'), 'url'=>['/page/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                                ['label'=>Yii::t('backend', '文章'), 'url'=>['/article/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                                ['label'=>Yii::t('backend', '文章分类'), 'url'=>['/article-category/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                                ['label'=>Yii::t('backend', '文本组件'), 'url'=>['/widget-text/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                                ['label'=>Yii::t('backend', '菜单组件'), 'url'=>['/widget-menu/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                                ['label'=>Yii::t('backend', '轮播组件'), 'url'=>['/widget-carousel/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                                ['label'=>Yii::t('backend', '预约信息'), 'url'=>['/campus/apply-to-play/index'], 'icon'=>'<i class="fa  fa-file-text"></i>'
                                ], 
                                ['label'=>Yii::t('backend', '联系我们'), 'url'=>['/campus/contact/index'], 'icon'=>'<i class=" fa  fa-file-text"></i>'
                                ],
                            ]
                        ],
                        [
                            'label'=>Yii::t('backend', '学校管理'),
                            'url' => '#',
                            'icon'=>'<i class="fa fa-university"></i>',
                            'options'=>['class'=>'treeview'],
                            'items'=>[
                                ['label'=>Yii::t('backend', '学校管理'), 'url'=>['/campus/school/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'
                                ],
                                ['label'=>Yii::t('backend', '班级分类管理'), 'url'=>['/campus/grade-category/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'
                                ],
                                ['label'=>Yii::t('backend', '班级管理'), 'url'=>['/campus/grade/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'
                                ],
                                ['label'=>Yii::t('backend', '学员管理'), 'url'=>['/campus/user-to-grade/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'
                                ],
                                ['label'=>Yii::t('backend', '学员档案管理'), 'url'=>['/campus/student-record/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'
                                ],
                                
                                /*
                                ['label'=>Yii::t('backend', '课件管理'), 'url'=>['/campus/courseware/index'], 'icon'=>'<i class="fa  fa-file-text"></i>'
                                ], 
                                */
                            ]
                        ],
                        [
                            'label'=>Yii::t('backend', 'System'),
                            'options' => ['class' => 'header']
                        ],
                        [
                            'label'=>Yii::t('backend', '用户管理'),
                            'url' => '#',
                            'icon'=>'<i class="fa fa-users"></i>',
                            'visible'=>Yii::$app->user->can('administrator'),
                            'options'=>['class'=>'treeview'],
                            'items'=>[
                                [
                                    'label'=>Yii::t('backend', '用户管理'),
                                    'icon'=>'<i class="fa fa-database"></i>',
                                    'options'=>['class'=>'treeview'],
                                    'url'=>['/user/index'],
                                    'visible'=>Yii::$app->user->can('administrator')
                                    //'badge'=> TimelineEvent::find()->today()->count(),
                                    //'badgeBgClass'=>'label-success',
                                ],
                                [
                                    'label'=>Yii::t('backend', '验证码管理'),
                                    'icon'=>'<i class="fa fa-hand-o-right"></i>',
                                    'options'=>['class'=>'treeview'],
                                    'url'=>['/user-token/index'],
                                    'visible'=>Yii::$app->user->can('administrator')
                                    //'badge'=> TimelineEvent::find()->today()->count(),
                                    //'badgeBgClass'=>'label-success',
                                ],
                            ]
                        ],
                        [
                            'label'=>Yii::t('backend', 'Other'),
                            'url' => '#',
                            'icon'=>'<i class="fa fa-cogs"></i>',
                            'options'=>['class'=>'treeview'],
                            'items'=>[
                                [
                                    'label'=>Yii::t('backend', 'i18n'),
                                    'url' => '#',
                                    'icon'=>'<i class="fa fa-flag"></i>',
                                    'options'=>['class'=>'treeview'],
                                    'items'=>[
                                        ['label'=>Yii::t('backend', 'i18n Source Message'), 'url'=>['/i18n/i18n-source-message/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                                        ['label'=>Yii::t('backend', 'i18n Message'), 'url'=>['/i18n/i18n-message/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                                    ]
                                ],
                                ['label'=>Yii::t('backend', 'Key-Value Storage'), 'url'=>['/key-storage/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                                ['label'=>Yii::t('backend', 'File Storage'), 'url'=>['/file-storage/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                                ['label'=>Yii::t('backend', 'Cache'), 'url'=>['/cache/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                                ['label'=>Yii::t('backend', 'File Manager'), 'url'=>['/file-manager/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                                [
                                    'label'=>Yii::t('backend', 'System Information'),
                                    'url'=>['/system-information/index'],
                                    'icon'=>'<i class="fa fa-angle-double-right"></i>'
                                ],
                                [
                                    'label'=>Yii::t('backend', 'PHP Info'),
                                    'url'=>['/system-information/info'],
                                    'icon'=>'<i class="fa fa-angle-double-right"></i>'
                                ],
                                [
                                    'label'=>Yii::t('backend', 'Logs'),
                                    'url'=>['/log/index'],
                                    'icon'=>'<i class="fa fa-angle-double-right"></i>',
                                    'badge'=>\backend\models\SystemLog::find()->count(),
                                    'badgeBgClass'=>'label-danger',
                                ],
                            ],
                        ],


                        [
                            'label'=>Yii::t('backend', '开发工具'),
                            'url' => '#',
                            'icon'=>'<i class="fa fa-heart"></i>',
                            'visible'=>Yii::$app->user->can('administrator'),
                            'options'=>['class'=>'treeview'],
                            'items'=>[
                                [
                                    'label'=>Yii::t('backend', '前台API'),
                                    'icon'=>'<i class="fa fa-database"></i>',
                                    'options'=>['class'=>'treeview'],
                                    'url'=> Yii::getAlias('@frontendUrl').'/site/frontend-doc',
                                    'visible'=>Yii::$app->user->can('administrator')
                                    //'badge'=> TimelineEvent::find()->today()->count(),
                                    //'badgeBgClass'=>'label-success',
                                ],
                                [
                                    'label'=>Yii::t('backend', '前台脚手架'),
                                    'icon'=>'<i class="fa fa-hand-o-right"></i>',
                                    'options'=>['class'=>'treeview'],
                                    'url'=>Yii::getAlias('@frontendUrl').'/gii',
                                    'visible'=>Yii::$app->user->can('administrator')
                                    //'badge'=> TimelineEvent::find()->today()->count(),
                                    //'badgeBgClass'=>'label-success',
                                ],
                                [
                                    'label'=>Yii::t('backend', '后台API'),
                                    'icon'=>'<i class="fa fa-database"></i>',
                                    'options'=>['class'=>'treeview'],
                                    'url'=> URL::to(['/site/doc']),
                                    'visible'=>Yii::$app->user->can('administrator')
                                    //'badge'=> TimelineEvent::find()->today()->count(),
                                    //'badgeBgClass'=>'label-success',
                                ],
                                [
                                    'label'=>Yii::t('backend', '后台脚手架'),
                                    'icon'=>'<i class="fa fa-hand-o-right"></i>',
                                    'url'=>['/gii'],
                                    'visible'=>Yii::$app->user->can('administrator'),
                                    //'badge'=> TimelineEvent::find()->today()->count(),
                                    //'badgeBgClass'=>'label-success',
                                ],
                                
                                /*
                                ['label'=>Yii::t('backend', '课件管理'), 'url'=>['/campus/courseware/index'], 'icon'=>'<i class="fa  fa-file-text"></i>'
                                ], 
                                */
                            ]
                        ],
                        
                    ]
                ]) ?>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    <?php echo $this->title ?>
                    <?php if (isset($this->params['subtitle'])): ?>
                        <small><?php echo $this->params['subtitle'] ?></small>
                    <?php endif; ?>
                </h1>

                <?php echo Breadcrumbs::widget([
                    'tag'=>'ol',
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
            </section>

            <!-- Main content -->
            <section class="content">
                <?php if (Yii::$app->session->hasFlash('alert')):?>
                    <?php echo \yii\bootstrap\Alert::widget([
                        'body'=>ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body'),
                        'options'=>ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options'),
                    ])?>
                <?php endif; ?>
                <?php echo $content ?>
            </section><!-- /.content -->
        </aside><!-- /.right-side -->
    </div><!-- ./wrapper -->

<?php $this->endContent(); ?>
<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\assets\NavSearchAsset;
use common\widgets\Alert;

AppAsset::register($this);
NavSearchAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>  
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Filmason',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    $menuItems = [
        '<form id="movie-search" class="navbar-form navbar-left nav-search" role="search" action="'.Yii::$app->homeUrl.'movie/search" method="get">
            <div class="Typeahead Typeahead--twitterUsers">
                <div class="u-posRelative">
                    <input class="Typeahead-hint" type="text" tabindex="-1" readonly>
                    <input class="Typeahead-input typeahead" id="demo-input" type="text" name="query" placeholder="ค้นหาหนัง...">
                    <img class="Typeahead-spinner" src="http://twitter.github.io/typeahead.js/img/spinner.gif">
                    <button type="submit" class="btn btn-default search">
                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                    </button>
                </div>
                <div class="Typeahead-menu"></div>
            </div>
            
            <!--button class="u-hidden" type="submit">blah</button-->
        </form>',
        // ['label' => 'Home', 'url' => ['/site/index']],
        // ['label' => 'About', 'url' => ['/site/about']],
        // ['label' => 'Contact', 'url' => ['/site/contact']],
    ];
    
    if (Yii::$app->user->isGuest) {
        // $menuItems[] = ['label' => 'Signup', 'url' => ['/user/registration/register']];
        // $menuItems[] = ['label' => 'Login', 'url' => ['/user/security/login']];
        $menuItems[] = ['label' => /*Icon::show('facebook-square'). */Yii::t('app', 'Facebook Login'), 'url' => ['/user/security/auth?authclient=facebook']];
    } else {
        // $menuItems[] = ['label' => '<img src="http://graph.facebook.com/'. Yii::$app->user->identity->accounts['facebook']->client_id .'/picture" style="width:20px">    '. Yii::$app->user->identity->username, /*'url' => ['/user/settings/profile']*/];
        $menuItems[] =  ['label' => '<img src="http://graph.facebook.com/'. Yii::$app->user->identity->accounts['facebook']->client_id .'/picture" style="width:20px"> '. Yii::$app->user->identity->username. '(Logout)', 'url' => ['/user/security/logout'], 'linkOptions' => ['data-confirm' => Yii::t('app', 'Are you sure to logout?'), 'data-method' => 'post']];
        // $menuItems[] = [
        //     'label' => '',
        //     'items' => [
        //         // ['label' => 'Profile', 'url' => ['/user/settings/profile'], 'visible' => true],
        //         ['label' => 'Logout', 'url' => ['/user/security/logout'], 'linkOptions' => ['data-method' => 'post']],
        //     ],
        // ];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
        'encodeLabels' => false,
    ]);
    NavBar::end();

        $xAction = $this->context->id.'/'.$this->context->action->id;
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $xAction != 'movie/index' ? $content : '' ?>
    </div>
        <?= $xAction == 'movie/index' ? $content : '' ?>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Filmason <?= date('Y') ?></p>
        <!-- <p class="pull-right"><?= Yii::powered() ?></p> -->
    </div>
</footer>

<script id="result-template" type="text/x-handlebars-template">
    <a class="no-underline" href="<?= Yii::$app->homeUrl.'movie/{{id}}/{{title_url}}' ?>">
    <div class="ProfileCard u-cf">
        <img class="ProfileCard-avatar" src="http://image.tmdb.org/t/p/w92/{{poster_path}}">

        <div class="ProfileCard-details">
            <div class="ProfileCard-realName">{{title}}</div>
            <div class="ProfileCard-release-date">@{{release_date}}</div>
            <div class="ProfileCard-description truncate">{{overview}}</div>
        </div>

        <!-- <div class="ProfileCard-stats">
            <div class="ProfileCard-stat"><span class="ProfileCard-stat-label">Ratings:</span> {{#if rating}} {{rating}} {{else}} 0 {{/if}}</div>
            <div class="ProfileCard-stat"><span class="ProfileCard-stat-label">Reviews:</span> {{#if review}} {{review}} {{else}} 0 {{/if}}</div>
        </div> -->
    </div>
    </a>
</script>

<script id="empty-template" type="text/x-handlebars-template">
    <div class="EmptyMessage">พิมพ์ชื่อหนัง...</div>
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

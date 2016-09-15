
<?php
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->registerJs("
$('#nav').affix({
    offset: {
        top: $('#nav').offset().top - 65,
        bottom: 100
    }
});

$('a').click(function(){
    $('html, body').animate({
        scrollTop: $( $(this).attr('href') ).offset().top - 60
    }, 500);
    return false;
});

$('body').scrollspy({ 
	target: '.scrollspy',
	offset: 200
});


", View::POS_READY);

$this->registerCss("
.affix {
  top: 290px;
}

.nav .active {
  font-weight: bold;
}

.nav .nav {
  display: none;
}

.nav .active .nav {
  display: block;
}

.nav .nav a {
  font-weight: normal;
  font-size: .85em;
}

.nav .nav span {
  margin: 0 5px 0 2px;
}

.nav .nav .active a,
.nav .nav .active:hover a,
.nav .nav .active:focus a {
  font-weight: bold;
  padding-left: 30px;
  border-left: 5px solid black;
}

.nav .nav .active span,
.nav .nav .active:hover span,
.nav .nav .active:focus span {
  display: none;
}

.backdrop{
  background-image : url('http://image.tmdb.org/t/p/original".(isset($movie->image[0]->path) ? $movie->image[0]->path : '')."');
  background-color: black;
  background-size: cover;
  background-position: 50% 10%;
  transition: all .5s;
  height: 500px;
  color: #000;
  position: relative;
  
}

.image-fade{
  position: absolute;
  width: 100%;
  height: 30%;
  bottom:0;
  background: linear-gradient(to top, rgba(255,255,255,1) 0%,rgba(255,255,255,0) 100%);
}

.wrap > .container{
  padding: 0;
}

.movie-header{
  width: 100%;
  position: absolute;
  bottom: 0;
  z-index:99;
}

#nav{
  margin-top: -220px;
}

@media only screen and (max-width: 760px) {
    /* For mobile phones: */
   .filmason-vote{
    font-size: 14px;
   }
}

@media only screen and (max-width: 350px) {
    /* For mobile phones: */
   .filmason-vote{
    font-size: 12px;
   }
}


");


?>
<div class="container-fluid">
  <div class="row">
    <section class="backdrop">
      <div class="image-fade"></div>
      <div class="movie-header">
        <div class="container"> 
          <div class="row">
            <div class="col-lg-10 col-md-9 col-lg-offset-2 col-md-offset-3">
              <div>
                <h1><?= $movie->title ?> <small><?= !empty($movie->release) ? date('Y', $movie->release) : '' ?></small></h1>
              </div>
            </div>              
          </div>
        </div>
      </div>
    </section>  
  </div>
</div>

<div class="container">
  <div class="row">
  	<div class="col-lg-2 col-md-3 hidden-sm hidden-xs scrollspy"> 
  		<div id="nav" class="affix-top" data-spy="affix">
  			<div class="poster">
  				<img src="http://image.tmdb.org/t/p/w185<?= $movie->poster ?>" alt="Af119b8d07">
  			</div>
  			<ul class="nav">
  				<!-- <li class=""><a href="#">Top</a></li> -->
<!--   				<li class=""><a href="#web-development">Web Development</a>
  					<ul class="nav">
  						<li class=""><a href="#ruby"><span class="fa fa-angle-double-right"></span>Ruby</a></li>
  						<li class=""><a href="#python"><span class="fa fa-angle-double-right"></span>Python</a></li>
  						<li class=""><a href="#php"><span class="fa fa-angle-double-right"></span>PHP</a></li>
  					</ul>
  				</li> -->
  				<li class=""><a href="#review">Review</a></li>
  				<li class=""><a href="#trailer">Trailer</a></li>
  			</ul>
  		</div>
  	</div>

<div class="col-lg-7 col-md-7" style="margin-left:20px">
  <hr>
  <?php if(!empty($movie->imdb->id)){ ?>
  <section id="review">
    <!-- <h2><span class="fa fa-edit"></span> IMDB</h2> -->
    <div class="row" style="padding: 10px; background: #333;">
      <div class="col-xs-3 col-md-3">
        <?= Html::img(Yii::getAlias('@web') .'/images/imdb.png', ['style' => 'width:200px;', 'class' => 'img-responsive']) ?>
      </div>
      <div class="col-xs-9 col-md-9">
          <div style="float:left;padding-top: 10px">
          <?= Html::img(Yii::getAlias('@web') .'/images/star.png', ['style' => 'width:50px']) ?>
          </div>
          <div style="float:left;padding:10px 0 0 10px">
            <span style="color: #fff; font-size:36px;"><?= number_format($movie->imdb->rating, 1)?></span>
            <span style="color: #777; font-size:12px;">/<?= $movie->imdb->max_rating ?></span>
            <span style="color: #777; font-size:12px;"> from <?= number_format($movie->imdb->num_review) ?> user(s)</span>
          </div>
      </div>
    </div>
  </section><!--end of #web-devlopment-->
  <?php } ?>
  <br>
  <?php if(!empty($movie->rt->id)){ ?>
  <section id="tomato-rotten">
    <div class="row" style="background: #FEF7CD;">
      <div class="col-xs-2 col-md-3">
        <a href="<?= $movie->rt->url ?>" target="_blank" title="ดูเพิ่มเติม">
          <?= Html::img(Yii::getAlias('@web') .'/images/rottentomatoes.png', ['style' => 'width:200px', 'class' => 'img-responsive']) ?>
        </a>
      </div>
      <div class="col-xs-10 col-md-9" style="padding:5px;">
        <div class="col-xs-6 col-md-7">
          <?php $meters = ['rotten' => 50, 'fresh' => 0, 'certified_fresh' => 100, 'upright' => 150, 'wts' => 200, 'spilled' => 250]; ?>
          <p style="font-size:11px">TOMATOMETER</p>
          <span style="
            background: transparent url(<?= Yii::getAlias('@web') .'/images/rt.png' ?>) no-repeat 0px -<?= $meters[$movie->rt->critic_meter_tomato] ?>px;
            display: inline-block;
            vertical-align: text-bottom;
            height: 48px;
            width: 48px;
          "></span>
           <span style="font-size:34px;font-weight:bold;"><?= $movie->rt->critic_score ?>%</span>
          <p style="font-size:11px; margin: 0 0 2px;"><span style="color:#999">Average Rating:</span> <?= $movie->rt->average_rating ?>/10</p>
          <p style="font-size:11px; margin: 0 0 2px;"><span style="color:#999">Reviews Counted:</span> <?= number_format($movie->rt->reviews_counted) ?></p>
          <p style="font-size:11px; margin: 0 0 2px;"><span style="color:#999">Fresh:</span> <?= $movie->rt->fresh ?></p>
          <p style="font-size:11px; margin: 0 0 2px;"><span style="color:#999">Rotten:</span> <?= $movie->rt->rotten ?></p>

        </div>
        <div class="col-xs-6 col-md-5" style="border-left:1px solid #555;">
          <p style="font-size:11px">AUDIENCE SCORE</p>
          <span style="
            background: transparent url(<?= Yii::getAlias('@web') .'/images/rt.png' ?>) no-repeat 0px -<?= $meters[$movie->rt->user_meter_tomato] ?>px;
            display: inline-block;
            vertical-align: text-bottom;
            height: 48px;
            width: 48px;
          "></span>
           <span style="font-size:34px;font-weight:bold;"><?= $movie->rt->user_score ?>%</span>
          <p style="font-size:11px; margin: 0 0 2px;"><span style="color:#999">Average Rating:</span> <?= $movie->rt->user_average_rating ?>/5</p>
          <p style="font-size:11px; margin: 0 0 2px;"><span style="color:#999">User Ratings:</span> <?= number_format($movie->rt->user_reviews_counted) ?></p>
        </div>
      </div>
    </div>
  </section><!--end of #marketing-->
  <?php } ?>
  <hr>
    <section id="filmason">
    <div style="background:#000; padding: 10px">
      <div class="row">
        <div class="col-xs-12"><h4 class="text-center" style="color:#ddd">ชาว Filmason ว่าไง?</h4></div>
      </div>
      <div class="row">
        
        <?php if(Yii::$app->user->isGuest) { ?>
        <div class="col-xs-6">
          <?= Html::a('<span>'. number_format($movie->goodCount) .'</span> <span class="glyphicon glyphicon-heart"></span> โรงเท่านั้น', ['/user/security/auth?authclient=facebook'], ['type' => 'button', 'class' => 'btn btn-success btn-lg', 'style' => 'width:100%;']) ?>
        </div>
        <div class="col-xs-6">
          <?= Html::a('<span>'. number_format($movie->badCount) .'</span> <span class="glyphicon glyphicon-piggy-bank"></span> รอแผ่นเถอะ', ['/user/security/auth?authclient=facebook'], ['type' => 'button', 'class' => 'btn btn-danger btn-lg', 'style' => 'width:100%;']) ?>
        </div>
        <?php }else{ ?>
          <?php Pjax::begin(['timeout' => 15000, 'enablePushState' => false ]); ?>
            <div class="col-xs-6">
              <div><h4 class="text-center" style="color: #fff"><?= number_format($movie->goodCount) ?></h4></div>
              <?= Html::a('<span class="filmason-vote"><span class="glyphicon glyphicon-heart"></span> โรงเท่านั้น</span>' . (isset($movie->myVote) && $movie->myVote->vote === 'g' ? ' <img src="http://graph.facebook.com/'. Yii::$app->user->identity->accounts['facebook']->client_id .'/picture" style="width:20px">' : ''), ['/movie/'. Yii::$app->request->get('id') .'/'. Yii::$app->request->get('name'), 'vote' => 'g'], ['type' => 'button', 'class' => 'btn btn-success btn-lg', 'style' => 'width:100%;']) ?>
            </div>
            <div class="col-xs-6">
              <div><h4 class="text-center" style="color: #fff"><?= number_format($movie->badCount) ?></h4></div>
              <?= Html::a('<span class="filmason-vote"><span class="glyphicon glyphicon-piggy-bank"></span> รอแผ่นเถอะ</span>' . (isset($movie->myVote) && $movie->myVote->vote  === 'b' ? ' <img src="http://graph.facebook.com/'. Yii::$app->user->identity->accounts['facebook']->client_id .'/picture" style="width:20px">' : ''), ['/movie/'. Yii::$app->request->get('id') .'/'. Yii::$app->request->get('name'), 'vote' => 'b'], ['type' => 'button', 'class' => 'btn btn-danger btn-lg', 'style' => 'width:100%;']) ?>
            </div>
          <?php Pjax::end(); ?>
        <?php } ?>
      </div>


    </div>
  </section><!--end of #marketing-->
        <hr>
    <section id="tomato-rotten">
    <div class="row">
      <div class="col-xs-12">
       <div class="fb-comments" data-width="100%" data-numposts="5"></div>
      </div>
    </div>
  </section><!--end of #marketing-->
  <hr>

  <section id="trailer">
    <div class="row">
      <div class="col-md-12">
        <?php 

          foreach($movie->trailer as $trailer){
            preg_match(
                '/[\\?\\&]v=([^\\?\\&]+)/',
                $trailer->path,
                $matches
            );

            echo '<div class="embed-responsive embed-responsive-16by9">
              <iframe class="embed-responsive-item" src="https://youtube.com/embed/'. $matches[1] .'"></iframe>
            </div><br>';
          }

        ?>
      </div>
    </div>
          
          <!-- <section id="ruby">
            <h3>Ruby</h3>
            <p>Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante.
              Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Nam quam nunc, blandit vel,
              luctus pulvinar, hendrerit id, lorem.
            </p>
            <p>Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante.
              Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Nam quam nunc, blandit vel,
              luctus pulvinar, hendrerit id, lorem.
            </p>
            <button type="button" class="btn btn-primary">Learn More</button>
          </section>

          <section id="python">
            <h3>Python</h3>
            <p>Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante.
              Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Nam quam nunc, blandit vel,
              luctus pulvinar, hendrerit id, lorem.
            </p>
            <p>Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante.
              Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Nam quam nunc, blandit vel,
              luctus pulvinar, hendrerit id, lorem.
            </p>
            <button type="button" class="btn btn-primary">Learn More</button>
          </section>

          <section id="php">
            <h3>PHP</h3>
            <p>Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante.
              Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Nam quam nunc, blandit vel,
              luctus pulvinar, hendrerit id, lorem.
            </p>
            <p>Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante.
              Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Nam quam nunc, blandit vel,
              luctus pulvinar, hendrerit id, lorem.
            </p>
            <p>Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante.
              Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Nam quam nunc, blandit vel,
              luctus pulvinar, hendrerit id, lorem.
            </p>
            <button type="button" class="btn btn-primary">Learn More</button>
          </section> -->
    </section><!--end of #graphic-design-->

  </div>

  <div class="col-lg-2 col-md-2">

  </div>
	</div>

</div>



<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v2.7&appId=474327099373950";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
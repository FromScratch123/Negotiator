<?php
  ini_set('log_errors', 'on');
  ini_set('error_log', 'error.log');
  session_start();

  //容疑者格納用配列
  $criminals = array();

  //説得セリフ
  $quotesConvince = array();
  //交渉人
  $quotesConvince[] = "お前はもう逃げられない。諦めるんだ！";
  $quotesConvince[] = "悪いようにはしない！大丈夫だ！";
  $quotesConvince[] = "こんな事しても何もならないぞ！";
  $quotesConvince[] = "望みは叶えてやるから、とにかく落ち着くんだ！";
  $quotesConvince[] = "人質を解放するんだ！人質には大事な家族がいるんだぞ！";
  //犯人
  $quotesConvince[] = "黙れ！近づいたら殺すぞ！";
  $quotesConvince[] = "偉そうにしやがって！";
  $quotesConvince[] = "やめろ！話しかけるな！";
  $quotesConvince[] = "・・・。";
  $quotesConvince[] = "うるさい！車を用意しろ！";

  //同情セリフ
  $quotesSympathize = array();
  //交渉人
  $quotesSympathize[] = "事情は聞いた！君も辛かったんだな。";
  $quotesSympathize[] = "大丈夫。君は何も悪くないよ。";
  $quotesSympathize[] = "落ち着いて。君の気持ちもわかるよ。";
  $quotesSympathize[] = "こんな事になってしまって、かわいそうに。";
  $quotesSympathize[] = "誰も君のこと責めないから大丈夫だよ。";
  //犯人
  $quotesSympathize[] = "・・・。";
  $quotesSympathize[] = "ずっと辛かったんだ…。";
  $quotesSympathize[] = "そうだ。何も悪くないんだ";
  $quotesSympathize[] = "お前に何がわかるんだ！";
  $quotesSympathize[] = "悪いのはあいつなんだ…。";

  //非難セリフ
  $quotesCriticize = array();
  //交渉人
  $quotesCriticize[] = "お前のやっていることは最低だぞ！";
  $quotesCriticize[] = "ふざけたことばっかり言ってんじゃねえ！";
  $quotesCriticize[] = "どうしようもない奴だな！";
  $quotesCriticize[] = "黙って人質を解放するんだ！";
  $quotesCriticize[] = "いい加減にしろ！";
  //犯人
  $quotesCriticize[] = "うるせぇ！";
  $quotesCriticize[] = "なめるなよ！";
  $quotesCriticize[] = "皆殺しだ！";
  $quotesCriticize[] = "お前と話すことは何もない！";
  $quotesCriticize[] = "てめぇ！ぶっ殺してやる！";

  class Sex{
    const MEN = 1;
    const WOMEN = 2;
  }

  abstract class Character{
    //プロパティ
    protected $name;
    protected $sex;
    protected $rate;
    protected $influenceMin;
    protected $influenceMax;

    //メソッド
    public function getName(){
      return $this->name;
    }
    public function setRate($num){
      return $this->rate = $num;
    }
    public function getRate(){
      return $this->rate;
    }

    public function setQuote($str){
      $_SESSION['quote'] = $str;
    }

    public function getQuote(){
      return (!empty($_SESSION['quote'])) ? $_SESSION['quote'] : "";
    }

    abstract public function influent($object);

  }

  class Negotiator extends Character{

    public function __construct($name, $sex, $rate, $influenceMin, $influenceMax){
      $this->name = $name;
      $this->sex = $sex;
      $this->rate = $rate;
      $this->influenceMin = $influenceMin;
      $this->influenceMax = $influenceMax;
    }

    public function influent($object){
      global $quotesConvince;
      global $quotesSympathize;
      global $quotesCriticize;
      if(!empty($_POST['convince'])){
        $this->setQuote($quotesConvince[mt_rand(0,4)]);
      }elseif(!empty($_POST['sympathize'])){
        $this->setQuote($quotesSympathize[mt_rand(0,4)]);
      }elseif(!empty($_POST['criticize'])){
        $this->setquote($quotesCriticize[mt_rand(0,4)]);
      }
      if(!empty($_SESSION['username'])){
        Log::set('【'.$_SESSION['username'].'】');
      }else{
        Log::set('【'.$this->name.'】');
      }
      Log::set($this->getQuote());
      $influenceRate = mt_rand($this->influenceMin, $this->influenceMax);

      if(!empty($_POST['convince'])){
        //1/3の確率で興奮度を下げる
        if(!mt_rand(0,2)){
          $object->setRate($object->getRate()-$influenceRate);
          Log::set($object->getName().'の興奮度は'.$influenceRate.'減少した。');
        }else{
          //2/3の確率で興奮度を上げる
          //1/10の確率で大きく動揺する
          $anxietyFlg = mt_rand(0,9);
          if(!$anxietyFlg){
            $influenceRate = $influenceRate * 2;
            $influenceRate = (int)$influenceRate;
            $object->setRate($object->getRate()+$influenceRate);
            Log::set($object->getName().'は大きく動揺した。');
          }
          if($anxietyFlg != 0){
            $object->setRate($object->getRate()+$influenceRate);
          }
          Log::set($object->getName().'の興奮度は'.$influenceRate.'増加した。');
        }
      }
      if(!empty($_POST['sympathize'])){
        //1/5の確率で興奮度を上げる
        if(!mt_rand(0,4)){
          $object->setRate($object->getRate()+$influenceRate);
          Log::set($object->getName().'の興奮度は'.$influenceRate.'増加した。');
        }else{
          $object->setRate($object->getRate()-$influenceRate);
          Log::set($object->getName().'の興奮度は'.$influenceRate.'減少した。');
        }
      }
      if(!empty($_POST['criticize'])){
        $object->setRate($object->getRate()+$influenceRate);
        Log::set($object->getName().'の興奮度は'.$influenceRate.'増加した。');
      }
    }
  }

  class Criminal extends Character{
    protected $img;

    public function __construct($name, $sex, $rate, $img, $influenceMin, $influenceMax){
      $this->name = $name;
      $this->sex = $sex;
      $this->rate = $rate;
      $this->img = $img;
      $this->influenceMin = $influenceMin;
      $this->influenceMax = $influenceMax;
    }
    public function getImg(){
      return $this->img;
    }

    public function influent($object){
      global $quotesConvince;
      global $quotesSympathize;
      global $quotesCriticize;
      if(!empty($_POST['convince'])){
        $this->setQuote($quotesConvince[mt_rand(5,9)]);
      }elseif(!empty($_POST['sympathize'])){
        $this->setQuote($quotesSympathize[mt_rand(5,9)]);
      }elseif(!empty($_POST['criticize'])){
        $this->setquote($quotesCriticize[mt_rand(5,9)]);
      }
      Log::set('【'.$this->name.'】');
      Log::set($this->getQuote());
      $influenceRate = mt_rand($this->influenceMin, $this->influenceMax);

      if(!empty($_POST['convince'])){
        //1/5の確率で信頼度を上げる
        if(!mt_rand(0,4)){
          $object->setRate($object->getRate()+$influenceRate);
          Log::set($object->getName().'への信頼度は'.$influenceRate.'増加した。');
        }else{
          //4/5の確率で信頼度を下げる
            //1/10の確率で信頼を大きく失う
            $reliabilityFlg = mt_rand(0,9);
            if(!$reliabilityFlg){
              $influenceRate = $influenceRate * 2;
              $influenceRate = (int)$influenceRate;
              $object->setRate($object->getRate()-$influenceRate);
              Log::set($object->getName().'は信頼を大きく失った。');
            }
          if($reliabilityFlg != 0){
            $object->setRate($object->getRate()-$influenceRate);
          }
          Log::set($object->getName().'への信頼度は'.$influenceRate.'減少した。');
        }
      }
      if(!empty($_POST['sympathize'])){
        $object->setRate($object->getRate()+$influenceRate);
        Log::set($object->getName().'への信頼度は'.$influenceRate.'増加した。');
      }
      if(!empty($_POST['criticize'])){
        $object->setRate($object->getRate()-$influenceRate);
        Log::set($object->getName().'への信頼度は'.$influenceRate.'減少した。');
      }
    }
  }

  interface Loginterface{
    public static function set($str);
    public static function clear();
  }

  class Log implements Loginterface{
    public static function set($str){
      if(empty($_SESSION['log'])) $_SESSION['log'] = "";
      $_SESSION['log'] .= $str.'<br>';
    }
    public static function clear(){
      unset($_SESSION['log']);
    }
  }

  //Criminalインスタンス生成
  $criminals[] = new Criminal("パトリシア", Sex::WOMEN, 50, "img/criminal01.jpg", 10, 25);
  $criminals[] = new Criminal("メリー", Sex::WOMEN, 70, "img/criminal02.jpg", 10, 40);
  $criminals[] = new Criminal("リンダ", Sex::WOMEN, 40, "img/criminal03.jpg", 5, 30);
  $criminals[] = new Criminal("ジェームス", Sex::MEN, 65, "img/criminal04.jpg", 10, 40);
  $criminals[] = new Criminal("ジェニファー", Sex::WOMEN, 75, "img/criminal05.jpg", 10, 30);
  $criminals[] = new Criminal("ジョセフ", Sex::MEN, 50, "img/criminal06.jpg", 10, 20);
  $criminals[] = new Criminal("ウィリアム", Sex::MEN, 70, "img/criminal07.jpg", 5, 25);
  $criminals[] = new Criminal("スーザン", Sex::WOMEN, 80, "img/criminal08.jpg", 0, 50);
  $criminals[] = new Criminal("マーガレット", Sex::WOMEN, 80, "img/criminal09.jpg", 5, 40);
  $criminals[] = new Criminal("ベティ", Sex::WOMEN, 50, "img/criminal10.jpg", 5, 20);
  $criminals[] = new Criminal("トーマス", Sex::MEN, 75, "img/criminal11.jpg", 10, 40);
  $criminals[] = new Criminal("ドナルド", Sex::MEN, 80, "img/criminal12.jpg", 0, 30);
  $criminals[] = new Criminal("アシュリー", Sex::WOMEN, 40, "img/criminal13.jpg", 10, 40);
  $criminals[] = new Criminal("ポール", Sex::MEN, 50, "img/criminal14.jpg", 0, 20);
  $criminals[] = new Criminal("ナンシー", Sex::WOMEN, 70, "img/criminal15.jpg", 10, 40);
  $criminals[] = new Criminal("ジョージ", Sex::MEN, 75, "img/criminal16.jpg", 10, 30);
  $criminals[] = new Criminal("ライアン", Sex::MEN, 60, "img/criminal17.jpg", 10, 30);
  $criminals[] = new Criminal("ブライアン", Sex::MEN, 50, "img/criminal18.jpg", 5, 40);
  $criminals[] = new Criminal("エリック", Sex::MEN, 70, "img/criminal19.jpg", 10, 40);
  $criminals[] = new Criminal("ニンジャ", Sex::MEN, 30, "img/criminal20.jpg", 0, 15);
  $criminals[] = new Criminal("フランク", Sex::MEN, 70, "img/criminal21.jpg", 5, 35);
  $criminals[] = new Criminal("アナ", Sex::WOMEN, 50, "img/criminal22.jpg", 0, 50);
  $criminals[] = new Criminal("ジャック", Sex::MEN, 60, "img/criminal23.jpg", 10, 35);
  $criminals[] = new Criminal("マリア", Sex::WOMEN, 80, "img/criminal24.jpg", 15, 40);
  $criminals[] = new Criminal("カイル", Sex::MEN, 50, "img/criminal25.jpg", 0, 25);
  $criminals[] = new Criminal("オリビア", Sex::WOMEN, 90, "img/criminal26.jpg", 0, 5);
  $criminals[] = new Criminal("フィリップ", Sex::MEN, 80, "img/criminal27.jpg", 10, 40);
  $criminals[] = new Criminal("ロイ", Sex::MEN, 50, "img/criminal28.jpg", 5, 25);
  $criminals[] = new Criminal("ソフィア", Sex::WOMEN, 60, "img/criminal29.jpg", 10, 25);
  $criminals[] = new Criminal("リサ", Sex::MEN, 65, "img/criminal30.jpg", 10, 35);

  function createCriminal(){
    global $criminals;
    $criminal = $criminals[mt_rand(0,29)];
    Log::set($criminal->getName().'との交渉を開始した。');
    $_SESSION['criminal'] = $criminal;
    $_SESSION['negotiator']->setRate(50);
    // if($_SESSION['saveCount'] == 0){
    //   $_SESSION['negotiator']->setRate($_SESSION['negotiator']->getRate());
    // }else{
    //   $_SESSION['negotiator']->setRate($_SESSION['negotiator']->getRate()-$_SESSION['negotiator']->getRate());
    // }
  }

  function createNegotiator(){
    //Negotiatorインスタンス生成
    if($_SESSION['username'] !== ""){
        $negotiator = new Negotiator($_SESSION['username'], Sex::MEN, 50, 10, 30);
    }else{
      $negotiator = new Negotiator("交渉人", Sex::MEN, 50, 10, 30);
    }
    $_SESSION['negotiator'] = $negotiator;
  }

  function init(){
    // Log::clear();
    $_SESSION['saveCount'] = 0;
    $_SESSION['username'] = (!empty($_POST['username'])) ? $_POST['username'] : "";
    $_SESSION['gameover'] = 0;
    createNegotiator();
    createCriminal();
  }

  function gameover(){
    $_SESSION = array();
  }

  if(!empty($_POST)){
    $influentFlg = (!empty($_POST['convince']) || !empty($_POST['sympathize']) || !empty($_POST['criticize'])) ? true : false;
    $startFlg = (!empty($_POST['start'])) ? true : false;
    error_log('POST送信がありました');
    if($startFlg){
      error_log('ゲームスタートのボタンが押されました');
      Log::set('ゲームスタート');
      init();
      error_log('交渉人の名前：'.$_SESSION['username']);
    }else{
      if(!empty($_SESSION && $influentFlg)){
        error_log('諦める以外のボタンが押されました');
      if(!empty($_SESSION['log'])) { $_SESSION['log'] = ""; }
        //犯人と交渉する
          $_SESSION['negotiator']->influent($_SESSION['criminal']);
          error_log('犯人の興奮度：'.$_SESSION['criminal']->getRate());
        //犯人の対応
        $_SESSION['criminal']->influent($_SESSION['negotiator']);
        error_log('交渉人の信頼度：'.$_SESSION['negotiator']->getRate());

        //信頼度が0以下または興奮度が100以上になると、ゲームオーバー
        if($_SESSION['negotiator']->getRate() <= 0 || $_SESSION['criminal']->getRate() >= 100){
          if($_SESSION['negotiator']->getRate() <= 0){
            error_log('交渉人の信頼度が0に達しました。');
          }
          if($_SESSION['criminal']->getRate() >= 100){
            error_log('犯人の興奮度が100に達しました');
          }
          $_SESSION['gameover'] = 1;
        }else{
          //信頼度が100以上または興奮度が0以下になると、次の犯人との交渉を始める
          if($_SESSION['criminal']->getRate() <= 0 || $_SESSION['negotiator']->getRate() >= 100){
            if($_SESSION['criminal']->getRate() <= 0){
              Log::set($_SESSION['criminal']->getName().'の興奮度が0に達した。');
              error_log('犯人の興奮度が0に達しました。');
            }
            if($_SESSION['negotiator']->getRate() >= 100){
              Log::set($_SESSION['negotiator']->getName().'の信頼度が100に達した。');
              error_log('信頼度が100以上に達しました。');
            }
            Log::set($_SESSION['criminal']->getName().'が投降した。');
            Log::set('人質は無事に保護された。');
            $_SESSION['saveCount'] = $_SESSION['saveCount'] +1;
            createCriminal();
          }
        }
      }else{
        if(!empty($_SESSION)){
        //諦めるを押した場合
        Log::set($_SESSION['negotiator']->getName().'は犯人との交渉を諦めた。');
        error_log('諦めるのボタンが押されました');
        createCriminal();
        }
      }
    }
    if(!empty($_POST['exit'])){
      gameover();
    }
    $_POST = array();
  }
 ?>



<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>Negotiator</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans|M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
  </head>
  <body>
    <div class="contents-wrap">
      <h1 id="main-title">Negotiator</h1>
      <?php if(empty($_SESSION)){ ?>
        <div class="rule">
          <div class="rule-header">
            <h2>交渉人となって人質を救いだせ！</h2>
            <p>犯人と交渉を行い、人質を救いだすゲームです。<br>
              犯人が興奮しないように注意し、犯人から信頼してもらえるように上手く交渉しましょう。<br></p>
            </div>
            <div class="rule-list">
              <h3>ルール</h3>
              <ol>
                <li>犯人の興奮度が０以下または犯人からの信頼度が100以上になるとクリアです。</li>
                <li>犯人の興奮度が100以上または犯人からの信頼度が0以下になるとゲームオーバーです。</li>
              </ol>
              <p>あなたの交渉術で多くの命を救いましょう！</p>
            </div>
        </div>
        <div class="start-area">
          <form action="" method="post">
            <label>名前：<input type="text" name="username" placeholder="名前" style="border-bottom: 1px solid gray;"></label>
            <input type="submit" name="start" value="▶︎ ゲームスタート" class="start">
          </form>
        </div>  <?php }else{ ?>
      <div class="criminal-area">
        <div class="img-wrap">
          <img src="<?php echo $_SESSION['criminal']->getImg() ?>" alt="criminal">
        </div>
        <div class="anxiety-rate">
          <p>興奮度：<?php echo $_SESSION['criminal']->getRate() ?></p>
        </div>
        <div class="criminal-name">
          <p><?php echo $_SESSION['criminal']->getName(); ?></p>
        </div>
      </div>
      <div class="negotiator-area">
        <div class="save-count">
          <p>救った人数：<?php echo $_SESSION['saveCount']; ?></p>
        </div>
        <div class="reliability-rate">
          <p>信頼度：<?php echo $_SESSION['negotiator']->getRate() ?></p>
        </div>
        <div class="options">
          <?php if(!$_SESSION['gameover']){ ?>
          <form action="" method="post">
            <input type="submit" name="convince" value="▶︎ 説得する">
            <input type="submit" name="sympathize" value="▶︎ 同情する">
            <input type="submit" name="criticize" value="▶︎ 非難する">
            <input type="submit" name="giveup" value="▶︎ 諦める">
          </form> <?php } ?>
        </div>
      </div>
      <div class="log-area">
        <p><?php echo (!empty($_SESSION['log'])) ? $_SESSION['log'] : ""; ?></p>
      </div> <?php } ?>
      <?php if(!empty($_SESSION) && $_SESSION['gameover']){ ?>
        <div class="gameover">
        <h1>Game Over</h1>
        <h2><?php echo $_SESSION['negotiator']->getName() ?>さんの結果</h2>
        <p>救った人数：<?php echo $_SESSION['saveCount'] ?>人</p>
        <p>結果をツイートしてみよう！</p>
        <div class="twitter-button">
          <a class="twitter-share-button"
          href="https://twitter.com/intent/tweet?" data-size="large">Tweet</a>
        </div>
        <div class="return-to-top">
          <form class="exit" action="" method="post">
            <input type="submit" name="exit" value="Replay">
          </form>
        </div>
      </div>
    </div> <?php } ?>
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
  </body>
</html>

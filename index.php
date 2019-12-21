<?php
ini_set('log_erros', 'on');
ini_set('error_log', 'error.log');
session_start();

//犯罪者格納用
$criminal = array();

//性別クラス
class Sex{
  const MEN = 1;
  const WOMEN = 2;
}

class Quote{
  $quote = array();
  $quote['negative'](
  const LINE01 = '';
  const LINE02 = '';
  const LINE03 = '';
  const LINE04 = '';
  const LINE05 = '';
  const LINE06 = '';
  const LINE07 = '';
  const LINE08 = '';
  const LINE09 = '';
  const LINE10 = '';
  )
  $quote['positive'](
  const LINE11 = '';
  const LINE12 = '';
  const LINE13 = '';
  const LINE14 = '';
  const LINE15 = '';
  )
}

abstract class Human{
  protected $name;
  protected $sex;
  protected $anxiety;
  protected $anxietyMin;
  protected $anxietyMax;
  abstract public function say();
  public function setName($str){
    $this->name = $str;
  }
  public function setSex($num){
    $this->sex = $num;
  }
  public function setAnxiety($num){
    $this->anxiety = $num
  }
  public function getName(){
    return $this->name;
  }
  public function getAnxiety(){
    return $this->anxiety;
  }
  public function setQuote($key){
    $line = $quote[$key][mt_rand(0,10)];
    return $line;
  }
  public function getQuote(){
    return $line;
  }
  public function negotiate($object){
    $effect = mt_rand($this->$anxietyMin, $this->$anxietyMax);
    $quoteFlg =  if($quoteCategory === 'negative'){
      return 0;
    }else{
      return 1;
    }
    if(!mt_rand(0,9)){
      $effect = $effect * 2.0;
      $effect = (int)$effect;
      $quoteSwich = mt_rand(1,3);
      switch($quoteSwich){
        case 1 :
        Log::set($this->getName().Quote::LINE01);
        break;
        case 2 :
        Log::set($this->getName().Quote::LINE02);
        break;
        case 3 :
        Log::set($this->getName().Quote::LINE03);
      }
    }
      switch($quoteFlg){
        case 0 :
        $object->setAnxiety($object->getAnxiety()-$effect);
        break;
        case 1 :
        $object->setAnxiety($object->getAnxiety()+$effect);
        break;
      }
      if($object->getAnxiety() >= 100){
        $object->setAnxiety(100);
      }
    Log::set($effect.)
  }
}

class Criminal extends Human{
  public function takeQuote($str){
    if(array_keys($quote, $line) === 'negative'){
      $quoteCategory = 'negative';
    }
  }else{
    $quoteCategory = 'positive';
  }
}


 ?>


<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>Negtiator</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <div class="contents-wrap">
      <h1>Negotiator</h1>

    </div>
  </body>
</html>

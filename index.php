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
  public function negotiate($object){
    $effect = mt_rand($this->$anxietyMin, $this->$anxietyMax);
    $quote = (getQuote())
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
    if()
    $object->setAnxiety($object->getAnxiety()-$effect);
    Log::set($effect.)
  }
}
 ?>

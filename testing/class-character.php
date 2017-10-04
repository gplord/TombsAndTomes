<?

// Test class, experimenting with Object-oriented character classes

class Character {

  public $hp = 10;
  public $energy = 5;
  public $level = 1;
  public $name = "Default Name";

  public $stats = array(
    "strength" => 1,
    "dexterity" => 1,
    "intellect" => 1,
    "cunning" => 1,
  );

  public $abilities = [];
  public $dialogue = [];

  public $resists;
  public $vulnerability;
 
  public function __construct($newName = null) {
      if ($newName != null) {
          $this->name = $newName;
      } else {
          $this->name = "NO NAME";
      }
      echo $this->name . " was constructed.<br />";
  }
 
  /*public function __destruct() {
      echo 'Memory reference to ' . __CLASS__ . ' was destroyed.<br />';
  }*/
 
  public function __toString() {
      echo "Using the toString method: ";
  }
 
  public function getHP() {
      return $this->name . " has " . $this->hp . " HP.<br />\n";
  }
  public function changeHP($amount) {
      return $this->hp += $amount;
  }
  public function takeDamage($amount) {
    $this->changeHP(-$amount);
    echo $this->name . " took " . $amount . " points of damage.<br />\n";
    return $this->hp;
  }

}
 
class Hero extends Character {

  /*public function __construct() {
      //parent::__construct();
      echo "A new constructor in " . __CLASS__ . ".<br />";
  }*/
 
  public function newMethod() {
      echo "From a new method in " . __CLASS__ . ".<br />";
  }
 
  public function callProtected() {
      return $this->getProperty();
  }

}

?>
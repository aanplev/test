<?php

namespace Figures;

class Figure
{
  protected $color;

  public function __construct($color)
  {
     $this->color = $color;
  }

  public function saveToDb($connection)
  {
  }
}

class Rectangle extends Figure
{
  public $height;
  public $width;

  public function __construct($height, $width, $color)
  {
     $this->height = $height;
     $this->width = $width;

     parent::__construct($color);
  }

  public function saveToDb($connection)
  {
     $sql = "INSERT INTO mytable (height, width, color) ".
        "VALUES (".$this->height.", ".$this->width.", '".$this->color."')";

     if (mysqli_query($connection, $sql) !== true)
     {
        throw new \Exception();
     }

     return mysqli_insert_id($connection);
  }
}

class Circle extends Figure
{
  public $r;

  public function __construct($r, $color)
  {
     $this->r = $r;

     parent::__construct($color);
  }

  public function saveToDb($connection)
  {
     $sql = "INSERT INTO mytable (radius, color) ".
        "VALUES (".$this->r.", '".$this->color."')";

     if (mysqli_query($connection, $sql) !== true)
     {
        throw new \Exception();
     }

     return mysqli_insert_id($connection);
  }
}

class Geometry
{
  public static function calculate(Figure $f)
  {
     if ($f instanceof Rectangle)
        return $f->height * $f->width;
     elseif ($f instanceof Circle)
        return $f->r * $f->r * 3.14;

     throw new \Exception();
  }

  public static function save($f, $connection)
  {
     return $f->saveToDb($connection);
  }
}

$con = mysqli_connect("localhost", "my_user", "my_password", "my_db");

$f = new Rectangle(2, 3, 'red');
echo "Square = ".Geometry::calculate($f).";";
Geometry::save($f, $con);

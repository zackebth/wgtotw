<?php

namespace Nojs\Flash;

/**
 * Simple way of showing a flashmessage.
 *
 */
class FlashDirect {

/*
* Members
*
*/
private $msg = "Message";
private $location = "";
private $time = 1500;
private $color = "#1ebe4b"; // Color of the message.
private $background = "#000"; // Background color for the message.

public function __construct($time = 1500)
{
  $this->time = $time;
}



public function showFlash() {

$html = " <div style='width: 100%; height: 100px; background: " . $this->background . "; '>";
$html .= "<p style=' width: 100%; text-align: center;  z-index: 99999;  padding-top: 35px; color: " . $this->color . ";' >" . $this->msg;
$html .= "</p></div>";

echo $html;
echo "<script>setTimeout(\"location.href = '" . $this->location . "';\",$this->time);</script>";

}

public function redirectTo($loc) {
  $this->location = $loc;
}

public function setTime($time) {
  $this->time = $time;
}

public function alert($msg = null) {

$this->color = "#de601a";

  if($msg != null) {
    $this->msg = $msg;
  } else {
    $this->msg = "Alert, something isn't right!";
  }
}

public function warning($msg = null) {

$this->color = "#de1a1a";

  if($msg != null) {
    $this->msg = $msg;
  } else {
    $this->msg = "WARNING, something isn't right!";
  }
}

public function success($msg = null) {

  $this->color = "#1ebe4b";

  if($msg != null) {
    $this->msg = $msg;
  } else {
    $this->msg = "success!";
  }
}
}

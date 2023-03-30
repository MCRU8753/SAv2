<?php

class pages_controller {
  public function error() {
    // Izpiše pogled s sporočilom o napaki
    require_once('views/pages/error.php');
  }

  public function api(){
    // Izpiše pogled, ki demonstrira uporabo API-ja
    require_once('views/pages/api.php');
  }
}
?>
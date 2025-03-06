<?php
require_once("function/CallPage.php");
callpage("header");
callpage("navbar");
if (isset($_GET['page'])) {
    callpage($_GET['page']);
} else {
   callpage("home");
}
callpage("footer");

?>
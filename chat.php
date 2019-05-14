<?php
require_once "metodos.php";
if (isset($usuario) && $usuario->getAdmin() == 0) {
    include "views/chat.view.phtml";
}
?>
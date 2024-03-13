<?php
class ControllerBaseAdmin extends ControllerBase{
    public function __construct()
    {
        if ((isset($_SESSION['role']) && $_SESSION['role'] != 'Admin') || !isset($_SESSION['user_id'])) {
            $this->redirect("home");
        }
    }
}
?>
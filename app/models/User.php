<?php
    /**
     * A simple user class
     */

    require_once('app/DUtils/DUtils.php');
    class User extends DUtils{

        public function login(){
            return $this->generate_orderCode();
        }

    }
?>
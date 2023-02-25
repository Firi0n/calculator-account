<?php
    interface IMail
    {
        public function send($contact, $header, $message) : bool;
    }
?>

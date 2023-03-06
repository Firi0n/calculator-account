<?php
    interface IMail
    {
        public function send(string $contact, string $header, string $message) : bool;
    }
?>

<?php
    class Template {
        // $vars is an array of variables to be used in the template
        private $vars;
        // constructor
        public function __construct(array $vars) {
            // set the variables and template path
            $this->vars = $vars;
            // start output buffering
            ob_start();
        }
        // print out the template
        public function print() {
            // get the contents of the output buffer
            $content = ob_get_clean();
            // extract the variables to a local namespace
            extract($this->vars);
            // include the template file
            require(__DIR__."/template.php");
        }

    }
?>

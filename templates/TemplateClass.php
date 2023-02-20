<?php
    class Template {
        // $vars is an array of variables to be used in the template
        private $vars;
        // $template is the path to the template file
        private $template;
        // constructor
        public function __construct(array $vars, string $template) {
            // set the variables and template path
            $this->vars = $vars;
            $this->template = $template;
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
            require($this->template);
        }

    }
?>

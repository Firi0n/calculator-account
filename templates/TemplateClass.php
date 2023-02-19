<?php
    class Template {
        private $vars;
        private $template;

        public function __construct(array $vars, string $template) {
            $this->vars = $vars;
            $this->template = $template;
            ob_start();
        }

        public function print() {
            $content = ob_get_clean();
            extract($this->vars);
            require($this->template);
        }

    }
?>

<?php

namespace Colorium\Templating;

interface Engine
{

    /**
     * Generate template content
     *
     * @param string $template
     * @param array $vars
     * @return string
     */
    public function render($template, array $vars = []);

}
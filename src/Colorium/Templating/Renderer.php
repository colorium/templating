<?php

namespace Colorium\Templating;

interface Renderer
{

    /**
     * Generate template
     *
     * @param string $template
     * @param array $sections
     * @return Compilable
     */
    public function make($template, array $sections = []);

    /**
     * Compile template content
     *
     * @param string $template
     * @param array $vars
     * @return string
     */
    public function render($template, array $vars = []);

}
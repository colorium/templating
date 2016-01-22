<?php

namespace Colorium\Templating\Contract;

interface TemplaterInterface
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
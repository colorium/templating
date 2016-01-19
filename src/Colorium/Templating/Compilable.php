<?php

namespace Colorium\Templating;

interface Compilable
{

    /**
     * Compile template
     *
     * @param array $vars
     * @return string
     */
    public function compile(array $vars = []);

}
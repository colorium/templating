<?php

namespace Colorium\Templating;

abstract class Sandbox
{

    /** @var Templater */
    private $templater;

    /** @var string */
    private $file;

    /** @var string */
    private $layout;

    /** @var string */
    private $record;

    /** @var array */
    private $blocks = [];

    /** @var array */
    private $helpers = [];

    /** @var bool */
    private $compiling = false;


    /**
     * Create template
     *
     * @param Templater $templater
     * @param string $file
     * @param array $blocks
     * @param array $helpers
     *
     * @throws \Exception
     */
    public function __construct(Templater $templater, $file, array $blocks = [], array $helpers = [])
    {
        if(!file_exists($file)) {
            throw new \LogicException('Unknown template file "' . $file . '".');
        }

        $this->templater = $templater;
        $this->file = $file;
        $this->blocks = $blocks;
        $this->helpers = $helpers;
    }


    /**
     * Set layout
     *
     * @param string $template
     * @param array $vars
     * @return string
     */
    protected function layout($template, array $vars = [])
    {
        $this->layout = [$template, $vars];
    }


    /**
     * Render block
     *
     * @param string $name
     * @param bool $record default block
     */
    protected function block($name, $record = false)
    {
        $this->record = [$name, false];
        ob_start();

        if(!$record) {
            $this->end();
        }
    }


    /**
     * Rewrite block
     *
     * @param string $name
     */
    protected function rewrite($name)
    {
        $this->record = [$name, true];
        ob_start();
    }


    /**
     * Stop recording block
     */
    protected function end()
    {
        // stop recording block
        $content = ob_get_clean();

        // read record
        list($name, $rewrite) = $this->record;

        // rewrite
        if($rewrite) {
            $this->blocks += [$name => $content];
        }
        // render
        else {
            echo isset($this->blocks[$name])
                ? $this->blocks[$name]
                : $content;
        }

        // reset record
        $this->record = null;
    }


    /**
     * Insert child content block
     *
     * @return string
     */
    protected function content()
    {
        $this->block('__CONTENT__');
        $this->end();
    }


    /**
     * Call helper
     *
     * @param string $helper
     * @param array $args
     * @return mixed
     *
     * @throws \LogicException
     */
    public function __call($helper, array $args = [])
    {
        if(!isset($this->helpers[$helper])) {
            throw new \LogicException('Unknown template helper "' . $helper . '"');
        }

        return call_user_func_array($this->helpers[$helper], $args);
    }


    /**
     * Compile template
     *
     * @param array $vars
     * @return string
     *
     * @throws \Exception
     */
    public function compile(array $vars = [])
    {
        // start compilation
        if($this->compiling) {
            throw new \LogicException('Template is already compiling.');
        }
        $this->compiling = true;

        // extract user vars
        extract($vars);
        unset($vars);

        // start stream capture
        ob_start();

        // display file
        require $this->file;

        // stop stream capture
        $content = ob_get_clean();
        $content .= "\n\n";

        // compile layout
        if($this->layout) {
            list($layout, $vars) = $this->layout;
            $blocks = array_merge($this->blocks, ['__CONTENT__' => $content]);
            $content = $this->templater->render($layout, $vars, $blocks);
        }

        // end
        $this->compiling = false;
        return $content;
    }

}
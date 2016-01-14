<?php

namespace Colorium\Templating;

abstract class Sandbox
{

    /** @var string */
    private $template;

    /** @var string */
    private $root;

    /** @var string */
    private $layout;

    /** @var string */
    private $section;

    /** @var array */
    private $sections = [];

    /** @var array */
    private $helpers = [];

    /** @var bool */
    private $rendering = false;


    /**
     * Create template
     *
     * @param string $template
     * @param array $sections
     * @param array $helpers
     * @param string $root
     *
     * @throws \Exception
     */
    public function __construct($template, array $sections = [], array $helpers = [], $root = null)
    {
        if(!file_exists($template)) {
            throw new \Exception('Unknown template "' . $template . '".');
        }

        $this->template = $template;
        $this->sections = $sections;
        $this->helpers = $helpers;
        $this->root = $root;

        $this->helpers['e'] = function($value) {
            return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        };
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
     * Start recording section
     *
     * @param $name
     */
    protected function section($name)
    {
        $this->section = $name;
        ob_start();
    }


    /**
     * Stop recording section
     */
    protected function end()
    {
        $this->sections[$this->section] = ob_get_clean();
        $this->section = null;
    }


    /**
     * Insert section
     *
     * @param $section
     * @return string
     */
    protected function insert($section)
    {
        return isset($this->sections[$section]) ? $this->sections[$section] : null;
    }


    /**
     * Insert child content
     *
     * @return string
     */
    protected function content()
    {
        return $this->insert('__content__');
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
     */
    public function compile(array $vars = [])
    {
        // start rendering
        if($this->rendering) {
            throw new \LogicException('Template is already rendering.');
        }
        $this->rendering = true;

        // start stream capture
        extract($vars);
        ob_start();

        // display file
        require $this->template;

        // stop stream capture
        $content = ob_get_clean();
        $content .= "\n\n";

        // render layout
        if($this->layout) {

            list($file, $data) = $this->layout;

            $file = rtrim($this->root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . trim($file, DIRECTORY_SEPARATOR ) . '.php';
            $vars = array_merge($vars, $data);
            $sections = array_merge($this->sections, ['__content__' => $content]);
            
            $layout = new static($file, $sections, $this->helpers, $this->root);
            $content = $layout->compile($vars);
        }

        // end
        $this->rendering = false;
        return $content;
    }

}
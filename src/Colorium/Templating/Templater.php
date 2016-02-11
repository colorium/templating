<?php
namespace Colorium\Templating;

class Templater implements Contract\TemplaterInterface
{

    /** @var string */
    public $directory;

    /** @var string */
    public $suffix = '.php';

    /** @var array */
    public $vars = [];

    /** @var array */
    public $helpers = [];

    /** @var Templater */
    protected static $instance;


    /**
     * Create new engine
     *
     * @param string $directory
     * @param string $suffix
     */
    public function __construct($directory = null, $suffix = '.php')
    {
        $this->directory = $directory;
        $this->suffix = $suffix;

        $this->helpers['render'] = [$this, 'render'];
        $this->helpers['e'] = function($value)
        {
            return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        };
    }


    /**
     * Generate content from template compilation
     *
     * @param string $template
     * @param array $vars
     * @param array $blocks
     * @return string
     */
    public function render($template, array $vars = [], array $blocks = [])
    {
        $directory = rtrim($this->directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $file = $directory . trim($template, DIRECTORY_SEPARATOR) . $this->suffix;
        $vars += $this->vars;

        $sandbox = new Template($this, $file, $blocks, $this->helpers);
        return $sandbox->compile($vars);
    }


    /**
     * Generate content from static template compilation
     *
     * @param string $template
     * @param array $vars
     * @param array $blocks
     * @return string
     */
    public static function make($template, array $vars = [], array $blocks = [])
    {
        if(!static::$instance) {
            static::$instance = new static;
        }

        return static::$instance->render($template, $vars, $blocks);
    }

}
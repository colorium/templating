<?php
namespace Colorium\Templating;

class Html implements Engine
{

	/** @var string */
	public $root;

	/** @var array */
	public $vars = [];

	/** @var array */
    public $helpers = [];


    /**
	 * Create new html engine
	 * 
	 * @param string $root
	 */
    public function __construct($root = null)
    {
    	$this->root = $root;
		$this->helpers['render'] = [$this, 'render'];
    }


	/**
	 * Generate content from template compilation
	 *
	 * @param string $template 
	 * @param array $vars 
	 * @return string
	 */
	public function render($template, array $vars = [])
	{
		// clean path
		$file = rtrim($this->root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . trim($template, DIRECTORY_SEPARATOR ) . '.php';

		// create template, prepare data
        $template = new Template($file, [], $this->helpers, $this->root);
        $vars = array_merge($this->vars, $vars);

        // compile
        return $template->compile($vars);
	}
}
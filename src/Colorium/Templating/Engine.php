<?php
namespace Colorium\Templating;

class Engine implements Renderer
{

	/** @var string */
	public $directory;

	/** @var string */
	public $suffix = '.php';

	/** @var array */
	public $vars = [];

	/** @var array */
    public $helpers = [];


	/**
	 * Create new html engine
	 *
	 * @param string $directory
	 * @param string $suffix
	 */
    public function __construct($directory = null, $suffix = '.php')
    {
		$this->directory = $directory;
		$this->suffix = $suffix;

		$this->helpers['render'] = [$this, 'render'];
    }


	/**
	 * Generate template
	 *
	 * @param string $template
	 * @param array $sections
	 * @return Template
	 */
	public function make($template, array $sections = [])
	{
		$directory = rtrim($this->directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		$file = $directory . trim($template, DIRECTORY_SEPARATOR) . $this->suffix;

		return new Template($file, $sections, $this->helpers, $this);
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
		return $this->make($template)->compile($vars);
	}

}
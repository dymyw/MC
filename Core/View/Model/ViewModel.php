<?php
/**
 * View model
 *
 * @package Core_View_Model
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-15
 * @version 2016-10-21
 */

namespace Core\View\Model;

use Core\View\Variables as ViewVariables;

class ViewModel implements ViewModelInterface
{
    /**
     * View variables
     *
     * @var ViewVariables
     */
    protected $variables = [];

    /**
     * Template to use when rendering this model
     *
     * @var string
     */
    protected $template = null;

    /**
     * The placeholder of parent
     *
     * @var string
     */
    protected $placeholder = '__content';

    /**
     * Child models
     *
     * @var ViewModel[]
     */
    protected $children = [];

    /**
     * Is this append to child with the same placeholder?
     *
     * @var bool
     */
    protected $append = false;

    /**
     * Constructor
     *
     * @param string|array|\ArrayAccess|\Traversable $variablesOrTemplate
     * @param string $template
     */
    public function __construct($variablesOrTemplate = [], $template = '')
    {
        // initializing the variables container
        $this->variables = new ViewVariables;

        // set template
        if (is_string($variablesOrTemplate)) {
            $this->setTemplate($variablesOrTemplate);
        }
        // set variables
        elseif ($variablesOrTemplate) {
            $this->setVariables($variablesOrTemplate, true);

            if ($template) {
                $this->setTemplate($template);
            }
        }
    }

    /**
     * Set a single view variable
     *
     * @param string $name
     * @param mixed $value
     * @return ViewModel
     */
    public function setVariable($name, $value)
    {
        if ($value instanceof ViewModelInterface) {
            $this->addChild($value, $name);
            return $this;
        }

        $this->variables[(string) $name] = $value;
        return $this;
    }

    /**
     * Get a single view variable
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getVariable($name, $default = null)
    {
        if (array_key_exists($name, $this->variables)) {
            return $this->variables[$name];
        }

        return $default;
    }

    /**
     * Set view variables
     *
     * Can be an array or a Traversable + ArrayAccess object
     *
     * @param array|\ArrayAccess|\Traversable $variables
     * @param bool $overwrite
     * @return ViewModel
     * @throws \InvalidArgumentException
     */
    public function setVariables($variables, $overwrite = false)
    {
        if (!is_array($variables)) {
            throw new \InvalidArgumentException(sprintf(
                '%s: expects an array, or Traversable argument; received "%s"',
                __METHOD__,
                (is_object($variables) ? get_class($variables) : gettype($variables))
            ));
        }

        if ($overwrite) {
            $this->variables = $variables;
        } else {
            foreach ($variables as $key => $value) {
                $this->setVariable($key, $value);
            }
        }

        return $this;
    }

    /**
     * Get view variables
     *
     * @return array|\ArrayObject
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Property overloading: set a single view variable
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->setVariable($name, $value);
    }

    /**
     * Property overloading: get a single view variable
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (!$this->__isset($name)) {
            return null;
        }

        return $this->getVariable($name);
    }

    /**
     * Property overloading: do we have the requested variable value?
     *
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->variables[$name]);
    }

    /**
     * Property overloading: unset the requested variable
     *
     * @param string $name
     * @return void
     */
    public function __unset($name)
    {
        if ($this->__isset($name)) {
            unset($this->variables[$name]);
        }
    }

    /**
     * Set the template to be used by this model
     *
     * @param string $template
     * @return ViewModel
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Get the template to be used by this model
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set the placeholder of parent
     *
     * @param string $placeholder
     * @return ViewModel
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * Get the placeholder of parent
     *
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * Add a child model
     *
     * @param ViewModelInterface $child
     * @param string $placeholder
     * @param bool $append
     * @return ViewModel
     */
    public function addChild(ViewModelInterface $child, $placeholder = '', $append = false)
    {
        $this->children[$placeholder] = $child;

        if ('' !== $placeholder) {
            $child->setPlaceholder($placeholder);
        }

        if (false !== $append) {
            $child->setAppend($append);
        }

        return $this;
    }

    /**
     * Get a child model
     *
     * @param string $placeholder
     * @return ViewModel
     */
    public function getChild($placeholder)
    {
        return $this->children[$placeholder];
    }

    /**
     * Get all children
     *
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Does the model have any children?
     *
     * @return bool
     */
    public function hasChildren()
    {
        return count($this->children) > 0;
    }

    /**
     * Set flag indicating whether or not append to child with the same placeholder
     *
     * @param bool $append
     * @return ViewModel
     */
    public function setAppend($append)
    {
        $this->append = (bool) $append;
        return $this;
    }

    /**
     * Is this append to child with the same placeholder?
     *
     * @return bool
     */
    public function isAppend()
    {
        return $this->append;
    }

    /**
     * Return count of children
     *
     * @return int
     */
    public function count()
    {
        return count($this->children);
    }

    /**
     * Get iterator of children
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->children);
    }
}

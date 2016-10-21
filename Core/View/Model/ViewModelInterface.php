<?php
/**
 * View model interface
 *
 * @package Core_View_Model
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-15
 * @version 2016-10-21
 */

namespace Core\View\Model;

/**
 * Interface describing a view model
 *
 * Extends "Countable", count() should return the number of children attached to the model
 * Extends "IteratorAggregate", should allow iterating over children
 */
interface ViewModelInterface extends \Countable, \IteratorAggregate
{
    /**
     * Set a single view variable
     *
     * @param string $name
     * @param mixed $value
     * @return ViewModelInterface
     */
    public function setVariable($name, $value);

    /**
     * Get a single view variable
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getVariable($name, $default = null);

    /**
     * Set view variables
     *
     * Can be an array or a Traversable + ArrayAccess object
     *
     * @param array|\ArrayAccess|\Traversable $variables
     * @param bool $overwrite
     * @return ViewModelInterface
     * @throws \InvalidArgumentException
     */
    public function setVariables($variables, $overwrite = false);

    /**
     * Get view variables
     *
     * @return array|\ArrayObject
     */
    public function getVariables();

    /**
     * Set the template to be used by this model
     *
     * @param string $template
     * @return ViewModelInterface
     */
    public function setTemplate($template);

    /**
     * Get the template to be used by this model
     *
     * @return string
     */
    public function getTemplate();

    /**
     * Set the placeholder of parent
     *
     * @param string $placeholder
     * @return ViewModelInterface
     */
    public function setPlaceholder($placeholder);

    /**
     * Get the placeholder of parent
     *
     * @return string
     */
    public function getPlaceholder();

    /**
     * Add a child model
     *
     * @param ViewModelInterface $child
     * @param string $placeholder
     * @param bool $append
     * @return ViewModelInterface
     */
    public function addChild(ViewModelInterface $child, $placeholder = '', $append = false);

    /**
     * Get a child model
     *
     * @param string $placeholder
     * @return ViewModelInterface
     */
    public function getChild($placeholder);

    /**
     * Get all children
     *
     * @return array
     */
    public function getChildren();

    /**
     * Does the model have any children?
     *
     * @return bool
     */
    public function hasChildren();

    /**
     * Set flag indicating whether or not append to child with the same placeholder
     *
     * @param bool $append
     * @return ViewModelInterface
     */
    public function setAppend($append);

    /**
     * Is this append to child with the same placeholder?
     *
     * @return bool
     */
    public function isAppend();
}

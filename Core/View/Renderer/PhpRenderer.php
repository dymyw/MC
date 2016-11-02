<?php
/**
 * View renderer - php
 *
 * @package Core_View_Renderer
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-17
 * @version 2016-11-02
 */

namespace Core\View\Renderer;

use Core\View\Resolver\ResolverInterface;
use Core\View\Resolver\Resolver;
use Core\View\Variables;
use Core\View\Model\ViewModelInterface;

class PhpRenderer implements RendererInterface
{
    /**
     * @var ResolverInterface
     */
    private $__resolver = null;

    /**
     * @var Variables
     */
    private $__vars = null;

    /**
     * @var Variables[]
     */
    private $__varsCached = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->__vars = new Variables;
    }

    /**
     * Return the template engine object
     *
     * Returns the object instance, as it is its own template engine
     *
     * @return PhpRenderer
     */
    public function getEngine()
    {
        return $this;
    }

    /**
     * Set the resolver used to map a template name to a resource the renderer may consume
     *
     * @param ResolverInterface $resolver
     * @return PhpRenderer
     */
    public function setResolver(ResolverInterface $resolver)
    {
        $this->__resolver = $resolver;
        return $this;
    }

    /**
     * Get template resolver
     *
     * @return ResolverInterface
     */
    public function getResolver()
    {
        return $this->__resolver ?: new Resolver();
    }

    /**
     * Processes a view script and returns the output
     *
     * @param string|ViewModelInterface $nameOrModel The script/resource process, or a view model
     * @param null|array|\ArrayAccess $values Values to use during rendering
     * @return string
     * @throws \RuntimeException
     */
    public function render($nameOrModel, $values = null)
    {
        if ($nameOrModel instanceof ViewModelInterface) {
            $model = $nameOrModel;
            $nameOrModel = $model->getTemplate();
            if (empty($nameOrModel)) {
                throw new \RuntimeException(sprintf(
                    '%s: received View Model argument, but template is empty',
                    __METHOD__
                ));
            }

            $values = $model->getVariables();
            unset($model);
        }

        // extract all assigned vars (pre-escaped), but not 'this'.
        if (array_key_exists('this', $values)) {
            unset($values['this']);
        }

        // cache the variables
        $this->__varsCached[] = $this->__vars;

        // save the variables to the renderer
        $this->setVariables($values);

        // render
        $template = $this->getResolver()->resolve($nameOrModel);
        ob_start();
        $this->_run((array) $values, $template);
        $___retval = ob_get_clean();

        // restore variables
        $this->setVariables(array_pop($this->__varsCached));

        // return
        return $___retval;
    }

    /**
     * Set variable storage
     *
     * Expects either an array, or an object implementing ArrayAccess.
     *
     * @param array|ArrayAccess $variables
     * @return PhpRenderer
     * @throws \InvalidArgumentException
     */
    public function setVariables($variables)
    {
        if (!is_array($variables) && !$variables instanceof \ArrayAccess) {
            throw new \InvalidArgumentException(sprintf(
                'Expected array or ArrayAccess object; received "%s"',
                (is_object($variables) ? get_class($variables) : gettype($variables))
            ));
        }

        // enforce a Variables container
        if (!$variables instanceof Variables) {
            $variablesAsArray = [];
            foreach ($variables as $key => $value) {
                $variablesAsArray[$key] = $value;
            }
            $variables = new Variables($variablesAsArray);
        }

        $this->__vars = $variables;
        return $this;
    }

    /**
     * Overloading: proxy to Variables container
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        // return
        return $this->__vars[$name] ?: '';
    }

    /**
     * Overloading: proxy to Variables container
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->__vars[$name] = $value;
    }

    /**
     * Overloading: proxy to Variables container
     *
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->__vars[$name]);
    }

    /**
     * Overloading: proxy to Variables container
     *
     * @param string $name
     * @return void
     */
    public function __unset($name)
    {
        if (!isset($this->__vars[$name])) {
            return;
        }
        unset($this->__vars[$name]);
    }

    /**
     * Include the template and extract the variables to it
     *
     * @param array|\ArrayAccess $__dumpValues
     * @param string $template
     * @return echo
     */
    protected function _run($__dumpValues)
    {
        extract($__dumpValues, EXTR_REFS);
        unset($__dumpValues);
        include func_get_arg(1);
    }
}

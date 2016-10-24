<?php
/**
 * Layout plugin
 *
 * @package Core_Controller_Plugin
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-17
 * @version 2016-10-24
 */

namespace Core\Controller\Plugin;

use Core\View\Model\ViewModelInterface;
use Core\View\Model\ViewModel;

class Layout extends AbstractPlugin
{
    /**
     * @const string
     */
    const LAYOUT_DEFAULT = 'layout/default';

    /**
     * Set a layout for controller
     *
     * @param ViewModelInterface|array|string|null|bool $model
     * @return Layout
     * @throws \InvalidArgumentException
     */
    public function __invoke($model = null)
    {
        // disable layout
        if (false === $model) {
            $this->getController()->setLayout(null);
            return $this;
        }

        // set model
        if ($model instanceof ViewModelInterface) {
            if (!$model->getTemplate()) {
                $model->setTemplate(self::LAYOUT_DEFAULT);
            }
            $this->getController()->setLayout($model);
            return $this;
        }

        // array
        if (is_array($model)) {
            $_model = new ViewModel($model);
            $_model->setTemplate(self::LAYOUT_DEFAULT);
            $this->getController()->setLayout($_model);
            return $this;
        }

        // string
        if (is_string($model)) {
            $_model = new ViewModel;
            $_model->setTemplate($model);
            $this->getController()->setLayout($_model);
            return $this;
        }

        // use the default model
        if (null === $model || true === $model) {
            $_model = new ViewModel;
            $_model->setTemplate(self::LAYOUT_DEFAULT);
            $this->getController()->setLayout($_model);
            return $this;
        }

        // exception
        throw new \InvalidArgumentException(sprintf(
            'Invalid parameter type: %s', gettype($model)
        ));
    }

    /**
     * @return ViewModelInterface|null
     */
    public function getModel()
    {
        return $this->getController()->getLayout();
    }
}

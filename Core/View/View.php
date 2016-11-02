<?php
/**
 * View
 *
 * @package Core_View
 * @author Dymyw <dymayongwei@163.com>
 * @since 2015-01-17
 * @version 2016-11-02
 */

namespace Core\View;

use Core\View\Renderer\RendererInterface;
use Core\View\Model\ViewModelInterface;

class View
{
    /**
     * @var RendererInterface
     */
    protected $renderer = null;

    /**
     * Set renderer
     *
     * @param RendererInterface $renderer
     */
    public function setRenderer(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Get renderer
     *
     * @return RendererInterface
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * Render view model
     *
     * @param ViewModelInterface $model
     * @return string
     */
    public function render(ViewModelInterface $model)
    {
        if ($model->hasChildren()) {
            $this->renderChildren($model);
        }

        $result = $this->getRenderer()->render($model);
        return $result;
    }

    /**
     * Render children of view model
     *
     * @param ViewModelInterface $model
     * @return void
     */
    protected function renderChildren(ViewModelInterface $model)
    {
        foreach ($model as $child) {
            $result = $this->render($child);
            $placeholder = $child->getPlaceholder();
            if (!empty($placeholder)) {
                if ($child->isAppend()) {
                    $oldResult = $model->{$placeholder};
                    $model->setVariable($placeholder, $oldResult . $result);
                } else {
                    $model->setVariable($placeholder, $result);
                }
            }
        }
    }
}

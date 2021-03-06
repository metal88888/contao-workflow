<?php

/**
 * This Contao-Workflow extension allows the definition of workflow process for entities from different providers. This
 * extension is a workflow framework which can be used from other extensions to provide their custom workflow handling.
 *
 * @package    workflow
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014-2017 netzmacht David Molineus
 * @license    LGPL 3.0
 * @filesource
 */

declare(strict_types=1);

namespace Netzmacht\ContaoWorkflowBundle\Workflow\View\Renderer;

use Netzmacht\ContaoWorkflowBundle\Workflow\View\View;
use Netzmacht\Workflow\Flow\Transition;
use Symfony\Component\Form\FormInterface as Form;

/**
 * Class FormRenderer
 */
class FormRenderer extends AbstractRenderer
{
    /**
     * Section name.
     *
     * @var string
     */
    protected static $section = 'form';

    /**
     * {@inheritDoc}
     */
    public function supports(View $view): bool
    {
        return $view->getContext() instanceof Transition && $view->getOption('form') instanceof Form;
    }

    /**
     * {@inheritDoc}
     */
    protected function renderParameters(View $view): array
    {
        /** @var Form $form */
        $form = $view->getOption('form');

        return ['form' => $form->createView()];
    }
}

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

namespace Netzmacht\ContaoWorkflowBundle\Workflow\Flow\Action\Property;

use Netzmacht\ContaoWorkflowBundle\Workflow\Flow\Action\ActionTypeFactory;
use Netzmacht\Workflow\Flow\Action;
use Netzmacht\Workflow\Flow\Transition;
use Netzmacht\Workflow\Flow\Workflow;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface as FormBuilder;

/**
 * Class PropertyActionFactory
 *
 * @package Netzmacht\ContaoWorkflowBundle\Action\Property
 */
class PropertyActionFactory implements ActionTypeFactory
{
    /**
     * {@inheritDoc}
     */
    public function getCategory(): string
    {
        return 'default';
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'property';
    }

    /**
     * {@inheritDoc}
     */
    public function isPostAction(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(Workflow $workflow): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $config, Transition $transition): Action
    {
        $action = new PropertyAction(
            $config['name'] ?: $config['id'],
            $config['label'],
            $config
        );

        if (isset($config['value'])) {
            $action->setValue($config['value']);
        }

        if (isset($config['logChanges']) === 'active'
            || (isset($config['logChanges']) === 'inherit' && $transition->getConfigValue('logChanges'))
        ) {
            $action->setLogChanges(true);
        }

        return $action;
    }

    /**
     * {@inheritDoc}
     */
    public function match(Action $action): bool
    {
        return $action instanceof PropertyAction;
    }


    /**
     * {@inheritDoc}
     */
    public function buildForm(Action $action, Transition $transition, FormBuilder $formBuilder): void
    {
        if (!$this->match($action)) {
            return;
        }

        /** @var PropertyAction $action */
        $formBuilder->add(
            'action_' . $action->getConfigValue('id'),
            TextType::class,
            [
                'required'  => true,
                'label'     => $action->getConfigValue('label'),
                'help'      => $action->getConfigValue('description')
            ]
        );
    }
}

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

namespace Netzmacht\ContaoWorkflowBundle\Flow\Action\Form;

use Netzmacht\ContaoWorkflowBundle\Flow\Action\AbstractAction;
use Netzmacht\Workflow\Flow\Context;
use Netzmacht\Workflow\Flow\Item;
use Netzmacht\Workflow\Flow\Transition;

/**
 * Class FormAction
 *
 * @package Netzmacht\ContaoWorkflowBundle\Flow\Action\Form
 */
class FormAction extends AbstractAction
{
    /**
     * @inheritDoc
     */
    public function getRequiredPayloadProperties(Item $item): array
    {
        return [$this->getName()];
    }

    /**
     * @inheritDoc
     */
    public function validate(Item $item, Context $context): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function transit(Transition $transition, Item $item, Context $context): void
    {
        $context->getProperties()->set($this->getName(), $context->getPayload()->get($this->getName()));
    }
}

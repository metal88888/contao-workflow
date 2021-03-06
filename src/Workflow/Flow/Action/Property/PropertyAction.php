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

use Netzmacht\ContaoWorkflowBundle\Workflow\Flow\Action\AbstractAction;
use Netzmacht\Workflow\Flow\Context;
use Netzmacht\Workflow\Flow\Item;
use Netzmacht\Workflow\Flow\Transition;

/**
 * Class PropertyAction changes the value of an entity property.
 *
 * @package Netzmacht\ContaoWorkflowBundle\Action
 */
class PropertyAction extends AbstractAction
{
    /**
     * Name of the property.
     *
     * @var string
     */
    private $property;

    /**
     * Value to be set.
     *
     * @var mixed
     */
    private $value;

    /**
     * {@inheritdoc}
     */
    public function transit(Transition $transition, Item $item, Context $context): void
    {
        $entity = $this->getEntity($item);
        $value  = $this->getValueForProperty($item, $context);

        $entity->setProperty($this->property, $value);
        $this->logChanges($this->property, $value, $context);
    }

    /**
     * Get the property.
     *
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set the property name which should be updated.
     *
     * @param string $property The property name.
     *
     * @return $this
     */
    public function setProperty($property)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getRequiredPayloadProperties(Item $item): array
    {
        if ($this->value !== null) {
            return [];
        }

        return [
            $this->getName() . '_value'
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function validate(Item $item, Context $context): bool
    {
        return $context->getProperties()->has($this->getName() . '_value');
    }

    /**
     * Get the property value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the value which will be set.
     *
     * @param mixed $value Property value.
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value for the property from the context.
     *
     * If value is given, it is used. If input is required then value from the context is extracted.
     *
     * @param Item    $item    The workflow item.
     * @param Context $context The transition contaext.
     *
     * @return mixed
     */
    private function getValueForProperty(Item $item, Context $context)
    {
        if ($this->value !== null) {
            return $this->value;
        } elseif ($this->getRequiredPayloadProperties($item)) {
            return $context->getPayload()->get($this->getName() . '_value');
        }

        return null;
    }
}

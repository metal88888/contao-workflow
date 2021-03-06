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

namespace Netzmacht\ContaoWorkflowBundle\Model\Transition;

use Contao\Model\Collection;
use Netzmacht\Contao\Toolkit\Data\Model\ContaoRepository;

/**
 * Class TransitionRepository
 */
class TransitionRepository extends ContaoRepository
{
    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct(TransitionModel::class);
    }

    /**
     * Find by workflow id.
     *
     * @param int   $workflowId The workflow id.
     * @param array $options    Query options.
     *
     * @return Collection|TransitionModel[]|null
     */
    public function findByWorkflow(int $workflowId, array $options = ['order' => '.sorting'])
    {
        return $this->findBy(
            ['.pid=?'],
            [$workflowId],
            $options
        );
    }

    /**
     * Find active by workflow id.
     *
     * @param int   $workflowId The workflow id.
     * @param array $options    Query options.
     *
     * @return Collection|TransitionModel[]|null
     */
    public function findActiveByTransition(int $workflowId, array $options = ['order' => '.sorting'])
    {
        return $this->findBy(
            ['.active=1', '.pid=?'],
            [$workflowId],
            $options
        );
    }
}

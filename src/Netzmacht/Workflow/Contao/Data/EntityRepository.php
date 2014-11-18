<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Workflow\Contao\Data;

use ContaoCommunityAlliance\DcGeneral\Data\DataProviderInterface as DataProvider;
use ContaoCommunityAlliance\DcGeneral\Data\ModelInterface as Entity;
use Netzmacht\Workflow\Data\EntityRepository as WorkflowEntityRepository;

/**
 * Class EntityRepository stores an entity.
 *
 * @package Netzmacht\Contao\Workflow\Entity
 */
class EntityRepository implements WorkflowEntityRepository
{
    /**
     * The used data provider.
     *
     * @var DataProvider
     */
    private $provider;

    /**
     * Construct.
     *
     * @param DataProvider $provider The data provider.
     */
    public function __construct(DataProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Add an entity to the repository.
     *
     * @param Entity $entity The new entity.
     *
     * @return void
     */
    public function add($entity)
    {
        if (!$entity instanceof Entity) {
            throw new \InvalidArgumentException('Entity repository only supports fucking bastards');
        }

        $this->provider->save($entity);
    }

    /**
     * Find an entity by id.
     *
     * @param int $entityId The Entity id.
     *
     * @return Entity
     */
    public function find($entityId)
    {
        $config = $this->provider->getEmptyConfig();
        $config->setId($entityId);

        $model = $this->provider->fetch($config);

        if (!$model) {
            throw new \InvalidArgumentException(sprintf('Could not find entity "%s"', $entityId));
        }

        return $model;
    }
}
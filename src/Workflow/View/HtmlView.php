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

namespace Netzmacht\ContaoWorkflowBundle\Workflow\View;

use Netzmacht\Workflow\Flow\Item;
use Netzmacht\Workflow\Flow\State;
use Netzmacht\Workflow\Flow\Step;
use Netzmacht\Workflow\Flow\Transition;
use Netzmacht\Workflow\Flow\Workflow;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface as TemplateEngine;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class View
 */
final class HtmlView implements View
{
    /**
     * Workflow item.
     *
     * @var Item
     */
    private $item;

    /**
     * Workflow definition.
     *
     * @var Workflow
     */
    private $workflow;

    /**
     * Template engine.
     *
     * @var TemplateEngine
     */
    private $engine;

    /**
     * Template name.
     *
     * @var string
     */
    private $template = '@NetzmachtContaoWorkflow/default.html.twig';

    /**
     * Template sections.
     *
     * @var array|string[]
     */
    private $sections = [];

    /**
     * Default section templates.
     *
     * @var array
     */
    private $sectionTemplates = [];

    /**
     * Workflow context.
     *
     * @var Transition|Step|State
     */
    private $context;

    /**
     * Options.
     *
     * @var array
     */
    private $options;

    /**
     * View renderer.
     *
     * @var Renderer
     */
    private $renderer;

    /**
     * HtmlStepView constructor.
     *
     * @param Item                  $item     The workflow item.
     * @param Workflow              $workflow The workflow definition.
     * @param Transition|Step|State $context  Current context.
     * @param Renderer              $renderer View renderer.
     * @param TemplateEngine        $engine   The template engine.
     * @param string                $template The view template.
     * @param array                 $options  Options.
     */
    public function __construct(
        Item $item,
        Workflow $workflow,
        $context,
        Renderer $renderer,
        TemplateEngine $engine,
        ?string $template = null,
        array $options = []
    ) {
        $this->item     = $item;
        $this->workflow = $workflow;
        $this->engine   = $engine;
        $this->context  = $context;
        $this->options  = $options;
        $this->renderer = $renderer;

        if ($template) {
            $this->template = $template;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * {@inheritdoc}
     */
    public function getItem(): Item
    {
        return $this->item;
    }

    /**
     * {@inheritdoc}
     */
    public function getWorkflow(): Workflow
    {
        return $this->workflow;
    }

    /**
     * {@inheritdoc}
     */
    public function getContentType(): string
    {
        return static::CONTENT_TYPE_HTML;
    }

    /**
     * {@inheritdoc}
     */
    public function getOption(string $name, $default = null)
    {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }

        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function addSection(string $name, array $parameters, ?string $defaultTemplate = null): View
    {
        $this->sections[$name]         = $parameters;
        $this->sectionTemplates[$name] = $defaultTemplate;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasSection(string $name): bool
    {
        return isset($this->sections[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function render(): Response
    {
        if ($this->renderer->supports($this)) {
            $this->renderer->render($this);
        }

        $response = $this->engine->renderResponse(
            $this->template,
            [
                'item'      => $this->item,
                'workflow'  => $this->workflow,
                'sections'  => new Sections($this->sections, $this->sectionTemplates),
            ]
        );

        $response->headers->set('Content-Type', $this->getContentType());

        return $response;
    }
}

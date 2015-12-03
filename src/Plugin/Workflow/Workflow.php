<?php

/**
 * @file
 * Contains \Drupal\commerce_workflow\Plugin\Workflow\Workflow.
 */

namespace Drupal\commerce_workflow\Plugin\Workflow;

use Drupal\Core\Plugin\PluginBase;

/**
 * Defines the class for workflows.
 */
class Workflow extends PluginBase implements WorkflowInterface {

  /**
   * The initialized states.
   *
   * @var \Drupal\commerce_workflow\Plugin\Workflow\WorkflowState[]
   */
  protected $states = [];

  /**
   * The initialized transitions.
   *
   * @var \Drupal\commerce_workflow\Plugin\Workflow\WorkflowTransition[]
   */
  protected $transitions = [];

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    // Populate value objects for states and transitions.
    foreach ($plugin_definition['states'] as $id => $state_definition) {
      $this->states[$id] = new WorkflowState($id, $state_definition['label']);
    }
    foreach ($plugin_definition['transitions'] as $id => $transition_definition) {
      $label = $transition_definition['label'];
      $from_state = $this->states[$transition_definition['from']];
      $to_state = $this->states[$transition_definition['to']];
      $this->transitions[$id] = new WorkflowTransition($id, $label, $from_state, $to_state);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->t($this->pluginDefinition['label'], [], ['context' => 'workflow']);
  }

  /**
   * {@inheritdoc}
   */
  public function getGroup() {
    return $this->pluginDefinition['group'];
  }

  /**
   * {@inheritdoc}
   */
  public function getStates() {
    return $this->states;
  }

  /**
   * {@inheritdoc}
   */
  public function getState($id) {
    return isset($this->states[$id]) ? $this->states[$id] : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getTransitions() {
    return $this->transitions;
  }

  /**
   * {@inheritdoc}
   */
  public function getTransition($id) {
    return isset($this->transitions[$id]) ? $this->transitions[$id] : NULL;
  }

}

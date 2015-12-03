<?php

/**
 * @file
 * Contains \Drupal\commerce_workflow\Plugin\Workflow\WorkflowTransition.
 */

namespace Drupal\commerce_workflow\Plugin\Workflow;

/**
 * Defines the class for workflow transitions.
 */
class WorkflowTransition implements TransitionInterface {

  /**
   * The transition id.
   *
   * @var string
   */
  protected $id;

  /**
   * The transition label.
   *
   * @var string
   */
  protected $label;

  /**
   * The "from" state.
   *
   * @var \Drupal\commerce_workflow\Plugin\Workflow\WorkflowState
   */
  protected $fromState;

  /**
   * The "to" state.
   *
   * @var \Drupal\commerce_workflow\Plugin\Workflow\WorkflowState
   */
  protected $toState;

  /**
   * Constructs a new WorkflowTransition object.
   *
   * @param string $id
   *   The transition id.
   * @param string $label
   *   The transition label.
   * @param \Drupal\commerce_workflow\Plugin\Workflow\WorkflowState $from_state
   *   The "from" state.
   * @param \Drupal\commerce_workflow\Plugin\Workflow\WorkflowState $to_state
   *   The "to" state.
   */
  public function __construct($id, $label, WorkflowState $from_state, WorkflowState $to_state) {
    $this->id = $id;
    $this->label = $label;
    $this->fromState = $from_state;
    $this->toState = $to_state;
  }

  /**
   * Gets the id.
   *
   * @return string
   *   The id.
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Gets the translated label.
   *
   * @return string
   *   The translated label.
   */
  public function getLabel() {
    return $this->t($this->label, [], ['context' => 'workflow transition']);
  }

  /**
   * Gets the "from" state.
   *
   * @return \Drupal\commerce_workflow\Plugin\Workflow\WorkflowState
   *   The "from" state.
   */
  public function getFromState() {
    return $this->fromState;
  }

  /**
   * Gets the "to" state.
   *
   * @return \Drupal\commerce_workflow\Plugin\Workflow\WorkflowState
   *   The "to" state.
   */
  public function getToState() {
    return $this->toState;
  }

}

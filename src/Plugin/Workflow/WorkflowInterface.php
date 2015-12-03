<?php

/**
 * @file
 * Contains \Drupal\commerce_workflow\Plugin\WorkflowGroup\WorkflowInterface.
 */

namespace Drupal\commerce_workflow\Plugin\Workflow;

/**
 * Defines the interface for workflows.
 */
interface WorkflowInterface {

  /**
   * Gets the translated label.
   *
   * @return string
   *   The translated label.
   */
  public function getLabel();

  /**
   * Gets the workflow group.
   *
   * @return string
   *   The workflow group.
   */
  public function getGroup();

  /**
   * Gets the workflow states.
   *
   * @return \Drupal\commerce_workflow\Plugin\Workflow\WorkflowState[]
   *   The states.
   */
  public function getStates();

  /**
   * Gets a workflow state with the given id.
   *
   * @return \Drupal\commerce_workflow\Plugin\Workflow\WorkflowState|null
   *   The requested state, or NULL if not found.
   */
  public function getState($id);

  /**
   * Gets the workflow transitions.
   *
   * @return \Drupal\commerce_workflow\Plugin\Workflow\WorkflowTransition[]
   *   The transitions.
   */
  public function getTransitions();

  /**
   * Gets a workflow transition with the given id.
   *
   * @return \Drupal\commerce_workflow\Plugin\Workflow\WorkflowTransition|null
   *   The requested transition, or NULL if not found.
   */
  public function getTransition($id);

}

<?php

/**
 * @file
 * Contains \Drupal\commerce_workflow\Plugin\WorkflowGroup\WorkflowGroupInterface.
 */

namespace Drupal\commerce_workflow\Plugin\WorkflowGroup;

/**
 * Defines the interface for workflow groups.
 */
interface WorkflowGroupInterface {

  /**
   * Gets the translated label.
   *
   * @return string
   *   The translated label.
   */
  public function getLabel();

  /**
   * Gets the entity type id.
   *
   * For example, "node" if all workflows in the group are used on content.
   *
   * @return string
   *   The entity type id.
   */
  public function getEntityTypeId();

  /**
   * Gets the workflow class.
   *
   * By default all workflows use the same class. A group can choose to
   * override the class for its workflows, to satisfy advanced use cases.
   *
   * @return string
   *   The workflow class.
   */
  public function getWorkflowClass();

}

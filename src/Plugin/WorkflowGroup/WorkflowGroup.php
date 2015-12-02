<?php

/**
 * @file
 * Contains \Drupal\commerce_workflow\Plugin\WorkflowGroup\WorkflowGroup.
 */

namespace Drupal\commerce_workflow\Plugin\WorkflowGroup;

use Drupal\Core\Plugin\PluginBase;

/**
 * Defines the class for workflow groups.
 */
class WorkflowGroup extends PluginBase implements WorkflowGroupInterface {

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->t($this->pluginDefinition['label'], [], ['context' => 'workflow group']);
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityTypeId() {
    return $this->pluginDefinition['entity_type'];
  }

  /**
   * {@inheritdoc}
   */
  public function getWorkflowClass() {
    return $this->pluginDefinition['workflow_class'];
  }

}

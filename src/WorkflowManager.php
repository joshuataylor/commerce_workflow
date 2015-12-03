<?php

/**
 * @file
 * Contains \Drupal\commerce_workflow\WorkflowManager.
 */

namespace Drupal\commerce_workflow;

use Drupal\Component\Plugin\CategorizingPluginManagerInterface;
use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Plugin\Discovery\ContainerDerivativeDiscoveryDecorator;
use Drupal\Core\Plugin\Discovery\YamlDiscovery;

/**
 * Manages discovery and instantiation of workflow plugins.
 *
 * @see \Drupal\commerce_workflow\Plugin\Workflow\WorkflowInterface
 * @see plugin_api
 */
class WorkflowManager extends DefaultPluginManager implements CategorizingPluginManagerInterface {

  /**
   * The workflow group manager.
   *
   * @var \Drupal\commerce_workflow\WorkflowGroupManager
   */
  protected $groupManager;

  /**
   * Default values for each workflow plugin.
   *
   * @var array
   */
  protected $defaults = [
    'id' => '',
    'label' => '',
    'group' => '',
    'states' => [],
    'transitions' => [],
  ];

  /**
   * Constructs a new WorkflowManager object.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   The cache backend.
   * @param \Drupal\commerce_workflow\WorkflowGroupManager $group_manager
   *   The workflow group manager.
   */
  public function __construct(ModuleHandlerInterface $module_handler, CacheBackendInterface $cache_backend, WorkflowGroupManager $group_manager) {
    $this->moduleHandler = $module_handler;
    $this->setCacheBackend($cache_backend, 'workflow', ['workflow']);
    $this->groupManager = $group_manager;
  }

  /**
   * {@inheritdoc}
   */
  protected function getDiscovery() {
    if (!isset($this->discovery)) {
      $this->discovery = new YamlDiscovery('workflows', $this->moduleHandler->getModuleDirectories());
      $this->discovery->addTranslatableProperty('label', 'label_context');
      $this->discovery = new ContainerDerivativeDiscoveryDecorator($this->discovery);
    }
    return $this->discovery;
  }

  /**
   * {@inheritdoc}
   */
  public function createInstance($plugin_id, array $configuration = array()) {
    $plugin_definition = $this->discovery->getDefinition($plugin_id);
    if (empty($plugin_definition['group'])) {
      throw new PluginException(sprintf('The workflow %s must define the group property.', $plugin_id));
    }
    $group = $this->groupManager->getDefinition($plugin_definition['group']);
    // getDefinitions() applies default values, but getDefinition() doesn't.
    $group += [
      'workflow_class' => 'Drupal\commerce_workflow\Plugin\Workflow\Workflow',
    ];
    $plugin_class = $group_definition['workflow_class'];

    if (is_subclass_of($plugin_class, 'Drupal\Core\Plugin\ContainerFactoryPluginInterface')) {
      return $plugin_class::create(\Drupal::getContainer(), $configuration, $plugin_id, $plugin_definition);
    }
    else {
      return new $plugin_class($configuration, $plugin_id, $plugin_definition);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function processDefinition(&$definition, $plugin_id) {
    parent::processDefinition($definition, $plugin_id);

    $definition['id'] = $plugin_id;
    foreach (['label', 'group', 'states', 'transitions'] as $required_property) {
      if (empty($definition[$required_property]) {
        throw new PluginException(sprintf('The workflow %s must define the %s property.', $plugin_id, $required_property));
      }
    }
    foreach ($definition['states'] as $state_id => $state_definition) {
      if (empty($state_definition['label'])) {
        throw new PluginException(sprintf('The workflow state %s must define the label property.', $state_id));
      }
    }
    foreach ($definition['transitions'] as $transition_id => $transition_definition) {
      foreach (['label', 'from', 'to'] as $required_property) {
        if (empty($transition_definition[$required_property])) {
          throw new PluginException(sprintf('The workflow transition %s must define the %s property.', $transition_id, $required_property));
        }
      }
      foreach (['from', 'to'] as $state_property) {
        $state_id = $transition_definition[$state_property];
        if (!isset($definition['states'][$state_id])) {
          throw new PluginException(sprintf('The workflow transition %s specified an invalid %s property.', $transition_id, $state_property));
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getCategories() {
    // Use workflow groups as categories.
    $categories = array_map(function ($definition) {
      return $definition['label'];
    }, $this->groupManager->getDefinitions());
    natcasesort($categories);

    return $categories;
  }

  /**
   * {@inheritdoc}
   */
  public function getSortedDefinitions(array $definitions = NULL) {
    // Sort the plugins first by group, then by label.
    $definitions = isset($definitions) ? $definitions : $this->getDefinitions();
    uasort($definitions, function ($a, $b) {
      if ($a['group'] != $b['group']) {
        return strnatcasecmp($a['group'], $b['group']);
      }
      return strnatcasecmp($a['label'], $b['label']);
    });

    return $definitions;
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupedDefinitions(array $definitions = NULL) {
    $definitions = $this->getSortedDefinitions($definitions);
    $categories = $this->getCategories();
    $grouped_definitions = [];
    foreach ($definitions as $id => $definition) {
      $group_id = $definition['group'];
      $category = isset($categories[$group_id]) ? $categories[$group_id] : '';
      $grouped_definitions[$category][$id] = $definition;
    }

    return $grouped_definitions;
  }

}

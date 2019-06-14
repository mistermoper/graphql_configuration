<?php

namespace Drupal\graphql_configuration\Plugin\Deriver\Fields;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Config\Entity\ConfigEntityTypeInterface;
use Drupal\Core\Config\TypedConfigManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\graphql\Utility\StringHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Generate the fields needed for graphl content entities.
 *
 * @package Drupal\graphql_configuration\Plugin\Deriver\Fields
 */
class ConfigEntityFieldDeriver extends DeriverBase implements ContainerDeriverInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  protected $typedConfigManager;

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $basePluginId) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('config.typed')
    );
  }

  /**
   * EntityTypeDeriver constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Instance of an entity type manager.
   * @param \Drupal\Core\Config\TypedConfigManagerInterface $typedConfigManager
   *   Instance of a config factory.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, TypedConfigManagerInterface $typedConfigManager) {
    $this->entityTypeManager = $entityTypeManager;
    $this->typedConfigManager = $typedConfigManager;
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($basePluginDefinition) {
    foreach ($this->entityTypeManager->getDefinitions() as $typeId => $type) {
      if (!($type instanceof ConfigEntityTypeInterface)) {
        continue;
      }

      $configEntityTypeDefinition = $this->typedConfigManager->getDefinition($type->getConfigPrefix() . '.*');

      if (!empty($configEntityTypeDefinition['mapping'])) {

        foreach ($configEntityTypeDefinition['mapping'] as $fieldName => $fieldSchema) {
          // Only filter by string for testing.
          if ($fieldSchema['type'] == 'string') {
            $this->derivatives[$typeId . ':' . $fieldName] = [
              'parents' => ['ConfigEntity' . StringHelper::camelCase($typeId), 'Entity'],
              'name' => $fieldName,
              'description' => $fieldSchema['label'],
              'field' => $fieldName,
              'schema_cache_tags' => $type->getListCacheTags(),
              'schema_cache_contexts' => $type->getListCacheContexts(),
              'type' => $fieldSchema['type'],
            ] + $basePluginDefinition;
          }
        }

      }
    }

    return parent::getDerivativeDefinitions($basePluginDefinition);
  }

}

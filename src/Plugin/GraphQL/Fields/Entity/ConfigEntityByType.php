<?php

namespace Drupal\graphql_configuration\Plugin\GraphQL\Fields\Entity;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Allow obtain a list of config entities filtering by type.
 *
 * @GraphQLField(
 *   id = "config_entity_by_type",
 *   name = "configEntitiesByType",
 *   type = "[Entity]",
 *   secure = true,
 *   arguments = {
 *     "type" = "String!"
 *   }
 * )
 */
class ConfigEntityByType extends FieldPluginBase implements ContainerFactoryPluginInterface {

  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
          $configuration,
          $plugin_id,
          $plugin_definition,
          $container->get('entity_type.manager')
      );
  }

  /**
   * ConfigEntityField initializer.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entityTypeManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    $config_entities = $this->entityTypeManager->getStorage($args['type'])->loadMultiple();
    foreach ($config_entities as $config_entity) {
      yield $config_entity;
    }
  }

}

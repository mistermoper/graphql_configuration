<?php

namespace Drupal\graphql_configuration\Plugin\GraphQL\Types\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Types\TypePluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Type for config entities.
 *
 * @GraphQLType(
 *   id = "config_entity",
 *   schema_cache_tags = {"config_entity_types"},
 *   interfaces = {"Entity"},
 *   deriver = "Drupal\graphql_configuration\Plugin\Deriver\Types\ConfigEntityDeriver"
 * )
 */
class ConfigEntity extends TypePluginBase {

  /**
   * {@inheritdoc}
   */
  public function applies($object, ResolveContext $context, ResolveInfo $info) {
    if (!$object instanceof ConfigEntityInterface) {
      return FALSE;
    }

    return TRUE;
  }

}

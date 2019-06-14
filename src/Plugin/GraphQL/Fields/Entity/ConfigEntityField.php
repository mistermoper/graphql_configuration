<?php

namespace Drupal\graphql_configuration\Plugin\GraphQL\Fields\Entity;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Obtains a field from a config entity.
 *
 * @GraphQLField(
 *   id = "config_entity_field",
 *   secure = true,
 *   weight = -2,
 *   deriver = "Drupal\graphql_configuration\Plugin\Deriver\Fields\ConfigEntityFieldDeriver"
 * )
 */
class ConfigEntityField extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    yield $value->get($info->fieldName);
  }

}

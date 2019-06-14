<?php

namespace Drupal\graphql_configuration\Plugin\GraphQL\Interfaces\Entity;

use Drupal\graphql\Plugin\GraphQL\Interfaces\InterfacePluginBase;

/**
 * @GraphQLInterface(
 *   id = "config_entity",
 *   name = "ConfigEntity",
 *   type = "entity",
 *   description = @Translation("Common entity interface containing generic entity properties.")
 * )
 */
class ConfigEntity extends InterfacePluginBase
{

}

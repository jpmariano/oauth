<?php

namespace Drupal\oauth_jwt\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'oauth_jwt' field type.
 *
 * @FieldType(
 *   id = "clienkeysecret_fieldtype",
 *   label = @Translation("Authorize Apps"),
 *   module = "oauth_jwt",
 *   description = @Translation("This field is use to list your authorize other apps and or websites"),
 *   default_widget = "clienkeysecret_fieldwidget",
 * )
 */
class ClienkeysecretFieldType extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => [
          'type' => 'text',
          'size' => 'medium',
          'not null' => FALSE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('value')->getValue();
    return $value === NULL || $value === '';
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Client Apps'));

    return $properties;
  }

}

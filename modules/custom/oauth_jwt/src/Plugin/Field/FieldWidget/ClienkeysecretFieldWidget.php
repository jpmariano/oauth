<?php

namespace Drupal\oauth_jwt\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'clienkeysecret_fieldwidget' widget.
 *
 * @FieldWidget(
 *   id = "clienkeysecret_fieldwidget",
 *   module = "oauth_jwt",
 *   label = @Translation("Add Client Key and Secret"),
 *   field_types = {
 *     "clienkeysecret_fieldtype"
 *   }
 * )
 */
class ClienkeysecretFieldWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $value = isset($items[$delta]->value) ? $items[$delta]->value : '';
    $element += [
      '#type' => 'hidden',
      '#default_value' => $value,
      '#attributes' => array('class' => array('client-secrectkey-field')),
      '#description' => t('Optional: Select one or more icon'),
      '#prefix' => '<div id="clientkeysercret-'.$delta.'" class="clientkeysercret" item="'.$delta.'">
      </div>',
      '#suffix' => '',
      '#attached' => array(
        'library' => array(
          'oauth_jwt/clientsecret_widget',
        ),
      )

    ];
    return ['value' => $element];
  }

}

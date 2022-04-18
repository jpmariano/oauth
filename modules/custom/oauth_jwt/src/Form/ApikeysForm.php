<?php
/**
 * @file
 * Contains \Drupal\oauth_jwt\Form\ApikeysForm.
 */

namespace Drupal\oauth_jwt\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

/**
 * Implements an example form.
 */
class ApikeysForm extends FormBase {

  /**
   * Form with 'add more' and 'remove' buttons.
   *
   * This example shows a button to "add more" - add another textfield, and
   * the corresponding "remove" button.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this
        ->t('List API Keys'),
    ];

    // Gather the number of names in the form already.
    $num_keys = $form_state
      ->get('num_keys');

    // We have to ensure that there is at least one name field.
    if ($num_keys === NULL) {
      $key_field = $form_state
        ->set('num_keys', 1);
      $num_keys = 1;
    }
    //var_dump($num_keys);
    $form['#tree'] = TRUE;
    $form['apikeys_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => $this
        ->t('API Keys'),
      '#prefix' => '<div id="apikeys-fieldset-wrapper">',
      '#suffix' => '</div>',
    ];
    $form['apikeys_fieldset']['item'] = [
      '#type' => 'fieldgroups',
      '#title' => $this
        ->t('Keys'),
      '#prefix' => '<div id="apikeys-group-wrapper">',
      '#suffix' => '</div>',
    ];
    $form['apikeys_fieldset']['actions'] = [
      '#type' => 'actions',
    ];
    for ($i = 0; $i < $num_keys; $i++) {

      $form['apikeys_fieldset']['item']['set'][$i] = [
        '#type' => 'fieldset',
        '#title' => $this
          ->t('Keys'),
        '#prefix' => '<div id="set-wrapper-'. $i .'" class="set-wrapper">',
        '#suffix' => '</div>',
      ];
      $form['apikeys_fieldset']['item']['set'][$i]['apikey'] = [
        '#type' => 'textfield',
        '#title' => $this
          ->t('API Key'),
        '#group' => 'item',
      ];
      $form['apikeys_fieldset']['item']['set'][$i]['apisecret'] = [
        '#type' => 'textfield',
        '#title' => $this
          ->t('API Secret'),
        '#group' => 'item',
      ];

      $form['apikeys_fieldset']['actions']['add_key']= [
        '#type' => 'submit',
        '#value' => $this
          ->t('Add one more'),
        '#submit' => [
          '::addOne',
        ],
        '#ajax' => [
          'callback' => '::addmoreCallback',
          'wrapper' => 'set-wrapper-'. $i.'',
        ],
      ];

      // If there is more than one name, add the remove button.
    if ($num_keys > 1) {
      $form['apikeys_fieldset']['actions']['remove_apikeys'] = [
        '#type' => 'submit',
        '#value' => $this
          ->t('Remove one'),
        '#submit' => [
          '::removeCallback',
        ],
        '#ajax' => [
          'callback' => '::addmoreCallback',
          'wrapper' => 'set-wrapper'. $i .'',
        ],
      ];
    }

    }


  //var_dump($num_keys);






    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this
        ->t('Submit'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'form_api_example_ajax_addmore';
  }

  /**
   * Callback for both ajax-enabled buttons.
   *
   * Selects and returns the fieldset with the names in it.
   */
  public function addmoreCallback(array &$form, FormStateInterface $form_state) {
    return $form['apikeys_fieldset']['item']['set'];
  }

  /**
   * Submit handler for the "add-one-more" button.
   *
   * Increments the max counter and causes a rebuild.
   */
  public function addOne(array &$form, FormStateInterface $form_state) {
    $key_field = $form_state
      ->get('num_keys');
    $add_button = $key_field + 1;
    $form_state
      ->set('num_keys', $add_button);
    // Since our buildForm() method relies on the value of 'num_keys' to
    // generate 'name' form elements, we have to tell the form to rebuild. If we
    // don't do this, the form builder will not call buildForm().
    $form_state
      ->setRebuild(TRUE);
  }

  /**
   * Submit handler for the "remove one" button.
   *
   * Decrements the max counter and causes a form rebuild.
   */
  public function removeCallback(array &$form, FormStateInterface $form_state) {
    $key_field = $form_state
      ->get('num_keys');
    if ($key_field > 1) {
      $remove_button = $key_field - 1;
      $form_state
        ->set('num_keys', $remove_button);
    }



    // Since our buildForm() method relies on the value of 'num_keys' to
    // generate 'name' form elements, we have to tell the form to rebuild. If we
    // don't do this, the form builder will not call buildForm().
    $form_state
      ->setRebuild(TRUE);
  }

  /**
   * Final submit handler.
   *
   * Reports what values were finally set.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state
      ->getValue([
      'apikeys_fieldgroup',
      'name',
    ]);
    $output = $this
      ->t('These people are coming to the picnic: @names', [
      '@names' => implode(', ', $values),
    ]);
    $this
      ->messenger()
      ->addMessage($output);
  }
}

<?php

namespace Drupal\timeago\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * Class TimeAgoSettingsForm.
 */
class TimeAgoSettingsForm extends FormBase
{


  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'time_ago_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
//    $form['info'] = [
//      '#markup' => '<p>' . t('Note that you can set Timeago as the default <a href="!datetime">date format</a>.',
//          ['!datetime' => $this->url('admin/config/regional/date-time')]) . ' ' .
//        t('This will allow you to use it for all dates on the site, overriding the settings below.') . '</p>',
//    ];

    $form['timeago_node'] = [
      '#type' => 'checkbox',
      '#title' => t('Use timeago for node creation dates'),
      '#default_value' => \Drupal::config('timeago_node')->getName(),
    ];

    $form['timeago_comment'] = [
      '#type' => 'checkbox',
      '#title' => t('Use timeago for comment creation/changed dates'),
      '#default_value' => \Drupal::config('timeago_comment')->getName(),
    ];

    $form['timeago_elem'] = [
      '#type' => 'radios',
      '#title' => t('Time element'),
      '#default_value' => \Drupal::config('timeago_elem')->getName(),
      '#options' => [
        'span' => t('span'),
        'abbr' => t('abbr'),
        'time' => t('time (HTML5 only)'),
      ],
    ];

    $form['settings'] = [
      '#type' => 'fieldset',
      '#title' => t('Override Timeago script settings'),
      '#collapsible' => FALSE,
    ];

    $form['settings']['timeago_js_refresh_millis'] = [
      '#type' => 'textfield',
      '#title' => t('Refresh Timeago dates after'),
      '#description' => t('Timeago can update its dates without a page refresh at this interval. Leave blank or set to zero to never refresh Timeago dates.'),
      '#default_value' => \Drupal::config('timeago_js_refresh_millis', 60000)->getName(),
      '#field_suffix' => ' ' . t('milliseconds'),
      '#element_validate' => ['timeago_validate_empty_or_nonnegative_integer'],
    ];

    $form['settings']['timeago_js_allow_future'] = [
      '#type' => 'checkbox',
      '#title' => t('Allow future dates'),
      '#default_value' => \Drupal::config('timeago_js_allow_future', 1)->getName(),
    ];

    $form['settings']['timeago_js_locale_title'] = [
      '#type' => 'checkbox',
      '#title' => t('Set the "title" attribute of Timeago dates to a locale-sensitive date'),
      '#default_value' => \Drupal::config('timeago_js_locale_title', 0)->getName(),
      '#description' => t('If this is disabled (the default) then the "title" attribute defaults to the original date that the Timeago script is replacing.'),
    ];

    $form['settings']['timeago_js_cutoff'] = [
      '#type' => 'textfield',
      '#title' => t('Do not use Timeago dates after'),
      '#field_suffix' => ' ' . t('milliseconds'),
      '#description' => t('Leave blank or set to zero to always use Timeago dates.'),
      '#default_value' => \Drupal::config('timeago_js_cutoff', '')->getName(),
      '#element_validate' => ['timeago_validate_empty_or_nonnegative_integer'],
    ];

    $form['settings']['strings'] = [
      '#type' => 'fieldset',
      '#title' => t('Strings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];

    $form['#attached']['library'][] = 'timeago/timeago';
    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => t('Save Configuration'),
    ];

//    if (timeago_library_detect_languages()) {
//      $form['settings']['strings']['warning'] = [
//        '#markup' => '<div class="messages warning">' . t('JavaScript translation files have been detected in the Timeago library. The following settings will not be used unless you remove those files.') . '</div>',
//      ];
//    }
//
//    // Load in and setup form items for our JavaScript variables.
//    $settings_vars = timeago_get_settings_variables();
//
//    foreach ($settings_vars as $js_var => $variable) {
//      $form['settings']['strings'][$variable['variable_name']] = [
//        '#type' => 'textfield',
//        '#title' => $variable['title'],
//        '#required' => $variable['required'],
//        '#default_value' => \Drupal::config($variable['variable_name'], $variable['default'])->getName(),
//      ];
//    }
//
//
//    $form['settings']['strings']['timeago_js_strings_word_separator'] = [
//      '#type' => 'textfield',
//      '#title' => t('Word separator'),
//      '#default_value' => \Drupal::config('timeago_js_strings_word_separator', ' ')->getName(),
//      '#description' => t('By default this is set to " " (a space).'),
//    ];

    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    // Display result.

    $node = Node::load('68');

    $timestamp = $node->getCreatedTime();


    $node = Node::load('68');

    $timestamp = $node->getCreatedTime();


    function get_time_ago($time)
    {
      $time_difference = time() - $time;

      if ($time_difference < 1) {
        return 'less than 1 second ago';
      }
      $condition = array(12 * 30 * 24 * 60 * 60 => 'year',
        30 * 24 * 60 * 60 => 'month',
        24 * 60 * 60 => 'day',
        60 * 60 => 'hour',
        60 => 'minute',
        1 => 'second'
      );

      foreach ($condition as $secs => $str) {
        $d = $time_difference / $secs;

        if ($d >= 1) {
          $t = round($d);
          return $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ago';
        }
      }
    }

    $node->setCreatedTime($timestamp - 1);


//    print_r(get_time_ago($timestamp));
//    die();



  }
}

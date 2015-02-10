<?php
/*
 * @file
 */

function onepage_form_system_theme_settings_alter(&$form, $form_state) {
  $form['light']['settings']['theme_settings']['show_fixed_menu'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show Fixed Menu'),
    '#default_value' => theme_get_setting('show_fixed_menu'),
  );
  $form['light']['settings']['theme_settings']['show_popup'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show Popup'),
    '#default_value' => theme_get_setting('show_popup'),
  );
}
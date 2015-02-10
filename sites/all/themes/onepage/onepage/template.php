<?php
/*
 * @files
 */

function onepage_preprocess_html(&$vars) {
  $node_id = drupal_lookup_path('source', '404-page');
  if (!empty($node_id)) {
    $parts = explode("/", $node_id);
    $n_id = false;
    if (count($parts) > 1) {
      $n_id = $parts[1];
    }
    if (in_array("html__node__$n_id", $vars['theme_hook_suggestions'])) {
      $vars['theme_hook_suggestions'][] = 'html__404';
    }
  }
  if (count($vars['theme_hook_suggestions']) == 1) {
    if (isset($vars['page']['content']['system_main']['main']['#markup']) && trim($vars['page']['content']['system_main']['main']['#markup']) == t('The requested page "@path" could not be found.', array('@path' => request_uri()))) {
      $vars['theme_hook_suggestions'][] = 'html__404';
    }
  }
  drupal_add_js(array('show_popup' => theme_get_setting('show_popup')), 'setting');
  if (isset($_GET['show_iframe']) && $_GET['show_iframe'] == 1) {
    $vars['theme_hook_suggestions'][] = 'html__popup_iframe';
  }
}

function onepage_preprocess_page(&$vars) {
  if (isset($_GET['show_iframe']) && $_GET['show_iframe'] == 1) {
    $vars['theme_hook_suggestions'][] = 'page__popup_iframe';
  }
  if (theme_get_setting('show_fixed_menu')) {
    $vars['show_fixed_menu'] = TRUE;
  }
  else {
    $vars['show_fixed_menu'] = FALSE;
  }

  if ($vars['is_front']) {
    $vars['logo'] = str_replace('logo.png', 'front_logo.png', $vars['logo']);
  }

  if (isset($vars['node'])) {
    if ($vars['node']->type != 'page') {
      $result = db_select('node_type', NULL, array('fetch' => PDO::FETCH_ASSOC))
        ->fields('node_type', array('name'))
        ->condition('type', $vars['node']->type)
        ->execute();
      foreach ($result as $item) {
        $vars['title'] = $item['name'];
      }
    }
  }
}

function onepage_preprocess_node(&$vars) {
  $vars['page'] = ($vars['type'] == 'page') ? TRUE : FALSE;
  $node = $vars['node'];
  if (variable_get('node_submitted_' . $node->type, TRUE)) {
    $vars['submitted'] = t('Submitted by !username on !datetime', array('!username' => $vars['name'], '!datetime' => date('F d, Y', $vars['created'])));
  }
}

function garnet_get_predefined_param($param, $pre_array = array(), $suf_array = array()) {
  global $theme_key;
  $theme_data = list_themes();
  $result = isset($theme_data[$theme_key]->info[$param]) ? $theme_data[$theme_key]->info[$param] : array();
  return $pre_array + $result + $suf_array;
}

function garnet_preprocess_node(&$vars) {
  $skins = garnet_get_predefined_param('skins', array('default' => t("Default skin")));
  foreach ($skins as $key => $val) {
    if ($vars['node_url'] == base_path() . 'skins/' . $key && (!isset($_COOKIE['light_skin']) || $_COOKIE['light_skin'] != $key)) {
      setcookie('light_skin', $key, time() + 100000, base_path());
      header('Location: ' . $vars['node_url']);
    }
  }
  $node = $vars['node'];
  if (variable_get('node_submitted_' . $node->type, TRUE)) {
    $vars['submitted'] = t('By !username on !datetime', array('!username' => $vars['name'], '!datetime' => date('F d, Y', $vars['created'])));
  }
}

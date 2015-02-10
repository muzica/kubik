<?php
function light_get_predefined_param($param, $pre_array = array(), $suf_array = array()) {
  global $theme_key;
  $theme_data = list_themes();
  $result = isset($theme_data[$theme_key]->info[$param]) ? $theme_data[$theme_key]->info[$param] : array();
  return $pre_array + $result + $suf_array;
}

function light_special_regions() {
  $special_regions = array('sidebar' => array(), 'panel' => array());
  global $theme_key;
  $theme_data = list_themes();
  $regions = $theme_data[$theme_key]->info['regions'];
  foreach ($regions as $key => $value) {
  	foreach ($special_regions as $type => $info) {
      if (strpos($key, $type . "_") === 0) {
        $special_regions[$type][] = $key;
      }
  	}
  }
  return $special_regions;
}

function light_panel_regions() {
  $special_regions = light_special_regions();
  $panels = $special_regions['panel'];
  $panel_regions = array();
  foreach($panels as $region) {
  	$parts = explode("_", $region);
  	$panel = $parts[0] . "_" . $parts[1];
  	if(!isset($panel_regions[$panel])) {
  	  $panel_regions[$panel] = array();
  	}
  	$panel_regions[$panel][] = $region;
  }
  return $panel_regions;
}

function light_regions_width($page) {
  $special_regions = light_special_regions();
  $regions_width = array();
  $main_region_width = 12;
  foreach($special_regions['sidebar'] as $region) {
    if ($markup = render($page[$region])) {
      $regions_width[$region] = theme_get_setting($region . "_width");
      $main_region_width -= $regions_width[$region];
    }
    else {
      $regions_width[$region] = 0;
    }
  }
  $regions_width['content'] = $main_region_width;
  foreach($special_regions['panel'] as $region) {
  	$regions_width[$region] = theme_get_setting($region . "_width");
  }
  return $regions_width;
}

function light_add_conditional_styles(&$css) {
  $themes = list_themes();
  $theme_keys = light_get_theme_keys();

  foreach ($theme_keys as $key) {
    $theme_path = drupal_get_path('theme', $key) . '/';
    if (isset($themes[$key]->info['stylesheets-conditional'])) {
      foreach (array_keys($themes[$key]->info['stylesheets-conditional']) as $condition) {
        foreach (array_keys($themes[$key]->info['stylesheets-conditional'][$condition]) as $media) {
          foreach ($themes[$key]->info['stylesheets-conditional'][$condition][$media] as $stylesheet) {
            $parts = explode("/", $stylesheet);
            $css_filename = $parts[count($parts) - 1];
            // Add each conditional stylesheet.
            $css = drupal_add_css(
              $theme_path . $stylesheet,
              array(
                'group' => CSS_THEME,
                'browsers' => array(
                  'IE' => $condition,
                  '!IE' => FALSE,
                ),
                'every_page' => TRUE,
                'basename' => $key . '-' . $css_filename,
              )
            );
          }
        }
      }
    }
  }
}

function light_get_theme_keys() {
  global $theme_key;
  $themes = list_themes();
  $theme_object = $themes[$theme_key];
  $theme_keys = array();
  if (isset($theme_object->base_themes)) {
    $theme_keys = array_keys($theme_object->base_themes);
    $theme_keys[] = $theme_key;
  }
  elseif (isset($theme_object->base_theme)) {
    $theme_keys = array($theme_object->base_theme, $theme_key);
  }
  else {
    $theme_keys = array($theme_key);
  }
  return $theme_keys;
}

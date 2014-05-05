<?php

/**
 * Add body classes if certain regions have content.
 */
function myweb_preprocess_html(&$variables) {
  if (!empty($variables['page']['featured'])) {
    $variables['classes_array'][] = 'featured';
  }

  if (!empty($variables['page']['triptych_first'])
    || !empty($variables['page']['triptych_middle'])
    || !empty($variables['page']['triptych_last'])) {
    $variables['classes_array'][] = 'triptych';
  }

  if (!empty($variables['page']['footer_firstcolumn'])
    || !empty($variables['page']['footer_secondcolumn'])
    || !empty($variables['page']['footer_thirdcolumn'])
    || !empty($variables['page']['footer_fourthcolumn'])) {
    $variables['classes_array'][] = 'footer-columns';
  }

  // Add conditional stylesheets for IE
  drupal_add_css(path_to_theme() . '/css/ie.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 7', '!IE' => FALSE), 'preprocess' => FALSE));
  drupal_add_css(path_to_theme() . '/css/ie6.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'IE 6', '!IE' => FALSE), 'preprocess' => FALSE));
}

/**
 * Override or insert variables into the page template for HTML output.
 */
function myweb_process_html(&$variables) {
  // Hook into color.module.
  if (module_exists('color')) {
    _color_html_alter($variables);
  }
}

/**
 * Override or insert variables into the page template.
 */
function myweb_process_page(&$variables) {
  // Hook into color.module.
  if (module_exists('color')) {
    _color_page_alter($variables);
  }
  // Always print the site name and slogan, but if they are toggled off, we'll
  // just hide them visually.
  $variables['hide_site_name']   = theme_get_setting('toggle_name') ? FALSE : TRUE;
  $variables['hide_site_slogan'] = theme_get_setting('toggle_slogan') ? FALSE : TRUE;
  if ($variables['hide_site_name']) {
    // If toggle_name is FALSE, the site_name will be empty, so we rebuild it.
    $variables['site_name'] = filter_xss_admin(variable_get('site_name', 'Drupal'));
  }
  if ($variables['hide_site_slogan']) {
    // If toggle_site_slogan is FALSE, the site_slogan will be empty, so we rebuild it.
    $variables['site_slogan'] = filter_xss_admin(variable_get('site_slogan', ''));
  }
  // Since the title and the shortcut link are both block level elements,
  // positioning them next to each other is much simpler with a wrapper div.
  if (!empty($variables['title_suffix']['add_or_remove_shortcut']) && $variables['title']) {
    // Add a wrapper div using the title_prefix and title_suffix render elements.
    $variables['title_prefix']['shortcut_wrapper'] = array(
      '#markup' => '<div class="shortcut-wrapper clearfix">',
      '#weight' => 100,
    );
    $variables['title_suffix']['shortcut_wrapper'] = array(
      '#markup' => '</div>',
      '#weight' => -99,
    );
    // Make sure the shortcut link is the first item in title_suffix.
    $variables['title_suffix']['add_or_remove_shortcut']['#weight'] = -100;
  }
}

/**
 * Implements hook_preprocess_maintenance_page().
 */
function myweb_preprocess_maintenance_page(&$variables) {
  // By default, site_name is set to Drupal if no db connection is available
  // or during site installation. Setting site_name to an empty string makes
  // the site and update pages look cleaner.
  // @see template_preprocess_maintenance_page
  if (!$variables['db_is_active']) {
    $variables['site_name'] = '';
  }
  drupal_add_css(drupal_get_path('theme', 'myweb') . '/css/maintenance-page.css');
}

/**
 * Override or insert variables into the maintenance page template.
 */
function myweb_process_maintenance_page(&$variables) {
  // Always print the site name and slogan, but if they are toggled off, we'll
  // just hide them visually.
  $variables['hide_site_name']   = theme_get_setting('toggle_name') ? FALSE : TRUE;
  $variables['hide_site_slogan'] = theme_get_setting('toggle_slogan') ? FALSE : TRUE;
  if ($variables['hide_site_name']) {
    // If toggle_name is FALSE, the site_name will be empty, so we rebuild it.
    $variables['site_name'] = filter_xss_admin(variable_get('site_name', 'Drupal'));
  }
  if ($variables['hide_site_slogan']) {
    // If toggle_site_slogan is FALSE, the site_slogan will be empty, so we rebuild it.
    $variables['site_slogan'] = filter_xss_admin(variable_get('site_slogan', ''));
  }
}

/**
 * Override or insert variables into the node template.
 */
function myweb_preprocess_node(&$variables) {
  if ($variables['view_mode'] == 'full' && node_is_page($variables['node'])) {
    $variables['classes_array'][] = 'node-full';
  }
}

/**
 * Override or insert variables into the block template.
 */
function myweb_preprocess_block(&$variables) {
  // In the header region visually hide block titles.
  if ($variables['block']->region == 'header') {
    $variables['title_attributes_array']['class'][] = 'element-invisible';
  }
  if ($variables['block']->subject == 'Send Message'){
    $variables['block']->subject = 'Send <span>Message</span>';
  }
  if ($variables['block']->subject == 'Our Location'){
    $variables['block']->subject = 'Our <span>Location</span>';
  }
 // print_r($variables);
}

/**
 * Implements theme_menu_tree().
 */
function myweb_menu_tree($variables) {
  return '<ul class="menu clearfix">' . $variables['tree'] . '</ul>';
}

/**
 * Implements theme_field__field_type().
 */
function myweb_field__taxonomy_term_reference($variables) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$variables['label_hidden']) {
    $output .= '<h3 class="field-label">' . $variables['label'] . ': </h3>';
  }

  // Render the items.
  $output .= ($variables['element']['#label_display'] == 'inline') ? '<ul class="links inline">' : '<ul class="links">';
  foreach ($variables['items'] as $delta => $item) {
    $output .= '<li class="taxonomy-term-reference-' . $delta . '"' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</li>';
  }
  $output .= '</ul>';

  // Render the top-level DIV.
  $output = '<div class="' . $variables['classes'] . (!in_array('clearfix', $variables['classes_array']) ? ' clearfix' : '') . '"' . $variables['attributes'] .'>' . $output . '</div>';

  return $output;
}



function myweb_getdirections_direction_form($variables) {
  $form = $variables['form'];
  // if you want to do fancy things with the form, do it here ;-)
  $getdirections_defaults = getdirections_defaults();
  $getdirections_misc = getdirections_misc_defaults();
  if (isset($form['mto'])) {
    $form['mto']['#prefix'] = '<div class="container-inline getdirections_display">';
    $form['mto']['#suffix'] = '</div>';
  }
  if (isset($form['mfrom'])) {
    $form['mfrom']['#prefix'] = '<div class="container-inline getdirections_display">';
    $form['mfrom']['#suffix'] = '</div>';
  }
  if (isset($form['travelmode'])) {
    $form['travelmode']['#prefix'] = '<div class="container-inline getdirections_display">';
    $form['travelmode']['#suffix'] = '</div>';
  }
  if (isset($form['travelextras'])) {
    $form['travelextras']['#prefix'] = '<div class="container-inline getdirections_display">';
    $form['travelextras']['#suffix'] = '</div>';
  }
  if (! $getdirections_defaults['advanced_autocomplete']) {
    if ($getdirections_misc['geolocation_enable'] && getdirections_is_mobile()) {
      if ($getdirections_misc['geolocation_option'] == 1) {
        // html5 geolocation
        $geolocation_button = '<input type="button" value="' . t('Find Location') . '" title="' . t('Get the latitude and longitude for your current position from the browser') . '" id="getdirections_geolocation_button_from" class="form-submit" />';
        $geolocation_button .= '<span id="getdirections_geolocation_status_from" ></span>';
        $form['from']['#field_suffix'] = '&nbsp;&nbsp;&nbsp;' . $geolocation_button;
      }
      elseif ($getdirections_misc['geolocation_option'] == 2 && module_exists('smart_ip')) {
        // smart ip
        $geolocation_button = '<input type="button" value="' . t('Locate by Smart IP') . '" title="' . t('Get the latitude and longitude for your current position from Smart IP') . '" id="getdirections_geolocation_button_from" class="form-submit" />';
        $geolocation_button .= '<span id="getdirections_geolocation_status_from" ></span>';
        $form['from']['#field_suffix'] = '&nbsp;&nbsp;&nbsp;' . $geolocation_button;
      }
      elseif ($getdirections_misc['geolocation_option'] == 3 && module_exists('ip_geoloc')) {
        // ip_geoloc
        $geolocation_button = '<input type="button" value="' . t('Locate by IP Geolocation') . '" title="' . t('Get the latitude and longitude for your current position from IP Geolocation') . '" id="getdirections_geolocation_button_from" class="form-submit" />';
        $geolocation_button .= '<span id="getdirections_geolocation_status_from" ></span>';
        $form['from']['#field_suffix'] = '&nbsp;&nbsp;&nbsp;' . $geolocation_button;
      }
    }
  }
  if (getdirections_is_advanced()) {
    $desc = t('Fill in the form below.<br />You can also click on the map and move the marker.');
    if (isset($form['country_from'])) {
      $desc = t('Select a country first, then type in a town.<br />You can also click on the map and move the marker.');
      $form['country_from']['#prefix'] = '<div id="getdirections_start"><div class="container-inline getdirections_display">';
      $form['country_from']['#suffix'] = '</div>';
    }
    if (isset($form['from'])
      && $form['from']['#type'] == 'textfield'
      && (module_exists('location') || module_exists('getlocations_fields'))
      && ! $getdirections_defaults['advanced_autocomplete']
      && isset($form['country_from'])
    ) {
      $form['from']['#suffix'] = '</div>';
    }
    if (isset($form['country_to'])) {
      $form['country_to']['#prefix'] = '<div id="getdirections_end"><div class="container-inline getdirections_display">';
      $form['country_to']['#suffix'] = '</div>';
    }
    if (isset($form['to'])
      && $form['to']['#type'] == 'textfield'
      && (module_exists('location') || module_exists('getlocations_fields'))
      && ! $getdirections_defaults['advanced_autocomplete']
      && isset($form['country_to'])
    ) {
      $form['to']['#suffix'] = '</div>';
    }
    if ($getdirections_defaults['advanced_autocomplete'] && $getdirections_defaults['waypoints'] > 0 && $getdirections_defaults['advanced_autocomplete_via'] && ! $getdirections_defaults['advanced_alternate'] ) {
      for ($ct = 1; $ct <= $getdirections_defaults['waypoints']; $ct++) {
      if ($ct == 1) {
          $form['via_autocomplete_' . $ct]['#prefix'] = '<div id="autocomplete_via_wrapper"><div class="container-inline getdirections_display">';
          $form['via_autocomplete_' . $ct]['#suffix'] = '</div>';
        }
        elseif ($ct == $getdirections_defaults['waypoints']) {
          $form['via_autocomplete_' . $ct]['#prefix'] = '<div class="container-inline getdirections_display">';
          $form['via_autocomplete_' . $ct]['#suffix'] = '</div></div>';
        }
        else {
          $form['via_autocomplete_' . $ct]['#prefix'] = '<div class="container-inline getdirections_display">';
          $form['via_autocomplete_' . $ct]['#suffix'] = '</div>';
        }
      }
    }
  }
  else {
    if (isset($form['country_from'])) {
      $form['country_from']['#prefix'] = '<div class="container-inline getdirections_display">';
      $form['country_from']['#suffix'] = '</div>';
    }
    if (isset($form['country_to'])) {
      $form['country_to']['#prefix'] = '<div class="container-inline getdirections_display">';
      $form['country_to']['#suffix'] = '</div>';
    }
    $desc = t('<h2>Get <span>Directions</span></h2>');
  }

  if (isset($form['trafficinfo'])) {
    $form['trafficinfo']['#prefix'] = '<div id="getdirections_trafficinfo">';
    $form['trafficinfo']['#suffix'] = '</div>';
  }
  if (isset($form['bicycleinfo'])) {
    $form['bicycleinfo']['#prefix'] = '<div id="getdirections_bicycleinfo">';
    $form['bicycleinfo']['#suffix'] = '</div>';
  }
  if (isset($form['transitinfo'])) {
    $form['transitinfo']['#prefix'] = '<div id="getdirections_transitinfo">';
    $form['transitinfo']['#suffix'] = '</div>';
  }

  if (isset($form['panoramio'])) {
    $form['panoramio']['#prefix'] = '<div id="getdirections_panoramio">';
    $form['panoramio']['#suffix'] = '</div>';
  }
  if (isset($form['switchfromto'])) {
    $form['switchfromto']['#prefix'] = '<div id="getdirections_switchfromto">';
    $form['switchfromto']['#suffix'] = '</div>';
  }
  if (isset($form['next'])) {
    $form['next']['#prefix'] = '<div id="getdirections_nextbtn">';
    $form['next']['#suffix'] = '</div>';
  }

  if (isset($form['submit'])) {
    $form['submit']['#prefix'] = '<div id="getdirections_btn">';
    $form['submit']['#suffix'] = '</div>';
  }
  $output = '<p class="description">' . $desc . '</p>';
  $output .= drupal_render_children($form);
  return $output;
}





/**
 * Implement theme_getdirections_show().
 */
function myweb_getdirections_show($variables) {
  $form = $variables['form'];
  $width = $variables['width'];
  $height = $variables['height'];
  $nid = $variables['nid'];
  $type =  $variables['type'];
  $output = '';
  $getdirections_returnlink_default = array(
    'page_enable' => 0,
    'page_link' => t('Return to page'),
    'user_enable' => 0,
    'user_link' => t('Return to page'),
    'term_enable' => 0,
    'term_link' => t('Return to page'),
    'comment_enable' => 0,
    'comment_link' => t('Return to page'),
  );
  $getdirections_returnlink = variable_get('getdirections_returnlink', $getdirections_returnlink_default);
  $returnlink = FALSE;
  if (isset($getdirections_returnlink['page_enable']) && $getdirections_returnlink['page_enable'] && $nid > 0 && $type == 'node') {
    $node = node_load($nid);
    if ($node) {
      $linktext = $getdirections_returnlink['page_link'];
      if ( preg_match("/%t/", $linktext)) {
        $linktext = preg_replace("/%t/", $node->title, $linktext);
      }
      $l = l($linktext, 'node/' . $node->nid);
      $returnlink = '<div class="getdirections_returnlink">' . $l . '</div>';
    }
  }
  elseif (isset($getdirections_returnlink['user_enable']) && $getdirections_returnlink['user_enable'] && $nid > 0 && $type == 'user') {
    // $nid is actually uid
    $account = user_load($nid);
    if ($account) {
      $linktext = $getdirections_returnlink['user_link'];
      if ( preg_match("/%n/", $linktext)) {
        $linktext = preg_replace("/%n/", $account->name, $linktext);
      }
      $l = l($linktext, 'user/' . $account->uid);
      $returnlink = '<div class="getdirections_returnlink">' . $l . '</div>';
    }
  }
  elseif (isset($getdirections_returnlink['page_enable']) && $getdirections_returnlink['page_enable'] && $nid > 0 && $type == 'location') {
    // $nid is actually lid
    $id = getdirections_get_nid_from_lid($nid);
    if ($id) {
      $node = node_load($id);
      $linktext = $getdirections_returnlink['page_link'];
      if ( preg_match("/%t/", $linktext)) {
        $linktext = preg_replace("/%t/", $node->title, $linktext);
      }
      $l = l($linktext, 'node/' . $node->nid);
      $returnlink = '<div class="getdirections_returnlink">' . $l . '</div>';
    }
  }
  elseif (isset($getdirections_returnlink['term_enable']) && $getdirections_returnlink['term_enable'] && $nid > 0 && $type == 'term') {
    // $nid is actually tid
    $term = taxonomy_term_load($nid);
    if ($term) {
      $linktext = $getdirections_returnlink['term_link'];
      if ( preg_match("/%n/", $linktext)) {
        $linktext = preg_replace("/%n/", $term->name, $linktext);
      }
      $l = l($linktext, 'taxonomy/term/' . $term->tid);
      $returnlink = '<div class="getdirections_returnlink">' . $l . '</div>';
    }
  }
  elseif (isset($getdirections_returnlink['comment_enable']) && $getdirections_returnlink['comment_enable'] && $nid > 0 && $type == 'comment') {
    // $nid is actually cid
    $comment = comment_load($nid);
    if ($comment) {
      $linktext = $getdirections_returnlink['comment_link'];
      if ( preg_match("/%n/", $linktext)) {
        $linktext = preg_replace("/%n/", $comment->subject, $linktext);
      }
      $l = l($linktext, 'comment/' . $comment->cid);
      $returnlink = '<div class="getdirections_returnlink">' . $l . '</div>';
    }
  }

  if ($returnlink) {
    $output .= $returnlink;
  }



  $getdirections_defaults = getdirections_defaults();
  $getdirections_misc = getdirections_misc_defaults();

  if ($getdirections_misc['show_distance']) {
    $output .= '<div id="getdirections_show_distance"></div>';
  }
  if ($getdirections_misc['show_duration']) {
    $output .= '<div id="getdirections_show_duration"></div>';
  }
  $help = '';
  if (getdirections_is_advanced()) {
    if ($getdirections_defaults['waypoints'] > 0 && ! $getdirections_defaults['advanced_alternate'] ) {
      $help = t('Drag <img src="http://labs.google.com/ridefinder/images/mm_20_!c.png"> to activate a waypoint', array('!c' => $getdirections_defaults['waypoint_color']));
      if ($getdirections_defaults['advanced_autocomplete'] && $getdirections_defaults['advanced_autocomplete_via'] ) {
        $help .= ' ' . t('or use the Autocomplete boxes');
      }
    }
    elseif ($getdirections_defaults['advanced_alternate']) {
      $help = t('You can drag the route to change it');
    }
  }
  $output .= '<div id="getdirections_help">' . $help . '</div>';
  $header = array();
  $rows1[] = array(
    array(
      'data' => '<div id="getdirections_map_canvas" style="width: ' . $width . '; height: ' . $height . '" ></div>',
      'valign' => 'top',
      'align' => 'center',
      'class' => 'getdirections-map',
    ),
    /*
    array(
      'data' => (getdirections_is_advanced() && $getdirections_defaults['advanced_alternate'] ? '<button id="getdirections-undo" onclick="Drupal.getdirectionsundo()">' . t('Undo') . '</button>' : '') . '<div id="getdirections_directions"></div>',
      'valign' => 'top' ,
      'align' => 'left',
      'class' => 'getdirections-list',
    ),*/
  );
  $output .= '<div class="getdirections">' . theme('table', array('header' => $header, 'rows' => $rows1)) . '</div>';
  $rows2[] = array(
    array(
      'data' => (getdirections_is_advanced() && $getdirections_defaults['advanced_alternate'] ? '<button id="getdirections-undo" onclick="Drupal.getdirectionsundo()">' . t('Undo') . '</button>' : '') . '<div id="getdirections_directions"></div>',
      'valign' => 'top' ,
      'align' => 'center',
      'class' => 'getdirections-list clearfix',
    ),
  );
  $output .= '<div class="getdirections">' . theme('table', array('header' => $header, 'rows' => $rows2)) . '</div>';
  $output .= $form;

  return $output;
}


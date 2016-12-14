<?php

/**
 * Enlever les CSS par défaut de Drupal qui sont moches ou inutiles.
 */
function base_f6_css_alter( &$css ) {
  unset( $css[drupal_get_path( 'module', 'system' ) . '/system.theme.css'] );
  unset( $css[drupal_get_path( 'module', 'system' ) . '/system.menus.css'] );
  unset( $css[drupal_get_path( 'module', 'system' ) . '/system.messages.css'] );
  unset( $css[drupal_get_path( 'module', 'date' ) . '/date_api/date.css'] );
  unset( $css[drupal_get_path( 'module', 'image' ) . '/image.css'] );
}
/**
 * Add body classes if certain regions have content.
 */
function base_f6_preprocess_html(&$variables) {
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
function base_f6_process_html(&$variables) {
  // Hook into color.module.
  if (module_exists('color')) {
    _color_html_alter($variables);
  }
}
/**
 * Override or insert variables into the page template.
 */
function base_f6_preprocess_page( &$variables, $hook ) {
  // Move secondary tabs into a separate variable.
  $variables['tabs2'] = array(
    '#theme' => 'menu_local_tasks',
    '#secondary' => $variables['tabs']['#secondary'],
  );

  if ( isset( $variables['main_menu'] ) ) {
    $variables['primary_nav'] = theme( 'links__system_main_menu', array(
        'links' => $variables['main_menu'],
        'attributes' => array(
          'class' => array(
            'links',
            'inline',
            'main-menu'
          ) ,
        ) ,
        'heading' => array(
          'text' => t( 'Main menu' ) ,
          'level' => 'h2',
          'class' => array(
            'element-invisible'
          ) ,
        )
      ) );
  }
  else {
    $variables['primary_nav'] = FALSE;
  }
  if ( isset( $variables['secondary_menu'] ) ) {
    $variables['secondary_nav'] = theme( 'links__system_secondary_menu', array(
        'links' => $variables['secondary_menu'],
        'attributes' => array(
          'class' => array(
            'links',
            'inline',
            'secondary-menu'
          ) ,
        ) ,
        'heading' => array(
          'text' => t( 'Secondary menu' ) ,
          'level' => 'h2',
          'class' => array(
            'element-invisible'
          ) ,
        )
      ) );
  }
  else {
    $variables['secondary_nav'] = FALSE;
  }
  // Prepare header.
  $site_fields = array();
  if ( !empty( $variables['site_name'] ) ) {
    $site_fields[] = $variables['site_name'];
  }
  if ( !empty( $variables['site_slogan'] ) ) {
    $site_fields[] = $variables['site_slogan'];
  }
  $variables['site_title'] = implode( ' ', $site_fields );
  if ( !empty( $site_fields ) ) {
    $site_fields[0] = '<span>' . $site_fields[0] . '</span>';
  }
  $variables['site_html'] = implode( ' ', $site_fields );
  // Set a variable for the site name title and logo alt attributes text.
  $slogan_text = $variables['site_slogan'];
  $site_name_text = $variables['site_name'];
  $variables['site_name_and_slogan'] = $site_name_text . ' ' . $slogan_text;
  if ( isset( $variables['node_title'] ) ) {
    $variables['title'] = $variables['node_title'];
  }
  // Adding classes wether #navigation is here or not
  if ( !empty( $vars['main_menu'] ) or !empty( $vars['sub_menu'] ) ) {
    $variables['classes_array'][] = 'with-navigation';
  }
  if ( !empty( $vars['secondary_menu'] ) ) {
    $variables['classes_array'][] = 'with-subnav';
  }
  // Do we have a node?
  if ( isset( $variables['node'] ) ) {
    // Ref suggestions cuz it's stupid long.
    $suggests = &$variables['theme_hook_suggestions'];
    // Get path arguments.
    $args = arg();
    // Remove first argument of "node".
    unset( $args[0] );
    // Set type.
    $type = "page__type_{$variables['node']->type}";
    // Bring it all together.
    $suggests = array_merge(
      $suggests,
      array( $type ),
      theme_get_suggestions( $args, $type )
    );
    // if the url is: 'http://domain.com/node/123/edit'
    // and node type is 'blog'..
    //
    // This will be the suggestions:
    //
    // - page__node
    // - page__node__%
    // - page__node__123
    // - page__node__edit
    // - page__type_blog
    // - page__type_blog__%
    // - page__type_blog__123
    // - page__type_blog__edit
    //
    // Which connects to these templates:
    //
    // - page--node.tpl.php
    // - page--node--%.tpl.php
    // - page--node--123.tpl.php
    // - page--node--edit.tpl.php
    // - page--type-blog.tpl.php          << this is what you want.
    // - page--type-blog--%.tpl.php
    // - page--type-blog--123.tpl.php
    // - page--type-blog--edit.tpl.php
    //
    // Latter items take precedence.
  }
  if ( isset( $variables['node']->type ) ) {
    $variables['theme_hook_suggestions'][] = 'page__' . $variables['node']->type;
  }
}
/**
 * Override or insert variables into the page template.
 */
function base_f6_process_page(&$variables) {
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
function base_f6_preprocess_maintenance_page(&$variables) {
  // By default, site_name is set to Drupal if no db connection is available
  // or during site installation. Setting site_name to an empty string makes
  // the site and update pages look cleaner.
  // @see template_preprocess_maintenance_page
  if (!$variables['db_is_active']) {
    $variables['site_name'] = '';
  }
  drupal_add_css(drupal_get_path('theme', 'bartik') . '/css/maintenance-page.css');
}

/**
 * Override or insert variables into the maintenance page template.
 */
function base_f6_process_maintenance_page(&$variables) {
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
function base_f6_preprocess_node(&$variables) {
  if ($variables['view_mode'] == 'full' && node_is_page($variables['node'])) {
    $variables['classes_array'][] = 'node-full';
  }
}

/**
 * Override or insert variables into the block template.
 */
function base_f6_preprocess_block(&$variables) {
  // In the header region visually hide block titles.
  if ($variables['block']->region == 'header') {
    $variables['title_attributes_array']['class'][] = 'element-invisible';
  }
    $variables['title_attributes_array']['class'][] = 'title';
}

/**
 * Implements theme_menu_tree().
 */
function base_f6_menu_tree($variables) {
  return '<ul class="menu clearfix">' . $variables['tree'] . '</ul>';
}

/**
 * Implements theme_field__field_type().
 */
function base_f6_field__taxonomy_term_reference($variables) {
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
/**
 * Adds `button` class to all submit buttons.
 */
function base_f6_preprocess_button( &$variables ) {
  $variables['element']['#attributes']['class'] = array();
  $variables['element']['#attributes']['class'][] = 'button';
  // Special styles for Delete/Destructive Buttons.
  if ( stristr( $variables['element']['#value'], 'Delete' ) !== FALSE ) {
    $variables['element']['#attributes']['class'][] = 'alert';
  }
    if ( stristr( $variables['element']['#value'], 'Preview' ) !== FALSE ) {
    $variables['element']['#attributes']['class'][] = 'secondary';
  }
}

function base_f6_status_messages($variables) {
  $display = $variables['display'];
  $output = '';

  $status_heading = array(
    'status' => t('Status message'),
    'error' => t('Error message'),
    'warning' => t('Warning message'),
  );
  // Map our drupal statuses to F6 classes
  $f6_classes = array(
    'status' => 'success',
    'error' => 'alert',
    'warning' => 'warning',
  );
  foreach (drupal_get_messages($display) as $type => $messages) {
       // Grab the bootstrap class that corresponds to $type
    $f6_class = $f6_classes[$type];
    $output .= "<div class=\"messages callout $f6_class\">\n";
    if (!empty($status_heading[$type])) {
      $output .= '<h2 class="element-invisible">' . $status_heading[$type] . "</h2>\n";
    }
    if (count($messages) > 1) {
      $output .= " <ul>\n";
      foreach ($messages as $message) {
        $output .= '  <li>' . $message . "</li>\n";
      }
      $output .= " </ul>\n";
    }
    else {
      $output .= reset($messages);
    }
    $output .= "</div>\n";
  }
  return $output;
}

function base_f6_breadcrumb($variables) {
$crumbs ='';
  $breadcrumb = $variables['breadcrumb'];
 if (!empty($breadcrumb)) {
      $crumbs = '<ul class="breadcrumbs">';


      foreach($breadcrumb as $value) {
           $crumbs .= '<li>'.$value.'</li>';
      }
      $crumbs .= '</ul>';
    }
    return $crumbs;

}

function base_f6_menu_local_tasks(&$variables) {
  $output = '';

  if (!empty($variables['primary'])) {
    $variables['primary']['#prefix'] = '<h2 class="element-invisible">' . t('Primary tabs') . '</h2>';
    $variables['primary']['#prefix'] .= '<div class="button-group">';
    $variables['primary']['#suffix'] = '</div>';
    $output .= drupal_render($variables['primary']);
  }
  if (!empty($variables['secondary'])) {
    $variables['secondary']['#prefix'] = '<h2 class="element-invisible">' . t('Secondary tabs') . '</h2>';
    $variables['secondary']['#prefix'] .= '<div class="secondary button-group">';
    $variables['secondary']['#suffix'] = '</div>';
    $output .= drupal_render($variables['secondary']);
  }

  return $output;
}

function base_f6_menu_local_task( &$variables ) {
  $link = $variables['element']['#link'];
  $link_text = $link['title'];
  $li_class = ( !empty( $variables['element']['#active'] ) ? 'secondary' : '' );
  if ( !empty( $variables['element']['#active'] ) ) {
    // Add text to indicate active tab for non-visual users.
    $active = '<span class="element-invisible">' . t( '(active tab)' ) . '</span>';
    // If the link does not contain HTML already, check_plain() it now.
    // After we set 'html'=TRUE the link will not be sanitized by l().
    if ( empty( $link['localized_options']['html'] ) ) {
      $link['title'] = check_plain( $link['title'] );
    }
    $link['localized_options']['html'] = TRUE;
       $link['localized_options']['attributes']['class'][] = 'secondary';
    $link_text = t( '!local-task-title!active', array( '!local-task-title' => $link['title'], '!active' => $active ) );
  }
  // Add section tab styling
  $link['localized_options']['attributes']['class'] = array($li_class, 'button' );
  $output = '';

  $output .= l( $link_text, $link['href'], $link['localized_options'] );

  return  $output;
}


function base_f6_form_alter(&$form, &$form_state, $form_id) {
    $form['actions']['#attributes']['class'][] = 'button-group expanded';

}

function base_f6_fieldset($variables) {
  $element = $variables['element'];
  element_set_attributes($element, array('id'));
  _form_set_class($element, array('form-wrapper'));

  $output = '<fieldset' . drupal_attributes($element['#attributes']) . '>';
  if (!empty($element['#title'])) {
    // Always wrap fieldset legends in a SPAN for CSS positioning.
    $output .= '<legend><span class="fieldset-legend label">' . $element['#title'] . '</span></legend>';
  }
  $output .= '<div class="fieldset-wrapper callout">';
  if (!empty($element['#description'])) {
    $output .= '<div class="fieldset-description small callout secondary">' . $element['#description'] . '</div>';
  }
  $output .= $element['#children'];
  if (isset($element['#value'])) {
    $output .= $element['#value'];
  }

  $output .= '</div>';
  $output .= "</fieldset>\n";
  return $output;
}

function base_f6_form_element_label($variables) {
  $element = $variables['element'];
  // This is also used in the installer, pre-database setup.
  $t = get_t();

  // If title and required marker are both empty, output no label.
  if ((!isset($element['#title']) || $element['#title'] === '') && empty($element['#required'])) {
    return '';
  }

  // If the element is required, a required marker is appended to the label.
  $required = !empty($element['#required']) ? theme('form_required_marker', array('element' => $element)) : '';

  $title = filter_xss_admin($element['#title']);

  $attributes = array();
  // Style the label as class option to display inline with the element.
  if ($element['#title_display'] == 'after') {
    $attributes['class'] = 'option';

  }
  // Show label only to screen readers to avoid disruption in visual flows.
  elseif ($element['#title_display'] == 'invisible') {
    $attributes['class'] = 'element-invisible';
  }

  else { $attributes['class'] = 'label';}

  if (!empty($element['#id'])) {
    $attributes['for'] = $element['#id'];

  }

  // The leading whitespace helps visually separate fields from inline labels.
  return ' <label ' . drupal_attributes($attributes) . '>' . $t('!title !required', array('!title' => $title, '!required' => $required)) . "</label>\n";
}
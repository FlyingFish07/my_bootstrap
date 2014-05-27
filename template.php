<?php

/**
 * @file
 * template.php
 */
/**
 * 自定义评分的显示
 * 
 * @param unknown_type $variables          
 */
function my_bootstrap_field__field_valuation(&$variables){
  $output = '';
  // dpm($variables);
  $vote_number_s = floatval($variables ['items'] [0] ['#markup']);
  $vote_number = $vote_number_s > 10 ? 10 : $vote_number_s;
  $width_v = $vote_number * 10;
  $output .= '<div class="star field"><span class="vote-star"><i style="width:' . $width_v . '%"></i></span><span class="vote-number">' . $vote_number . '</span></div>';
  return $output;
}
/**
 * 更改comment表格的显示
 */
function my_bootstrap_form_comment_form_alter(&$form, &$form_state){
  global $user;
  // dpm($form);
  // dpm($form_state);
  $form ['author'] ['#type'] = 'fieldset';
  $form ['author'] ['#title'] = 'Your Information';
  $form ['author'] ['#collapsible'] = FALSE;
  // 如果是匿名用户
  if ($user->uid == 0) {
    $form ['author'] ['name'] ['#required'] = TRUE;
    $form ['author'] ['mail'] ['#required'] = TRUE;
    $form ['author'] ['homepage'] ['#access'] = TRUE;
    $form ['author'] ['mail'] ['#access'] = TRUE;
  }
  $form ['your_comment'] = array (
      '#type' => 'fieldset',
      '#title' => t('Your Comment'),
      '#collapsible' => FALSE,
      '#weight' => 2 
  );
  
  // Subject
  $form ['your_comment'] ['subject'] = $form ['subject'];
  unset($form ['subject']);
  $form ['your_comment'] ['subject'] ['#weight'] = - 10;
  
  // Comment
  $form ['your_comment'] ['comment_body'] = $form ['comment_body'];
  unset($form ['comment_body']);
}

/**
 * 去掉comment下面的帮助信息
 * 取消显示（drupal Switch to plain text editor）在ckeditor中配置
 * 
 * @param
 *          $element
 */
function my_bootstrap_preprocess_field_multiple_value_form(&$vars){
  global $user;
  if ($vars ['element'] [0] ['#field_name'] == "comment_body") {
    // if (!user_access('administer filters')){
    if ($user->uid != 1) {
      $vars ['element'] [0] ['format'] ['#printed'] = TRUE;
    }
  }
}

/**
 * rewrite the theme_node_recent_block in the node.module
 * 
 * @param
 *          $variables
 * @return string
 */
function my_bootstrap_node_recent_block($variables){
  $rows = array ();
  $output = '';
  $output .= '<ul class="most_view">';
  $l_options = array (
      'query' => drupal_get_destination() 
  );
  $i = 1;
  foreach ( $variables ['nodes'] as $node ) {
      // dpm($node);
      $output .= '<li><span class="tab_nodes">' . $i . '</span>';
      $output .= l($node->title, 'node/' . $node->nid);
      $output .= theme('mark', array (
        'type' => node_mark($node->nid, $node->changed),
        'attributes' => array (
            'class' => array (
                'label label-default' 
            ) 
        ) 
    ));   
    
   // $output .= '<span>'.date("Y-m-d H:i",$node->created).'</span></li>';
    $output .= '</li>';
    $i = $i + 1;
  }
  $output .= '</ul>';
  return $output;
}
/**
 * rewrite the theme_feed_icon in includes/theme.inc
 * 
 * @param [type] $variables
 *          [description]
 * @return [type] [description]
 */
function my_bootstrap_feed_icon($variables){
  global $theme_path;
  $output = '';
  $text = t('Subscribe to !feed-title', array (
      '!feed-title' => 'FishOnSky' 
  ));
  if ($image = theme('image', array (
      'path' => $theme_path . '/img/rss2_3_64.png',
      'width' => 64,
      'height' => 64,
      'alt' => $text 
  ))) {
    $output .= l($image, $variables ['url'], array (
        'html' => TRUE,
        'attributes' => array (
            'class' => array (
                'feed-icon' 
            ),
            'title' => $text 
        ) 
    ));
  }
  return $output;
}


/**
 * Implements theme_field__field_type().
 */
function my_bootstrap_field__taxonomy_term_reference($variables) {
  $output = '';
  
  $output .= '<div class="tags"><i class="fa fa-tags">&nbsp;&nbsp;</i>';
  foreach ($variables['items'] as $delta => $item) {
    $output .= '<span>' . drupal_render($item) . '</span>';
    if($delta != sizeof($variables['items']) - 1){
      $output .= '<span> | </span>';
    }
  }
  $output .= '</div>';
  return $output;
}


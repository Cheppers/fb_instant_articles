<?php

/**
 * Implements hook_views_data_alter().
 */
function fb_instant_articles_views_views_data_alter(array &$data) {

  $data['node_field_data']['fb_instant_articles_views'] = array(
    'title' => t('Valid Facebook Instant Articles filter'),
    'filter' => array(
      'title' => t('Valid Facebook Instant Articles filter'),
      'help' => t('Filter for items that should be listed in the Facebook Instant Articles pages.'),
      'field' => 'type', // we are actually filtering by type really, so we declare this as the field (is it skipped?)
      'id' => 'validfacebookinstantarticles'
    ),
  );

}

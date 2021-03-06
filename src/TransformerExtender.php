<?php

namespace Drupal\fb_instant_articles;

use Facebook\InstantArticles\Transformer\Transformer;

/**
 * Encapsulates Drupal-specific logic when using the Transformer class.
 */
class TransformerExtender extends Transformer {

  /**
   * Transformer constructor.
   *
   * Adds a Drupal hook to allow transformer rules to be added by other modules.
   * We invoke the hook in a constructor - as opposed to inside transform() or a
   * new invokeRules() method - because of the way Transformer is normally used.
   *
   * @see hook_fb_instant_articles_transformer_rules()
   *
   * Note the parent class does not have a constructor, so we do not call
   * @code parent::__construct() @endcode.
   */
  public function __construct() {
    $hook = 'fb_instant_articles_transformer_rules';
    $rules = \Drupal::moduleHandler()->invokeAll($hook);
    \Drupal::moduleHandler()->alter($hook, $rules);
    $this->addRules($rules);
  }

  /**
   * Adds rules from an array of rule information.
   *
   * @todo Propose adding this method upstream to the FB Instant SDK parent.
   *
   * @param array $rules
   *   An array of transformer rule arrays. This is a PHP array representation
   *   of the JSON list of Rules information expected by parent::loadRules().
   *
   * @see TransformerExtender::__construct()
   * @see \Facebook\InstantArticles\Transformer\Transformer::loadRules()
   */
  public function addRules(array $rules) {
    foreach ($rules as $rule) {
      $clazz = $rule['class'];
      try {
        $factory_method = new \ReflectionMethod($clazz, 'createFrom');
      }
      catch (\ReflectionException $e) {
        $factory_method =
          new \ReflectionMethod(
            'Facebook\\InstantArticles\\Transformer\\Rules\\' . $clazz,
            'createFrom'
          );
      }
      $this->addRule($factory_method->invoke(NULL, $rule));
    }
  }

}

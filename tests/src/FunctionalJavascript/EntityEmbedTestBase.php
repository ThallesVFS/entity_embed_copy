<?php

namespace Drupal\Tests\entity_embed\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Base class for all entity_embed tests.
 */
abstract class EntityEmbedTestBase extends WebDriverTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [
    'entity_embed',
    'entity_embed_test',
    'node',
    'ckeditor',
  ];

  /**
   * Assigns a name to the CKEditor iframe, to allow use of ::switchToIFrame().
   *
   * @see \Behat\Mink\Session::switchToIFrame()
   */
  protected function assignNameToCkeditorIframe() {
    $javascript = <<<JS
(function(){
  document.getElementsByClassName('cke_wysiwyg_frame')[0].id = 'ckeditor';
})()
JS;
    $this->getSession()->evaluateScript($javascript);
  }

  /**
   * Waits for CKEditor to initialize.
   */
  protected function waitForEditor() {
    $this->getSession()->wait(5000, "(typeof CKEDITOR != 'undefined' && typeof CKEDITOR.instances['edit-body-0-value'] != 'undefined' && CKEDITOR.instances['edit-body-0-value'].instanceReady)");
  }

}

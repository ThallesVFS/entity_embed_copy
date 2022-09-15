<?php

declare(strict_types = 1);

namespace Drupal\entity_embed\EventSubscriber;

use Drupal\ckeditor5\Plugin\CKEditor5PluginManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\entity_embed\Event\EmbedButtonEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscribes to Embed Button updates, and invalidate the CKEditor5 plugin
 * cache, to update plugin definitions.
 */
class EmbedButtonSubscriber implements EventSubscriberInterface {

  /**
   * The CKEditor5 Plugin Manager.
   *
   * @var \Drupal\ckeditor5\Plugin\CKEditor5PluginManagerInterface
   */
  protected $ckeditor5PluginManager;

  /**
   * Creates EmbedButtonSubscriber object.
   *
   * @param \Drupal\ckeditor5\Plugin\CKEditor5PluginManagerInterface $ckeditor5_plugin_manager
   *   The CKEditor5 Plugin Maanger service.
   */
  public function __construct(CKEditor5PluginManagerInterface $ckeditor5_plugin_manager) {
    $this->ckeditor5PluginManager = $ckeditor5_plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      EmbedButtonEvent::EMBED_BUTTON_UPDATED => 'onEmbedButtonUpdate',
    ];
  }

  /**
   * Subscribe to the Embed Button updates.
   *
   * @param \Drupal\entity_embed\Event\EmbedButtonEvent $event
   *   The Embed Button update event.
   *
   * @return void
   */
  public function onEmbedButtonUpdate(EmbedButtonEvent $event) {
    // Invalidate the CKEditor5 plugin cache, so new toolbar items will appear
    // based on how many Embed Buttons are in the system.
    $this->ckeditor5PluginManager->clearCachedDefinitions();
  }

}

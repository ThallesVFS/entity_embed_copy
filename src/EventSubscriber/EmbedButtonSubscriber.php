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
   * @var \Drupal\ckeditor5\Plugin\CKEditor5PluginManagerInterface|null
   */
  protected $ckeditor5PluginManager;

  /**
   * Creates EmbedButtonSubscriber object.
   *
   * @param \Drupal\ckeditor5\Plugin\CKEditor5PluginManagerInterface|null $ckeditor5_plugin_manager
   *   The CKEditor5 Plugin Manager service.
   */
  public function __construct(
    CKEditor5PluginManagerInterface $ckeditor5_plugin_manager = NULL
  ) {
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
  public function onEmbedButtonUpdate(EmbedButtonEvent $event): void {
    // If the CKEditor5 module is not installed, the service for CKEditor5
    // would not exist in the system, and when an Embed Button is saved a
    // Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
    // would be thrown. Hence, we do not try to clear cache for CKEditor5.
    if (!$this->ckeditor5PluginManager) {
      return;
    }
    // Invalidate the CKEditor5 plugin cache, so new toolbar items will appear
    // based on how many Embed Buttons are in the system.
    $this->ckeditor5PluginManager->clearCachedDefinitions();
  }

}

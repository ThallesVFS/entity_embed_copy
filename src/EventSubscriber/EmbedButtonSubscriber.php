<?php

declare(strict_types = 1);

namespace Drupal\entity_embed\EventSubscriber;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\entity_embed\Event\EmbedButtonEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscribes to Embed Button updates, and invalidate the CKEditor5 plugin
 * cache, to update plugin definitions.
 */
class EmbedButtonSubscriber implements EventSubscriberInterface {

  use StringTranslationTrait;

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
    \Drupal::service('plugin.manager.ckeditor5.plugin')->clearCachedDefinitions();
  }

}

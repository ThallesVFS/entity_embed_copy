<?php

declare(strict_types = 1);

namespace Drupal\entity_embed\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\embed\EmbedButtonInterface;

class EmbedButtonEvent extends Event {

  const EMBED_BUTTON_UPDATED = 'entity_embed_embed_button';

  /**
   * The Embed Button.
   *
   * @var \Drupal\embed\EmbedButtonInterface
   */
  public $embedButton;

  /**
   * Constructs the Embed Button event.
   *
   * @param \Drupal\embed\EmbedButtonInterface $embed_button
   *   The embed button being updated.
   */
  public function __construct(EmbedButtonInterface $embed_button) {
    $this->embedButton = $embed_button;
  }

}

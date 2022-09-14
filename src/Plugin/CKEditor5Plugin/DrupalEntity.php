<?php

declare(strict_types = 1);

namespace Drupal\entity_embed\Plugin\CKEditor5Plugin;

use Drupal\ckeditor5\Plugin\CKEditor5PluginDefault;
use Drupal\ckeditor5\Plugin\CKEditor5PluginDefinition;
use Drupal\Component\Utility\Html;
use Drupal\Core\Access\CsrfTokenGenerator;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\editor\EditorInterface;
use Drupal\embed\Entity\EmbedButton;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * CKEditor 5 plugin.
 */
class DrupalEntity extends CKEditor5PluginDefault implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\Core\Access\CsrfTokenGenerator
   */
  protected CsrfTokenGenerator $csrfTokenGenerator;

  /**
   * Media constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param \Drupal\ckeditor5\Plugin\CKEditor5PluginDefinition $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Access\CsrfTokenGenerator $csrf_token_generator
   *   The CSRF Token generator service.
   */
  public function __construct(
    array $configuration,
    string $plugin_id,
    CKEditor5PluginDefinition $plugin_definition,
    CsrfTokenGenerator $csrf_token_generator
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->csrfTokenGenerator = $csrf_token_generator;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('csrf_token')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDynamicPluginConfig(
    array $static_plugin_config,
    EditorInterface $editor
  ): array {
    // Register embed buttons as individual buttons on admin pages.
    $dynamic_plugin_config = $static_plugin_config;
    $embed_buttons = EmbedButton::loadMultiple();
    $buttons = [];
    /** @var \Drupal\embed\EmbedButtonInterface $embed_button */
    foreach ($embed_buttons as $embed_button) {
      $id = $embed_button->id();
      $label = Html::escape($embed_button->label());
      $buttons[$id] = [
        'id' => $id,
        'name' => $label,
        'label' => $label,
        'image' => $embed_button->getIconUrl(),
      ];
    }
    // Add configured embed buttons and pass it to the UI.
    $dynamic_plugin_config['entityEmbed'] = [
      'buttons' => $buttons,
      'format' => $editor->getFilterFormat()->id(),
      'dialogSettings' => [
        'dialogClass' => 'entity-select-dialog',
        'height' => 'auto',
        'width' => 'auto',
      ],
      'previewCsrfToken' => $this->csrfTokenGenerator->get('X-Drupal-EmbedPreview-CSRF-Token'),
    ];

    return $dynamic_plugin_config;
  }

}

import { Plugin } from 'ckeditor5/src/core';
import { Widget, toWidget, toWidgetEditable } from 'ckeditor5/src/widget';
import EntityEmbedCommand from './command';

export default class EntityEmbedEditing extends Plugin {

  static get requires() {
    return [Widget];
  }

  init() {
    this.attrs = {
      alt: 'alt',
      title: 'title',
      dataCaption: 'data-caption',
      dataAlign: 'data-align',
      drupalEntityLangCode: 'data-langcode',
      drupalEntityEntityType: 'data-entity-type',
      drupalEntityEntityUuid: 'data-entity-uuid',
      drupalEntityEmbedButton: 'data-embed-button',
      drupalEntityEmbedDisplay: 'data-entity-embed-display',
      drupalEntityEmbedDisplaySettings: 'data-entity-embed-display-settings',
    };

    this._defineSchema();
    this._defineConverters();
    this.editor.commands.add(
      'drupalentity',
      new EntityEmbedCommand(this.editor),
    );
  }

  _defineSchema() {
    const schema = this.editor.model.schema;

    schema.register('drupalEntity', {
      isObject: true,
      isContent: true,
      isBlock: true,
      allowWhere: '$block',
      allowAttributes: Object.keys(this.attrs),
    });
    this.editor.editing.view.domConverter.blockElements.push('drupal-entity');
  }

  _defineConverters() {
    const {conversion} = this.editor;

    conversion.for('upcast').elementToElement({
      model: 'drupalEntity',
      view: {
        name: 'drupal-entity',
      },
    });

    conversion.for('dataDowncast').elementToElement({
      model: 'drupalEntity',
      view: {
        name: 'drupal-entity',
      },
    });

    // Convert the <drupalEntity> model into an editable <drupal-entity> widget.
    conversion.for('editingDowncast').elementToElement({
      model: 'drupalEntity',
      view: (modelElement, { writer: viewWriter }) => {
        const drupalEntity = viewWriter.createRawElement('drupal-entity', {
          'data-entity-type': 'node',
        })
        return toWidgetEditable(drupalEntity, viewWriter);
      },
    });

    // Set attributeToAttribute conversion for all supported attributes.
    Object.keys(this.attrs).forEach((modelKey) => {
      const attributeMapping = {
        model: {
          key: modelKey,
          name: 'drupalEntity',
        },
        view: {
          name: 'drupal-entity',
          key: this.attrs[modelKey],
        },
      };
      // Attributes should be rendered only in dataDowncast to avoid having
      // unfiltered data-attributes on the Drupal Entity widget.
      conversion.for('dataDowncast').attributeToAttribute(attributeMapping);
      conversion.for('upcast').attributeToAttribute(attributeMapping);
    });
  }

  /**
   * @inheritdoc
   */
  static get pluginName() {
    return 'EntityEmbedEditing';
  }
}

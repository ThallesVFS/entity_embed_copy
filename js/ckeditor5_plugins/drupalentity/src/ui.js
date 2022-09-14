/**
 * @file Registers the entity embed button(s) to the CKEditor instance(s) and binds functionality to it/them.
 */

import { Plugin } from 'ckeditor5/src/core';
import { ButtonView } from 'ckeditor5/src/ui';
import defaultIcon from '../entity.svg';

export default class EntityEmbedUI extends Plugin {
  init() {
    const editor = this.editor;
    const command = editor.commands.get('drupalentity');
    const options = editor.config.get('entityEmbed');
    if (!options) {
      return;
    }
    const { dialogSettings = {} } = options;
    const embed_buttons = options.buttons;

    // Register each embed button to the toolbar based on configuration.
    Object.keys(embed_buttons).forEach(id => {
      let libraryURL = Drupal.url('entity-embed/dialog/' + options.format + '/' + id);
      // Add each button to the toolbar.
      editor.ui.componentFactory.add(id, (locale) => {
        let button = embed_buttons[id];
        let buttonView = new ButtonView(locale);

        buttonView.set({
          label: button.label,
          icon: defaultIcon, // @todo Get image from embed button config (DrupalEntity plugin - button.image).
          tooltip: true,
        });
        buttonView.bind('isOn', 'isEnabled').to(command, 'value', 'isEnabled');

        this.listenTo(buttonView, 'execute', () =>
          // Open a dialog to select entity to embed.
          Drupal.ckeditor5.openDialog(
            libraryURL,
            ({ attributes }) => {
              editor.execute('drupalentity', attributes);
            },
            dialogSettings,
          ),
        );

        return buttonView;
      })
    });
  }
}

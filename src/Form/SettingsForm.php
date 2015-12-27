<?php
/**
 * @file
 * Contains \Drupal\viewfile\Form\SettingsForm.
 */

namespace Drupal\viewfile\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form with the settings for the module.
 */
class SettingsForm extends ConfigFormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'viewfile_settings';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
        return [
            'viewfile.settings',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $config = $this->config('viewfile.settings');
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
      // $form_state->setErrorByName('css_mode', $this->t('GeSHi'));.
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $errors = $form_state->getErrors();
        if (count($errors) == 0) {
            $config = $this->config('geshifilter.settings');
            $config->set('use_format_specific_options', $form_state->getValue('use_format_specific_options'))
                ->set('default_highlighting', $form_state->getValue('default_highlighting'))
                ->set('default_line_numbering', $form_state->getValue('default_line_numbering'))
                ->set('use_highlight_string_for_php', $form_state->getValue('use_highlight_string_for_php'))
                ->set('enable_keyword_urls', $form_state->getValue('enable_keyword_urls'))
                ->set('css_mode', $form_state->getValue('css_mode'))
                ->set('code_container', $form_state->getValue('code_container'));
            // These values are not always set, so this prevents a warning.
            if ($form_state->hasValue('tags')) {
                $config->set('tags', $form_state->getValue('tags'));
                $config->set('tag_styles', $form_state->getValue('tag_styles'));
                $config->set('decode_entities', $form_state->getValue('decode_entities'));
            }
            $config->save();

            // Regenerate language css.
            if ($config->get('css_mode') == GeshiFilter::CSS_CLASSES_AUTOMATIC) {
                GeshiFilterCss::generateLanguagesCssFile();
            }
            // Always clear the filter cache.
            Cache::invalidateTags(array('geshifilter'));
            parent::submitForm($form, $form_state);
        }
    }
}
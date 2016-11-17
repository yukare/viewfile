<?php

namespace Drupal\viewfile\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class with the form to edit the folder.
 */
class FolderForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $folder = $this->entity;
    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $folder->getLabel(),
      '#description' => $this->t("Label for the Folder."),
      '#required' => TRUE,
    );

    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $folder->id(),
      '#machine_name' => array(
        'exists' => '\Drupal\viewfile\Entity\Folder::load',
      ),
      '#disabled' => !$folder->isNew(),
    );

    $form['permission'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Has permission'),
      '#default_value' => $folder->getPermission(),
      '#description' => $this->t('Create a dedicated permission to this folder.'),
    );

    $form['private'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Private folder'),
      '#default_value' => $folder->getPrivate(),
      '#description' => $this->t('If this folder is visible only to users with permission for this folders. Users with permission to see all folders will not see it.'),
    );

    $form['path'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Path'),
      '#maxlength' => 255,
      '#default_value' => $folder->getPath(),
      '#description' => $this->t("Absolute path or relative to Drupal root."),
      '#required' => TRUE,
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $folder = $this->entity;
    $folder->set('label', trim($folder->label()));
    $folder->set('permission', $form_state->getValue('permission'));
    $folder->set('private', $form_state->getValue('private'));
    $folder->set('path', $form_state->getValue('path'));
    $status = $folder->save();

    if ($status) {
      drupal_set_message($this->t('Saved the %label Folder.', array(
        '%label' => $folder->label(),
      )));
    }
    else {
      drupal_set_message($this->t('The %label Folder was not saved.', array(
        '%label' => $folder->label(),
      )));
    }
    $form_state->setRedirect('entity.folder.list');
  }

}

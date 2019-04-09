<?php

namespace Drupal\idix_publish\Plugin\Field\FieldWidget;

use Drupal\Core\Datetime\Plugin\Field\FieldWidget\TimestampDatetimeWidget;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'idix publish' widget.
 *
 * @FieldWidget(
 *   id = "idix_publish_default_widget",
 *   label = @Translation("Idix publish Date"),
 *   field_types = {
 *     "timestamp"
 *   }
 * )
 */
class IdixPublishDefaultWidget extends TimestampDatetimeWidget  {

  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    $element = parent::formElement($items, $delta, $element, $form, $form_state);

    $new_element['enforce'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Forcer la date de publication'),
    );
    $new_element['publication_date'] = array(
      '#type' => 'container',
    );

    $new_element['publication_date'] += $element;

    return $new_element;
  }

}
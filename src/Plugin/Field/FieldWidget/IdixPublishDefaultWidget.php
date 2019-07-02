<?php

namespace Drupal\idix_publish\Plugin\Field\FieldWidget;

use Drupal\Core\Datetime\DrupalDateTime;
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

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as &$item) {
      // @todo The structure is different whether access is denied or not, to
      //   be fixed in https://www.drupal.org/node/2326533.
      if (isset($item['publication_date']['value']) && $item['publication_date']['value'] instanceof DrupalDateTime) {
        $date = $item['publication_date']['value'];
      }
      elseif (isset($item['publication_date']['value']['object']) && $item['publication_date']['value']['object'] instanceof DrupalDateTime) {
        $date = $item['publication_date']['value']['object'];
      }
      else {
        $date = new DrupalDateTime();
      }
      $item['value'] = $date->getTimestamp();
    }
    return $values;
  }

}
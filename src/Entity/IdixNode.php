<?php
namespace Drupal\idix_publish\Entity;
/**
 * Created by PhpStorm.
 * User: sylvaint
 * Date: 09/02/2018
 * Time: 14:34
 */

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\node\Entity\Node as BaseNode;

class IdixNode extends BaseNode {

  public function published_at() {
    $query = \Drupal::database()->select('publication_date');
    $query->addField('publication_date','published_at');
    $query->condition('nid',$this->id());

    $published_date  = $query->execute()->fetchField();

    return ($published_date) ? $published_date : $this->getCreatedTime();
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(EntityStorageInterface $storage, $update = TRUE) {
    parent::postSave($storage);

    //node is getting updated from unpublished to published status
    if($update && $this->original->status->value == 0 && $this->status->value == 1) {
      $this->_publication_date_set_date();
    }

    //node is getting created with a publish status
    if(!$update && $this->status->value == 1) {
      $this->_publication_date_set_date();
    }
  }

  function _publication_date_set_date() {

    // Save the publication date to the database.
    \Drupal::database()->merge('publication_date')
      ->key(array('nid' => $this->id()))
      ->insertFields(array(
        'nid' => $this->id(),
        'published_at' => \Drupal::time()->getRequestTime()
      ))
      ->updateFields(
        array(
          'published_at' => \Drupal::time()->getRequestTime()
        )
      )
      ->execute();
  }
}
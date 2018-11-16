<?php
namespace Drupal\idix_publish\Entity;
/**
 * Created by PhpStorm.
 * User: sylvaint
 * Date: 09/02/2018
 * Time: 14:34
 */

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\node\Entity\Node as BaseNode;

class IdixNode extends BaseNode {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['published_at'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Publication date'))
      ->setDescription(t('The node publication date.'))
      ->setDefaultValue(NULL);

    return $fields;
  }

  public function published_at() {

    $published_date  = $this->get('published_at')->value;

    return (!is_null($published_date)) ? $published_date : $this->getCreatedTime();
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    if($this->isNew()) {
      $this->set('published_at', time());

    }
    //node is getting updated from unpublished to published status
    if(isset($this->original) && $this->original->status->value == 0 && $this->status->value == 1) {
      $this->set('published_at', time());
    }
  }
}
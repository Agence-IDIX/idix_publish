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
//      ->setLabel(t('IDIX Publication date'))
      ->setDescription(t('The node publication date.'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDefaultValue(NULL);

    return $fields;
  }

  public function published_at() {

    $published_date  = $this->get('published_at')->value;

    return (!is_null($published_date)) ? $published_date : $this->getCreatedTime();
  }
}
<?php
/**
 * @file
 * Contains
 */

namespace Drupal\Component\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 */
class VariableMeta
{
  public $local;

  public $environment;
}
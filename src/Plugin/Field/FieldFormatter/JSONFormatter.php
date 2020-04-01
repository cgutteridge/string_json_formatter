<?php

namespace Drupal\string_json_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Component\Utility\Html;

/**
 * Plugin implementation of the 'string_json_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "string_json_formatter",
 *   label = @Translation("Render JSON from lext and long text"),
 *   field_types = {
 *     "string_long",
 *     "string",
 *   },
 *   edit = {
 *     "editor" = "form"
 *   },
 *   quickedit = {
 *     "editor" = "plain_text"
 *   }
 * )
 */
class JSONFormatter extends FormatterBase {


  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = array();

    foreach ($items as $delta => $item) {
      $d = json_decode( $item->value, true );
      if( !is_array( $d ) ) {
        $elements[$delta] = [ '#type'=>'html_tag', '#tag'=>'div', '#attributes'=>['style'=>'color: red'], '#value'=>'failed to parse JSON' ];
      } else {
        $elements[$delta] = $this->renderJSON( $d );
      }
    }

    return $elements;
  }

  public function renderJSON( $d ) {
    if( is_null( $d )) { 
      return [ '#type'=>'html_tag', '#tag'=>'div', '#attributes'=>['style'=>'color: blue'], '#value'=>'NULL' ];
    }
    if( !is_array( $d )) { 
      return [ '#type'=>'html_tag', '#tag'=>'div', '#attributes'=>['style'=>'color: black'], '#value'=>$d ];
    }
    $list = [];
    foreach( $d as $index=>$item ) {
      $list []=  [ '#type'=>'html_tag', '#tag'=>'tr', 'child'=>[
        [ '#type'=>'html_tag', '#tag'=>'th', '#value'=>$index ],
        [ '#type'=>'html_tag', '#tag'=>'td', 'child'=>$this->renderJSON($item) ]
      ]];
    }
    return [ '#type'=>'html_tag', '#tag'=>'table', 'child'=>$list ];
  }
}

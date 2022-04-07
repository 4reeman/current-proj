<?php

namespace Drupal\additional_fieldset_paragraph_behavior\Plugin\paragraphs\Behavior;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\paragraphs\Annotation\ParagraphsBehavior;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\paragraphs\ParagraphInterface;
use Drupal\paragraphs\ParagraphsBehaviorBase;

/**
 * @ParagraphsBehavior(
 *   id = "paragraph_img_float_behavior",
 *   label = @Translation("Paragraph img float side"),
 *   description = @Translation("Allow to select side for display image"),
 *   weight = 0,
 * )
*/
class ParagraphImgFloatBehavior extends ParagraphsBehaviorBase {

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(ParagraphsType $paragraphs_type) {
    if($paragraphs_type->id == 'additional_fieldset') {
      return TRUE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function view(array &$build, Paragraph $paragraph, EntityViewDisplayInterface $display, $view_mode) {
    $img_side = $paragraph->getBehaviorSetting($this->getPluginId(), 'css_class_options', []);
    $build['#attributes']['class'][] = 'image-side' . str_replace('_', '-', $img_side);
  }

  /**
   * {@inheritdoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state) {
      $form['img_side'] = [
        '#type' => 'select',
        '#title' => $this->t('Image Side Display'),
        '#description' => $this->t('Side of image side'),
        '#options' => $this->getImageSide(),
        '#default_value' => $paragraph->getBehaviorSetting($this->getPluginId(), 'css_class_options', []),
      ];

    return $form;
  }

//  /**
//   * {@inheritdoc}
//   */
//  public function settingsSummary(Paragraph $paragraph) {
//    $image_size = $paragraph->getBehaviorSetting($this->getPluginId(), 'css_class_options', 'left');
//    $image_size_options = $this->getImageSide();
//
//    $summary = [];
//    $summary[] = $this->t('Image side: @value', ['@value' => $image_size_options[$image_size]]);
//    return $summary;
//  }

  /**
   * Return options for defining image side.
   */
  private function getImageSide() {
    return [
      'left' => $this->t('left'),
      'right' => $this->t('right'),
    ];
  }


}

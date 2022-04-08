<?php

namespace Drupal\additional_fieldset_paragraph_behavior\Plugin\paragraphs\Behavior;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\paragraphs\ParagraphInterface;
use Drupal\paragraphs\ParagraphsBehaviorBase;

/**
 * Behavior allows you to determine the side of the image relative to the text.
 *
 * @ParagraphsBehavior(
 *   id = "paragraph_img_float_behavior",
 *   label = @Translation("Paragraph img float side"),
 *   description = @Translation("Allow to select side for display image"),
 *   weight = 0,
 * )
 */
final class ParagraphImgFloatBehavior extends ParagraphsBehaviorBase {

  /**
   * {@inheritdoc}
   */
  public function view(array &$build, Paragraph $paragraph, EntityViewDisplayInterface $display, $view_mode) {
    $img_side = $paragraph->getBehaviorSetting($this->getPluginId(), 'img_sides', 'left');
    $build['#attributes']['class'][] = 'image-side-' . $img_side;
  }

  /**
   * {@inheritdoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state) {
    $form['img_sides'] = [
      '#type' => 'select',
      '#title' => $this->t('Image Side Display'),
      '#description' => $this->t('Side of image side'),
      '#options' => $this->getImageSide(),
      '#default_value' => $paragraph->getBehaviorSetting($this->getPluginId(), 'img_sides', 'left'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary(Paragraph $paragraph) {
    $image_side = $paragraph->getBehaviorSetting($this->getPluginId(), 'css', 'left');
    $image_side_options = $this->getImageSide();

    $summary = [];
    $summary[] = $this->t('Image side: @value', ['@value' => $image_side_options[$image_side]]);

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(ParagraphsType $paragraphs_type) {
    if ($paragraphs_type->id() === 'additional_fieldset') {
      return TRUE;
    }
    return FALSE;
  }

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

<?php

namespace Drupal\pulses\Behavior\ParagraphImgFloatBehavior;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\paragraphs\Annotation\ParagraphsBehavior;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\paragraphs\ParagraphInterface;
use Drupal\paragraphs\ParagraphsBehaviorBase;

/**
 * @ParagraphsBehavior(
 *   id = "pulses_paragraph_img_float",
 *   label = @Translation("Paragraph img float side"),
 *   description = @Translation("Allow to select side for display image"),
 *   weight = 0,
 * )
*/
class AdditionalFieldsetParagraphBehavior extends ParagraphsBehaviorBase {

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(ParagraphsType $paragraphs_type) {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function view(array &$build, Paragraph $paragraph, EntityViewDisplayInterface $display, $view_mode) {

  }

  /**
   * {@inheritdoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state) {
    $form['image_size'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Syka Ny Davai'),
      '#default_value' => TRUE,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary(Paragraph $paragraph) {
    $title_element = $paragraph->getBehaviorSetting($this->getPluginId(), 'title_element');
    return [$title_element ? $this->t('Title element: @element', ['@element' => $title_element]) : 'adsfasdf'];
  }

  /**
   * Return options for image size.
   */
  private function getImageSizeOptions() {
    return [
      '4_12' => $this->t('4 of 12'),
      '6_12' => $this->t('6 of 12'),
      '8_12' => $this->t('8 of 12'),
    ];
  }

  /**
   * Return options for image position.
   */
  private function getImagePositionOptions() {
    return [
      'left' => $this->t('Left'),
      'right' => $this->t('Right'),
    ];
  }

}

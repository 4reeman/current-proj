<?php

namespace Drupal\pulses_audio_widget\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'file_audio' formatter.
 *
 * @FieldFormatter(
 *   id = "pulses_audio",
 *   label = @Translation("Pulses audio"),
 *   description = @Translation("Display the file using an HTML5 audio tag."),
 *   field_types = {
 *     "file"
 *   }
 * )
 */
class FilePulsesAudioFormatter extends FilePulsesAudioFormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function getMediaType() {
    return 'audio';
  }

  /**
   * Builds a renderable array for a field value.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $items
   *   The field values to be rendered.
   * @param string $langcode
   *   The language that should be used to render the field.
   *
   * @return array
   *   A renderable array for $items, as an array of child elements keyed by
   *   consecutive numeric indexes starting from 0.
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      $url = $item->target_id;
      $src = \Drupal::entityTypeManager()->getStorage('file')->load($url)->getFileUri();
      $file_path = \Drupal::service('file_url_generator')->generateString($src);
      $elements[$delta] = [
        '#theme' => $this->getPluginId(),
        '#src' => $file_path,
        '#attached' => [
          'library' => [
            'pulses_audio_widget/pulses-audio-player',
          ],
        ],
      ];
    }

    return $elements;
  }

}

<?php

namespace Drupal\social_auth_facebook\Settings;

use Drupal\social_auth_extra\Settings\SettingsExtraInterface;

/**
 * Defines an interface for Social Auth Facebook settings.
 */
interface FacebookAuthSettingsInterface extends SettingsExtraInterface {

  /**
   * Gets the app ID.
   *
   * @return string
   *   The app ID.
   */
  public function getAppId();

  /**
   * Gets the app secret.
   *
   * @return string
   *   The app secret.
   */
  public function getAppSecret();

  /**
   * Gets the default graph version.
   *
   * @return string
   *   The app default graph version.
   */
  public function getGraphVersion();

}

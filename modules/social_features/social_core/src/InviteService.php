<?php

namespace Drupal\social_core;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\social_event_invite\SocialEventInviteStatusHelper;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Class InviteService
 */
class InviteService {

  /**
   * Request stack object.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * InviteService constructor.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   Request.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler;
   *   ModuleHandler.
   * @param \Drupal\Core\Session\AccountProxyInterface $currentUser
   *   The current user.
   */
  public function __construct(RequestStack $request_stack, ModuleHandlerInterface $moduleHandler, AccountProxyInterface $currentUser) {
    $this->requestStack = $request_stack;
    $this->moduleHandler = $moduleHandler;
    $this->currentUser = $currentUser;
  }

  /**
   * The route name that we will redirect to.
   *
   * @return array $invite_data
   *  Array containing the route name and invite amount.
   */
  public function baseRoute() {
    // Empty by default, we will decorate this in our custom extensions.
    // these can decide on priority what the baseRoute should be.
    $route = [
      'amount' => 0,
      'name' => '',
    ];
    // If module is enabled and it has invites, lets add the route.
    if ($this->moduleHandler->moduleExists('social_event_invite')) {
      if (\Drupal::hasService('social_event_invite.status_helper')) {
        /** @var \Drupal\social_event_invite\SocialEventInviteStatusHelper $eventHelper */
        $eventHelper = \Drupal::service('social_event_invite.status_helper');
        $event_invites = $eventHelper->getAllEventEnrollments($this->currentUser->id());
        if (NULL !== $event_invites && $event_invites > 0) {
          $route['amount'] += count($event_invites);
          $route['name'] = 'view.user_event_invites.page_user_event_invites';
        }
      }
    }
    if ($this->moduleHandler->moduleExists('social_group_invite')) {
      if (\Drupal::hasService('ginvite.invitation_loader')) {
        /** @var \Drupal\ginvite\GroupInvitationLoader $loader */
        $loader = \Drupal::service('ginvite.invitation_loader');
        $group_invites = count($loader->loadByUser());
        if (NULL !== $group_invites && $group_invites > 0) {
          $route['amount'] += count($group_invites);
          $route['name'] = 'view.social_group_invitations.page_1';
        }
      }
    }

    return $route;
  }

}
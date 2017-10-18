<?php

namespace Drupal\civicrm_member_roles\Form;

use Drupal\civicrm_member_roles\CivicrmMemberRoles;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ManualSyncForm.
 */
class ManualSyncForm extends FormBase {

  /**
   * CiviCRM member roles service.
   *
   * @var \Drupal\civicrm_member_roles\CivicrmMemberRoles
   */
  protected $memberRoles;

  /**
   * CivicrmMemberRoleRuleForm constructor.
   *
   * @param \Drupal\civicrm_member_roles\CivicrmMemberRoles $memberRoles
   *   CiviCRM member roles service.
   */
  public function __construct(CivicrmMemberRoles $memberRoles) {
    $this->memberRoles = $memberRoles;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('civicrm_member_roles'));
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'civicrm_member_roles_manual_sync';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['manual_sync'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Manual Synchronization:'),
    ];

    $form['manual_sync']['manual_sync_submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Synchronize CiviMember Membership Types to Drupal Roles now'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if ($this->memberRoles->sync()) {
      drupal_set_message($this->t('CiviMember Memberships and Drupal Roles have been synchronized using available rules. Note: if no association rules exist then synchronization has not been completed.'));
    }
    else {
      drupal_set_message($this->t('There was an error. CiviMember Memberships and Drupal Roles could not be synchronized.'), 'error');
    }
  }

}

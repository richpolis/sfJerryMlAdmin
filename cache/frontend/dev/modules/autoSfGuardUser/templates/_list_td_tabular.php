<td class="sf_admin_text sf_admin_list_td_username">
  <?php echo link_to($sf_guard_user->getUsername(), 'sf_guard_user_edit', $sf_guard_user) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_first_name">
  <?php echo $sf_guard_user->getFirstName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_last_name">
  <?php echo $sf_guard_user->getLastName() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_email_address">
  <?php echo $sf_guard_user->getEmailAddress() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_updated_at">
  <?php echo false !== strtotime($sf_guard_user->getUpdatedAt()) ? format_date($sf_guard_user->getUpdatedAt(), "dd/MM/y") : '&nbsp;' ?>
</td>

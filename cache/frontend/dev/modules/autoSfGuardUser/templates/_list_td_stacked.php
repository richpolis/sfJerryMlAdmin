<td colspan="5">
  <?php echo __('%%username%% - %%first_name%% - %%last_name%% - %%email_address%% - %%updated_at%%', array('%%username%%' => link_to($sf_guard_user->getUsername(), 'sf_guard_user_edit', $sf_guard_user), '%%first_name%%' => $sf_guard_user->getFirstName(), '%%last_name%%' => $sf_guard_user->getLastName(), '%%email_address%%' => $sf_guard_user->getEmailAddress(), '%%updated_at%%' => false !== strtotime($sf_guard_user->getUpdatedAt()) ? format_date($sf_guard_user->getUpdatedAt(), "dd/MM/y") : '&nbsp;'), 'messages') ?>
</td>

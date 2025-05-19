import { adl } from './function';

adl('DOMContentLoaded', () => {
  $('#show-pwd').on('change', function () {
    const form = $(this).closest('form');
    const isChecked = this.checked;

    form.find('.toggle-password').attr('type', isChecked ? 'text' : 'password');
  });
});

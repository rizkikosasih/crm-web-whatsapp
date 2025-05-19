import { adl, numberOnly } from './function';

adl('DOMContentLoaded', function () {
  /** Input Number Only */
  adl('input', 'input.number-only', function () {
    this.value = numberOnly(this.value);
  });
});

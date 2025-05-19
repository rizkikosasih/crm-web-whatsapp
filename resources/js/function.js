/** Add Document Listener */
export function adl(type, selectorOrCallback, callbackOrOptions, options = false) {
  let selector, callback;

  if (typeof selectorOrCallback === 'function') {
    // Format: type, callback, options
    callback = selectorOrCallback;
    document.addEventListener(type, callbackOrOptions || callback, options);
    return function removeListener() {
      document.removeEventListener(type, callbackOrOptions || callback, options);
    };
  } else {
    // Format: type, selector, callback, options
    selector = selectorOrCallback;
    callback = callbackOrOptions;

    const handler = function (event) {
      if (event.target.matches(selector) || event.target.closest(selector)) {
        callback.call(event.target, event);
      }
    };

    document.addEventListener(type, handler, options);
    return function removeListener() {
      document.removeEventListener(type, handler, options);
    };
  }
}

/** Convert Number Only */
export function numberOnly(string) {
  if (!string) return null;
  return string.replace(/[^\d]/g, '');
}

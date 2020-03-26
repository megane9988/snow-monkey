'use strict';

import {
  getHtml,
} from './_helper';

export function scrollChecker() {
  if ('undefined' === typeof IntersectionObserver) {
    return;
  }

  const options = {
    root: null,
    rootMargin: "0px",
    threshold: 0,
  };

  const toggle   = (isIntersecting) => getHtml().setAttribute('data-scrolled', ! isIntersecting);
  const callback = (entries) => entries.forEach(entry => toggle(entry.isIntersecting));
  const observer = new IntersectionObserver(callback, options);
  observer.observe(document.getElementById('page-start'));
}

'use strict';

import { smoothScroll } from './module/_smooth-scroll';
import forEachHtmlNodes from '@inc2734/for-each-html-nodes';

document.addEventListener(
  'DOMContentLoaded',
  () => {
    const smoothScrollLinks = document.querySelectorAll(
      [
        '.c-page-top a[href^="#"]',
        '.wpco a[href^="#"]',
        '.u-smooth-scroll[href*="#"]',
        '.u-smooth-scroll a[href*="#"]',
      ].join(',')
    );
    forEachHtmlNodes(smoothScrollLinks, smoothScroll);
  },
  false
);

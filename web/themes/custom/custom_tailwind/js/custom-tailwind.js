/**
 * @file
 * Custom Tailwind behaviors.
 */
(function (Drupal, once) {
  'use strict';

  Drupal.behaviors.customTailwind = {
    attach(context, settings) {
      once('customTailwind', '#theme-toggle', context).forEach((toggle) => {

        const thumb = toggle.querySelector('#theme-thumb');

        // On page load, check if dark mode should be enabled
        if (localStorage.getItem('theme') === 'dark') {
          document.documentElement.classList.add('dark');
          thumb.classList.add('translate-x-8'); // move the thumb to right
        }

        toggle.addEventListener('click', () => {
          document.documentElement.classList.toggle('dark');

          if (document.documentElement.classList.contains('dark')) {
            localStorage.setItem('theme', 'dark');
            thumb.classList.add('translate-x-8');
          } else {
            localStorage.setItem('theme', 'light');
            thumb.classList.remove('translate-x-8');
          }
        });

      });
    }
  };

})(Drupal, once);

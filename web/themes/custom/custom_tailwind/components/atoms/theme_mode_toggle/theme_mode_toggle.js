(function (Drupal, once) {
  'use strict';

  Drupal.behaviors.customTailwind = {
    attach(context, settings) {
      once('customTailwind', '#theme-toggle', context).forEach((toggle) => {
        const thumb = toggle.querySelector('#theme-thumb');
        const buttons = toggle.querySelectorAll('button[data-theme]');
        const root = document.documentElement;

        const setTheme = (theme) => {
          // Theme class on <html>
          if (theme === 'dark') {
            root.classList.add('dark');
          } else if (theme === 'light') {
            root.classList.remove('dark');
          } else if (theme === 'system') {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            root.classList.toggle('dark', prefersDark);
          }


          // Update thumb position
          // Remove all position classes first:
          thumb.classList.remove('left-1', 'left-1/2', 'right-1', 'translate-x-0', '-translate-x-1/2');

          // Apply for each mode:
          if (theme === 'light') {
            thumb.classList.add('left-1');
          } else if (theme === 'system') {
            thumb.classList.add('left-1/2', '-translate-x-1/2');
          } else if (theme === 'dark') {
            thumb.classList.add('right-1');
          }


          // Update button states
          buttons.forEach((btn) => {
            const isActive = btn.getAttribute('data-theme') === theme;
            // btn.classList.toggle('dark:bg-white', isActive);
            // btn.classList.toggle('dark:bg-gray-800', isActive);
            btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
          });

          // Store preference
          localStorage.setItem('theme', theme);
        };

        // Init: check localStorage or system
        const saved = localStorage.getItem('theme');

        if (saved === 'dark' || saved === 'light') {
          setTheme(saved);
        } else {
          // system default
          const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
          setTheme(prefersDark ? 'dark' : 'light');
          localStorage.setItem('theme', 'system');
        }

        buttons.forEach((btn) => {
          btn.addEventListener('click', () => {
            const chosen = btn.getAttribute('data-theme');
            setTheme(chosen);
            const icons = toggle.querySelectorAll(`.theme-icon-${chosen}`);
            icons.forEach((icon) => {
              icon.classList.remove('spin-once');
              void icon.offsetWidth;
              icon.classList.add('spin-once');
              icon.addEventListener(
                'animationend',
                () => icon.classList.remove('spin-once'),
                { once: true }
              );

            });

          });
        });
      });
    }
  };
})(Drupal, once);

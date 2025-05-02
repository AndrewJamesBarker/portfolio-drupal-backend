(function (Drupal, once) {

  Drupal.behaviors.parallaxHero = {
    attach(context) {
      let rellaxInstance;

      function initRellax() {
        if (rellaxInstance) {
          rellaxInstance.destroy();
        }
          rellaxInstance = new Rellax('.rellax');
      }

      //initial load
      once('parallax-hero', '.rellax', context).forEach(() => {
        if (typeof Rellax !== 'undefined') {
          initRellax();
          // console.log('[ParallaxHero] Initializing Rellax on:', document.querySelectorAll('.rellax'));
        } else {
          console.warn('Rellax not loaded.')
        }
      });

      let resizeTimeout;
      window.addEventListener('resize', function () {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
          if (typeof Rellax !== 'undefined') {
            initRellax();
          }
        }, 200);
      });
    }
  };
})(Drupal, once);

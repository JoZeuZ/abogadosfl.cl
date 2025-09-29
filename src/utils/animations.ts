/**
 * Funciones de utilidad para animaciones
 */

/**
 * Anima un contador desde 0 hasta un valor objetivo
 * @param {HTMLElement} element - Elemento del contador
 * @param {number} target - Valor objetivo
 * @param {string} [suffix=''] - Sufijo opcional (%, +, etc.)
 * @returns {Promise<void>}
 */
function animateCounter(element: HTMLElement, target: number, suffix: string = ''): Promise<void> {
  return new Promise((resolve) => {
    const duration = 2000; // 2 segundos
    const start = Date.now();
    
    function update() {
      const now = Date.now();
      const progress = Math.min((now - start) / duration, 1);
      
      // Función de easing para hacer la animación más natural
      const easeOutQuart = 1 - Math.pow(1 - progress, 4);
      const current = Math.floor(target * easeOutQuart);
      
      element.textContent = `${current}${suffix}`;
      
      if (progress < 1) {
        requestAnimationFrame(update);
      } else {
        resolve();
      }
    }
    
    requestAnimationFrame(update);
  });
}

/**
 * Inicializa las animaciones de contadores en un contenedor
 * @param {HTMLElement} container - Contenedor de los contadores
 */
export function initNumberAnimation(container: HTMLElement): void {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const counters = container.querySelectorAll('[data-counter]');
          
          counters.forEach((counter, index) => {
            if (counter instanceof HTMLElement && !counter.dataset.animated) {
              const target = parseInt(counter.dataset.counter || '0', 10);
              const suffix = counter.dataset.suffix || '';
              
              // Agregar delay escalonado
              setTimeout(() => {
                animateCounter(counter, target, suffix);
                counter.dataset.animated = 'true';
              }, index * 200);
            }
          });
          
          observer.unobserve(container);
        }
      });
    },
    { threshold: 0.5 }
  );
  
  observer.observe(container);
}
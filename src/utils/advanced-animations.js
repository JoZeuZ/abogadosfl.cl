/**
 * Utility module for advanced animations in the Abogados FL website
 */

/**
 * Aplica un efecto de aparición escalonada a un conjunto de elementos
 * @param {HTMLElement} container - Contenedor de los elementos a animar
 * @param {string} selector - Selector CSS para los elementos a animar
 * @param {number} delay - Retraso base entre elementos en milisegundos
 */
export function initStaggerAnimation(container, selector = '.fade-in', baseDelay = 100) {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const elements = entry.target.querySelectorAll(selector);
        elements.forEach((el, index) => {
          if (!el.dataset.hasAnimated) {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
              el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
              el.style.opacity = '1';
              el.style.transform = 'translateY(0)';
              el.dataset.hasAnimated = 'true';
            }, index * baseDelay);
          }
        });
        
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.1,
    rootMargin: '0px'
  });
  
  observer.observe(container);
}

/**
 * Aplica un efecto parallax suave a un elemento de fondo
 * @param {HTMLElement} element - Elemento al que aplicar el parallax
 * @param {number} speed - Velocidad del efecto (1 = normal, <1 más lento, >1 más rápido)
 */
export function initParallaxEffect(element, speed = 0.5) {
  let ticking = false;
  
  window.addEventListener('scroll', () => {
    if (!ticking) {
      window.requestAnimationFrame(() => {
        const scrolled = window.pageYOffset;
        const offset = scrolled * speed;
        
        element.style.transform = `translateY(${offset}px)`;
        ticking = false;
      });
      
      ticking = true;
    }
  });
}

/**
 * Aplica efectos hover sutiles a elementos de tarjeta
 * @param {string} selector - Selector CSS para las tarjetas
 */
export function initHoverEffects(selector = '.card') {
  const cards = document.querySelectorAll(selector);
  
  cards.forEach(card => {
    card.addEventListener('mouseenter', () => {
      card.style.transform = 'translateY(-5px)';
      card.style.boxShadow = 'var(--tw-shadow-hover)';
    });
    
    card.addEventListener('mouseleave', () => {
      card.style.transform = 'translateY(0)';
      card.style.boxShadow = 'var(--tw-shadow-card)';
    });
  });
}
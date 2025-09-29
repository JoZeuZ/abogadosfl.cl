/**
 * Utility module for handling animations in the Abogados FL website
 */

/**
 * Anima un número desde 0 hasta un valor objetivo
 * @param {HTMLElement} element - Elemento que contiene el número
 * @param {number} target - Número objetivo
 * @param {number} duration - Duración de la animación en milisegundos
 * @param {string} prefix - Prefijo opcional (ej: "+")
 * @param {string} suffix - Sufijo opcional (ej: "%", "+")
 * @returns {Promise} - Promise que se resuelve cuando la animación termina
 */
export function animateNumber(element, target, duration = 2000, prefix = '', suffix = '') {
    return new Promise((resolve) => {
        const startTime = performance.now();
        const startValue = 0;
        
        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Función de easing para hacer la animación más natural
            const easeOutQuart = 1 - Math.pow(1 - progress, 4);
            
            const current = Math.floor(startValue + (target - startValue) * easeOutQuart);
            element.textContent = `${prefix}${current.toLocaleString()}${suffix}`;
            
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
 * Inicia la animación de números cuando el elemento es visible
 * @param {HTMLElement} element - Elemento contenedor
 * @param {string} selector - Selector para encontrar elementos numéricos
 * @param {string} dataAttribute - Atributo data que contiene el valor objetivo
 */
export function initNumberAnimation(element, selector = '[data-counter]', dataAttribute = 'counter') {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counters = entry.target.querySelectorAll(selector);
                counters.forEach(counter => {
                    if (!counter.dataset.hasAnimated) {
                        const target = parseInt(counter.dataset[dataAttribute], 10);
                        const prefix = counter.dataset.prefix || '';
                        const suffix = counter.dataset.suffix || '';
                        
                        animateNumber(counter, target, 2000, prefix, suffix);
                        counter.dataset.hasAnimated = 'true';
                    }
                });
                
                // Dejar de observar después de la animación
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.5,
        rootMargin: '0px'
    });
    
    observer.observe(element);
}
declare module '*/utils/animations.js' {
  /**
   * Anima un número desde 0 hasta un valor objetivo
   */
  export function animateNumber(
    element: HTMLElement,
    target: number,
    duration?: number,
    prefix?: string,
    suffix?: string
  ): Promise<void>;

  /**
   * Inicia la animación de números cuando el elemento es visible
   */
  export function initNumberAnimation(
    element: HTMLElement,
    selector?: string,
    dataAttribute?: string
  ): void;
}
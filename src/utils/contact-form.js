
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('contactForm');
  const submitBtn = document.querySelector('.contact-submit');
  const btnText = document.querySelector('.btn-text');
  const btnLoader = document.querySelector('.btn-loader');

  if (form && submitBtn && btnText && btnLoader) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      if (submitBtn.disabled) return;

      try {
        submitBtn.disabled = true;
        btnText.style.display = 'none';
        btnLoader.style.display = 'inline';

        const recaptchaResponse = window.grecaptcha?.getResponse();

        if (!recaptchaResponse) {
          alert('Por favor, complete la verificación reCAPTCHA.');
          return;
        }

        const formData = new FormData(form);
        formData.append('g-recaptcha-response', recaptchaResponse);

        const response = await fetch('/enviar_correo.php', {
          method: 'POST',
          body: formData
        });

        if (response.ok) {
          window.location.href = '/gracias.html';
        } else {
          throw new Error('Error al enviar el formulario');
        }
      } catch (error) {
        console.error('Error:', error);
        window.location.href = '/error.html';
      } finally {
        submitBtn.disabled = false;
        btnText.style.display = 'inline';
        btnLoader.style.display = 'none';
        window.grecaptcha?.reset();
      }
    });
  }
});

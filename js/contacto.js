document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("form-contacto");
  if (!form) return;

  const statusBox = document.getElementById("status-box");
  const submitBtn = form.querySelector('button[type="submit"]');
  const tokenField = form.querySelector('input[name="token"]');

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    // Bloquear botón
    submitBtn.disabled = true;
    const originalText = submitBtn.textContent;
    submitBtn.textContent = "Enviando...";
    submitBtn.classList.add("opacity-60", "cursor-not-allowed");

    const formData = new FormData(form);

    try {
      const response = await fetch("php/contacto.php", {
        method: "POST",
        body: formData,
        cache: "no-store",
      });

      const data = await response.json();

      if (data.success) {
        statusBox.innerHTML =
          `<div class="bg-green-200 text-green-800 p-3 rounded mb-3"> ${data.message}</div>`;
        form.reset();

        // Pedir un nuevo token automáticamente
        const resToken = await fetch("php/nuevo_token.php");
        const dataToken = await resToken.json();
        tokenField.value = dataToken.token;

        // Hacer desaparecer el mensaje luego de 5 segundos
        setTimeout(() => {
          statusBox.innerHTML = "";
        }, 4000);
      } else {
        statusBox.innerHTML =
          `<div class="bg-red-200 text-red-800 p-3 rounded mb-3"> ${data.message}</div>`;
      }
    } catch (error) {
      statusBox.innerHTML =
        `<div class="bg-red-200 text-red-800 p-3 rounded mb-3"> Error inesperado: ${error}</div>`;
    } finally {
      // Rehabilitar botón
      submitBtn.disabled = false;
      submitBtn.textContent = originalText;
      submitBtn.classList.remove("opacity-60", "cursor-not-allowed");
    }
  });
});

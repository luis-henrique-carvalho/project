document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.getElementById("login-form");
  
  loginForm.addEventListener("submit", async function (event) {
    event.preventDefault();

    const cpf = document.getElementById("cpf").value;
    const nome = document.getElementById("nome").value;

    if (cpf && nome) {
      try {
        const response = await fetch("php/login.php", {
          method: "POST",
          body: JSON.stringify({ cpf: cpf, nome: nome }),
          headers: {
            "Content-Type": "application/json",
          },
        });

        if (response.ok) {
          console.log("Login realizado com sucesso");
          window.location.href = "php/avaliacao.php";
        } else {
          console.error("Erro ao fazer login");
        }
      } catch (error) {
        console.error("Erro na requisição:", error);
      }
    }
  });
});

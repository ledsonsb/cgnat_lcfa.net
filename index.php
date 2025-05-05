<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gerador de CGNAT</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      body {
        background-color: #f8f9fa;
      }
      .container {
        max-width: 700px;
        margin-top: 50px;
      }
      pre {
        white-space: pre-wrap;
        word-break: break-word;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <h2 class="text-center mb-4">Gerador de CGNAT - LCFA.Net</h2>

      <div class="card shadow-sm">
        <div class="card-body">
          <form method="post" action="">
            <div class="mb-3">
              <label for="campo1" class="form-label">IP público da Loopback</label>
              <input type="text" class="form-control" id="campo1" name="campo1" placeholder="Ex: 200.200.200.1" required>
              <div class="form-text">
                Exemplo: 200.200.200.X irá gerar uma range de 100.64.X.Y com blocos de portas.
              </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Gerar Script</button>
          </form>
        </div>
      </div>

      <?php if (!empty($_POST['campo1'])): ?>
        <?php
          $loopback = $_POST['campo1'];
          $x = explode(".", $loopback);
          $portaInicial = 1024;
          $portaFinal = 0;
          $ippublico = $loopback;
          $iterador = 0;

          ob_start(); // Inicia buffer para capturar a saída
          echo '/ip address add address=' . $ippublico . '/32 interface=loopback comment="CGNAT IP ' . $x[3] . '"' . "\n";
          echo '/ip firewall nat add chain=srcnat protocol=icmp action=src-nat src-address=100.64.' . $x[3] . '.0/27 to-address=' . $ippublico . ' comment=" ======== CGNAT ' . $ippublico . ' ========== "' . "\n";

          for ($i = $portaInicial; $i <= 63519; $i += 2015) {
              $portaFinal = $portaInicial + 2015;
              echo "/ip firewall nat add chain=srcnat protocol=tcp action=src-nat src-address=100.64." . $x[3] . "." . $iterador . " to-address=" . $ippublico . " to-ports=" . $portaInicial . "-" . $portaFinal . "\n";
              echo "/ip firewall nat add chain=srcnat protocol=udp action=src-nat src-address=100.64." . $x[3] . "." . $iterador . " to-address=" . $ippublico . " to-ports=" . $portaInicial . "-" . $portaFinal . "\n";
              $portaInicial = $portaFinal + 1;
              $iterador += 1;
          }

          echo '/ip pool add ranges=100.64.' . $x[3] . '.0/27 name=Cgant' . $x[3] . "\n";
          $scriptGerado = ob_get_clean(); // Armazena o conteúdo gerado
        ?>

        <div class="card mt-4 shadow-sm">
          <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            Script Gerado
            <button class="btn btn-light btn-sm" onclick="copiarScript()">Copiar Script</button>
          </div>
          <div class="card-body">
            <pre><code id="codigoScript"><?= htmlspecialchars($scriptGerado); ?></code></pre>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <script>
      function copiarScript() {
        const codigo = document.getElementById("codigoScript").innerText;
        navigator.clipboard.writeText(codigo).then(() => {
          alert("Script copiado para a área de transferência!");
        }).catch(err => {
          alert("Erro ao copiar: " + err);
        });
      }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" defer></script>
  </body>
</html>

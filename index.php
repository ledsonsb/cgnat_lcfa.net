<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Boostrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous" defer></script>
    <title>Bootstrap demo</title>
</head>
  <body>
    <div class="container">
    <h3>Gerador de CCGNAT LCFA.Net</h3>
        <form method="post" href="#">
            <div class="form-group">
                <label>Ip público da loopback </label>
                <input type="text" class="form-control" name="campo1" placeholder="200.200.200.1">
                <p>200.200.200.X vai gerar uma range de 100.64.X.Y onde o Y serão os ranges de portas</p>
            </div>
            <br>
            <div>
                <button type="submit" class="btn btn-primary" data-toggle="button" aria-pressed="false" autocomplete="off">Gerar script</button>
            </div>
        </form>
    </div>
    <hr>
    <!-- Inicio dos Códigos PHP -->
    <div class="container">
    <?php
        $loopback = 0;
        if ($_POST['campo1']) {
            $loopback = $_POST['campo1'];
    }

    $x = explode(".", $loopback);

    $portaInicial = 1024;
    $portaFinal = 0;
    $ippublico = $loopback;
    $iterador = 0;

    echo "/ip firewall nat add chain=src-nat protocol=icmp action=srcnat src-address=100.64.".$x[3].".0/25 to-address=".$ippublico."<br>";
    # For para o inicio
    for ($i=$portaInicial; $i <= 63519; $i += 2015) {
        $portaFinal = $portaInicial + 2015;
        echo "/ip firewall nat add chain=src-nat protocol=tcp action=srcnat src-address=100.64.".$x[3].".".$iterador." to-address=".$ippublico." to-port to-ports=".$portaInicial."-".$portaFinal."<br>" ;
        echo "/ip firewall nat add chain=src-nat protocol=udp action=srcnat src-address=100.64.".$x[3].".".$iterador." to-address=".$ippublico." to-port to-ports=".$portaInicial."-".$portaFinal."<br>" ;
        $portaInicial = $portaFinal+1;
        $iterador += 1;
    }
    
    ?>
    </div>
  </body>
</html>

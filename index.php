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
                <input type="text" class="form-control" name="campo1" aria-describedby="emailHelp" placeholder="200.200.200.1">
            </div>
            <div class="form-group">
                <label>Range do CGNAT </label>
                <input type="text" class="form-control" name="campo2" aria-describedby="emailHelp" placeholder="100.64.0.1">
            <small id="emailHelp" class="form-text text-muted">32 ips para cada ip público</small>
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
        var_dump($_POST);
        $teste1 = 0;
        if ($_POST['campo1']) {
            $teste1 = $_POST['campo1'];
    }

    $loopback = $teste1;
    echo $loopback;
    $cgnat_range = 150;

        echo "/ip firewall nat add chain=src-nat protocol=icmp action=srcnat  to-address=".$loopback."<br>";
        for ($i=0; $i < 32 ; $i++) { 
            echo "/ip firewall nat add chain=src-nat protocol=tcp action=srcnat src-address=".$i." to-address=".$cgnat_range." to-port to-ports=1024-3039 <br>";
            echo "/ip firewall nat add chain=src-nat protocol=udp action=srcnat src-address=".$i." to-address=".$cgnat_range." to-port to-ports=1024-3039 <br>";
        }
    
    ?>
    </div>
    <!-- Regras para serem geradas

    /ip firewall nat add chain=src-nat protocol=tcp action=srcnat src-address=100.64.0.0 to-address=2.2.2.2 to-port to-ports=1024-3039 
    /ip firewall nat add chain=src-nat protocol=udp action=srcnat src-address=100.64.0.0 to-address=2.2.2.2 to-port to-ports=1024-3039 

    /ip firewall nat add chain=src-nat protocol=icmp action=srcnat  to-address=2.2.2.2

    -->
  </body>
</html>
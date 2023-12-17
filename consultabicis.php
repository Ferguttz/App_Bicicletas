<?php
// Programa principal
define('FILEUSER','Bicis.csv');
include_once 'BiciElectrica.php';

$tabla = cargabicis();
if (!empty($_GET['coordx']) && !empty($_GET['coordy'])) {
$biciRecomendada = bicimascercana($_GET['coordx'], $_GET['coordy'], $tabla);
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>MOSTRAR BICIS OPERATIVAS</title>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
        }
    </style>

</head>

<body>
    <h1> Listado de bicicletas operativas </h1>
    <?= mostrartablabicis($tabla); ?>
    <?php if (isset($biciRecomendada)) : ?>
        <h2> Bicicleta disponible más cercana es <?= $biciRecomendada ?> </h2>
        <button onclick="history.back()"> Volver </button>
    <?php else : ?>
        <h2> Indicar su ubicación: <h2>
                <form>
                    Coordenada X: <input type="number" name="coordx"><br>
                    Coordenada Y: <input type="number" name="coordy"><br>
                    <input type="submit" value=" Consultar ">
                </form>
            <?php endif ?>
</body>

</html>

<?php


function cargabicis() : Array{
    $tabla = [];

    if (!is_readable(FILEUSER)) {
        // El directorio donde se crea tiene que tener permisos adecuados
        $fich = @fopen(FILEUSER, "w") or die("Error al crear el fichero.");
        fclose($fich);
    }
    $fich = @fopen(FILEUSER, 'r') or die("ERROR al abrir fichero de usuarios"); // abrimos el fichero para lectura

    while ($partes = fgetcsv($fich)) {
        $bici = new BiciElectrica();
        $bici->id = $partes[0];
        $bici->coordx = $partes[1];
        $bici->coordy = $partes[2];
        $bici->bateria = $partes[3];
        $bici->operativa = $partes[4];

        $tabla[] = $bici;
    }

    fclose($fich);

    return $tabla;
}

function bicimascercana($pos_x,$pos_y,$tabla) {
    $bicisOperativas = array_filter($tabla,"bicisOperativas");
    $distacias = [];

    //calcular distancias de cada bici operativa
    foreach ($bicisOperativas as $key => $bici) {
        $distacias[$key] = $bici->distancia($pos_x,$pos_y);
    }

    //sacar la mínima distancia y savar su clave
    $cercano = min($distacias);
    $clave = array_search($cercano,$distacias);

    return $bicisOperativas[$clave];
}

//funcion Callback para devolver las bicis operatvias
function bicisOperativas ($bici) {
    return $bici->operativa == 1;
}


function mostrartablabicis($tabla) : String{
    //Titulo para la tabla
    $titulos = ["Id","Coord X","Coord Y","Bateria"];

    //Inicio de tabla con los títulos
    $msg = "<table>";
    $msg .= "<tr>";
    foreach ($titulos as $value) {
        $msg .= "<th>$value</th>";
    }
    $msg .= "</tr>";


    //recorer la tabla de objectos BiciElectricas
    foreach ($tabla as $bici) {
        $msg .= "<tr>";
        foreach ($titulos as  $value) {
            if($bici->operativa == 1) {
                $atributo = strtolower(str_replace(" ","",$value)); //transformar los titulos para que sean iguales a los atributos de la clase
                $msg .= "<td>".$bici->$atributo."</td>";
            } 
        }
        $msg .= "</tr>";
    }

    return $msg;
}

?>
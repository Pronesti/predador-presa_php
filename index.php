<?php
/*
miTablero[fila][columna]
[0,1,2,3,4,5,6,7,8,9]
[0,1,2,3,4,5,6,7,8,9]
[0,1,2,3,4,5,6,7,8,9]
[0,1,2,3,4,5,6,7,8,9]
[0,1,2,3,4,5,6,7,8,9]
[0,1,2,3,4,5,6,7,8,9]
[0,1,2,3,4,5,6,7,8,9]
[0,1,2,3,4,5,6,7,8,9]
[0,1,2,3,4,5,6,7,8,9]
[0,1,2,3,4,5,6,7,8,9]

Preferencias:
    1 2 3
    8   4
    7 6 5

Referencias:
    L = leon
    A = Antilope
    M = Montaña

Acciones:
    1) Comer:
    2) Reproducirse:
    3) Mover:         

*/

function crearTablero($filas,$columnas){
    $tablero= array();
    foreach (range(0,$filas) as $row) {
    foreach (range(0,$columnas) as $col) {
    $tablero[$row][$col] = "M";
    }
    }
    $n_fila = $filas;
    $n_col = $columnas;

    $i=1;
    while($i < $filas){
        $j=1;
        while($j < $columnas){
            $tablero[$i][$j]= " ";
            $j += 1;
        }
        $i += 1;
    }
    return $tablero;
}

function agregar($tablero, $tipo, $posicion){
    $tablero[$posicion[0]][$posicion[1]] = $tipo;
    return $tablero;
}

function misVecinos($posicion_central){ #[fila, columna]
    $f = $posicion_central[0]; #filas
    $c = $posicion_central[1]; #columnas
    return array([$f-1,$c-1], [$f-1,$c], [$f-1,$c+1], [$f,$c+1], [$f+1, $c+1], [$f+1, $c], [$f+1,$c-1], [$f,$c-1]);
}

function buscarAdyacente($tablero, $coord_centro, $objetivo){
    foreach (misVecinos($coord_centro) as $k => $v){
        //print_r($tablero[$v[0]][$v[1]]);
        //print("\n");
        if ($tablero[$v[0]][$v[1]] === $objetivo){
            return array($v);
        }
    }
    return array();
}


function buscarAdyacenteAleatorio($tablero, $coord_centro, $objetivo){
    $lista = misVecinos($coord_centro);
    shuffle($lista);
    foreach ($lista as $k => $v){
        if ($tablero[$v[0]][$v[1]] === $objetivo){
            return array($v);
        }
    }
    return array();
}

function ejercicio_4($tablero){
    // definimos la coordenadas de nuestros personajes
    $fil = [1 , 2 , 3 , 3 , 1];
    $col = [3 , 1 , 1 , 3 , 2];
    $tipo = ["A" , "A" , "A" , "A" , "L"];
    // y ahora los asignamos dentro del tablero
    $i = 0;
    while ($i < count($tipo)){
        $tablero[$fil[$i]][$col[$i]] = $tipo[$i];
        $i = $i + 1;
    }
    // veamos como queda :
    graficarMatriz($tablero);
}

function faseMover($tablero){
    $n_fila = count($tablero);
    $n_col = count($tablero[0]);
    $i = 1;
    while($i < $n_fila - 1){
        $j = 0;
        while ($j < $n_col - 1){
            if (in_array($tablero[$i][$j],["A", "L"])){
                $quien_es = $tablero[$i][$j];
                if (buscarAdyacente($tablero, [$i,$j], " ") != []){
                    $primer_lugar_vacio = buscarAdyacente($tablero, [$i,$j], " ")[0];
                    print("Un " . $quien_es . " en " . $i . "," . $j . " " . "se mueve a ");
                    print_r(implode(",", $primer_lugar_vacio));
                    print("\n");
                    $tablero[$primer_lugar_vacio[0]][$primer_lugar_vacio[1]] = $quien_es;
                    $tablero[$i][$j] = " ";
                }
                else{
                     print("Un" . $quien_es . "[" . $i . "," . $j ."]" . "no se puede mover \n");
                }
            }
            $j +=1;
        }
        $i +=1;
    }
    return $tablero;
}

function faseAlimentacion($tablero){
    $n_fila = count($tablero);
    $n_col = count($tablero[0]);
    $i = 1;
    while($i < $n_fila - 1){
        $j = 0;
        while ($j < $n_col - 1){
            if ($tablero[$i][$j] == "L"){
                $quien_es = $tablero[$i][$j];
                if (buscarAdyacente($tablero, [$i,$j], "A") != []){
                    $primer_lugar_comida = buscarAdyacente($tablero, [$i,$j], "A")[0];
                    print("Un " . $quien_es . " en " . $i . "," . $j . " se come a ");
                    print(implode(",", $primer_lugar_comida));
                    print("\n");
                    $tablero[$primer_lugar_comida[0]][$primer_lugar_comida[1]] = $quien_es;
                    $tablero[$i][$j] = " ";
                }
                else{
                    print("Un " . $quien_es . " en " . $i . "," . $j . " no tiene para comer \n");
                }
            }
            $j +=1;
        }
        $i+=1;
    }
    return $tablero;
}

function faseReproduccion($tablero){
    $n_fila = count($tablero);
    $n_col = count($tablero[0]);
    $i = 1;
    while($i < $n_fila - 1){
        $j = 0;
        while ($j < $n_col - 1){
            if (in_array($tablero[$i][$j],["A", "L"])){
                $quien_es = $tablero[$i][$j];
                if (buscarAdyacente($tablero, [$i,$j], $quien_es) != []){
                    $primer_lugar_pareja = buscarAdyacente($tablero, [$i,$j], $quien_es)[0];
                    if (buscarAdyacente($tablero, [$i,$j], "") != []){
                        $primer_lugar_vacio = buscarAdyacente($tablero, [$i,$j], "")[0];
                        $tablero[$primer_lugar_vacio[0]][$primer_lugar_vacio[1]] = $quien_es;
                        print("Un " . $quien_es . " en " . $i . "," . $j . " " . "se reproduce con otro en la posicion ");
                        print_r(implode(",", $primer_lugar_pareja));
                        print("\n");
                }   
                }
                else{
                     print("Un " . $quien_es . " en " . $i . "," . $j . " no se puede reproducir \n");
                }
            }
            $j +=1;
        }
        $i +=1;
    }
    return $tablero;
}

function evolucionar($tablero){
    $tablero = faseAlimentacion($tablero);
    $tablero = faseReproduccion($tablero);
    $tablero = faseMover($tablero);
    return $tablero;
}

function evolucionarEnElTiempo($tablero,int $n){
    $i=0;
    while ($i < $n){
    print("Año " . ($i+1) . ": \n");
    $tablero = faseAlimentacion($tablero);
    $tablero = faseReproduccion($tablero);
    $tablero = faseMover($tablero);
    $i += 1;
    }
    return $tablero;
}

function contarAnimales($tablero, $animal){
    $n_fila = count($tablero);
    $n_col = count($tablero[0]);
    $contador = 0;
    $i = 1;
    while($i < $n_fila - 1){
        $j = 0;
        while ($j < $n_col - 1){
            if ($tablero[$i][$j] === $animal){
                $contador = $contador + 1;
            }
        $j +=1;
        }
        $i+=1;
    }
    return $contador;
}



function graficarMatriz($tablero){
    $i=0;
    while($i < count($tablero)){
        $j=0;
        while($j < count($tablero[0])){
            print($tablero[$i][$j]);
            $j += 1;
        }
        print(" \n");
        $i +=1;
    }
}

//graficarMatriz(crearTablero(5,5));
//print_r(misVecinos([2,3]));
//ejercicio_4(crearTablero(10,10));

$miTablero = crearTablero(10,10);
graficarMatriz($miTablero);
$miTablero = agregar($miTablero,"L",[1,2]);
$miTablero = agregar($miTablero,"A",[4,4]);
$miTablero = agregar($miTablero,"A",[3,2]);
 graficarMatriz($miTablero);
 // $miTablero = faseAlimentacion($miTablero);
 // graficarMatriz($miTablero);
 // $miTablero = faseReproduccion($miTablero);
 // graficarMatriz($miTablero);
 // $miTablero = faseMover($miTablero);
 $miTablero = evolucionarEnElTiempo($miTablero,10);
 graficarMatriz($miTablero);

?>
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
    foreach (misVecinos($coord_centro) as $k){
        if ($v === $objetivo){
            return array($vecino);
        }
    }
    return array();
}


function buscarAdyacenteAleatorio($tablero, $coord_centro, $objetivo){
    $lista = misVecinos($coord_centro);
    shuffle($lista);
    foreach ($lista as $k){
        if ($v === $objetivo){
            return array($vecino);
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

function fase_mover($tablero){
    $n_fila = count($tablero);
    $n_col = count($tablero[0]);
    $i = 1;
    $j = 0;
    while($i < $n_fila - 1){
        while ($j < $n_col - 1){
            if (in_array($tablero[$i][$j],["A", "L"])){
                $quien_es = $tablero[$i][$j];
                if (buscarAdyacente($tablero, [$i,$j], " ") != []){
                    $primer_lugar_vacio = buscarAdyacente($tablero, [$i,$j], " ")[0];
                    print("Un" . $quien_es . [$i,$j] . "se mueve a" . $primer_lugar_vacio);
                    $tablero[$primer_lugar_vacio] = $quien_es;
                    $tablero[$i][$j] = " ";
                }
                else{
                     print("Un" . $quien_es . "[" . $i . "," . $j ."]" . "no se puede mover");
                }
            }
        }
    }
    return $tablero;
}

function fase_alimentacion($tablero){
    $n_fila = count($tablero);
    $n_col = count($tablero[0]);
    $i = 1;
    $j = 0;
    while($i < $n_fila - 1){
        while ($j < $n_col - 1){
            if ($tablero[$i][$j] == "L"){
                $quien_es = $tablero[$i][$j];
                if (buscarAdyacente($tablero, [$i,$j], "A") != []){
                    $primer_lugar_comida = buscarAdyacente($tablero, [$i,$j], "A")[0];
                    print("Un" . $quien_es . [$i,$j] . "se come a" . $primer_lugar_comida);
                    $tablero[$primer_lugar_comida] = $quien_es;
                    $tablero[$i][$j] = " ";
                }
                else{
                     print("Un" . $quien_es . "[" . $i . "," . $j ."]" . "no se tiene para comer");
                }
            }
        }
    }
    return $tablero;

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
ejercicio_4(crearTablero(10,10));

?>
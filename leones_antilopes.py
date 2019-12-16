#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Mon Dec  9 10:23:17 2019

@author: diego
"""

"""
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

"""

import numpy as np
import random
import csv

def crear_tablero(filas,columnas, antilopes=[], leones=[]):
    tablero = np.repeat("M", filas*columnas).reshape(filas, columnas)
    n_fila = tablero.shape[0]
    n_col = tablero.shape[1]
    for i in range(1, n_fila - 1):
        for j in range(1, n_col - 1):
            tablero[(i,j)] = " "
    for posicion in antilopes:
        agregar(tablero, "A", posicion)
        # print("A",posicion)
    for posicion in leones:
        agregar(tablero, "L", posicion)
        # print("L",posicion)
    return tablero

def graficar(tablero):
    grafico = tablero.copy()
    n_fila = grafico.shape[0]
    n_col = grafico.shape[1]
    for i in range(0, n_fila):
        for j in range(0, n_col):
            if grafico[(i,j)] == "L":
                grafico[(i,j)] = chr(0x0001F981)
            if grafico[(i,j)] == "A":
                grafico[(i,j)] = chr(0x0001F98C)
            if grafico[(i,j)] == "M":
                grafico[(i,j)] = chr(0x000026F0)
            if grafico[(i,j)] == " ":
                grafico[(i,j)] = chr(0x0001F331)
    return grafico

def agregar(tablero, tipo, posicion):
    tablero[posicion] = tipo
    return tablero

def mis_vecinos(posicion_central): #(fila, columna)
    f = posicion_central[0] #filas
    c = posicion_central[1] #columnas
    return [(f-1,c-1), (f-1,c), (f-1,c+1), (f,c+1), (f+1, c+1), (f+1, c), (f+1,c-1), (f,c-1)]

def buscar_adyacente(tablero, coord_centro, objetivo):
    for vecino in mis_vecinos(coord_centro):
        if tablero[vecino] == objetivo:
            return [vecino]
    return []

def buscar_adyacente_aleatoria(tablero, coord_centro, objetivo):
    lista = mis_vecinos(coord_centro)
    random.shuffle(lista)
    for vecino in lista:
        if tablero[vecino] == objetivo:
            return [vecino]
    return []


def ejercicio_4(t):
    # definimos la coordenadas de nuestros personajes
    fil = [1 , 2 , 3 , 3 , 1]
    col = [3 , 1 , 1 , 3 , 2]
    tipo = [ " A " , " A " , " A " , " A " , " L " ]
    # y ahora los asignamos dentro del tablero
    for i in range ( len ( tipo ) ) :
        t[(fil[i], col[i])] = tipo[i]
    # veamos como queda :
    print(t)

def fase_mover(tablero):
    n_fila = tablero.shape [0]
    n_col = tablero.shape [1]
    for i in range (1 , n_fila - 1) :
        for j in range (1 , n_col - 1) :
            if tablero[(i,j)] in ["A", "L"]:
                quien_es = tablero[(i,j)]
                if buscar_adyacente(tablero, (i,j), " ") != []:
                    primer_lugar_vacio = buscar_adyacente(tablero, (i,j), " ")[0]
                    print("Un", quien_es, (i,j),"se mueve a", primer_lugar_vacio)
                    tablero[primer_lugar_vacio] = quien_es
                    tablero[(i,j)] = " "
                else:
                     print("Un", quien_es, (i,j),"no se puede mover")
    return tablero

def fase_alimentacion(tablero):
    n_fila = tablero.shape [0]
    n_col = tablero.shape [1]
    for i in range (1 , n_fila - 1) :
        for j in range (1 , n_col - 1) :
            quien_es = tablero[(i,j)]
            if quien_es == "L":
                if buscar_adyacente(tablero, (i,j), "A") != []:
                    primer_lugar_comida = buscar_adyacente(tablero, (i,j), "A")[0]
                    print("Un", quien_es, (i,j),"se come a", primer_lugar_comida)
                    tablero[primer_lugar_comida] = "L"
                    tablero[(i,j)] = " "
                else:
                    print("Un", quien_es, (i,j),"no tiene para comer")             
    return tablero
            

def fase_reproduccion(tablero):
    n_fila = tablero.shape [0]
    n_col = tablero.shape [1]
    for i in range (1 , n_fila - 1) :
        for j in range (1 , n_col - 1) :
            quien_es = tablero[(i,j)]
            if quien_es in ["A", "L"]:
                if buscar_adyacente(tablero, (i,j), quien_es) != []:
                    primer_lugar_pareja = buscar_adyacente(tablero, (i,j), quien_es)[0]
                    if buscar_adyacente(tablero, (i,j), " ") != []:
                        primer_lugar_vacio = buscar_adyacente(tablero, (i,j), " ")[0]
                        tablero[primer_lugar_vacio] = quien_es
                        print("Un", quien_es, (i,j),"se reproduce con", primer_lugar_pareja, "y deja el pibe en", primer_lugar_vacio)
                    else:
                        print("Un", quien_es, (i,j),"no tiene con quien reproducirse")
    return tablero
    
def evolucionar(tablero):
    fase_alimentacion(tablero)
    fase_reproduccion(tablero)
    print(graficar(tablero))
    fase_mover(tablero)
    return tablero

def evolucionar_en_el_tiempo(tablero, tiempo_limite):
    for iteracion in range(tiempo_limite):
        print("Año", iteracion+1)
        print(graficar(tablero))
        fase_alimentacion(tablero)
        fase_reproduccion(tablero)
        fase_mover(tablero)
    print(graficar(tablero))
    return tablero

def contar_animales(tablero, animal):
    n_fila = tablero.shape [0]
    n_col = tablero.shape [1]
    contador = 0
    for i in range (1 , n_fila - 1) :
        for j in range (1 , n_col - 1) :
            if tablero[(i,j)] == animal:
                contador = contador + 1
    return contador

def mezclar_celdas ( tablero ) :
    n_fila = tablero.shape [0]
    n_col = tablero.shape [1]
    celdas = []
    for i in range(1 , n_fila - 1):
        for j in range(1 , n_col - 1):
            celdas.append((i, j))
            # Ahora las mezclamos
            random.shuffle(celdas)
    return celdas

def generar_tablero_azar(filas,columnas,n_antilopes, n_leones):
    tab_azar = crear_tablero(filas, columnas)
    mezcla = mezclar_celdas(tab_azar)
    for orden in range(n_antilopes):
        tab_azar[mezcla.pop()] = "A"
    for orden in range(n_leones):
        tab_azar[mezcla.pop()] = "L"
    return tab_azar

def cuantos_de_cada(tablero):
    return [contar_animales(tablero, "A"), contar_animales(tablero, "L")]

def registrar_evolucion(tablero,tiempo):
    res = []
    for iteracion in range(tiempo):
        print("Año", iteracion+1)
        print(graficar(tablero))
        fase_alimentacion(tablero)
        fase_reproduccion(tablero)
        fase_mover(tablero)
        res.append(cuantos_de_cada(tablero))
    print(graficar(tablero))
    with open ( "predpres.csv" , "w" , newline = "" ) as csvfile :
        csv_writer = csv.writer( csvfile )
        csv_writer.writerow([ "antilopes" , "leones" ])
        csv_writer.writerows( res )
    return res

def probar_alimentacion():
    ali = crear_tablero(5, 5, [(1,3),(2,1),(3,1),(3,3)], [(1,2)])
    fase_alimentacion(ali)
    
def ejercicio_12():
    tab_hecho = crear_tablero(6, 8,[(2,2),(3,3),(4,3)],[(4,5),(2,5)])
    print(graficar(tab_hecho))
    print(graficar(evolucionar(tab_hecho)))
    print(contar_animales(tab_hecho, "A"))
    print(cuantos_de_cada(tab_hecho))

def ejercicio_13():
    tab_hecho = crear_tablero(6, 8,[(2,2),(3,3),(4,3)],[(4,5),(2,5)])
    evolucionar_en_el_tiempo(tab_hecho, 10)
    #Se extinguieron los Antilopes en el ciclo 9
    
def ejercicio_14():
    tab_14 = generar_tablero_azar(12, 12, 10, 5)
    mezclar_celdas(tab_14)
    print(tab_14)
    
newTablero = crear_tablero(6,6,[(1,3),(2,1),(3,1),(3,3)],[(1,2)])
res1 = registrar_evolucion(newTablero, 10)
# print(buscar_adyacente_aleatoria(newTablero,(2,2), "A"))
# agregar(newTablero, "A", (1,3))
# agregar(newTablero, "A", (2,1))
# agregar(newTablero, "A", (3,1))
# agregar(newTablero, "A", (3,3))
# agregar(newTablero, "L", (1,2))
# agregar(newTablero, "L", (2,3))
# print(graficar(newTablero))
# print(graficar(evolucionar(newTablero)))
# res = ((evolucionar_en_el_tiempo(newTablero, 4)))

# print(buscar_adyacente(newTablero, (1,1), "A"))

# print(graficar(newTablero))
# print(graficar(fase_mover(newTablero)))
# print(graficar(fase_alimentacion(fase_mover(newTablero))))
# print(graficar(fase_reproduccion(fase_alimentacion(fase_mover(newTablero)))))
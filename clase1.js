var x=2; //se escapa del bloque
let y=3; //funcion solo dentro del bloque
const z=4; //constante no se puede cambiar

//operador ternario
let edad = 25;
let msj = edad>= 18 ? "mayor de edad" : "menor de edad";
//console.log(msj);

//1-Crear una función declarada dividir que tome dos números como argumento y devuelva la división.
function dividir(a,b){
    return a/b;
}
//console.log(dividir(20,4))
//2-Crea una función expresada llamada saludar que tome un nombre como argumento y devuelva un mensaje de saludo
const saludar=function(nombre){
    return "Hola "+nombre+" bienvenido.";
}
//console.log(saludar("Jorge"))
//3-Crea una función declarada llamada calcularArea que tome el largo y el ancho de un rectángulo como argumentos y devuelva su área.
function calcularArea(largo,ancho){
    return largo * ancho;
}
//console.log(calcularArea(2,4));
//4-Crea una función expresada llamada esPar que tome un número como argumento y devuelva true si es par y false en caso contrario.
const esPar = function(num){
    return num%2 == 0;
}
//console.log(esPar(5));
//5-Crea una función declarada llamada encontrarMayor que tome dos números como argumentos y devuelva el mayor de los dos.
function encontrarMayor(n1,n2){
    if(n1>n2){
        return n1
    }
    else{
        return n2
    }
}
//console.log(encontrarMayor(44,32));
function encontrarMayor1(n1, n2) {
    return n1 > n2 ? n1 : n2;
}
console.log(encontrarMayor1(44, 32));
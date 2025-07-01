package controller;

import java.util.List;

import modelo.DataBase;
import modelo.DataBaseModelo; 
import modelo.Municipi;
import modelo.MunicipiModelo;
import modelo.Partit;
import modelo.PartitModelo;

/**
 * Una clase en donde hice pruebas para verificar cosas
 */
public class Main {
    public static void main(String[] args) {
        // Procesar el CSV
    	DataBase db1 = new DataBase();
        CsvController controlador = new CsvController();
        controlador.leerYGuardarCSV("src/file/Eleccions4.csv");
        db1.desconectar();
        //controlador.mostrarResultados();
        // Verificar los datos en la base de datos 
        
        
        System.out.println("Metodo verificar datos");
        DataBaseModelo db = new DataBaseModelo();
        db.verificarDatos();  // Verificamos los datos insertados
        
        db1.desconectar();
        
        /*MunicipiModelo municipi = new MunicipiModelo();
        List<Municipi> municipios = municipi.obtenerTodosLosMunicipis();

        for (Municipi m : municipios) {
            System.out.println("Municipio: " + m.getNom());
        }
    	
        PartitModelo partitModelo = new PartitModelo();
        List<Partit> partidos = partitModelo.obtenerTodosLosPartits();

        for (Partit p : partidos) {
            System.out.println("Partido: " + p.getSigles());
        }
*/
    	
    }
}

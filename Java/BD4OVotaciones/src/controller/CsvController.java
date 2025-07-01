package controller;

import modelo.Municipi;
import modelo.MunicipiModelo;
import modelo.Partit;
import modelo.PartitModelo;
import modelo.Resultat;
import modelo.ResultatModelo;
import modelo.DataBase;
import modelo.DataBaseModelo;

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

/**
 * Clase controladora encargada de manejar el csv y las isnercciones a la base de datos con metodos de los modelos
 */
public class CsvController {

    private MunicipiModelo municipiModelo;
    private PartitModelo partitModelo;
    private ResultatModelo resultatModelo;
    private List<String> elementosNoInsertados;
    private List<String> actualizaciones;

    public CsvController() {
        municipiModelo = new MunicipiModelo();
        partitModelo = new PartitModelo();
        resultatModelo = new ResultatModelo();
        elementosNoInsertados = new ArrayList<>();
        actualizaciones = new ArrayList<>();
    }

    /**
     * Metodo que se encarga de procesar el csv y de meterlo a la base de datos
     * @param archivo
     */
    public void leerYGuardarCSV(String archivo) {
    	//Aqui se limpian las listas por cada nuevo archivo csv
        elementosNoInsertados.clear();
        actualizaciones.clear();
        Pattern pattern = Pattern.compile("\"([^\"]*)\"|([^,]+)");  
        
        int municipiosInsertados = 0;
        int partidosInsertados = 0;
        int resultadosInsertados = 0;
        int resultadosActualizados = 0;
        int municipiosExistentes = 0;
        int partidosExistentes = 0;
       

        try (BufferedReader br = new BufferedReader(new FileReader(archivo))) {
            String linea;
            boolean primeraLinea = true;

            while ((linea = br.readLine()) != null) {
                if (primeraLinea) {
                    primeraLinea = false;
                    continue; 
                }

                List<String> valores = new ArrayList<>();
                Matcher matcher = pattern.matcher(linea);

                while (matcher.find()) {
                    if (matcher.group(1) != null) {
                        valores.add(matcher.group(1));  
                    } else {
                        valores.add(matcher.group(2));  
                    }
                }

                if (valores.size() < 7) {
                    elementosNoInsertados.add("Línea inválida (menos de 7 columnas): " + linea);
                    continue; 
                }

                try {
                    String codiEns = valores.get(0).trim();
                    String nomMunicipi = valores.get(1).trim();
                    int anyEleccio = Integer.parseInt(valores.get(2).trim());
                    String siglesCandidatura = valores.get(3).trim();
                    int vots = Integer.parseInt(valores.get(4).trim());
                    double percentatgeVots = Double.parseDouble(valores.get(5).trim().replace(",", "."));
                    int regidors = Integer.parseInt(valores.get(6).trim());

                    Municipi municipi = municipiModelo.obtenerMunicipi(codiEns);
                    if (municipi == null) {
                        municipi = new Municipi(codiEns, nomMunicipi);
                        municipiModelo.insertarMunicipi(municipi);
                        municipiosInsertados++;
                    } else {
                        municipiosExistentes++; 
                    }
                    
                    Partit partit = partitModelo.obtenerPartit(siglesCandidatura);
                    if (partit == null) {
                        partit = new Partit(siglesCandidatura);
                        partitModelo.insertarPartit(partit);
                        partidosInsertados++;
                    } else {
                        partidosExistentes++; 
                    }

                    Resultat nuevoResultado = new Resultat(municipi, partit, anyEleccio, vots, percentatgeVots, regidors);
                    
                    Resultat resultadoExistente = resultatModelo.obtenerResultadoExistente(nuevoResultado);
                    
                    if (resultadoExistente != null) {
                        if (resultadoExistente.getVots() != vots ||
                            resultadoExistente.getPercentatgeVots() != percentatgeVots ||
                            resultadoExistente.getRegidors() != regidors) {
                            
                            resultatModelo.actualizarResultado(resultadoExistente, nuevoResultado);
                            resultadosActualizados++;
                            
                            this.actualizaciones.add(String.format(
                                "%s en %s (%d) - Votos: %d→%d, Porcentaje: %.2f→%.2f, Regidores: %d→%d",
                                siglesCandidatura, nomMunicipi, anyEleccio,
                                resultadoExistente.getVots(), vots,
                                resultadoExistente.getPercentatgeVots(), percentatgeVots,
                                resultadoExistente.getRegidors(), regidors
                            ));
                        }
                    } else {
                        resultatModelo.insertarResultat(nuevoResultado);
                        resultadosInsertados++;
                    }

                } catch (NumberFormatException e) {
                    elementosNoInsertados.add("Error de formato en la línea: " + linea);
                }
            }

            System.out.println("\n--- Resumen de inserciones ---");
            System.out.println("Municipios insertados: " + municipiosInsertados);
            System.out.println("Municipios ya existentes: " + municipiosExistentes);
            System.out.println("Partidos insertados: " + partidosInsertados);
            System.out.println("Partidos ya existentes: " + partidosExistentes);
            System.out.println("Resultados insertados: " + resultadosInsertados);
            System.out.println("Resultados actualizados: " + resultadosActualizados);
            System.out.println("CSV procesado y datos actualizados correctamente.");

            if (!elementosNoInsertados.isEmpty()) {
                System.out.println("\n--- Elementos NO procesados ---");
                elementosNoInsertados.forEach(System.out::println);
            }

            if (!actualizaciones.isEmpty()) {
                System.out.println("\n--- Detalle de actualizaciones ---");
                actualizaciones.forEach(System.out::println);
            }

        } catch (IOException e) {
            elementosNoInsertados.add("Error al leer el archivo CSV: " + archivo);
        }
    }

    /**
     * Una lista de los objetos que no se pudieron insertar
     * @return una lista
     */
    public List<String> getElementosNoInsertados() {
        return elementosNoInsertados;
    }

    /**
     * Obtiene una lista con los elementos que fueron actualizados
     * @return una lista
     */
    public List<String> getActualizaciones() {
        return actualizaciones;
    }

    /**
     * Mostrar los resultados que hay en la base de datos
     */
    public void mostrarResultados() {
        DataBaseModelo db = new DataBaseModelo();  
        db.imprimirResultados();
    }
}

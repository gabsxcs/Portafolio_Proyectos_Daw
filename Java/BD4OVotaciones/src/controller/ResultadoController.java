package controller;

import modelo.Resultat;
import modelo.ResultatModelo;
import com.db4o.ObjectSet;
import java.util.ArrayList;
import java.util.List;
/**
 * Clase controller que se encarga de usar los metodos del modelo
 */
public class ResultadoController {

    private ResultatModelo resultatModelo;

    public ResultadoController() {
        this.resultatModelo = new ResultatModelo();
    }

    /**
	 * Insertar un resultado a la base de datos
	 * @param resultat
	 */
    public void insertarResultado(Resultat resultat) {
        resultatModelo.insertarResultat(resultat);
    }

	/**
	 * Verifica si existe un resultado o no
	 * @param resultat
	 * @return true o false
	 */
    public boolean existeResultado(Resultat resultat) {
        return resultatModelo.existeResultado(resultat);
    }

    /**
     * Obtiene una lista de todos los resultados
     * @return una lista
     */
    public List<Resultat> obtenerTodosLosResultados() {
        ObjectSet<Resultat> resultados = resultatModelo.obtenerTodosLosResultats();
        return new ArrayList<>(resultados);
    }

    /**
     * Obtiene una lista de los resultados de un partido
     * @param sigles
     * @return
     */
    public List<Resultat> obtenerResultadosPorPartido(String sigles) {
        ObjectSet<Resultat> resultados = resultatModelo.obtenerResultatsPorPartit(sigles);
        return new ArrayList<>(resultados);
    }

    /**
     * Obtiene una lista de los resultados dde un municipio
     * @param codiMunicipi
     * @return
     */
    public List<Resultat> obtenerResultadosPorMunicipio(String codiMunicipi) {
        return resultatModelo.obtenerResultadosPorMunicipio(codiMunicipi);
    }

    /**
     * Obtiene una lista de las siglas de los partidos
     * @return una lista
     */
    public List<String> obtenerSiglasPartidos() {
        return resultatModelo.obtenerSiglasPartidos();
    }

    /**
     * Obtiene los nombres de los municipios
     * @return una lista
     */
    public List<String> obtenerNombresMunicipios() {
        return resultatModelo.obtenerNombresMunicipios();
    }
    
    /**
     * Obtiene el codigo de un municipio por su nombre
     * @param nombreMunicipio
     * @return
     */
    public String obtenerCodigoMunicipioPorNombre(String nombreMunicipio) {
        return resultatModelo.obtenerCodigoMunicipioPorNombre(nombreMunicipio);
    }
    
    /**
     * ELimina un resultado
     * @param resultat
     */
    public void eliminarResultado(Resultat resultat) {
        resultatModelo.eliminarResultat(resultat);
    }
    
    /**
     * Obtiene un resultado por su municipio y partido
     * @param codiMunicipi
     * @param siglesPartido
     * @return
     */
    public List<Resultat> obtenerResultadosPorMunicipioYPartido(String codiMunicipi, String siglesPartido) {
        return resultatModelo.obtenerResultadosPorMunicipioYPartido(codiMunicipi, siglesPartido);
    }
    
    
}

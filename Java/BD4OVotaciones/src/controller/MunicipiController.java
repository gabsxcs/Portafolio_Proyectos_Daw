package controller;

import modelo.Municipi;
import modelo.MunicipiModelo;
import java.util.List;

/**
 * Clase controller que se encarga de usar los metodos del modelo
 */
public class MunicipiController {

    private MunicipiModelo municipiModelo;

    public MunicipiController() {
        this.municipiModelo = new MunicipiModelo();
    }

    /**
     * Insertar un nuevo municipio
     * @param codiEns
     * @param nom
     */
    public void insertarMunicipi(String codiEns, String nom) {
        Municipi municipi = new Municipi(codiEns, nom);
        municipiModelo.insertarMunicipi(municipi);
    }

    /**
     * Obtener un municipio por código
     * @param codiEns
     * @return un municipi
     */
    public Municipi obtenerMunicipi(String codiEns) {
        return municipiModelo.obtenerMunicipi(codiEns);
    }

    /**
     * Verificar si un municipio existe
     * @param codiEns
     * @return true or false
     */
    public boolean existeMunicipi(String codiEns) {
        return municipiModelo.existeMunicipi(codiEns);
    }

    /**
     * Eliminar un municipio
     * @param codiEns
     */
    public void eliminarMunicipi(String codiEns) {
        municipiModelo.eliminarMunicipi(codiEns);
    }

    /**
     * Obtener todos los municipios
     * @return una lista
     */
    public List<Municipi> obtenerTodosLosMunicipis() {
        return municipiModelo.obtenerTodosLosMunicipis();
    }

    /**
     * Actualizar información de un municipio
     * @param municipiActualizado
     */
    public void actualizarMunicipi(Municipi municipiActualizado) {
        municipiModelo.actualizarMunicipi(municipiActualizado);
    }

    /**
     * Obtener cantidad de municipios en la base de datos
     * @return la cantidad
     */
    public int contarMunicipis() {
        return municipiModelo.contarMunicipis();
    }
}

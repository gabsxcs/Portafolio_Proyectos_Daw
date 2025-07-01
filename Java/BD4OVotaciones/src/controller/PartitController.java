package controller;

import modelo.Partit;
import modelo.PartitModelo;
import java.util.List;
/**
* Clase controller que se encarga de usar los metodos del modelo
*/
public class PartitController {

    private PartitModelo partitModelo;

    public PartitController() {
        this.partitModelo = new PartitModelo();
    }

    /**
     * Insertar un nuevo partido
     * @param sigles
     */
    public void insertarPartit(String sigles) {
        Partit partit = new Partit(sigles);
        partitModelo.insertarPartit(partit);
    }

    /**
     * Obtener un partido por siglas
     * @param sigles
     * @return un partido
     */
    public Partit obtenerPartit(String sigles) {
        return partitModelo.obtenerPartit(sigles);
    }

    /**
     * Verificar si un partido existe
     * @param sigles
     * @return true or false
     */
    public boolean existePartit(String sigles) {
        return partitModelo.existePartit(sigles);
    }

    /**
     * Eliminar un partido
     * @param sigles
     */
    public void eliminarPartit(String sigles) {
        partitModelo.eliminarPartit(sigles);
    }

    /**
     * Obtener todos los partidos
     * @return una lista
     */
    public List<Partit> obtenerTodosLosPartits() {
        return partitModelo.obtenerTodosLosPartits();
    }

    /**
     * Actualizar información de un partido
     * @param partitActualizado
     */
    public void actualizarPartit(Partit partitActualizado) {
        partitModelo.actualizarPartit(partitActualizado);
    }

    /**
     * Obtener cantidad de partidos en la base de datos
     * @return la cantidad
     */
    public int contarPartits() {
        return partitModelo.contarPartits();
    }
}

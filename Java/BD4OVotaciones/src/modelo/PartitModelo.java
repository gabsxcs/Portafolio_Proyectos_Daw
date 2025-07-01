package modelo;

import java.util.ArrayList;
import java.util.List;
import com.db4o.ObjectSet;

/**
 * Clase model con metodos que se relacionan con el objeto de negocio Partido
 */
public class PartitModelo extends DataBase {

    /**
     * Inserta un partido si no existe
     * @param partit
     */
	public void insertarPartit(Partit partit) {
	    ObjectSet<Partit> result = db.queryByExample(new Partit(partit.getSigles()));
	    System.out.println("Consultando partido con sigles: " + partit.getSigles());
	    if (result.isEmpty()) { 
	        db.store(partit);
	       // System.out.println("Partido insertado: " + partit.getSigles());
	    } else {
	        System.out.println("Partido ya existe: " + partit.getSigles());
	    }
	}


    /**
     * Obtiene un partido por siglas
     * @param sigles
     * @return partido
     */
	public Partit obtenerPartit(String sigles) {
	    ObjectSet<Partit> result = db.queryByExample(new Partit(sigles));
	    if (!result.isEmpty()) {
	        return result.next();
	    }
	    return null; 
	}

	/**
	 * Verifica si un partido existe
	 * @param sigles
	 * @return tru o false
	 */
    public boolean existePartit(String sigles) {
        return obtenerPartit(sigles) != null;
    }

    /**
     * Elimina un partido 
     * @param sigles
     */
    public void eliminarPartit(String sigles) {
        Partit partit = obtenerPartit(sigles);
        if (partit != null) {
            db.delete(partit);
            db.commit();
        }
    }

   /**
    *  Obtiene una lista de todos los partidos
    * @return una lista
    */
    public List<Partit> obtenerTodosLosPartits() {
        List<Partit> listaPartits = new ArrayList<>();
        ObjectSet<Partit> result = db.query(Partit.class);
        while (result.hasNext()) {
            listaPartits.add(result.next());
        }
        return listaPartits;
    }

   /**
    *  Actualiza un partido existente
    * @param partitActualizado
    */
    public void actualizarPartit(Partit partitActualizado) {
        Partit partitExistente = obtenerPartit(partitActualizado.getSigles());
        if (partitExistente != null) {
            db.delete(partitExistente);
            db.store(partitActualizado);
            db.commit();
        }
    }

    /**
     * Cuenta la cantidad de partidos en la base de datos
     * @return la cantidad
     */
    public int contarPartits() {
        return db.query(Partit.class).size();
    }
}

package modelo;

import java.util.ArrayList;
import java.util.List;

import com.db4o.ObjectSet;
/**
 * Una clase con metodos relacionado con el objeto de negocio Municipi
 */
public class MunicipiModelo extends DataBase {

	public void insertarMunicipi(Municipi municipi) {
	    ObjectSet<Municipi> result = db.queryByExample(new Municipi(municipi.getCodiEns(), null));
	    
	    if (result.isEmpty()) { 
	        db.store(municipi);
	       // System.out.println("Municipio insertado: " + municipi.getNom());
	        
	    } else {
	       // System.out.println("Municipio ya existe: " + municipi.getNom());
	        for (Municipi m : result) {
	            System.out.println("Ya existe: " + m.getNom());
	        }
	        
	    }
	}

	/**
	 * Obtiene un municipio a partir del codigo ens
	 * @param codiEns
	 * @return
	 */
	public Municipi obtenerMunicipi(String codiEns) {
	    ObjectSet<Municipi> result = db.queryByExample(new Municipi(codiEns, null));
	    if (!result.isEmpty()) {
	        return result.next(); 
	    }
	    return null; 
	}

    /**
     *Verifica si un municipio existe
     * @param codiEns
     */
    public boolean existeMunicipi(String codiEns) {
        return obtenerMunicipi(codiEns) != null;
    }

    /**
     * Elimina un municipio a partir del codigo ens
     * @param codiEns
     */
    public void eliminarMunicipi(String codiEns) {
        Municipi municipi = obtenerMunicipi(codiEns);
        if (municipi != null) {
            db.delete(municipi);
            db.commit();
        }
    }

    /**
     * Obtiene una lista con todos los municipios
     * @return una lista de todos los municipios
     */
    public List<Municipi> obtenerTodosLosMunicipis() {
        List<Municipi> listaMunicipis = new ArrayList<>();
        ObjectSet<Municipi> result = db.query(Municipi.class);
        while (result.hasNext()) {
            listaMunicipis.add(result.next());
        }
        return listaMunicipis;
    }

    /**
     * Actualiza un municipio existente
     * @param municipiActualizado
     */
    public void actualizarMunicipi(Municipi municipiActualizado) {
        Municipi municipiExistente = obtenerMunicipi(municipiActualizado.getCodiEns());
        if (municipiExistente != null) {
            db.delete(municipiExistente);
            db.store(municipiActualizado);
            db.commit();
        }
    }

    /**
     * Cuenta la cantidad de municipios en la base de datos
     * @return la cantidad de municipios
     */
    public int contarMunicipis() {
        return db.query(Municipi.class).size();
    }
}

package modelo;

import java.util.ArrayList;
import java.util.List;

import com.db4o.ObjectSet;
import com.db4o.query.Query;

/**
 * Clase con metodos relacionados con el objeto de negocio resultado
 */
public class ResultatModelo extends DataBase {

	/**
	 * Metodo que antes de insertar un resultado a la base de datos, verifica que no exista.
	 * @param resultat
	 */
	public void insertarResultat(Resultat resultat) {
        if (existeResultado(resultat)) {
            System.out.println("El resultado ya existe en la base de datos. No se ha insertado.");
        } else {
            db.store(resultat);
            db.commit();
            //System.out.println("Resultado insertado correctamente: " + resultat);
        }
    }
	
	/**
	 * Verifica si existe un resultado o no
	 * @param resultat
	 * @return true o false
	 */
    public boolean existeResultado(Resultat resultat) {
        ObjectSet<Resultat> resultados = db.queryByExample(resultat);
        return !resultados.isEmpty();
    }
    
    /**
     * Obtener resultados por municpio
     * @param codiEns
     * @return una lista
     */
    public ObjectSet<Resultat> obtenerResultatsPorMunicipi(String codiEns) {
        return db.queryByExample(new Resultat(new Municipi(codiEns, null), null, 0, 0, 0, 0));
    }

    /**
     * Obtiene resultados por partido
     * @param sigles
     * @return
     */
    public ObjectSet<Resultat> obtenerResultatsPorPartit(String sigles) {
        return db.queryByExample(new Resultat(null, new Partit(sigles), 0, 0, 0, 0));
    }

    /**
     * Obtiene todos los resultados existentes
     * @return
     */
    public ObjectSet<Resultat> obtenerTodosLosResultats() {
        return db.query(Resultat.class);
    }
    
    /**
     * Obtiene resultados con el codigo del municipio
     * @param codiMunicipi
     * @return una lista
     */
    public List<Resultat> obtenerResultadosPorMunicipio(String codiMunicipi) {
        List<Resultat> resultados = new ArrayList<>();
        
        try {
            Query query = db.query();
            query.constrain(Resultat.class);
            query.descend("municipi").descend("codiEns").constrain(codiMunicipi);
            
            ObjectSet<Resultat> resultSet = query.execute();
            
            while (resultSet.hasNext()) {
                resultados.add(resultSet.next());
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        
        return resultados;
    }
    
    /**
     * Obtiene una lista con los nombres de todos los municipios
     * @return una lista
     */
    public List<String> obtenerNombresMunicipios() {
        List<String> nombres = new ArrayList<>();
        try {
            Query query = db.query();
            query.constrain(Resultat.class);
            query.descend("municipi").descend("nom").constrain(null).not();
            
            ObjectSet<Resultat> resultSet = query.execute();
            while (resultSet.hasNext()) {
                String nombre = resultSet.next().getMunicipi().getNom();
                if (!nombres.contains(nombre)) {
                    nombres.add(nombre);
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        return nombres;
    }
    
    /**
     * Obtiene el codigo ens del nombre del municipio introducido
     * @param nombreMunicipio
     * @return
     */
    public String obtenerCodigoMunicipioPorNombre(String nombreMunicipio) {
        try {
            Query query = db.query();
            query.constrain(Resultat.class);
            query.descend("municipi").descend("nom").constrain(nombreMunicipio);
            
            ObjectSet<Resultat> resultSet = query.execute();
            if (resultSet.hasNext()) {
                return resultSet.next().getMunicipi().getCodiEns();
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        return null;
    }
    
    /**
     * Obtiene los partidos
     * @return una lista
     */
    public List<String> obtenerSiglasPartidos() {
        List<String> siglas = new ArrayList<>();
        try {
            Query query = db.query();
            query.constrain(Resultat.class);
            query.descend("partit").descend("sigles").constrain(null).not();
            
            ObjectSet<Resultat> resultSet = query.execute();
            while (resultSet.hasNext()) {
                String sigla = resultSet.next().getPartit().getSigles();
                if (!siglas.contains(sigla)) {
                    siglas.add(sigla);
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        return siglas;
    }
    
    /**
     * Obtiene una lista de resultados que concuerde con el partido
     * @param siglesPartido
     * @return una lista
     */
    public List<Resultat> obtenerResultadosPorPartido(String siglesPartido) {
        List<Resultat> resultados = new ArrayList<>();
        try {
            Query query = db.query();
            query.constrain(Resultat.class);
            query.descend("partit").descend("sigles").constrain(siglesPartido);
            
            ObjectSet<Resultat> resultSet = query.execute();
            while (resultSet.hasNext()) {
                resultados.add(resultSet.next());
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        return resultados;
    }

    /**
     * Obtiene una lista de resultados que conicidan con el municipio y el partido
     * @param codiMunicipi
     * @param siglesPartido
     * @return una lista
     */
    public List<Resultat> obtenerResultadosPorMunicipioYPartido(String codiMunicipi, String siglesPartido) {
        List<Resultat> resultados = new ArrayList<>();
        try {
            Query query = db.query();
            query.constrain(Resultat.class);
            
            query.descend("municipi").descend("codiEns").constrain(codiMunicipi);
            
            query.descend("partit").descend("sigles").constrain(siglesPartido);
            
            query.descend("anyEleccio").orderDescending();
            
            ObjectSet<Resultat> resultSet = query.execute();
            while (resultSet.hasNext()) {
                resultados.add(resultSet.next());
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        return resultados;
    }
    
    /**
     * Elimina un resultado
     * @param resultat
     */
    public void eliminarResultat(Resultat resultat) {
        db.delete(resultat);
        db.commit();
    }
    
    /**
     * Obtiene un resultado existente con los mismos valores de año, municipio y partido
     * @param resultat
     */
    public Resultat obtenerResultadoExistente(Resultat resultat) {
        Query query = db.query();
        query.constrain(Resultat.class);
        query.descend("municipi").descend("codiEns").constrain(resultat.getMunicipi().getCodiEns());
        query.descend("partit").descend("sigles").constrain(resultat.getPartit().getSigles());
        query.descend("anyEleccio").constrain(resultat.getAnyEleccio());
        
        ObjectSet<Resultat> resultSet = query.execute();
        return resultSet.hasNext() ? resultSet.next() : null;
    }

    /**
     * Actualiza un resultado existente con nuevos valores
     * @param existente
     * @param nuevosDatos
     */
    public void actualizarResultado(Resultat existente, Resultat nuevosDatos) {
        existente.setVots(nuevosDatos.getVots());
        existente.setPercentatgeVots(nuevosDatos.getPercentatgeVots());
        existente.setRegidors(nuevosDatos.getRegidors());
        db.store(existente);
        db.commit();
    }
}

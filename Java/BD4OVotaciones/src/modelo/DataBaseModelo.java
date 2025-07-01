package modelo;

import com.db4o.ObjectSet;
/**
 * Esta es una clase Modelo de database, los metodos que están aqui no se usan para la entrea final, solo los use al principio para hacer pruebas desde un main
 */
public class DataBaseModelo {

	private DataBase db;
	
	public DataBaseModelo() {
        this.db = new DataBase(); 
    }
	/**
	 * Metodo para mostrar por pantalla una lista de los datos que se han introducido
	 */
	public void verificarDatos() {
		
		ObjectSet<Municipi> municipis = db.getDb().query(Municipi.class);
		System.out.println("Municipios en la base de datos: " + municipis.size());
		for (Municipi m : municipis) {
		    System.out.println("Municipio: " + m.getNom());
		}

		ObjectSet<Partit> partits = db.getDb().query(Partit.class);
		System.out.println("Partidos en la base de datos: " + partits.size());
		for (Partit p : partits) {
		    System.out.println("Partido: " + p.getSigles());
		}

		ObjectSet<Resultat> resultats = db.getDb().query(Resultat.class);
		System.out.println("Resultados en la base de datos: " + resultats.size());
		for (Resultat r : resultats) {
		    System.out.println("Resultado: " + r.getMunicipi().getNom() + " - " + r.getPartit().getSigles());
		}
		System.out.println("Total resultados" + resultats.size());

    }
	/**
	 * Moetodo que muestra por pantalla los resultados con el formato del csv
	 */
	public void imprimirResultados() {
        System.out.println("CODI_ENS,MUNICIPI,ANY_ELECCIO,SIGLES_CANDIDATURA,VOTS,% VOTS,REGIDORS");

        ObjectSet<Resultat> resultats = db.getDb().query(Resultat.class);

        if (resultats.isEmpty()) {
            System.out.println("No hay resultados en la base de datos.");
        } else {
            for (Resultat r : resultats) {
                String codiEns = r.getMunicipi().getCodiEns();  
                String municipi = r.getMunicipi().getNom(); 
                int anyEleccio = r.getAnyEleccio();  
                String siglesCandidatura = r.getPartit().getSigles(); 
                int vots = r.getVots();  
                double percentatgeVots = r.getPercentatgeVots(); 
                int regidors = r.getRegidors();  
                
                System.out.printf("%s,%s,%d,%s,%d,%.2f,%d%n", codiEns, municipi, anyEleccio, siglesCandidatura, vots, percentatgeVots, regidors);
            }
        }
    }
}

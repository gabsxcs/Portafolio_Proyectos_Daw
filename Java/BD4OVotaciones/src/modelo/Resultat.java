package modelo;

/**
 * Clase del objeto de negocio Resultat, que tiene un atributo que es un objeto de Municipio, un atributo que es un objeto de Partido, el año de eleccion, cantidad de votos,
 * porcentaje de votos  y regidores
 */
public class Resultat {
    private Municipi municipi;
    private Partit partit;
    private int anyEleccio;
    private int vots;
    private double percentatgeVots;
    private int regidors;

    public Resultat(Municipi municipi, Partit partit, int anyEleccio, int vots, double percentatgeVots, int regidors) {
        this.municipi = municipi;
        this.partit = partit;
        this.anyEleccio = anyEleccio;
        this.vots = vots;
        this.percentatgeVots = percentatgeVots;
        this.regidors = regidors;
    }

    public Municipi getMunicipi() {
        return municipi;
    }

    public Partit getPartit() {
        return partit;
    }

    public int getAnyEleccio() {
        return anyEleccio;
    }

    public int getVots() {
        return vots;
    }

    public double getPercentatgeVots() {
        return percentatgeVots;
    }

    public int getRegidors() {
        return regidors;
    }
    
 
    public void setVots(int vots) {
		this.vots = vots;
	}

	public void setPercentatgeVots(double percentatgeVots) {
		this.percentatgeVots = percentatgeVots;
	}

	public void setRegidors(int regidors) {
		this.regidors = regidors;
	}

	@Override
    public String toString() {
        return "Resultado para el municipio '" + municipi.getNom() + "' (" + municipi.getCodiEns() + ")" +
                " en " + anyEleccio + ":\n" +
                "Partido: " + partit.getSigles() + "\n" +
                "Votos: " + vots + " (" + percentatgeVots + "% de los votos)" +
                " -> Regidores obtenidos: " + regidors;
    }
}

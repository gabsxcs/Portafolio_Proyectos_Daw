package modelo;

/**
 * Clase objeto de negocio Partido con su atributo de sigles
 */
public class Partit {
    private String sigles;

    public Partit(String sigles) {
        this.sigles = sigles;
    }

    public String getSigles() {
        return sigles;
    }

    public void setSigles(String sigles) {
        this.sigles = sigles;
    }

    @Override
    public String toString() {
        return "Partit{" + "sigles='" + sigles + '\'' + '}';
    }
}

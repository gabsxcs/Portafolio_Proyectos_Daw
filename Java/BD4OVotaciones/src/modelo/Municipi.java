package modelo;
/**
 * Clase del Objeto de neogcio de Municipi, quien tiene de atributo su codigo y su nombre
 */
public class Municipi {
    private String codiEns;
    private String nom;

    public Municipi(String codiEns, String nom) {
        this.codiEns = codiEns;
        this.nom = nom;
    }

    public String getCodiEns() {
        return codiEns;
    }

    public void setCodiEns(String codiEns) {
        this.codiEns = codiEns;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    @Override
    public String toString() {
        return "Municipi{" + "codiEns='" + codiEns + '\'' + ", nom='" + nom + '\'' + '}';
    }
}

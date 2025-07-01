package modelo;

import com.db4o.Db4oEmbedded;
import com.db4o.ObjectContainer;
import com.db4o.ObjectSet;
/**
 * Clae database que es la que se encarga de hacer la conexion con la base de datos
 */
public class DataBase {
    protected static ObjectContainer db;

    public DataBase() {
        if (db == null) {
            this.conectar();
        }
    }

    private void conectar() {
        this.db = Db4oEmbedded.openFile(Db4oEmbedded.newConfiguration(), "src/baseDatos/votaciones.db4o");
        System.out.println("Base de datos conectada.");
    }

    public void desconectar() {
        if (db != null) {
            db.close();
            db = null;
            System.out.println("Base de datos desconectada.");
        }
    }

    public ObjectContainer getDb() {
        return db;
    }

	

}

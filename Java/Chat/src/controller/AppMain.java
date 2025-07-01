package controller;

import java.sql.Connection;
import java.sql.SQLException;

import modelo.DBConexion;

/**
 * Esta es una clase de prueba para verificar que la conexion y desconexion a la base de datos se hace correctamente
 */
public class AppMain {

	public static void main(String[] args) {
		// TODO Auto-generated method stub

		
		DBConexion dbConexion = null;

        try {
            dbConexion = new DBConexion();
            Connection connection = dbConexion.getConnexioBD();

            if (connection != null) {
                System.out.println("La conexión se ha establecido correctamente.");
            }

        } catch (SQLException | ClassNotFoundException e) {
            e.printStackTrace();
        } finally {
            if (dbConexion != null) {
                dbConexion.closeConexionBD();
            }
        }
		
	}

}

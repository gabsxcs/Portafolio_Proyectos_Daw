package modelo;

import java.sql.*;

/**
 * Esta clase se encarga de gestionar la conexion a la base de datos.
 * La he puesto en la carpeta de modelo porque es la que se encara de gestionar la logica de la conexion
 */
public class DBConexion {

	private Connection connexioBD = null;
    private String servidor = "jdbc:mysql://192.168.1.144:3306/";
    private String bbdd = "chat";
    private String user = "appuser";
    private String password = "TiC.123456";
	
    public DBConexion() throws SQLException, ClassNotFoundException {
    	
    	try {
			if (this.connexioBD == null) {
				Class.forName("com.mysql.cj.jdbc.Driver");
				this.connexioBD = DriverManager.getConnection(this.servidor+this.bbdd, this.user, this.password);				
			}
		} catch (Exception e){
			throw e;			
		} 
    }

	public Connection getConnexioBD() {
		return connexioBD;
	}
	
	public void closeConexionBD() {
        if (this.connexioBD != null) {
            try {
                this.connexioBD.close();
                System.out.println("Conexión cerrada correctamente");
            } catch (SQLException e) {
                e.printStackTrace();
            }
        }
    }
	
}

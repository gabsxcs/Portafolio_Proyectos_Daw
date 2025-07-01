package modelo;

import java.sql.*;
import java.util.ArrayList;
import java.util.List;

/**
 * Esta clase se encarga de crear métodos, a través de los procedures en la base de datos, que gestionan las funcionalidades que se pueden hacer en el chat
 * Ademas usa una instancia de DBConexion para interactuar con la base de datos
 * La he puesto en el modelo porque se encarga de la lógica relacionada con la interacción con la base de datos.
 */
public class ChatModel {

    private DBConexion dbConexion;

    public ChatModel() throws SQLException, ClassNotFoundException {
        this.dbConexion = new DBConexion();
    }

    /**
     * Metodo que hace la conexion a la base de datos con el "nick" del usuario,  y si está conectado, hace una desconexión antes de conectarlo
     * @param nick - Nombre de usuario
     * @return error en caso de que algo haya fallado
     */
    public String connect(String nick) {
        try {
           //Verifica si el usuario si ya está conectado, porque si ya está conectado entonces lo desconecta
            try (CallableStatement stmtDisconnect = dbConexion.getConnexioBD().prepareCall("{call disconnect()}")) {
                stmtDisconnect.execute();
            } catch (SQLException e) {
                
            }

            // Aca hace la conexion
            try (CallableStatement stmt = dbConexion.getConnexioBD().prepareCall("{call connect(?)}")) {
                stmt.setString(1, nick);
                stmt.execute();
                return "Conectado con éxito como: " + nick;
            }
        } catch (SQLException e) {
            return "Error: " + e.getMessage();
        }
    }


    /**
     * Método que envía un mensaje al chat.
     *
     * @param mensaje El mensaje a enviar.
     * @return Un mensaje que indica si el mensaje fue enviado correctamente o si hubo un error.
     */
    public String sendMessage(String mensaje) {
        try (CallableStatement stmt = dbConexion.getConnexioBD().prepareCall("{call send(?)}")) {
            stmt.setString(1, mensaje);
            stmt.execute();
            return "Mensaje enviado.";
        } catch (SQLException e) {
            return "Error: " + e.getMessage();
        }
    }

    /**
     * Método que obtiene todos los mensajes almacenados en la base de datos.
     *
     * @return Una lista de objetos `Missatge` que contiene los mensajes obtenidos.
     */
    public List<Missatge> getMessages() {
        List<Missatge> mensajes = new ArrayList<>();
        try (CallableStatement stmt = dbConexion.getConnexioBD().prepareCall("{call getMessages()}");
             ResultSet rs = stmt.executeQuery()) {
            while (rs.next()) {
                String nick = rs.getString("nick");
                String message = rs.getString("message");
                Timestamp ts = rs.getTimestamp("ts");
                mensajes.add(new Missatge(nick, message, ts));
            }
        } catch (SQLException e) {
            System.out.println("Error al obtener mensajes: " + e.getMessage());
        }
        return mensajes;
    }

    /**
     * Método que obtiene la lista de usuarios actualmente conectados al chat.
     *
     * @return Una lista de objetos `Usuari` que representa a los usuarios conectados.
     */
    public List<Usuari> getConnectedUsers() {
        List<Usuari> users = new ArrayList<>();
        try (CallableStatement stmt = dbConexion.getConnexioBD().prepareCall("{call getConnectedUsers()}");
             ResultSet rs = stmt.executeQuery()) {
            while (rs.next()) {
                String nick = rs.getString("nick");
                Timestamp dateCon = rs.getTimestamp("date_con");
                users.add(new Usuari(nick, rs.getString("nick"), dateCon, 0)); 
            }
        } catch (SQLException e) {
            System.out.println("Error al obtener usuarios conectados: " + e.getMessage());
        }
        return users;
    }

    
    /**
     * Método que desconecta al usuario actual del chat.
     *
     * @return Un mensaje que indica si la desconexión fue exitosa o si ocurrió un error.
     */
    public String disconnect() {
        try (CallableStatement stmt = dbConexion.getConnexioBD().prepareCall("{call disconnect()}")) {
            stmt.execute();
            return "Desconectado correctamente.";
        } catch (SQLException e) {
            return "Error: " + e.getMessage();
        }
    }


    /**
     * Cierra la conexión a la base de datos.
     */
    public void closeConnection() {
        dbConexion.closeConexionBD();
    }
}

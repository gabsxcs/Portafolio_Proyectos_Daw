package controller;

import modelo.Missatge;
import modelo.Usuari;
import modelo.ChatModel;

import java.sql.SQLException;
import java.util.List;


/**
 * Esta clase es el controllador que  se encarga de manejar la interacción entre la vista y el modelo.
 * Crea unos propios metodos a traves de una instancia del ChatModel llamado a los metodos que ya exiten posteriormente allí.
 * La he puesto en controller por el mismo motivo de que controla la interacion entre la vista del chat y el ChatModel
 */
public class ChatController {

    private ChatModel chatModel;

    public ChatController() throws SQLException, ClassNotFoundException {
        this.chatModel = new ChatModel();
    }

    public String connectToChat(String nick) {
        return chatModel.connect(nick);
    }

    public String sendMessage(String message) {
        return chatModel.sendMessage(message);
    }

    public List<Missatge> getMessages() {
        return chatModel.getMessages();
    }

    public List<Usuari> getConnectedUsers() {
        return chatModel.getConnectedUsers();
    }

    public String disconnect() {
        return chatModel.disconnect();
    }

    public void closeConnection() {
        chatModel.closeConnection();
    }
}

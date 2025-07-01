package modelo;

import java.sql.Timestamp;

/**
 * Esta clase es la encargada de representar un mensaje en el chat
 * La he puesto en el modelo porque representa la estructura de datos de un mensaje
 */
public class Missatge {

    private String nick;
    private String message;
    private Timestamp ts;

    public Missatge(String nick, String message, Timestamp ts) {
        this.nick = nick;
        this.message = message;
        this.ts = ts;
    }

    
    public String getNick() {
        return nick;
    }

    public void setNick(String nick) {
        this.nick = nick;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public Timestamp getTs() {
        return ts;
    }

    public void setTs(Timestamp ts) {
        this.ts = ts;
    }

    @Override
    public String toString() {
        return "Mensaje de " + nick + " (" + ts + "): " + message;
    }
}


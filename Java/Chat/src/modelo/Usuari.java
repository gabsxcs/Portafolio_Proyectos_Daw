package modelo;

import java.sql.Timestamp;

/**
 * Esta clase es la encargada de representar un usuario en el chat
 * La he puesto en el modelo porque representa la estructura de datos de un usuario
 */
public class Usuari {

    private String nick;
    private String userhost;
    private Timestamp dateCon;
    private int lastRead;

    public Usuari(String nick, String userhost, Timestamp dateCon, int lastRead) {
        this.nick = nick;
        this.userhost = userhost;
        this.dateCon = dateCon;
        this.lastRead = lastRead;
    }

    public String getNick() {
        return nick;
    }

    public void setNick(String nick) {
        this.nick = nick;
    }

    public String getUserhost() {
        return userhost;
    }

    public void setUserhost(String userhost) {
        this.userhost = userhost;
    }

    public Timestamp getDateCon() {
        return dateCon;
    }

    public void setDateCon(Timestamp dateCon) {
        this.dateCon = dateCon;
    }

    public int getLastRead() {
        return lastRead;
    }

    public void setLastRead(int lastRead) {
        this.lastRead = lastRead;
    }

    @Override
    public String toString() {
        return "Usuario: " + nick + " conectado desde " + userhost + " a las " + dateCon;
    }
}

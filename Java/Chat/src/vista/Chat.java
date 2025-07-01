package vista;

import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.KeyEvent;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

import javax.swing.*;
import javax.swing.border.EmptyBorder;
import javax.swing.border.LineBorder;

import controller.ChatController;
import modelo.Missatge;
import modelo.Usuari;

/**
 * Clase que se encarga de hacer la vista gráfica del chat con Jframe.
 * He hecho que esta clase cree una instancia de ChatController para mandar al controlador las funcionalidades de este chat y que el controlador las maneje 
 * con los metodos del modelo. Decidí hacer asi debido a que la vista no debe interactuar directamente con el modelo, ya que no debe conocer la 
 * logica interna ni manejar la conexion con la  base de datos
 */
public class Chat extends JFrame {

    private static final long serialVersionUID = 1L;
    private JPanel contentPane;
    private JTextField campoMensaje, textFieldUsername;
    private JTextArea areaMensajes;
    private DefaultListModel<String> connectedUsersModel;
    private JButton btnSend, btnLogin;
    private JButton btnLogout;
    private JPanel chatPanel, usuariosPanel;
    private String username = "";
    private String mensaje = "";
    private ChatController chatController;
    private JPanel panelMensajes;
    private JScrollPane scrollPaneChat;
    private List<Missatge> listaMensajes = new ArrayList<>();

    
    private boolean estasConectat = false; 

  
    /**
     * Launch the application.
     */
    public static void main(String[] args) {
        EventQueue.invokeLater(() -> {
            try {
                Chat frame = new Chat();
                frame.setVisible(true);
            } catch (Exception e) {
                e.printStackTrace();
            }
        });
    }

    /**
     * Create the frame.
     */
    public Chat() {
    	
    	//el timer que actualiza los mensajes y usuarios conectados cada 5 segundos
    	javax.swing.Timer timer = new javax.swing.Timer(5000, new ActionListener() {
    	    @Override
    	    public void actionPerformed(ActionEvent ae) {
    	        if (!username.isEmpty()) {
    	            cargarMensajes();
    	            actualizarUsuariosConectados();
    	        }
    	    }
    	});
    	timer.start();

    	
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setBounds(100, 100, 650, 660);
        setTitle("💖 Chat Room 💖");
        setResizable(false);

        contentPane = new JPanel();
        contentPane.setBorder(new EmptyBorder(10, 10, 10, 10));
        contentPane.setBackground(new Color(255, 240, 245));
        setContentPane(contentPane);
        contentPane.setLayout(null);

        JLabel lblTitle = new JLabel(" Chat ");
        lblTitle.setFont(new Font("Segoe UI", Font.BOLD, 22));
        lblTitle.setForeground(new Color(255, 105, 180));
        lblTitle.setHorizontalAlignment(SwingConstants.CENTER);
        lblTitle.setBounds(150, 10, 350, 30);
        contentPane.add(lblTitle);

        JLabel lblUsername = new JLabel("Entra tu usuario:");
        lblUsername.setFont(new Font("Segoe UI", Font.BOLD, 14));
        lblUsername.setForeground(new Color(199, 21, 133));
        lblUsername.setBounds(12, 52, 200, 20);
        contentPane.add(lblUsername);

        textFieldUsername = new JTextField();
        textFieldUsername.setFont(new Font("Segoe UI", Font.PLAIN, 14));
        textFieldUsername.setForeground(Color.BLACK);
        textFieldUsername.setBackground(Color.WHITE);
        textFieldUsername.setBounds(10, 75, 220, 35);
        textFieldUsername.setBorder(new LineBorder(new Color(255, 105, 180), 2, true));
        contentPane.add(textFieldUsername);

        btnLogin = new JButton("Log In");
        btnLogin.setFont(new Font("Segoe UI", Font.BOLD, 14));
        btnLogin.setForeground(Color.WHITE);
        btnLogin.setBackground(new Color(255, 105, 180));
        btnLogin.setBorder(new LineBorder(new Color(199, 21, 133), 2, true));
        btnLogin.setBounds(242, 75, 120, 35);
        btnLogin.setFocusPainted(false);
        btnLogin.setCursor(new Cursor(Cursor.HAND_CURSOR));
        contentPane.add(btnLogin);

        chatPanel = new JPanel();
        chatPanel.setBounds(200, 130, 420, 400);
        chatPanel.setBorder(new LineBorder(new Color(255, 105, 180), 2, true));
        chatPanel.setBackground(Color.WHITE);
        chatPanel.setLayout(new BorderLayout());
        chatPanel.setVisible(false);

        panelMensajes = new JPanel();
        panelMensajes.setLayout(new BoxLayout(panelMensajes, BoxLayout.Y_AXIS));
        panelMensajes.setBackground(Color.WHITE);

        scrollPaneChat = new JScrollPane(panelMensajes);
        scrollPaneChat.setVerticalScrollBarPolicy(JScrollPane.VERTICAL_SCROLLBAR_AS_NEEDED);

        chatPanel.add(scrollPaneChat, BorderLayout.CENTER);
        contentPane.add(chatPanel);


        areaMensajes = new JTextArea();
        areaMensajes.setFont(new Font("Segoe UI", Font.PLAIN, 13));
        areaMensajes.setForeground(Color.BLACK);
        areaMensajes.setBackground(new Color(255, 245, 247));
        areaMensajes.setEditable(false);
        areaMensajes.setWrapStyleWord(true);
        areaMensajes.setLineWrap(true);

        usuariosPanel = new JPanel();
        usuariosPanel.setBackground(new Color(255, 228, 225));
        usuariosPanel.setBounds(10, 130, 180, 400);
        usuariosPanel.setBorder(new LineBorder(new Color(255, 105, 180), 2, true));
        usuariosPanel.setLayout(new BorderLayout());
        usuariosPanel.setVisible(false);

        connectedUsersModel = new DefaultListModel<>();
        JList<String> listConnectedUsers = new JList<>(connectedUsersModel);
        listConnectedUsers.setFont(new Font("Segoe UI", Font.PLAIN, 12));
        listConnectedUsers.setForeground(Color.WHITE);
        listConnectedUsers.setBackground(new Color(255, 182, 193));

        JScrollPane scrollPaneUsers = new JScrollPane(listConnectedUsers);
        usuariosPanel.add(scrollPaneUsers, BorderLayout.CENTER);
        contentPane.add(usuariosPanel);

        campoMensaje = new JTextField();
        campoMensaje.setFont(new Font("Segoe UI", Font.PLAIN, 14));
        campoMensaje.setBounds(20, 540, 480, 40);
        campoMensaje.setBorder(new LineBorder(new Color(255, 105, 180), 2, true));
        campoMensaje.setVisible(false);
        contentPane.add(campoMensaje);

        btnSend = new JButton("Enviar");
        btnSend.setFont(new Font("Segoe UI", Font.BOLD, 14));
        btnSend.setForeground(Color.WHITE);
        btnSend.setBackground(new Color(255, 105, 180));
        btnSend.setBorder(new LineBorder(new Color(199, 21, 133), 2, true));
        btnSend.setBounds(510, 540, 100, 40);
        btnSend.setFocusPainted(false);
        btnSend.setCursor(new Cursor(Cursor.HAND_CURSOR));
        btnSend.setVisible(false);
        contentPane.add(btnSend);
        
        btnLogout = new JButton("Log Out");
        btnLogout.setFont(new Font("Segoe UI", Font.BOLD, 14));
        btnLogout.setForeground(Color.WHITE);
        btnLogout.setBackground(new Color(255, 102, 153));
        btnLogout.setBorder(new LineBorder(new Color(199, 21, 133), 2, true));
        btnLogout.setBounds(500, 75, 120, 35);
        btnLogout.setFocusPainted(false);
        btnLogout.setCursor(new Cursor(Cursor.HAND_CURSOR));
        btnLogout.setVisible(false);  
        contentPane.add(btnLogout);

        
        try {
            this.chatController = new ChatController();  
        } catch (SQLException | ClassNotFoundException e) {
            JOptionPane.showMessageDialog(null, "Error al conectar con la base de datos: " + e.getMessage(),
                    "Error de Conexión", JOptionPane.ERROR_MESSAGE);
            return;
        }

        //Esto es para que al tocar la tecla enter se envíe el usuario sin tener que darle necesariamente al boton
        textFieldUsername.addKeyListener(new java.awt.event.KeyAdapter() {
            @Override
            public void keyPressed(java.awt.event.KeyEvent evt) {
                if (evt.getKeyCode() == KeyEvent.VK_ENTER) {  
                    btnLogin.doClick();
                }
            }
        });
       
        btnLogin.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                if (chatController == null) {
                    JOptionPane.showMessageDialog(null, "No se puede conectar al chat en este momento.", "Error", JOptionPane.ERROR_MESSAGE);
                    return;
                }

                username = textFieldUsername.getText().trim();
                if (!username.isEmpty()) {
                    try {
                        String respuestaBtn = chatController.connectToChat(username);
                        JOptionPane.showMessageDialog(null, respuestaBtn, "Estado de conexión", JOptionPane.INFORMATION_MESSAGE);

                        if (!respuestaBtn.startsWith("Error")) {
                            chatPanel.setVisible(true);
                            usuariosPanel.setVisible(true);
                            campoMensaje.setVisible(true);
                            btnLogout.setVisible(true);
                            btnSend.setVisible(true);
                            connectedUsersModel.addElement(username + " (You)");
                            textFieldUsername.setEnabled(false);
                            btnLogin.setEnabled(false);
                            estasConectat = true;
                            actualizarUsuariosConectados();
                            cargarMensajes();
                        }
                    } catch (Exception ex) {
                        JOptionPane.showMessageDialog(null, "Ocurrió un error: " + ex.getMessage(), "Error", JOptionPane.ERROR_MESSAGE);
                    }
                } else {
                    JOptionPane.showMessageDialog(null, "Por favor, ingresa un nombre de usuario.", "Error", JOptionPane.ERROR_MESSAGE);
                }
            }
        });
        
        
        btnLogout.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                if (chatController != null) {
                    try {
                        String respuestaBtn = chatController.disconnect();
                        JOptionPane.showMessageDialog(null, respuestaBtn, "Estado de conexión", JOptionPane.INFORMATION_MESSAGE);

                        if (!respuestaBtn.startsWith("Error")) {
                        	chatPanel.setVisible(false);
                            usuariosPanel.setVisible(false);
                            campoMensaje.setVisible(false);
                            btnSend.setVisible(false);
                            btnLogout.setVisible(false);
                            connectedUsersModel.clear();
                            textFieldUsername.setEnabled(true);
                            btnLogin.setEnabled(true);
                            textFieldUsername.setText("");
                            estasConectat = false; 
                        }

                    } catch (Exception ex) {
                        JOptionPane.showMessageDialog(null, "Error al desconectar: " + ex.getMessage(), "Error", JOptionPane.ERROR_MESSAGE);
                    }
                }
            }
        });
        
      //Esto es para que al tocar la tecla enter se envíe el mensaje sin tener que darle necesariamente al boton
        campoMensaje.addKeyListener(new java.awt.event.KeyAdapter() {
            @Override
            public void keyPressed(java.awt.event.KeyEvent evt) {
                if (evt.getKeyCode() == KeyEvent.VK_ENTER) {  
                    
                    btnSend.doClick();
                }
            }
        });
        btnSend.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
           
                mensaje = campoMensaje.getText().trim();

                if (!mensaje.isEmpty()) {
                    try {
                        String respuestaBtn = chatController.sendMessage(mensaje);
                        
                        JOptionPane.showMessageDialog(null, respuestaBtn, "Estado del mensaje", JOptionPane.INFORMATION_MESSAGE);

                        campoMensaje.setText("");
                        
                        cargarMensajes();

                    } catch (Exception ex) {
                        JOptionPane.showMessageDialog(null, "Error al enviar mensaje: " + ex.getMessage(), "Error", JOptionPane.ERROR_MESSAGE);
                    }
                } else {
                    JOptionPane.showMessageDialog(null, "Por favor, escribe un mensaje.", "Advertencia", JOptionPane.WARNING_MESSAGE);
                }
            }
        });

    }
    
    //Carga los mensajes mas recientes que hayan
    private void cargarMensajes() {
        if (chatController != null) {
            try {
                List<Missatge> nuevosMensajes = chatController.getMessages();

                if (!nuevosMensajes.isEmpty()) {
                    listaMensajes.addAll(nuevosMensajes);

                    panelMensajes.removeAll(); 

                    for (Missatge msg : listaMensajes) {
                        panelMensajes.add(crearMensajePanel(msg));
                    }
                    
                    panelMensajes.revalidate();
                    panelMensajes.repaint();
                    scrollPaneChat.getVerticalScrollBar().setValue(scrollPaneChat.getVerticalScrollBar().getMaximum());
                }
            } catch (Exception e) {
                JOptionPane.showMessageDialog(null, "Error al cargar los mensajes: " + e.getMessage(), "Error", JOptionPane.ERROR_MESSAGE);
            }
        }
    }


//Actualiza la lista de usuarios conectados a la base de datos y lo muestra
    private void actualizarUsuariosConectados() {
        if (chatController != null) {
            List<Usuari> usuarios = chatController.getConnectedUsers();
            connectedUsersModel.clear(); 

            for (Usuari usuario : usuarios) {
                connectedUsersModel.addElement(usuario.getNick()); 
            }
        }
    }

    /**
     * Este método crea un diseño para representar cada mensaje individual en el chat.
     */
    private JPanel crearMensajePanel(Missatge msg) {
        JPanel mensajePanel = new JPanel();
        mensajePanel.setLayout(new BoxLayout(mensajePanel, BoxLayout.Y_AXIS)); 
        mensajePanel.setBackground(new Color(255, 235, 255)); 
        mensajePanel.setBorder(BorderFactory.createLineBorder(new Color(255, 105, 180), 1, true)); 
        mensajePanel.setPreferredSize(new Dimension(380, 70));
        mensajePanel.setMaximumSize(new Dimension(400, 90));  

        JPanel contentPanel = new JPanel();
        contentPanel.setLayout(new BorderLayout());
        contentPanel.setBackground(new Color(255, 235, 255)); 
        contentPanel.setBorder(BorderFactory.createEmptyBorder(10, 10, 10, 10));  

        JLabel lblNick = new JLabel("🗣 " + msg.getNick());
        lblNick.setFont(new Font("Segoe UI", Font.BOLD, 14));  
        lblNick.setForeground(new Color(255, 20, 147)); 

        JLabel lblMensaje = new JLabel("<html><body style='width: 350px;'>" + msg.getMessage() + "</body></html>");
        lblMensaje.setFont(new Font("Segoe UI", Font.PLAIN, 12)); 
        lblMensaje.setForeground(Color.BLACK); 

        JLabel lblTimestamp = new JLabel("[" + msg.getTs() + "]");
        lblTimestamp.setFont(new Font("Segoe UI", Font.ITALIC, 10));  
        lblTimestamp.setForeground(new Color(169, 169, 169)); 

       
        contentPanel.add(lblNick, BorderLayout.NORTH);
        contentPanel.add(lblMensaje, BorderLayout.CENTER);
        contentPanel.add(lblTimestamp, BorderLayout.SOUTH);

        mensajePanel.add(contentPanel);

        return mensajePanel;
    }




}

package vista;

import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.File;
import java.util.List;

import javax.swing.*;
import javax.swing.border.EmptyBorder;
import javax.swing.filechooser.FileNameExtensionFilter;
import javax.swing.table.DefaultTableCellRenderer;

import controller.CsvController;
import controller.MunicipiController;
import controller.PartitController;
import controller.ResultadoController;
import modelo.Municipi;
import modelo.Partit;
import modelo.Resultat;

public class VotacionView extends JFrame {

    private static final long serialVersionUID = 1L;
    private JPanel contentPane;
    private JComboBox<String> opcionesComboBox;
    private CsvController controlador;
    private MunicipiController municipiController;

    public static void main(String[] args) {
        EventQueue.invokeLater(() -> {
            try {
                VotacionView frame = new VotacionView();
                frame.setVisible(true);
            } catch (Exception e) {
                e.printStackTrace();
            }
        });
    }

    public VotacionView() {
    	controlador = new CsvController();
        municipiController = new MunicipiController();
        
        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        setBounds(100, 100, 450, 300);
        setTitle("VOTACIONES");

        contentPane = new JPanel();
        contentPane.setBackground(new Color(255, 182, 193)); 
        contentPane.setLayout(new BorderLayout(10, 10));
        setContentPane(contentPane);

        JPanel mainPanel = new JPanel();
        mainPanel.setBackground(new Color(255, 182, 193));
        mainPanel.setLayout(new GridBagLayout());
        contentPane.add(mainPanel, BorderLayout.CENTER);

        GridBagConstraints gbc = new GridBagConstraints();
        gbc.insets = new Insets(10, 10, 10, 10);
        gbc.gridx = 0;
        gbc.gridy = 0;
        gbc.gridwidth = 2;

        JLabel tituloLabel = new JLabel("VOTACIONES", SwingConstants.CENTER);
        tituloLabel.setFont(new Font("Serif", Font.BOLD, 24));
        tituloLabel.setForeground(new Color(139, 0, 139));
        mainPanel.add(tituloLabel, gbc);

        gbc.gridy = 1;
        gbc.gridwidth = 1;
        JButton mostrarListadoButton = new JButton("Mostrar Listado");
        estiloBoton(mostrarListadoButton);
        mainPanel.add(mostrarListadoButton, gbc);

        mostrarListadoButton.addActionListener(e -> {
            String opcionSeleccionada = (String) opcionesComboBox.getSelectedItem();
            System.out.println("Opción seleccionada: " + opcionSeleccionada); 
            
            switch(opcionSeleccionada) {
                case "Listado de todos los partidos":
                	 mostrarListadoPartidos();
                    break;
                case "Listado de todos los municipios":
                    mostrarListadoMunicipios();
                    break;
                case "Resultados por partido en un municipio":
                	 mostrarSelectorMunicipiosParaResultados();  
                    break;
                case "Resultados por municipio de un partido":
                	mostrarSelectorPartidosParaMunicipios();
                    break;
                case "Resultados por partido y municipio":
                	mostrarSelectorMunicipioYPartido();
                	 break;
            }
        });
        
        gbc.gridx = 1;
        JButton cargarCsvButton = new JButton("Cargar CSV");
        estiloBoton(cargarCsvButton);
        mainPanel.add(cargarCsvButton, gbc);

        cargarCsvButton.addActionListener(new ActionListener() {
            public void actionPerformed(ActionEvent e) {
                JFileChooser fileChooser = new JFileChooser();
                fileChooser.setFileFilter(new FileNameExtensionFilter("Archivos CSV", "csv"));
                int returnValue = fileChooser.showOpenDialog(null);
                if (returnValue == JFileChooser.APPROVE_OPTION) {
                    File selectedFile = fileChooser.getSelectedFile();
                    if (!selectedFile.getName().toLowerCase().endsWith(".csv")) {
                        JOptionPane.showMessageDialog(VotacionView.this, 
                            "Error: Seleccione un archivo CSV válido.", "Error", JOptionPane.ERROR_MESSAGE);
                        return;
                    }

                    //Aqui hago como un modal que salga mientras se ejecuta el metodo de leerYGuardarCSV que informe que se está
                    // ejecutando el proceso, para que el usuario sepa que si se demora es porque se está ejecutando y que por favor espere
                    JDialog progressDialog = new JDialog(VotacionView.this, "Procesando", true);
                    progressDialog.setLayout(new BorderLayout());
                    progressDialog.add(new JLabel("Procesando archivo, por favor espere...", SwingConstants.CENTER), BorderLayout.CENTER);
                    progressDialog.setSize(300, 100);
                    progressDialog.setLocationRelativeTo(VotacionView.this);
                    
                    new Thread(() -> {
                        try {
                            SwingUtilities.invokeLater(() -> progressDialog.setVisible(true));
                            
                            controlador.leerYGuardarCSV(selectedFile.getAbsolutePath());
                            
                            SwingUtilities.invokeLater(() -> {
                                progressDialog.dispose(); 
                                
                                if (!controlador.getElementosNoInsertados().isEmpty()) {
                                    mostrarElementosNoInsertados(controlador.getElementosNoInsertados());
                                }
                                if (!controlador.getActualizaciones().isEmpty()) {
                                    mostrarActualizaciones(controlador.getActualizaciones());
                                }
                            });
                            
                        } catch (Exception ex) {
                            SwingUtilities.invokeLater(() -> {
                                progressDialog.dispose(); 
                                JOptionPane.showMessageDialog(VotacionView.this, 
                                    "Error al procesar el archivo: " + ex.getMessage(), 
                                    "Error", 
                                    JOptionPane.ERROR_MESSAGE);
                            });
                        }
                    }).start();
                }
            }
        });

        gbc.gridx = 0;
        gbc.gridy = 2;
        gbc.gridwidth = 2;
        String[] opciones = {
                "Listado de todos los partidos",
                "Listado de todos los municipios",
                "Resultados por partido en un municipio",
                "Resultados por municipio de un partido",
                "Resultados por partido y municipio"
        };
        opcionesComboBox = new JComboBox<>(opciones);
        opcionesComboBox.setFont(new Font("Arial", Font.PLAIN, 12));
        mainPanel.add(opcionesComboBox, gbc);
    }

    private void estiloBoton(JButton boton) {
        boton.setFont(new Font("Arial", Font.BOLD, 12));
        boton.setForeground(Color.WHITE);
        boton.setBackground(new Color(139, 0, 139));
        boton.setBorder(BorderFactory.createLineBorder(new Color(139, 0, 139), 2));
        boton.setFocusPainted(false);
        boton.setOpaque(true);
        boton.setPreferredSize(new Dimension(140, 30));
    }
    
    /**
     * Esto muestra un pop-up mostrando una lista de objetos que no pudieron ser insertados a la bbdd. 
     * Lo he hecho para poder manejar de esta forma los objetos no insertado y que el usuario tenga conocimiento de lo que no se ha podido insertar
     * @param elementosNoInsertados
     */
    private void mostrarElementosNoInsertados(List<String> elementosNoInsertados) {
        JTextArea textArea = new JTextArea(15, 50);  
        textArea.setText(String.join("\n", elementosNoInsertados));
        textArea.setEditable(false);
        textArea.setFont(new Font("Monospaced", Font.PLAIN, 12)); 
        
        JScrollPane scrollPane = new JScrollPane(textArea);
        scrollPane.setPreferredSize(new Dimension(600, 300));  
        //Esto es un pop up que muestra los objetos que no se han podido introducir porque ya estaban en la base de datos.
        //He decidido que si hay un objeto repetido, que quizas venga en otro csv, no se pueda volver a introducir a la base de datos
        JOptionPane.showMessageDialog(
            this, scrollPane, "Elementos No Insertados",  JOptionPane.WARNING_MESSAGE
        );
    }

    /**
	 * Esto es un pop up que salta si han habido actualizaciones de algun objeto resultado.
	 * Esto lo he decidido porue si hay un archivo csv que viene con informacion nueva y correcta, entonces se actualizan los datos que habian antes.
	 * Mi criterio ha sido que si hay un objeto resultado en la bbdd que tiene el mismo año, mismo partido y mismo municipio que un nuevo objeto que se intenta insertar, 
	 * y ambos tienen diferente votos, porcentage y/o regidores, entonces se actualiza
	 * @param actualizaciones
	 */
    private void mostrarActualizaciones(List<String> actualizaciones) {
        JTextArea textArea = new JTextArea(15, 50);
        textArea.setText(String.join("\n", actualizaciones));
        textArea.setEditable(false);
        textArea.setFont(new Font("Monospaced", Font.PLAIN, 12));
        
        JScrollPane scrollPane = new JScrollPane(textArea);
        scrollPane.setPreferredSize(new Dimension(600, 300));
        
        JOptionPane.showMessageDialog(
            this, 
            scrollPane, 
            "Resultados Actualizados", 
            JOptionPane.INFORMATION_MESSAGE
        );
    }
    /**
     * Metodo para mostrar la lista de municipios en un pop up
     */
    private void mostrarListadoMunicipios() {
        try {
            List<Municipi> municipios = municipiController.obtenerTodosLosMunicipis();
            System.out.println("Número de municipios obtenidos: " + municipios.size()); 

            if (municipios.isEmpty()) {
                JOptionPane.showMessageDialog(this, "No hay municipios disponibles.", "Información", JOptionPane.INFORMATION_MESSAGE);
                return;
            }

            String[] columnNames = {"Código ENS", "Nombre"};
            String[][] data = new String[municipios.size()][2];

            for (int i = 0; i < municipios.size(); i++) {
                data[i][0] = municipios.get(i).getCodiEns();
                data[i][1] = municipios.get(i).getNom();
            }

            JTable table = new JTable(data, columnNames);
            table.setFont(new Font("Arial", Font.PLAIN, 14));
            table.setRowHeight(25);
            table.getTableHeader().setFont(new Font("Arial", Font.BOLD, 14));

            JScrollPane scrollPane = new JScrollPane(table);
            scrollPane.setPreferredSize(new Dimension(400, 300));

            JDialog dialog = new JDialog(this, "Listado de Municipios", true);
            dialog.setLayout(new BorderLayout());
            dialog.add(scrollPane, BorderLayout.CENTER);
            dialog.setSize(450, 350);
            dialog.setLocationRelativeTo(this);
            dialog.setVisible(true);
        } catch (Exception e) {
            e.printStackTrace();
            JOptionPane.showMessageDialog(this, "Error al mostrar los municipios: " + e.getMessage(), "Error", JOptionPane.ERROR_MESSAGE);
        }
    }
    
    /**
     * Metodo para mostrar la lista de partidos en un pop up
     */
    private void mostrarListadoPartidos() {
        try {
            List<Partit> partidos = new PartitController().obtenerTodosLosPartits();
            System.out.println("Número de partidos obtenidos: " + partidos.size()); 

            if (partidos.isEmpty()) {
                JOptionPane.showMessageDialog(this, "No hay partidos disponibles.", "Información", JOptionPane.INFORMATION_MESSAGE);
                return;
            }

            String[] columnNames = {"Siglas del Partido"};
            String[][] data = new String[partidos.size()][1];

            for (int i = 0; i < partidos.size(); i++) {
                data[i][0] = partidos.get(i).getSigles();
            }

            JTable table = new JTable(data, columnNames);
            table.setFont(new Font("Arial", Font.PLAIN, 14));
            table.setRowHeight(25);
            table.getTableHeader().setFont(new Font("Arial", Font.BOLD, 14));

            JScrollPane scrollPane = new JScrollPane(table);
            scrollPane.setPreferredSize(new Dimension(400, 300));

            JDialog dialog = new JDialog(this, "Listado de Partidos", true);
            dialog.setLayout(new BorderLayout());
            dialog.add(scrollPane, BorderLayout.CENTER);
            dialog.setSize(450, 350);
            dialog.setLocationRelativeTo(this);
            dialog.setVisible(true);
        } catch (Exception e) {
            e.printStackTrace();
            JOptionPane.showMessageDialog(this, "Error al mostrar los partidos: " + e.getMessage(), "Error", JOptionPane.ERROR_MESSAGE);
        }
    }

	/**
	 * Metodo para mostrar un pop up con un select de la lista de municipios disponibles para escoger
	 */
    private void mostrarSelectorMunicipiosParaResultados() {
        try {
            ResultadoController resultadoController = new ResultadoController();
            List<String> nombresMunicipios = resultadoController.obtenerNombresMunicipios();
            
            if (nombresMunicipios.isEmpty()) {
                JOptionPane.showMessageDialog(this, "No hay municipios disponibles.", 
                                           "Información", JOptionPane.INFORMATION_MESSAGE);
                return;
            }

            nombresMunicipios.sort(String::compareToIgnoreCase);

            JPanel panel = new JPanel(new GridLayout(2, 1, 5, 5));
            panel.setBorder(new EmptyBorder(10, 10, 10, 10));
            panel.add(new JLabel("Seleccione un municipio:"));
            JComboBox<String> comboBox = new JComboBox<>(nombresMunicipios.toArray(new String[0]));
            comboBox.setFont(new Font("Arial", Font.PLAIN, 14));
            panel.add(comboBox);

            int result = JOptionPane.showConfirmDialog(this, panel, "Seleccionar Municipio", 
                                                     JOptionPane.OK_CANCEL_OPTION, JOptionPane.PLAIN_MESSAGE);
            
            if (result == JOptionPane.OK_OPTION) {
                String nombreMunicipio = (String) comboBox.getSelectedItem();
                String codiMunicipi = resultadoController.obtenerCodigoMunicipioPorNombre(nombreMunicipio);
                if (codiMunicipi != null) {
                    mostrarResultadosPorMunicipio(codiMunicipi);
                } else {
                    JOptionPane.showMessageDialog(this, "No se encontró el código del municipio seleccionado.", 
                                                "Error", JOptionPane.ERROR_MESSAGE);
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
            JOptionPane.showMessageDialog(this, "Error al cargar municipios: " + e.getMessage(), 
                                        "Error", JOptionPane.ERROR_MESSAGE);
        }
    }

    /**
     * Metodo para mostrar un pop up con una tabla de los reultados que hay en el municipio escogido
     * @param codiMunicipi
     */
    private void mostrarResultadosPorMunicipio(String codiMunicipi) {
        try {
            ResultadoController resultadoController = new ResultadoController();
            List<Resultat> resultados = resultadoController.obtenerResultadosPorMunicipio(codiMunicipi);
            
            if (resultados.isEmpty()) {
                JOptionPane.showMessageDialog(this, "No hay resultados disponibles para este municipio.", 
                                            "Información", JOptionPane.INFORMATION_MESSAGE);
                return;
            }

            resultados.sort((r1, r2) -> {
                int yearCompare = Integer.compare(r2.getAnyEleccio(), r1.getAnyEleccio());
                return yearCompare != 0 ? yearCompare : Double.compare(r2.getPercentatgeVots(), r1.getPercentatgeVots());
            });

            String[] columnNames = {"Año", "Partido", "Votos", "% Votos", "Regidores"};
            Object[][] data = new Object[resultados.size()][5];

            String nombreMunicipio = resultados.get(0).getMunicipi().getNom();
            
            for (int i = 0; i < resultados.size(); i++) {
                Resultat r = resultados.get(i);
                data[i][0] = r.getAnyEleccio();
                data[i][1] = r.getPartit().getSigles();
                data[i][2] = r.getVots();
                data[i][3] = r.getPercentatgeVots();
                data[i][4] = r.getRegidors();
            }

            JTable table = new JTable(data, columnNames) {
                @Override
                public Class<?> getColumnClass(int column) {
                    if (column == 0) return Integer.class; 
                    if (column == 3) return Double.class;  
                    return super.getColumnClass(column);
                }
            };

            table.getColumnModel().getColumn(0).setCellRenderer(new DefaultTableCellRenderer() {
                @Override
                public Component getTableCellRendererComponent(JTable table, Object value, 
                        boolean isSelected, boolean hasFocus, int row, int column) {
                    return super.getTableCellRendererComponent(table, value, isSelected, hasFocus, row, column);
                }
            });

            table.getColumnModel().getColumn(3).setCellRenderer(new DefaultTableCellRenderer() {
                @Override
                public Component getTableCellRendererComponent(JTable table, Object value, 
                        boolean isSelected, boolean hasFocus, int row, int column) {
                    if (value instanceof Double) {
                        value = String.format("%.2f%%", (Double) value);
                    }
                    return super.getTableCellRendererComponent(table, value, isSelected, hasFocus, row, column);
                }
            });

            table.setFont(new Font("Arial", Font.PLAIN, 14));
            table.setRowHeight(25);
            table.getTableHeader().setFont(new Font("Arial", Font.BOLD, 14));
            table.setAutoCreateRowSorter(true);

            table.getColumnModel().getColumn(0).setPreferredWidth(60);  
            table.getColumnModel().getColumn(1).setPreferredWidth(120); 
            table.getColumnModel().getColumn(2).setPreferredWidth(80); 
            table.getColumnModel().getColumn(3).setPreferredWidth(80); 
            table.getColumnModel().getColumn(4).setPreferredWidth(80);  

            JScrollPane scrollPane = new JScrollPane(table);
            scrollPane.setPreferredSize(new Dimension(600, 350));

            JDialog dialog = new JDialog(this, "Resultados en " + nombreMunicipio, true);
            dialog.setLayout(new BorderLayout());
            dialog.add(scrollPane, BorderLayout.CENTER);
            dialog.pack();
            dialog.setLocationRelativeTo(this);
            dialog.setVisible(true);
        } catch (Exception e) {
            e.printStackTrace();
            JOptionPane.showMessageDialog(this, "Error al mostrar resultados: " + e.getMessage(), 
                                        "Error", JOptionPane.ERROR_MESSAGE);
        }
    }
    
	/**
	 * Metodo para mostrar un pop up con un select de la lista de partidos disponibles para escoger
	 */
    private void mostrarSelectorPartidosParaMunicipios() {
        try {
            ResultadoController resultadoController = new ResultadoController();
            List<String> siglasPartidos = resultadoController.obtenerSiglasPartidos();
            
            if (siglasPartidos.isEmpty()) {
                JOptionPane.showMessageDialog(this, "No hay partidos disponibles.", 
                                           "Información", JOptionPane.INFORMATION_MESSAGE);
                return;
            }

            siglasPartidos.sort(String::compareToIgnoreCase);

            JPanel panel = new JPanel(new GridLayout(2, 1, 5, 5));
            panel.setBorder(new EmptyBorder(10, 10, 10, 10));
            panel.add(new JLabel("Seleccione un partido:"));
            JComboBox<String> comboBox = new JComboBox<>(siglasPartidos.toArray(new String[0]));
            comboBox.setFont(new Font("Arial", Font.PLAIN, 14));
            panel.add(comboBox);

            int result = JOptionPane.showConfirmDialog(this, panel, "Seleccionar Partido", 
                                                     JOptionPane.OK_CANCEL_OPTION, JOptionPane.PLAIN_MESSAGE);
            
            if (result == JOptionPane.OK_OPTION) {
                String siglesPartido = (String) comboBox.getSelectedItem();
                mostrarResultadosPorPartido(siglesPartido);
            }
        } catch (Exception e) {
            e.printStackTrace();
            JOptionPane.showMessageDialog(this, "Error al cargar partidos: " + e.getMessage(), 
                                        "Error", JOptionPane.ERROR_MESSAGE);
        }
    }

    /**
     * Metodo para mostrar un pop up con una tabla de los reultados que hay en el partido escogido por municipio
     * @param codiMunicipi
     */
    private void mostrarResultadosPorPartido(String siglesPartido) {
        try {
            ResultadoController resultadoController = new ResultadoController();
            List<Resultat> resultados = resultadoController.obtenerResultadosPorPartido(siglesPartido);
            
            if (resultados.isEmpty()) {
                JOptionPane.showMessageDialog(this, "No hay resultados disponibles para este partido.", 
                                            "Información", JOptionPane.INFORMATION_MESSAGE);
                return;
            }

            resultados.sort((r1, r2) -> {
                int yearCompare = Integer.compare(r2.getAnyEleccio(), r1.getAnyEleccio());
                if (yearCompare != 0) return yearCompare;
                return r1.getMunicipi().getNom().compareToIgnoreCase(r2.getMunicipi().getNom());
            });

            String[] columnNames = {"Año", "Municipio", "Votos", "% Votos", "Regidores"};
            Object[][] data = new Object[resultados.size()][5];

            for (int i = 0; i < resultados.size(); i++) {
                Resultat r = resultados.get(i);
                data[i][0] = r.getAnyEleccio();
                data[i][1] = r.getMunicipi().getNom();
                data[i][2] = r.getVots();
                data[i][3] = r.getPercentatgeVots();
                data[i][4] = r.getRegidors();
            }

            JTable table = new JTable(data, columnNames) {
                @Override
                public Class<?> getColumnClass(int column) {
                    if (column == 0) return Integer.class;  
                    if (column == 3) return Double.class;  
                    return super.getColumnClass(column);
                }
            };

            table.getColumnModel().getColumn(0).setCellRenderer(new DefaultTableCellRenderer() {
                @Override
                public Component getTableCellRendererComponent(JTable table, Object value, 
                        boolean isSelected, boolean hasFocus, int row, int column) {
                    return super.getTableCellRendererComponent(table, value, isSelected, hasFocus, row, column);
                }
            });

            table.getColumnModel().getColumn(3).setCellRenderer(new DefaultTableCellRenderer() {
                @Override
                public Component getTableCellRendererComponent(JTable table, Object value, 
                        boolean isSelected, boolean hasFocus, int row, int column) {
                    if (value instanceof Double) {
                        value = String.format("%.2f%%", (Double) value);
                    }
                    return super.getTableCellRendererComponent(table, value, isSelected, hasFocus, row, column);
                }
            });

            table.setFont(new Font("Arial", Font.PLAIN, 14));
            table.setRowHeight(25);
            table.getTableHeader().setFont(new Font("Arial", Font.BOLD, 14));
            table.setAutoCreateRowSorter(true);

                        table.getColumnModel().getColumn(0).setPreferredWidth(60); 
            table.getColumnModel().getColumn(1).setPreferredWidth(150); 
            table.getColumnModel().getColumn(2).setPreferredWidth(80);  
            table.getColumnModel().getColumn(3).setPreferredWidth(80);  
            table.getColumnModel().getColumn(4).setPreferredWidth(80); 

            JScrollPane scrollPane = new JScrollPane(table);
            scrollPane.setPreferredSize(new Dimension(650, 350)); 

            JDialog dialog = new JDialog(this, "Resultados del " + siglesPartido, true);
            dialog.setLayout(new BorderLayout());
            dialog.add(scrollPane, BorderLayout.CENTER);
            dialog.pack();
            dialog.setLocationRelativeTo(this);
            dialog.setVisible(true);
        } catch (Exception e) {
            e.printStackTrace();
            JOptionPane.showMessageDialog(this, "Error al mostrar resultados: " + e.getMessage(), 
                                        "Error", JOptionPane.ERROR_MESSAGE);
        }
    }
    
    /*PopUp que muestra un selector para escoger municipio y partido*/
    private void mostrarSelectorMunicipioYPartido() {
        try {
            ResultadoController resultadoController = new ResultadoController();
            
            List<String> nombresMunicipios = resultadoController.obtenerNombresMunicipios();
            if (nombresMunicipios.isEmpty()) {
                JOptionPane.showMessageDialog(this, "No hay municipios disponibles.", 
                                           "Información", JOptionPane.INFORMATION_MESSAGE);
                return;
            }
            
            List<String> siglasPartidos = resultadoController.obtenerSiglasPartidos();
            if (siglasPartidos.isEmpty()) {
                JOptionPane.showMessageDialog(this, "No hay partidos disponibles.", 
                                           "Información", JOptionPane.INFORMATION_MESSAGE);
                return;
            }

            nombresMunicipios.sort(String::compareToIgnoreCase);
            siglasPartidos.sort(String::compareToIgnoreCase);

            JPanel panel = new JPanel(new GridLayout(3, 2, 5, 5));
            panel.setBorder(new EmptyBorder(10, 10, 10, 10));
            
            panel.add(new JLabel("Seleccione un municipio:"));
            JComboBox<String> comboMunicipios = new JComboBox<>(nombresMunicipios.toArray(new String[0]));
            comboMunicipios.setFont(new Font("Arial", Font.PLAIN, 14));
            panel.add(comboMunicipios);
            
            panel.add(new JLabel("Seleccione un partido:"));
            JComboBox<String> comboPartidos = new JComboBox<>(siglasPartidos.toArray(new String[0]));
            comboPartidos.setFont(new Font("Arial", Font.PLAIN, 14));
            panel.add(comboPartidos);

            int result = JOptionPane.showConfirmDialog(this, panel, "Seleccionar Municipio y Partido", 
                                                     JOptionPane.OK_CANCEL_OPTION, JOptionPane.PLAIN_MESSAGE);
            
            if (result == JOptionPane.OK_OPTION) {
                String nombreMunicipio = (String) comboMunicipios.getSelectedItem();
                String siglesPartido = (String) comboPartidos.getSelectedItem();
                
                String codiMunicipi = resultadoController.obtenerCodigoMunicipioPorNombre(nombreMunicipio);
                if (codiMunicipi != null) {
                    mostrarResultadosPorMunicipioYPartido(codiMunicipi, siglesPartido);
                } else {
                    JOptionPane.showMessageDialog(this, "No se encontró el código del municipio seleccionado.", 
                                                "Error", JOptionPane.ERROR_MESSAGE);
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
            JOptionPane.showMessageDialog(this, "Error al cargar datos: " + e.getMessage(), 
                                        "Error", JOptionPane.ERROR_MESSAGE);
        }
    }

    /**
     * Muestra una lista de resultados, si hay, que concuerde con el municipio y el partido
     * @param codiMunicipi
     * @param siglesPartido
     */
    private void mostrarResultadosPorMunicipioYPartido(String codiMunicipi, String siglesPartido) {
        try {
            ResultadoController resultadoController = new ResultadoController();
            List<Resultat> resultados = resultadoController.obtenerResultadosPorMunicipioYPartido(codiMunicipi, siglesPartido);
            
            if (resultados.isEmpty()) {
                JOptionPane.showMessageDialog(this, "No hay resultados disponibles para esta combinación de municipio y partido.", "Información", JOptionPane.INFORMATION_MESSAGE);
                return;
            }

            String nombreMunicipio = resultados.get(0).getMunicipi().getNom();
            
            String[] columnNames = {"Año", "Votos", "% Votos", "Regidores"};
            Object[][] data = new Object[resultados.size()][4];

            for (int i = 0; i < resultados.size(); i++) {
                Resultat r = resultados.get(i);
                data[i][0] = r.getAnyEleccio();
                data[i][1] = r.getVots();
                data[i][2] = r.getPercentatgeVots();
                data[i][3] = r.getRegidors();
            }

            JTable table = new JTable(data, columnNames) {
                @Override
                public Class<?> getColumnClass(int column) {
                    if (column == 0) return Integer.class;  
                    if (column == 2) return Double.class;   
                    return super.getColumnClass(column);
                }
            };

            table.getColumnModel().getColumn(2).setCellRenderer(new DefaultTableCellRenderer() {
                @Override
                public Component getTableCellRendererComponent(JTable table, Object value, 
                        boolean isSelected, boolean hasFocus, int row, int column) {
                    if (value instanceof Double) {
                        value = String.format("%.2f%%", (Double) value);
                    }
                    return super.getTableCellRendererComponent(table, value, isSelected, hasFocus, row, column);
                }
            });

            table.setFont(new Font("Arial", Font.PLAIN, 14));
            table.setRowHeight(25);
            table.getTableHeader().setFont(new Font("Arial", Font.BOLD, 14));
            table.setAutoCreateRowSorter(true);

            table.getColumnModel().getColumn(0).setPreferredWidth(80);  
            table.getColumnModel().getColumn(1).setPreferredWidth(100); 
            table.getColumnModel().getColumn(2).setPreferredWidth(100); 
            table.getColumnModel().getColumn(3).setPreferredWidth(100); 

            JScrollPane scrollPane = new JScrollPane(table);
            scrollPane.setPreferredSize(new Dimension(500, 200));

            JDialog dialog = new JDialog(this, 
                                      "Resultados de " + siglesPartido + " en " + nombreMunicipio, 
                                      true);
            dialog.setLayout(new BorderLayout());
            dialog.add(scrollPane, BorderLayout.CENTER);
            dialog.pack();
            dialog.setLocationRelativeTo(this);
            dialog.setVisible(true);
        } catch (Exception e) {
            e.printStackTrace();
            JOptionPane.showMessageDialog(this, "Error al mostrar resultados: " + e.getMessage(), "Error", JOptionPane.ERROR_MESSAGE);
        }
    }
}

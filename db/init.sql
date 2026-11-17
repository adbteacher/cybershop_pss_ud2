CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  bio TEXT,
  credit_card VARCHAR(30),
  is_admin TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  image VARCHAR(255),
  stock INT DEFAULT 100
);

CREATE TABLE IF NOT EXISTS reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  user_id INT NOT NULL,
  comment TEXT,
  rating INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  card_snapshot VARCHAR(30),
  status VARCHAR(30) DEFAULT 'pendiente',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL
);

CREATE TABLE IF NOT EXISTS password_resets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  token VARCHAR(64) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Usuarios de ejemplo. Contraseñas en MD5 (a propósito: A02 Cryptographic Failures)
-- admin / admin123
-- lucia / lucia2024
-- marcos / marcos2024
-- El resto de cuentas siguen el patron <usuario><anio>, ver walkthrough.md si hace falta.
INSERT INTO users (username, email, password, bio, credit_card, is_admin) VALUES
('admin',  'admin@cybershop.local', MD5('admin123'),  'Administrador de la tienda', '4111111111111111', 1),
('lucia',  'lucia@example.com',     MD5('lucia2024'), 'Me encanta la tecnologia y el gaming', '4222222222222222', 0),
('marcos', 'marcos@example.com',    MD5('marcos2024'),'Coleccionista de gadgets retro', '4333333333333333', 0);

-- Clientes adicionales para dar volumen realista de datos (pedidos, opiniones...).
INSERT INTO users (username, email, password, bio, credit_card, is_admin) VALUES
('andrea92',     'andrea.dev@example.com',     MD5('andrea2024'),  'Ingeniera informatica, apasionada del hardware y el overclocking.', '4444555566667777', 0),
('pablo_gamer',  'pablo.gamer@example.com',    MD5('pablo123'),    'Streamer a tiempo parcial, siempre buscando el mejor setup.', '4123456789012345', 0),
('sofia_office', 'sofia.office@example.com',   MD5('sofia2024'),   'Trabajo en remoto, busco comodidad y buena ergonomia.', '4555000011112222', 0),
('javi_streamer','javi.streamer@example.com',  MD5('javi123'),     'Monto directos los fines de semana, el audio es lo primero.', '4321432143214321', 0),
('nerea_photo',  'nerea.photo@example.com',    MD5('nerea2024'),   'Fotografa freelance, necesito almacenamiento rapido y fiable.', '4666777788889999', 0),
('carlos_it',    'carlos.it@example.com',      MD5('carlos123'),   'Tecnico de sistemas, monto equipos para la empresa.', '4111222233334444', 0),
('elena_home',   'elena.home@example.com',     MD5('elena2024'),   'Renovando mi despacho en casa poco a poco.', '4222333344445555', 0),
('raul92',       'raul92@example.com',         MD5('raul123'),     'Coleccionista de perifericos retro y modernos.', '4777888899990000', 0),
('marta_design', 'marta.design@example.com',   MD5('marta2024'),   'Disenadora grafica, necesito buena pantalla y color fiel.', '4888999900001111', 0),
('diego_office', 'diego.office@example.com',   MD5('diego123'),    'Responsable de compras de material informatico en mi empresa.', '4999000011112222', 0),
('irene_tech',   'irene.tech@example.com',     MD5('irene2024'),   'Entusiasta de las redes domesticas y la domotica.', '4321987654321098', 0),
('hugo_dev',     'hugo.dev@example.com',       MD5('hugo123'),     'Desarrollador backend, trabajo con dos monitores siempre.', '4123123123123123', 0);

-- Catalogo. Los primeros 5 productos (id 1-5) se mantienen intactos porque
-- walkthrough.md y el material docente referencian su orden, precio y numero
-- de columnas explicitamente (ver seccion 4.1 y 5.1 del walkthrough).
INSERT INTO products (name, description, price, image, stock) VALUES
('Teclado mecanico RGB', 'Teclado mecanico retroiluminado con switches azules, ideal para gaming y programacion.', 49.99, 'teclado.jpg', 50),
('Raton inalambrico', 'Raton ergonomico inalambrico 2.4GHz con sensor optico de alta precision.', 19.99, 'raton.jpg', 80),
('Monitor 27" 144Hz', 'Monitor gaming Full HD 144Hz con panel IPS y bajo tiempo de respuesta.', 229.99, 'monitor.jpg', 20),
('SSD NVMe 1TB', 'Disco solido NVMe PCIe 4.0 de alta velocidad para tu equipo.', 89.99, 'ssd.jpg', 35),
('Webcam HD', 'Webcam 1080p con microfono integrado y correccion de luz automatica.', 34.99, 'webcam.jpg', 60);

-- Resto del catalogo (id 6+), para dar volumen y variedad de categorias.
INSERT INTO products (name, description, price, image, stock) VALUES
('Teclado inalambrico compacto', 'Teclado compacto Bluetooth multi-dispositivo, cambia entre hasta 3 equipos con un boton.', 39.99, 'teclado-compacto.jpg', 45),
('Raton gaming 16000 DPI', 'Raton gaming con sensor de 16000 DPI, perfiles programables y iluminacion RGB.', 29.99, 'raton-gaming.jpg', 70),
('Alfombrilla XXL para escritorio', 'Alfombrilla extendida antideslizante, cubre teclado y raton, base de goma.', 14.99, 'alfombrilla.jpg', 120),
('Mando inalambrico compatible PC', 'Mando inalambrico con vibracion y gatillos analogicos, compatible con PC y Android.', 44.99, 'mando.jpg', 40),
('Memoria RAM DDR4 16GB 3200MHz', 'Kit dual channel 2x8GB, disipador de aluminio, compatible con placas AM4 e Intel.', 54.99, 'ram.jpg', 55),
('Tarjeta grafica gama media 8GB', 'Tarjeta grafica 8GB GDDR6, ideal para juegos en 1440p con trazado de rayos.', 399.99, 'grafica.jpg', 12),
('Procesador 6 nucleos', 'Procesador de 6 nucleos y 12 hilos, hasta 4.6GHz en modo turbo.', 189.99, 'procesador.jpg', 18),
('Placa base ATX gama media', 'Placa base ATX con WiFi integrado, soporte para memoria DDR4 y multiples M.2.', 129.99, 'placa-base.jpg', 25),
('Fuente de alimentacion 650W', 'Fuente modular 650W certificacion 80+ Bronze, ventilador de bajo ruido.', 69.99, 'fuente.jpg', 30),
('Kit de refrigeracion liquida 240mm', 'Refrigeracion liquida AIO 240mm con iluminacion ARGB sincronizable.', 89.99, 'refrigeracion.jpg', 22),
('Caja ATX cristal templado', 'Caja de PC ATX con panel lateral de cristal templado y 4 ventiladores incluidos.', 74.99, 'caja.jpg', 28),
('SSD SATA 500GB', 'Disco solido SATA de 500GB, reemplazo ideal para portatiles y equipos antiguos.', 39.99, 'ssd-sata.jpg', 60),
('Disco duro externo 2TB', 'Disco duro externo USB 3.0 de 2TB para copias de seguridad y almacenamiento.', 64.99, 'disco-externo.jpg', 40),
('Memoria USB 128GB', 'Pendrive USB 3.1 de 128GB con velocidad de lectura de hasta 150MB/s.', 15.99, 'usb.jpg', 100),
('Tarjeta microSD 256GB', 'Tarjeta microSD clase 10 de 256GB, ideal para camaras y consolas portatiles.', 24.99, 'microsd.jpg', 90),
('Monitor 24" Full HD IPS', 'Monitor 24 pulgadas Full HD panel IPS, colores fieles para oficina y teletrabajo.', 129.99, 'monitor-24.jpg', 33),
('Monitor curvo 32" QHD', 'Monitor curvo 32 pulgadas QHD 165Hz, panel VA para una experiencia inmersiva.', 349.99, 'monitor-curvo.jpg', 10),
('Monitor portatil 15.6" USB-C', 'Monitor portatil USB-C, perfecto como segunda pantalla para tu portatil.', 159.99, 'monitor-portatil.jpg', 15),
('Auriculares gaming 7.1', 'Auriculares gaming con sonido envolvente 7.1 y microfono retractil con cancelacion de ruido.', 59.99, 'auriculares.jpg', 45),
('Altavoces 2.1 de escritorio', 'Set de altavoces 2.1 con subwoofer independiente, entrada USB y jack de 3.5mm.', 44.99, 'altavoces.jpg', 38),
('Barra de sonido compacta', 'Barra de sonido compacta con Bluetooth, ideal para monitor o televisor pequeno.', 79.99, 'barra-sonido.jpg', 20),
('Microfono USB de condensador', 'Microfono USB de condensador con soporte antivibracion, ideal para streaming y podcasts.', 69.99, 'microfono.jpg', 25),
('Router WiFi 6 AX3000', 'Router WiFi 6 de doble banda, hasta 8 dispositivos simultaneos sin perder velocidad.', 89.99, 'router.jpg', 24),
('Repetidor WiFi largo alcance', 'Repetidor WiFi para ampliar la cobertura de tu red en zonas con poca senal.', 29.99, 'repetidor.jpg', 50),
('Switch de red 8 puertos', 'Switch de red Gigabit de 8 puertos, plug and play, ideal para pequenas oficinas.', 34.99, 'switch.jpg', 32),
('Adaptador USB WiFi AC1200', 'Adaptador USB WiFi de doble banda AC1200, compatible con PC y portatil.', 19.99, 'adaptador-wifi.jpg', 65),
('Impresora multifuncion WiFi', 'Impresora multifuncion con WiFi: impresion, escaneo y copia desde el movil.', 119.99, 'impresora.jpg', 15),
('Silla de oficina ergonomica', 'Silla ergonomica con respaldo ajustable y reposabrazos 3D para largas jornadas.', 179.99, 'silla.jpg', 12),
('Lampara LED de escritorio', 'Lampara LED regulable con puerto USB de carga integrado.', 22.99, 'lampara.jpg', 55),
('Hub USB-C 7 en 1', 'Hub USB-C con salida HDMI, puertos USB 3.0, lector de tarjetas SD y carga PD.', 34.99, 'hub.jpg', 48),
('Funda para portatil 15.6"', 'Funda acolchada resistente al agua para portatiles de hasta 15.6 pulgadas.', 17.99, 'funda.jpg', 70),
('Mochila para portatil antirrobo', 'Mochila antirrobo con compartimento acolchado y puerto USB de carga externo.', 39.99, 'mochila.jpg', 42),
('Bateria externa 20000mAh', 'Power bank de 20000mAh con carga rapida y doble puerto USB de salida.', 29.99, 'powerbank.jpg', 58),
('Cable USB-C a USB-C 2m', 'Cable trenzado USB-C a USB-C de 2 metros, carga rapida de hasta 100W.', 9.99, 'cable-usb-c.jpg', 150);

-- Opiniones de clientes. Se mantienen las dos originales (id 1 y 3) y se
-- amplian con muchas mas para que el catalogo tenga aspecto de tienda real.
INSERT INTO reviews (product_id, user_id, comment, rating, created_at) VALUES
(1, 2,  'Muy buen teclado, el sonido de los switches es genial.', 5, '2026-02-14 10:12:00'),
(3, 3,  'Excelente monitor, se nota la fluidez en juegos competitivos.', 5, '2026-02-18 19:40:00'),
(1, 4,  'Llevo tres meses usandolo a diario y sigue como el primer dia.', 5, '2026-02-20 08:05:00'),
(1, 9,  'El cable es un poco corto pero por lo demas perfecto.', 4, '2026-03-01 12:30:00'),
(2, 5,  'Comodo para partidas largas, la bateria dura muchisimo.', 5, '2026-02-22 21:15:00'),
(2, 11, 'Buena relacion calidad-precio, aunque el click es algo ruidoso.', 4, '2026-03-05 09:50:00'),
(3, 5,  'La curva de 165Hz se nota muchisimo respecto a mi monitor anterior.', 5, '2026-03-08 22:10:00'),
(3, 12, 'Los colores son muy fieles, perfecto para retoque fotografico tambien.', 4, '2026-03-12 17:00:00'),
(4, 8,  'Velocidades de lectura tal cual las anuncia el fabricante.', 5, '2026-02-25 11:20:00'),
(4, 15, 'Instalacion sencilla, mi equipo arranca en segundos ahora.', 5, '2026-03-02 16:45:00'),
(5, 7,  'Buena calidad de imagen para directos, el microfono se queda algo corto.', 4, '2026-03-04 20:05:00'),
(5, 6,  'Perfecta para videollamadas de trabajo, facil de instalar.', 5, '2026-03-06 09:30:00'),
(6, 10, 'Se conecta sin problema a mi tablet y a mi portatil a la vez.', 5, '2026-03-10 14:00:00'),
(7, 5,  'Los perfiles RGB se guardan bien, el software es sencillo de usar.', 5, '2026-03-11 23:20:00'),
(7, 4,  'Un poco pesado pero el agarre es excelente.', 4, '2026-03-14 18:10:00'),
(8, 14, 'Tamano ideal, cubre todo mi escritorio sin arrugarse.', 5, '2026-03-15 10:40:00'),
(9, 5,  'Funciona genial con mis juegos de PC favoritos, sin cables sueltos.', 4, '2026-03-16 21:00:00'),
(10, 9, 'Subida notable de rendimiento en tareas con muchas pestañas abiertas.', 5, '2026-03-18 08:55:00'),
(10, 13,'Facil de instalar, compatible con mi placa sin problema.', 5, '2026-03-20 13:25:00'),
(11, 4, 'Mueve todo en 1440p sin bajar de los 60fps, muy contenta.', 5, '2026-03-22 19:10:00'),
(11, 15,'Algo cara pero el salto de rendimiento merece la pena.', 4, '2026-03-25 22:30:00'),
(12, 9, 'Rinde muy bien para tareas de virtualizacion y compilacion.', 5, '2026-03-27 09:15:00'),
(13, 9, 'WiFi integrado funciona de maravilla, buena estabilidad.', 4, '2026-03-29 15:50:00'),
(14, 12,'Silenciosa incluso con carga alta, buen cableado incluido.', 5, '2026-04-01 11:05:00'),
(15, 7, 'Las temperaturas bajaron muchisimo respecto al disipador de serie.', 5, '2026-04-03 20:40:00'),
(16, 13,'Se ve genial montada y el cristal no acumula tantas huellas como esperaba.', 4, '2026-04-05 17:25:00'),
(17, 8, 'Le dio una segunda vida a mi portatil antiguo.', 5, '2026-04-07 09:00:00'),
(18, 8, 'Uso este disco para respaldar todas mis sesiones de fotos.', 5, '2026-04-09 14:35:00'),
(19, 11,'Rapido y fiable, lo llevo siempre en la mochila.', 4, '2026-04-11 19:50:00'),
(20, 8, 'Perfecta para mi camara de accion, sin cortes al grabar en 4K.', 5, '2026-04-13 08:20:00'),
(21, 6, 'Ideal para trabajar todo el dia sin cansar la vista.', 5, '2026-04-15 12:10:00'),
(21, 13,'Buena opcion para la oficina, facil de ajustar en altura con el soporte.', 4, '2026-04-17 16:45:00'),
(22, 5, 'La curvatura se nota mucho en juegos de mundo abierto.', 5, '2026-04-19 21:30:00'),
(23, 15,'Perfecto como segunda pantalla cuando trabajo fuera de casa.', 5, '2026-04-21 10:15:00'),
(24, 7, 'El sonido envolvente ayuda mucho a ubicar pasos en los juegos.', 5, '2026-04-23 22:00:00'),
(24, 5, 'Comodos incluso en sesiones de varias horas.', 4, '2026-04-25 20:10:00'),
(25, 6, 'Suenan muy bien para el precio, el subwoofer se nota en las peliculas.', 4, '2026-04-27 18:35:00'),
(26, 7, 'Facil de emparejar y con buena autonomia de bateria.', 5, '2026-04-29 21:55:00'),
(27, 7, 'La calidad de grabacion mejoro muchisimo mis directos.', 5, '2026-05-01 19:20:00'),
(28, 9, 'Cobertura excelente incluso en las habitaciones mas alejadas del router.', 5, '2026-05-03 10:05:00'),
(28, 14,'Facil de configurar, la app es bastante intuitiva.', 4, '2026-05-05 13:40:00'),
(29, 14,'Solucionó los cortes de WiFi en el piso de arriba.', 4, '2026-05-07 17:15:00'),
(30, 9, 'Cumple perfectamente para montar la red de la oficina.', 5, '2026-05-09 09:50:00'),
(31, 14,'Buena velocidad, se instala sin drivers adicionales.', 4, '2026-05-11 14:30:00'),
(32, 13,'Escanea rapido y la conexion WiFi va sin cortes.', 4, '2026-05-13 20:00:00'),
(33, 6, 'Mi espalda lo agradece despues de tantas horas sentada.', 5, '2026-05-15 12:25:00'),
(34, 6, 'Luz calida muy agradable para trabajar de noche.', 5, '2026-05-17 21:45:00'),
(35, 10,'Imprescindible para mi portatil que solo tiene puertos USB-C.', 5, '2026-05-19 16:10:00'),
(36, 11,'Protege muy bien, se nota la calidad del acolchado.', 5, '2026-05-21 11:35:00'),
(37, 12,'Comoda de llevar y el compartimento para el portatil es muy seguro.', 4, '2026-05-23 09:20:00'),
(38, 10,'Carga mi telefono varias veces sin problema, muy recomendable.', 5, '2026-05-25 19:05:00'),
(39, 15,'Cable resistente, no se ha deshilachado tras meses de uso diario.', 5, '2026-05-27 08:45:00');

-- Pedidos de ejemplo para que "Mis pedidos" y el panel de administracion
-- tengan historico real desde el primer arranque (todos con total >= 5,
-- para no interferir con el codigo de verificacion de logica de negocio
-- que el alumnado debe obtener manipulando el precio en el carrito).
INSERT INTO orders (user_id, total, card_snapshot, status, created_at) VALUES
(2,  79.97,  '4222222222222222', 'entregado', '2026-03-02 10:30:00'),
(3,  119.96, '4333333333333333', 'entregado', '2026-03-10 16:20:00'),
(4,  399.99, '4444555566667777', 'enviado',   '2026-04-01 09:15:00'),
(5,  89.98,  '4123456789012345', 'entregado', '2026-04-15 18:40:00'),
(6,  309.98, '4555000011112222', 'pagado',    '2026-05-05 11:05:00'),
(7,  149.98, '4321432143214321', 'entregado', '2026-05-20 20:30:00'),
(8,  79.98,  '4666777788889999', 'pagado',    '2026-06-01 13:50:00'),
(9,  159.97, '4111222233334444', 'enviado',   '2026-06-15 10:10:00'),
(11, 84.98,  '4777888899990000', 'pendiente', '2026-07-05 17:25:00');

INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES
(1, 1, 1, 49.99), (1, 8, 2, 14.99),
(2, 4, 1, 89.99), (2, 39, 3, 9.99),
(3, 11, 1, 399.99),
(4, 24, 1, 59.99), (4, 7, 1, 29.99),
(5, 21, 1, 129.99), (5, 33, 1, 179.99),
(6, 27, 1, 69.99), (6, 26, 1, 79.99),
(7, 17, 2, 39.99),
(8, 28, 1, 89.99), (8, 30, 2, 34.99),
(9, 9, 1, 44.99), (9, 6, 1, 39.99);

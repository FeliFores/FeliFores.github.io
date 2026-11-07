-- FF_LIBROS: importá este script en phpMyAdmin.
-- Crea la estructura y 8 registros de prueba.
-- Si tu hosting ya tiene una base de datos asignada, seleccionála en phpMyAdmin.
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS libros (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(150) NOT NULL,
  genero VARCHAR(80) NOT NULL,
  descripcion TEXT NOT NULL,
  imagen VARCHAR(255) NOT NULL,
  enlace VARCHAR(1000) NOT NULL,
  destacado TINYINT(1) NOT NULL DEFAULT 0,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS administradores (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(80) NOT NULL UNIQUE,
  clave_hash VARCHAR(255) NOT NULL,
  debe_cambiar_clave TINYINT(1) NOT NULL DEFAULT 1
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO libros (titulo, genero, descripcion, imagen, enlace, destacado) VALUES
  ('Coraline', 'Aventura', 'Una historia de aventura y fantasía en la que Coraline descubre un mundo alternativo.', 'imagenes/Caroline.jpg', 'https://docs.google.com/file/d/0B_Q4k6k8MyYJbkFoLVdlWTJ0bzA/view?resourcekey=0-ReugSdUUQH0747n74-_xDg', 0),
  ('Frankenstein', 'Ciencia Ficción', 'La historia de un científico que da vida a una criatura creada a partir de distintos cuerpos.', 'imagenes/frankenstein-9781982146160_xlg.jpg', 'https://drive.google.com/file/d/0B8Bcptq_5FyXamtia1VwQ3hxNGM/view?resourcekey=0-iM1c9DHUqbsCZUHsIbO9zg', 0),
  ('Otelo', 'Tragedia', 'Una tragedia de William Shakespeare atravesada por los celos, la manipulación y la traición.', 'imagenes/otelo_classicos_14x21_9788525429926_hd.jpg', 'https://drive.google.com/file/d/0B1JjW_N9HUizLVdVN28wZjR3UWs/view?resourcekey=0-kLVCrs1FoVmyA7CVpoLinA', 0),
  ('It', 'Terror', 'Una novela de terror en la que un grupo de amigos se enfrenta a una presencia aterradora.', 'imagenes/terror.jpeg', 'https://docs.google.com/file/d/0B3N8jtudSydeYWgwbzJxOXZYS1U/edit?resourcekey=0-G7ZxKLWMipMM8vqegxBU3Q', 1),
  ('Romeo y Julieta', 'Romance', 'La historia de dos jóvenes enamorados pertenecientes a familias enfrentadas.', 'imagenes/Rome y Julieta.webp', 'https://ia800305.us.archive.org/29/items/romeo-y-julieta-texto-completo/Romeo%20y%20Julieta%20-%20William%20Shakespeare.pdf', 0),
  ('Los Escorpiones', 'Ficción', 'Una propuesta de ficción contemporánea incorporada al catálogo de FF_LIBROS.', 'imagenes/Los-Escorpiones-i7n24218684.jpg', 'https://www.casadellibro.com/libro-los-escorpiones/9788426418784/14461451', 0),
  ('Drácula', 'Terror', 'El clásico de Bram Stoker que relata la misteriosa historia del conde Drácula.', 'imagenes/DRAKULA.jpeg', 'https://www.gutenberg.org/ebooks/345', 0),
  ('Orgullo y prejuicio', 'Romance', 'La novela de Jane Austen sobre Elizabeth Bennet, la sociedad y los prejuicios.', 'imagenes/Orgullo y prejuicio.jpg', 'https://www.gutenberg.org/ebooks/1342', 1);

-- La contraseña inicial se entregó por separado: nunca subirla a GitHub.
INSERT INTO administradores (usuario, clave_hash, debe_cambiar_clave)
VALUES ('admin', '$2y$12$HDdCuyOenXrqmQamCVbyfut.HTdIYr/icVh9IUSqElOtlH3Mmsw.O', 1);

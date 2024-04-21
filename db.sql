CREATE DATABASE acervo;

USE acervo;

CREATE TABLE fotos(
	id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    imagem VARCHAR(100),
	descricao TEXT,
    data_imagem DATE
);
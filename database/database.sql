DROP DATABASE IF EXISTS petshop;

CREATE DATABASE petshop;

USE petshop;

    KEY idx_funcionarios_email (email)
);

INSERT INTO funcionarios (nome, email, senha)
VALUES
('Funcionário PetLove', 'funcionario@email.com', '123456');


CREATE TABLE chefes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    UNIQUE KEY uq_chefes_email (email),
    KEY idx_chefes_email (email)
);

INSERT INTO chefes (nome, email, senha)
VALUES
('Chefe PetLove', 'chefe@email.com', '123456');


CREATE TABLE animais (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    especie VARCHAR(50) NOT NULL,
    raca VARCHAR(100) DEFAULT NULL,
    idade INT DEFAULT NULL,
    cliente_id INT NOT NULL,
    KEY idx_animais_cliente (cliente_id),
    CONSTRAINT animais_ibfk_1 FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    CONSTRAINT chk_animais_especie CHECK (
        especie IN ('Cachorro', 'Gato', 'Pássaro', 'Coelho', 'Outro')
    ),
    CONSTRAINT chk_animais_idade CHECK (idade >= 0)
);

INSERT INTO animais (id, nome, especie, raca, idade, cliente_id)
VALUES
(1, 'Rex', 'Cachorro', 'Golden Retriever', 5, 2),
(2, 'Luna', 'Gato', 'Siamês', 3, 3),
(3, 'Thor', 'Cachorro', 'Labrador', 4, 4);


CREATE TABLE servicos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao VARCHAR(255) DEFAULT NULL,
    preco DECIMAL(10,2) NOT NULL,
    CONSTRAINT chk_servicos_preco CHECK (preco >= 0)
);

INSERT INTO servicos (id, nome, descricao, preco)
VALUES
(1, 'Banho', 'Serviço de banho', 50.00),
(2, 'Tosa', 'Serviço de tosa', 70.00),
(3, 'Banho e Tosa', 'Serviço completo de banho e tosa', 100.00),
(4, 'Consulta Veterinária', 'Consulta com veterinário', 150.00);

CREATE TABLE produtos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao VARCHAR(255) DEFAULT NULL,
    descricao_completa TEXT DEFAULT NULL,
    preco DECIMAL(10,2) NOT NULL,
    estoque INT NOT NULL DEFAULT 0,
    categoria VARCHAR(50) NOT NULL,
    imagem VARCHAR(255) DEFAULT NULL,

    UNIQUE KEY uq_produtos_nome (nome),
    KEY idx_produtos_nome (nome),
    KEY idx_produtos_categoria (categoria),

    CONSTRAINT chk_produtos_estoque CHECK (estoque >= 0),
    CONSTRAINT chk_produtos_preco CHECK (preco >= 0)
);


INSERT INTO produtos
(id, nome, descricao, descricao_completa, preco, estoque, categoria, imagem)
VALUES

(1,
 'Ração para Cachorro Adulto 15kg',
 'Alimentação completa para cães.',
 'Ração completa para cães adultos, rica em proteínas e vitaminas essenciais. Ideal para cães de porte médio a grande. Embalagem de 15kg, com validade prolongada.',
 149.90,
 20,
 'Ração',
 'ID1.jpg'),

(2,
 'Ração para Gatos Adultos 10kg',
 'Alimentação nutritiva para gatos.',
 'Ração nutritiva formulada especialmente para gatos adultos, com nutrientes que fortalecem o sistema imunológico e mantêm o pelo saudável. Embalagem de 10kg.',
 109.90,
 20,
 'Ração',
 'ID2.jpg'),


(3,
 'Mordedor',
 'Ideal para cães brincarem.',
 'Mordedor resistente e durável, ideal para cães que adoram roer. Ajuda na saúde bucal e entretém seu pet por horas.',
 27.90,
 15,
 'Brinquedos',
 'ID3.jpg'),

(4,
 'Bola',
 'Diversão para cães e gatos.',
 'Bola resistente para brincadeiras ao ar livre ou dentro de casa. Diverte cães e gatos de todos os tamanhos.',
 15.90,
 20,
 'Brinquedos',
 'ID4.jpg'),

(5,
 'Varinha para Gatos',
 'Ideal para brincar e distrair seu gato.',
 'Varinha interativa com penas, perfeita para estimular o instinto de caça do seu gato e fortalecer o vínculo com o tutor.',
 20.90,
 15,
 'Brinquedos',
 'ID5.jpg'),

(6,
 'Arranhador',
 'Para seu gato se divertir.',
 'Arranhador robusto para gatos, ajuda a proteger seus móveis e proporciona diversão e exercício para o pet.',
 62.90,
 10,
 'Brinquedos',
 'ID6.jpg'),


(7,
 'Shampoo',
 'Limpeza dos pelos de cães e gatos.',
 'Shampoo especial para cães e gatos, com fórmula suave que limpa profundamente sem ressecar a pele.',
 29.90,
 20,
 'Higiene',
 'ID7.jpg'),

(8,
 'Condicionador',
 'Para cuidar dos pelos.',
 'Condicionador hidratante que deixa o pelo do seu pet macio, brilhante e fácil de escovar.',
 34.90,
 20,
 'Higiene',
 'ID8.jpg'),

(9,
 'Escova',
 'Cuidados com os pelos.',
 'Escova de cerdas macias, ideal para remover pelos soltos e manter a pelagem do seu pet sempre bonita e saudável.',
 25.90,
 20,
 'Higiene',
 'ID9.jpg'),


(10,
 'Coleira',
 'Para passeios seguros.',
 'Coleira resistente e ajustável, garante conforto e segurança durante os passeios com seu pet.',
 39.90,
 25,
 'Acessórios',
 'ID10.jpg'),

(11,
 'Guia',
 'Para passear com seu pet.',
 'Guia reforçada, ideal para passeios tranquilos e seguros com seu cão de qualquer porte.',
 44.90,
 20,
 'Acessórios',
 'ID11.jpg'),

(12,
 'Cama',
 'Conforto para descansar.',
 'Cama confortável e acolchoada, proporciona o descanso ideal para seu pet dormir com conforto.',
 99.90,
 10,
 'Acessórios',
 'ID12.jpg'),


(13,
 'Petisco Pequeno para Cães',
 'Para cães pequenos.',
 'Petisco crocante especialmente formulado para cães de pequeno porte, ótimo para recompensas durante o treinamento.',
 12.90,
 30,
 'Petiscos',
 'ID13.jpg'),

(14,
 'Petisco Médio para Cães',
 'Para cães médios.',
 'Petisco saboroso para cães de porte médio, rico em nutrientes e ideal para o dia a dia.',
 18.90,
 30,
 'Petiscos',
 'ID14.png'),

(15,
 'Petisco Grande para Cães',
 'Para cães grandes.',
 'Petisco robusto para cães de grande porte, perfeito para matar a vontade de mastigar.',
 24.90,
 30,
 'Petiscos',
 'ID15.png'),


(16,
 'Petisco Pequeno para Gatos',
 'Porção pequena.',
 'Petisco em porção pequena, ideal para gatos que preferem lanches leves ao longo do dia.',
 10.90,
 30,
 'Petiscos',
 'ID16.jpg'),

(17,
 'Petisco Médio para Gatos',
 'Porção média.',
 'Petisco em porção média, perfeito para recompensar seu gato com sabor e nutrição.',
 17.90,
 30,
 'Petiscos',
 'ID17.jpg'),

(18,
 'Petisco Grande para Gatos',
 'Porção grande.',
 'Petisco em porção grande, ideal para gatos com apetite maior, sem abrir mão do sabor.',
 26.90,
 30,
 'Petiscos',
 'ID18.jpg');


CREATE TABLE vendas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT NOT NULL,
    data DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    KEY idx_vendas_cliente (cliente_id),
    KEY idx_vendas_data (data),
    CONSTRAINT vendas_ibfk_1 FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    CONSTRAINT chk_vendas_total CHECK (total >= 0)
);


CREATE TABLE itens_venda (
    id INT PRIMARY KEY AUTO_INCREMENT,
    venda_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    KEY idx_itens_venda_venda (venda_id),
    KEY idx_itens_venda_produto (produto_id),
    CONSTRAINT itens_venda_ibfk_1 FOREIGN KEY (venda_id) REFERENCES vendas(id),
    CONSTRAINT itens_venda_ibfk_2 FOREIGN KEY (produto_id) REFERENCES produtos(id),
    CONSTRAINT chk_itens_preco CHECK (preco_unitario >= 0),
    CONSTRAINT chk_itens_quantidade CHECK (quantidade > 0)
);


CREATE TABLE agendamentos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    animal_id INT NOT NULL,
    servico_id INT NOT NULL,
    tipo_banho VARCHAR(50) DEFAULT NULL,
    tipo_tosa VARCHAR(50) DEFAULT NULL,
    data DATE NOT NULL,
    horario TIME NOT NULL,
    status VARCHAR(30) DEFAULT 'Agendado',
    observacao VARCHAR(255) DEFAULT NULL,
    KEY idx_agendamentos_animal (animal_id),
    KEY idx_agendamentos_servico (servico_id),
    KEY idx_agendamentos_data (data),
    KEY idx_agendamentos_tipo_banho (tipo_banho),
    KEY idx_agendamentos_tipo_tosa (tipo_tosa),

    CONSTRAINT agendamentos_ibfk_1
        FOREIGN KEY (animal_id) REFERENCES animais(id),

    CONSTRAINT agendamentos_ibfk_2
        FOREIGN KEY (servico_id) REFERENCES servicos(id),

    CONSTRAINT chk_agendamentos_tipo_banho CHECK (
        tipo_banho IS NULL OR tipo_banho IN (
            'Banho Tradicional',
            'Banho Hidratante',
            'Banho Antipulgas',
            'Banho para Filhotes'
        )
    ),

    CONSTRAINT chk_agendamentos_tipo_tosa CHECK (
        tipo_tosa IS NULL OR tipo_tosa IN (
            'Tosa Higiênica',
            'Tosa na Máquina',
            'Tosa na Tesoura',
            'Tosa Bebê'
        )
    ),

    CONSTRAINT chk_agendamentos_status CHECK (
        status IN (
            'Agendado',
            'Confirmado',
            'Concluído',
            'Cancelado'
        )
    )
);


CREATE TABLE observacoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    animal_id INT NOT NULL,
    texto VARCHAR(255) NOT NULL,
    data DATETIME DEFAULT CURRENT_TIMESTAMP,
    KEY idx_observacoes_animal (animal_id),

    CONSTRAINT observacoes_ibfk_1
        FOREIGN KEY (animal_id) REFERENCES animais(id)
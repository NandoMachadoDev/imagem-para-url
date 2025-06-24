# 📘 Guia de Instalação e Uso da Aplicação **Imagem para URL** (via XAMPP + Ngrok)

Esta aplicação permite que você cole uma imagem (`Ctrl+V`) e obtenha uma **URL acessível**, ideal para usar em ferramentas como o Microsoft Planner, que não permitem colar imagens diretamente. As imagens são armazenadas localmente e organizadas por usuário.

---

## ✅ Pré-requisitos

- [XAMPP](https://www.apachefriends.org/index.html) instalado  
- [Git](https://git-scm.com/downloads) instalado  
- Conta gratuita no [Ngrok](https://ngrok.com)  
- Conta no GitHub *(opcional, caso deseje contribuir)*  
- Navegador moderno (Chrome, Firefox, Edge)

---

## ⚙️ Etapas para configurar a aplicação

### 1. Clone ou copie o projeto para o XAMPP

Abra o terminal (CMD ou Bash) e execute:

bash
cd C:\xampp\htdocs\
git clone https://github.com/seu-usuario/imagem-para-url.git


Ou simplesmente baixe o ZIP do repositório e extraia em:
C:\xampp\htdocs\imagem-para-url


---

🛠️ 2. Inicie o XAMPP
Abra o painel do XAMPP

Clique em Start no Apache e no MySQL

---

🗄️ 3. Crie o banco de dados e as tabelas
Acesse: http://localhost/phpmyadmin

Crie um banco de dados chamado: imagem_url

Importe o script SQL abaixo:

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE imagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    caminho VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

---

🌐 4. Acesse a aplicação localmente
No navegador, acesse:
http://localhost/imagem-para-url/

Cadastre-se com nome, e-mail e senha

Faça login

Cole uma imagem com Ctrl+V

Uma URL será gerada automaticamente

---

🌍 5. Tornar a URL acessível fora da máquina (Ngrok)
A. Instale o Ngrok (apenas uma vez)
Baixe: https://ngrok.com/download

Extraia para uma pasta (ex: C:\ngrok)

No terminal:

CMD ou bash

ngrok config add-authtoken SEU_TOKEN_AQUI

(O token está disponível no seu dashboard do Ngrok)

B. Inicie o Ngrok
Com o Apache rodando, execute:
ngrok http 80

Será exibido uma URL como: https://ef5b-201-xxx-xxx.ngrok.io
Use essa URL para acessar a aplicação de qualquer lugar

---

Dicas finais
As imagens são salvas na pasta uploads/NOME_DO_USUARIO/

O histórico, exclusão e download em .zip funcionam por usuário

A URL do Ngrok muda sempre que você fecha o terminal (salvo plano Pro)

Para tornar a URL fixa, use plano Ngrok Pro ou faça deploy no Render

---

👨‍💻 Desenvolvido por
Fernando Machado
github.com/NandoMachadoDev

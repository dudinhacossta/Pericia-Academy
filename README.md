# 🕵️‍♂️ Perícia Academy - ForensicX

*Perícia Academy* é uma plataforma web desenvolvida para a venda e gerenciamento de cursos online com foco em *investigação digital, segurança da informação e áreas correlatas. O projeto foi desenvolvido pela equipe da ForensicX, utilizando HTML, CSS, PHP e Bootstrap.

# 🕵️ Instruções de Instalação

## ⚙️ Requisitos
- [XAMPP](https://www.apachefriends.org/index.html) (com Apache e MySQL)
- [Visual Studio Code](https://code.visualstudio.com/) (recomendado)

---

## 🚀 Como Executar o Sistema

1. **Baixe o projeto:**
   - Faça o download do arquivo `pericia.zip` para sua máquina.

2. **Extraia o conteúdo:**
   - Extraia os arquivos e abra a pasta do projeto na sua IDE (como o VS Code).

3. **Configure o ambiente local:**
   - Copie a pasta extraída para dentro de:
     ```
     C:\xampp\htdocs\
     ```

4. **Importe o banco de dados:**
   - Abra o XAMPP e inicie os serviços **Apache** e **MySQL**.
   - Acesse o **phpMyAdmin** pelo navegador:
     ```
     http://localhost/phpmyadmin
     ```
   - Importe o arquivo `.sql` fornecido com o projeto para criar as tabelas necessárias.

5. **Acesse o sistema:**
   - No navegador, digite:
     ```
     http://localhost/pericia
     ```

---

## 💡 Observações
- Certifique-se de que a pasta do projeto esteja corretamente posicionada dentro de `htdocs`.
- Verifique se não há conflitos de porta com o Apache ou MySQL no XAMPP.

---
## 👨‍💻 Mural dos Desenvolvedores

| Nome                 | GitHub                                      | LinkedIn                                     |
|----------------------|---------------------------------------------|----------------------------------------------|
| Maria Eduarda      | [GitHub](https://github.com/dudinhacossta/Pericia-Academy/) | [linkedin.com/](https://www.linkedin.com/in/maria-e-bb0233248/) |
> *Quer fazer parte da equipe da ForensicX?* Entre em contato!

---

## 📞 Contato

Entre em contato conosco através da página 61998246659 ou pelo e-mail:  
📧 duda06736@gmail.com

---

## 📄 Licença

Este projeto está sob a Licença MIT. Consulte o arquivo LICENSE para mais detalhes.

---

## 🚀 Funcionalidades Principais

### 👥 Acesso e Cadastro
- Login e Cadastro de usuários (Alunos e Professores)
- Redirecionamento com base no perfil:
  - *Aluno*: acesso aos cursos comprados, carrinho e minha conta
  - *Professor*: acesso ao painel de cursos, dashboard e criação de novos cursos

---

### 🎓 Módulo de Cursos
- Catálogo com cursos investigativos disponíveis
- Visualização detalhada de cada curso
- Sistema de compra com integração ao carrinho
- CRUD completo de cursos para professores (Criar, Editar, Listar e Excluir)

---

### 🛒 Carrinho de Compras
- Visualização de cursos adicionados
- Quantidade de itens no carrinho
- Opção de finalizar compra
- Redirecionamento automático ao adicionar curso

---

### 💳 Pagamento Seguro
- Integração com *SecurePay* da ForensicX
- Opções de pagamento:
  - Cartão de Crédito
  - PIX (com geração automática de QR Code)

---

### 📊 Dashboard (Visão do Professor)
- Gráficos e métricas em tempo real:
  - 📈 Faturamento total
  - 👨‍🎓 Total de alunos
  - 📚 Cursos ativos
  - 💰 Soma dos preços dos cursos

---

## 🖥️ Tecnologias Utilizadas

- *HTML5*
- *CSS3*
- *Bootstrap 5*
- *PHP 7+*
- *JavaScript (mínimo uso)*
- *SecurePay (Gateway de pagamento)*

---

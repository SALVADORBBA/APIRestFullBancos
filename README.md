# API do Itaú para Fluxo de Boleto Bancário

Bem-vindo à API do Itaú para gerenciamento de boletos bancários. Esta API permite a criação, atualização e impressão de boletos do banco Itaú. Simplifique o processo de geração de boletos e ofereça uma experiência de pagamento eficiente para seus clientes.

## Recursos Principais

### Criação de Boleto
Crie um novo boleto para um beneficiário com informações como valor, vencimento e identificação.

**Importante Instalação via composer:**
    -  `mpdf`   composer require mpdf/mpdf
    - `picqer`  composer require picqer/php-barcode-generator   

**Parâmetros:**
- `valor` (decimal): O valor do boleto.
- `data_vencimento` (data): A data de vencimento do boleto.
- `beneficiario_id` (inteiro): O ID do beneficiário.
- Outros parâmetros relevantes.

### Atualização de Boleto
Atualize os detalhes de um boleto existente, como data de vencimento, valor ou informações do beneficiário.

**Endpoint:**

**Parâmetros:**
- `id` (inteiro): O ID único do boleto.
- `data_vencimento` (data): A nova data de vencimento do boleto.
- Outros parâmetros para atualização.

### Impressão de Boleto
Obtenha o link para a impressão do boleto gerado, pronto para ser apresentado ao cliente.

**Endpoint:** 

**Parâmetros:**
- `id` (inteiro): O ID único do boleto.

## Autenticação

Para utilizar esta API, é necessário autenticar-se através de credenciais fornecidas pelo Itaú. As credenciais devem ser incluídas nos cabeçalhos das requisições.
 
**Contribuição:**
 Agradecemos por considerar contribuir para a API do Itaú! O guia de contribuição pode ser encontrado na documentação da API.

Código de Conduta
Para garantir que a comunidade da API do Itaú seja acolhedora para todos, por favor, revise e siga o Código de Conduta.

Vulnerabilidades de Segurança
Se você descobrir uma vulnerabilidade de segurança na API , por favor, envie um e-mail para salvadorbba@gmail.com. Todas as vulnerabilidades de segurança serão tratadas prontamente.

Licença
A API de comunicação é um software de código aberto licenciado sob a licença MIT.

Autor: Rubens dos Santos
Contato: salvadorbba@gmail.com  / Whatsapp (71)99675-8056

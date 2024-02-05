# API Caixa Eletrônico

As tecnologias que compõe a base da API proposta são: 
- **Laravel 10**
- **PHP 8**
- **Redis**
- **Nginx**
- **Docker**

## Arquitetura

As escolhas arquiteturais levaram em consideração o tamanho da api, mas também a possibilidade de demonstrar repertório de soluções consolidadas e praticadas - em algum nível - dentro do mercado.  
O modelo MVC norteou a solução; a camada Service foi utilizada para trabalhar as regras de negócio, enquanto o Repository Pattern e inversão de dependência foram adotados para dar flexibilidade e maior independência entre as ações que envolvem os dados e os serviços. O DTO foi adotado para enviar dados, enquanto o padrão Request do Laravel ficou responsável pela entrada de informações validadas. Os Conversores foram utilizados para transformar dados de entrada em Models e o Adapter ficou responsável por modificar o response com o conteúdo e o status HTTP. Enums foram utilizados para padronizar as mensagens de erro e os valores financeiros descritos na documentação (10, 20, 50, 100).

## Persistência dos dados
A atividade proposta prevê o uso da memória para lidar com as informações, não possibilitando usar banco de dados. A estratégia adotada a partir disso foi utilizar o Redis para salvar os dados em cache. 
As informações sobre o caixa eletrônico puderam ser armazenadas sob a mesma chave, pois o caixa é único, mas para guardar em cache, as informações sobre os saques, a decisão tomada foi implementar uma lista encadeada, através da qual foi possível acessar todos os saques realizados. A chave utilizada nesse caso foi o UUID.
 
## Testes

Os testes de features e unitários foram escritos dentro da tecnologia padrão utilizada pelo Laravel. Todos os pontos descritos na documentação da API proposta foram cobertos. 

## Executar a aplicação

Para executar a aplicação é necessário ter o docker instalado na máquina e seguir os seguintes passos:
- Descompactar o arquivo .zip.
- Navegar com o prompt de comandos até a raiz do projeto.
-  Executar o comando:
	- > docker-compose up -d

A aplicação estará pronta para uso na **porta 8989**. 

Para executar os testes, os seguintes passos devem ser seguidos:
- Entrar no container da aplicação. Para isso é necessário navegar pelo prompt até a raiz da aplicação.
- Executar os seguintes comandos:
	- >docker-compose exec app bash
	- >php artisan test

## Endpoints
Esses são os endpoints da API:

|Ação            |Path                           |Método HTTP                  |
|----------------|-------------------------------|-----------------------------|
|Abastecimento   |`/api/fill`                    |POST                         |
|Saque           |`/api/withdraw`                |POST                         |

Os dois endpoints precisam receber dados no formato JSON, no body da requisição.
O padrão das chamadas e respostas estão descritos na documentação da atividade.

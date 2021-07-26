# Conversor de Moedas

Algoritmo recebe os identificadores da moeda de origem e de destino e o montante em decimal a ser convertido.

## QuickStart

**Com Docker Instalado**
```bash
foo@bar:~$ ./docker_console 1 USD BRL
```
**Com PHP Instalado**
```bash
foo@bar:~$ ./console 1 USD BRL
```

## Interface

O algoritmo pode ser executado pela linha de comando utilizando a sintaxe:
```
{valor_decimal} {codigo_moeda_origem} {codigo_moeda_destino}
```
Para converter 6,5 reais (BRL) para dolar americado (USD) use
```
6.5 BRL USD
```

## Cotações

Atualmente o algoritmo aceita cotações através de dois arquivos _./currencies.json_ e _./currencies.txt_ ambos na raiz do projeto com as seguintes sintaxes:
**./currencies.json**
```json
[
    {
        "from": "USD",
        "to": "BRL",
        "quotation": 5.65
    },
    //...
]
```
**./currencies.txt**
```
1 USD = 5.65 BRL
//...
```

Mas pode facilmente ser extendido para aceitar novas origens de dados e novas sintaxes.

## Executaveis

Os arquivos _./console_ e _./docker\_console_ rodam em linha de comando executando o algoritmo, sendo que _./console_ espera que o PHP esteja instalado, enquanto _./docker\_console_ espera que o docker esteja instalado para poder baixar a imagem **php:7.4**, criar e "levantar" um container com a mesma e por fim executar o script.
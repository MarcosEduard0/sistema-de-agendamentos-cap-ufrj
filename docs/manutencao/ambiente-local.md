# Ambiente local e configuração

[Voltar ao manual](../README.md)

Este capítulo é destinado ao responsável técnico. O ambiente de referência utiliza XAMPP no Windows e banco MySQL/MariaDB. Não altere os dados de produção para executar o sistema localmente.

## Preparar uma cópia local

1. Mantenha uma cópia do código na pasta servida pelo Apache.
2. Inicie Apache e MySQL no XAMPP.
3. Crie o banco local no phpMyAdmin.
4. Selecione o banco local e importe um dump com estrutura e dados.
5. Ajuste somente a configuração local para apontar para esse banco.
6. Acesse o sistema e confira login, perfil e agenda.

Requisitos de PHP e extensões devem ser conferidos com a versão efetivamente instalada. O uso do formatador de datas desta adaptação exige a extensão `intl`. A conexão de referência utiliza PDO MySQL.

## Arquivos de configuração

| Arquivo | Função |
| --- | --- |
| `local/config.php` | Dados específicos da instalação, incluindo conexão do banco. |
| `application/config/database.php` | Configuração padrão e carregamento dos dados de `local/config.php`. |
| `application/config/constants.php` | Versão exibida e ano fixo do rodapé. |

Não copie a configuração local do XAMPP sobre a configuração de produção. Não registre credenciais em exemplos ou documentos.

Na versão de referência, o rodapé mostra **Versão 2.6.9 © 2026**. O ano foi fixado intencionalmente; não muda automaticamente com o calendário.

## Atualização durante o desenvolvimento

O Apache executa o PHP a cada requisição. Salvar um arquivo não recarrega o navegador automaticamente. Use **F5** ou **Ctrl+F5** quando necessário para renovar CSS e JavaScript em cache. Uma ferramenta de recarga automática é uma configuração adicional, não um recurso atualmente documentado como instalado.

## Diagnóstico inicial

| Erro | Verificação |
| --- | --- |
| `Unknown database` | Nome do banco local e importação do dump. |
| `mysqli::real_connect(): No such file or directory` | Driver e host efetivos; confira se a configuração da instalação está sendo carregada. |
| HTTP 500 | Logs da aplicação em `local/logs` e logs do Apache/PHP. |
| Alteração visual não aparece | Arquivo e caminho enviados, versão carregada e cache do navegador. |

Uma mensagem de conexão não comprova perda dos dados. Confira primeiro configuração, serviço e acesso ao banco.

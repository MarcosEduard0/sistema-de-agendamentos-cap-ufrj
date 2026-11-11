# Usuários e configurações

[Voltar ao manual](../README.md)

## Usuários

Em **Usuários** (`/users`), use os filtros para localizar uma conta ou selecione **Adicionar Usuário**. Revise usuário de acesso, tipo, habilitação, e-mail, senha, dados pessoais e departamento.

Conceda o tipo **Administrador** somente às pessoas responsáveis pela operação do sistema. A conta de professor tem navegação e permissões próprias.

Para manutenção de contas existentes, use as ações da lista e confira o retorno ao salvar. Antes de excluir uma conta, leia a confirmação e avalie os registros associados.

### Importar usuários

A opção **Importar Usuários** recebe um arquivo CSV e permite definir valores padrão. Siga as etapas e o mapeamento apresentados na tela. Não reutilize um modelo de outra versão sem conferir os campos aceitos.

Faça um teste com uma pequena amostra antes da carga completa. Não inclua senhas, e-mails reais ou arquivos com dados pessoais nas capturas do manual.

> **Captura pendente:** lista de usuários com dados fictícios. Arquivo: `administrador/usuarios.png`.
<!-- Inserir quando disponível: ![Gestão de usuários](../imagens/administrador/usuarios.png) -->

## Configurações gerais

Abra **Configurações** (`/settings/general`).

| Seção | O que revisar |
| --- | --- |
| Agendamentos | Validade das contas, antecedência, máximo de reservas ativas, tipo de exibição, colunas e detalhes dos usuários. |
| Formatos de data | Fuso horário e apresentação das datas e períodos. |
| Mensagem de Login | Habilitação e texto do aviso na tela de acesso. |
| Modo de Manutenção | Habilitação e mensagem de manutenção. |

Leia a orientação de cada campo antes de alterar valores. Nas regras de antecedência e máximo de agendamentos ativos, **0** significa ausência daquele limite. Isso não elimina outras restrições, como ocupação, feriados e ano não agendável.

Após salvar, teste com um professor: o administrador possui permissões mais amplas e não é uma referência suficiente para validar as restrições desse perfil.

O modo de manutenção restringe a área de agendamentos para professores; não o trate como bloqueio de todas as rotas do site.

> **Captura pendente:** configurações gerais sem informações sensíveis. Arquivo: `administrador/configuracoes.png`.
<!-- Inserir quando disponível: ![Configurações gerais](../imagens/administrador/configuracoes.png) -->

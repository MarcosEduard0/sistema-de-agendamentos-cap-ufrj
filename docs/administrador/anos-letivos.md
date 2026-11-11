# Anos letivos

[Voltar ao manual](../README.md)

Abra **Anos letivos** (`/sessions`). A interface utiliza esse termo; os caminhos internos continuam com `sessions`.

## Cadastrar um ano

1. Clique em **Adicionar ano letivo**.
2. Informe nome, data de início e data de fim.
3. Defina **Agendável** conforme a disponibilidade desejada para professores.
4. Salve e abra o calendário do ano.
5. Associe os tipos de semana/trimestres às datas.
6. Revise os feriados e faça um teste com uma conta de professor.

Não sobreponha os intervalos de anos letivos. O sistema identifica o atual pelas datas; mantenha o cadastro coerente com o calendário da escola.

| Perfil | Consultar anos anteriores | Criar novos agendamentos |
| --- | --- | --- |
| Professor | Sim | Exige Agendável ativo e cumprimento das demais regras. |
| Administrador | Sim | Não é bloqueado pela flag Agendável; conflitos e consistência continuam relevantes. |

Desativar **Agendável** não exclui os registros existentes. O ano passa a oferecer consulta sem criação para professores.

> **Captura pendente:** formulário do ano letivo com Agendável. Arquivo: `administrador/ano-letivo.png`.
<!-- Inserir quando disponível: ![Cadastro do ano letivo](../imagens/administrador/ano-letivo.png) -->

## Aplicação em massa por intervalo

1. Abra o ano letivo.
2. Em **Aplicação em massa**, escolha a **Semana / trimestre**.
3. Em **Aplicar em**, escolha **Um intervalo de datas**.
4. Informe a data de início e a data de fim.
5. Clique em **Aplicar** e confira o calendário atualizado.

Exemplo: para aplicar o 2º Trimestre às semanas de 23 de fevereiro a 5 de abril de 2026, use **23/02/2026** e **05/04/2026**. Os limites são inclusivos. Para semanas completas, escolha a segunda-feira inicial e o domingo final.

O intervalo deve estar dentro do ano letivo. Datas fora dele mantêm a classificação anterior. A operação altera a classificação das datas, preservando os agendamentos existentes; revise possíveis efeitos sobre o planejamento de recorrências.

Para aplicar ao ano inteiro, escolha explicitamente **Todo o ano letivo**. Essa ação substitui as classificações anteriores de todas as datas desse ano.

> **Captura pendente:** aplicação em massa com intervalo preenchido. Arquivo: `administrador/aplicar-trimestre.png`.
<!-- Inserir quando disponível: ![Aplicação de trimestre por intervalo](../imagens/administrador/aplicar-trimestre.png) -->

## Ajuste manual e exclusão

O calendário também permite alternar a semana nas datas indicadas pela interface. Após ajustes manuais, use **Salvar**. Evite manter a página aberta com alterações não salvas enquanto executa uma aplicação em massa, pois um salvamento posterior pode reaplicar a seleção antiga.

Mantenha anos antigos para consulta. Excluir um ano é uma ação destrutiva: a confirmação informa a exclusão dos agendamentos e feriados associados. Faça backup antes de executá-la.

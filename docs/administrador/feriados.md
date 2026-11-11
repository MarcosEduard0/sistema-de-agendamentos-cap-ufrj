# Feriados e recessos

[Voltar ao manual](../README.md)

Abra o ano letivo e selecione **Feriados**. Um registro pode representar uma data ou um intervalo, como um recesso. As datas cadastradas são consideradas na disponibilidade da grade.

## Adicionar ou editar

1. Clique em **Adicionar Feriado**, ou use o lápis de um registro existente.
2. Informe o nome e as datas de início e término.
3. Para uma única data, repita a mesma data nos dois campos.
4. Use datas dentro do período do ano letivo.
5. Salve e confira o calendário e a grade.

Cadastrar um feriado não deve ser tratado como cancelamento automático de reservas anteriores. Revise os agendamentos existentes nas datas afetadas e comunique os envolvidos quando necessário.

> **Captura pendente:** lista de feriados. Arquivo: `administrador/feriados.png`.
<!-- Inserir quando disponível: ![Feriados do ano letivo](../imagens/administrador/feriados.png) -->

## Importar de outro ano letivo

1. Abra **Feriados** no ano de destino.
2. Clique em **Importar de outro ano letivo**.
3. Escolha a origem e clique em **Carregar feriados**.
4. Marque apenas os registros que deseja importar.
5. Revise e ajuste as datas de início e término propostas.
6. Marque a confirmação de revisão.
7. Clique em **Confirmar importação** e confira o resumo de importados e duplicados ignorados.

A proposta conserva dia e mês e desloca o ano conforme a diferença entre os inícios dos anos letivos. Ela **não calcula feriados móveis**. Revise Carnaval, Páscoa, Corpus Christi, recessos e datas locais de acordo com o calendário aprovado pela escola. Uma proposta inválida, como 29 de fevereiro em ano não bissexto, fica em branco para correção.

Apenas os registros marcados são validados e importados. Itens com o mesmo nome e o mesmo intervalo já presentes no destino são ignorados. Nomes diferentes na mesma data não são considerados duplicados; evite intervalos sobrepostos desnecessários.

> **Captura pendente:** prévia da importação com datas revisadas. Arquivo: `administrador/importar-feriados.png`.
<!-- Inserir quando disponível: ![Revisão da importação de feriados](../imagens/administrador/importar-feriados.png) -->

## Aviso de data fora do período

Se o ano letivo termina em **22/12/2026**, o Natal de **25/12/2026** está correto como data, mas está fora do intervalo cadastrado. Desmarque esse feriado para importar os demais. Altere o término do ano somente se o calendário escolar realmente exigir.

Ao receber um erro, confira os feriados marcados e o intervalo de destino mostrado na tela. A importação valida o conjunto antes de gravar e apresenta erros sem confirmar uma importação parcial.

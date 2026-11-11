# Capturas de tela do manual

[Voltar ao manual](../README.md)

Os capítulos contêm blocos **Captura pendente** e uma linha Markdown comentada com o nome sugerido do arquivo. Assim não há imagens quebradas enquanto os prints ainda não foram produzidos.

## Adicionar uma imagem

1. Abra a tela na versão do sistema descrita no manual.
2. Use uma conta de teste e dados fictícios.
3. Recorte a área útil, mantendo os controles necessários para entender o procedimento.
4. Salve em PNG na subpasta indicada, por exemplo `docs/imagens/professor/login.png`.
5. No capítulo, remova o bloco **Captura pendente** e substitua o comentário pela imagem:

```markdown
![Tela de acesso ao sistema](../imagens/professor/login.png)
```

Se houver duas telas importantes, adicione duas imagens com descrições diferentes em vez de reduzir tudo a uma captura ilegível.

## Convenções

- Use nomes em minúsculas, sem espaços ou acentos, separados por hífen.
- Organize as imagens em `professor/`, `administrador/` e `manutencao/`, conforme necessário.
- Não capture senhas, tokens, dados reais de professores, conexões salvas ou caminhos que exponham credenciais.
- Inclua uma descrição alternativa que identifique a tela e a ação mostrada.
- Revise as imagens quando mudar o texto dos botões ou a organização da tela.

As capturas devem ser do sistema adaptado do CAp, não reaproveitadas automaticamente do site original.

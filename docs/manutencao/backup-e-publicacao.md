# Backup, publicação por SFTP e recuperação

[Voltar ao manual](../README.md)

## Backup antes de publicar

Mantenha uma cópia dos arquivos remotos que serão substituídos e um backup do banco antes de alterações que possam afetar dados. Código, configurações e banco são componentes distintos; recuperar apenas um deles pode não restaurar toda a instalação.

Exporte estrutura e dados pelo acesso autorizado ao banco, pelo DBeaver quando disponível ou por backup fornecido pela administração do servidor. Confira o resultado e, preferencialmente, teste a restauração em um banco local separado.

O botão de dump criado temporariamente durante a migração não é um recurso permanente desta documentação. Não suponha que ele esteja disponível no ambiente.

## Publicar pelo FileZilla

1. Separe somente os arquivos da alteração, mantendo os caminhos do projeto.
2. Faça backup dos correspondentes remotos.
3. Na instalação de referência, abra `/php` no lado remoto e confirme que essa é a raiz correta.
4. Envie as pastas do pacote, como `application` e `assets`, para essa raiz.
5. Para um pacote revisado, escolha **Substituir** e aplique a ação somente à fila atual.
6. Confira transferências malsucedidas e aguarde o término da fila.
7. Recarregue o site e teste os fluxos afetados com as contas adequadas.

Exemplo de pacote:

```text
publicacao/
├── application/
│   └── views/
│       └── exemplo.php
└── assets/
    └── css/
        └── exemplo.css
```

Envie o conteúdo de `publicacao` para `/php`; não crie acidentalmente `/php/publicacao` ou `/php/application/application`.

Não envie a pasta inteira do ambiente de desenvolvimento. Exclua do pacote credenciais, `local/config.php`, dumps, logs, sessões, dependências não alteradas e arquivos temporários. Arquivos removidos no projeto não são apagados automaticamente no servidor.

## Se ocorrer uma falha

1. Registre a mensagem e os arquivos publicados.
2. Confira os caminhos e a conclusão da transferência.
3. Restaure os arquivos afetados a partir do backup quando necessário.
4. Em erros de banco, confira primeiro as configurações de produção; não importe nem sobrescreva dados por tentativa.
5. Verifique o funcionamento após a recuperação.

O FileZilla não deve ser tratado como histórico de versões: um arquivo sobrescrito pode exigir backup do editor, da hospedagem ou uma cópia anterior para recuperação.

## SSH e SFTP

SFTP funcionando não garante que o servidor permita túnel SSH para o banco. A mensagem `administratively prohibited` no encaminhamento exige revisão da permissão com o administrador do servidor.

Se a identificação SSH do host mudar, confirme a nova chave por um canal confiável antes de aceitá-la. Não remova todas as identificações salvas para resolver um problema de uma única conexão.

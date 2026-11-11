# Referências e diferenças desta adaptação

[Voltar ao manual](README.md)

## Referência original

O sistema deriva do **classroombookings**, de Craig A Rodway. Sua documentação oficial foi consultada como referência conceitual e de organização:

- [Documentação self-hosted](https://www.classroombookings.com/docs/self-hosted/)
- [Sessions: cadastro e função dos períodos letivos](https://www.classroombookings.com/docs/self-hosted/sessions/sessions/)

Consulta realizada em **11/11/2026**. Estes dois conteúdos foram lidos na preparação; outros capítulos do site não devem ser considerados revisados apenas por constarem no índice original.

O texto deste manual foi redigido para a versão adaptada e não é uma tradução integral do site. As capturas serão produzidas no CAp. Preserve as atribuições do projeto original; a licença do código está no [arquivo de licença do repositório](../LICENSE.txt).

## Diferenças relevantes

| Tema | Versão adaptada documentada |
| --- | --- |
| Sessions | Termo da interface alterado para Ano letivo; rotas e entidades internas preservadas. |
| Consulta de anos | Professor pode consultar anos anteriores; a flag Agendável controla novos agendamentos. |
| Semanas | Tipos de semana usados também como trimestres. |
| Aplicação em massa | Escolha entre ano inteiro e intervalo inclusivo de datas. |
| Feriados | Importação de outro ano, seleção, revisão das datas e prevenção de duplicados exatos. |
| Interface | Cards, formulários e mensagens adaptados à identidade do CAp. |
| Rodapé | Versão 2.6.9 e ano fixo 2026. |

## Base de conferência no código

| Assunto | Arquivos principais |
| --- | --- |
| Acesso e perfil | `application/controllers/Login.php`, `Profile.php` e views `login/`, `profile/`. |
| Home e indicadores | `Dashboard.php`, views `dashboard/` e `Bookings_model.php`. |
| Permissão para agendar | `Userauth.php`, `Bookings_model.php`, componentes `bookings/Context.php`, `Slot.php` e `grid/Controls.php`. |
| Criação e cancelamento | `Bookings.php`, componentes `bookings/agent/` e views `bookings/`. |
| Anos e intervalos | `Sessions.php`, `Dates_model.php` e `sessions/view_apply_week.php`. |
| Importação de feriados | `Holidays.php`, `Holidays_model.php`, `Holiday_import.php` e `holidays/import_year.php`. |
| Versão e ano do rodapé | `application/config/constants.php`. |

Os caminhos de controladores, modelos e bibliotecas acima são abreviados. A leitura do código permite conferir regras, mas não substitui a homologação dos fluxos no servidor.

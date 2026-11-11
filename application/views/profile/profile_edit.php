<?php echo $this->session->flashdata('saved'); ?>
<section class="profile-card" aria-labelledby="profile-title">
    <h1 id="profile-title">Editar perfil</h1>
    <p class="profile-intro">Atualize seus dados pessoais e, se desejar, sua senha.</p>
    <?= form_open('profile/save', ['id' => 'profile_edit']) ?>
    <fieldset>
        <legend>Dados pessoais</legend>
        <div class="profile-fields">
        <?php
        $fields = [
            'firstname' => ['label' => 'Nome', 'max' => 20, 'autocomplete' => 'given-name'],
            'lastname' => ['label' => 'Sobrenome', 'max' => 20, 'autocomplete' => 'family-name'],
            'displayname' => ['label' => 'Nome de exibição', 'max' => 20, 'autocomplete' => 'nickname'],
            'email' => ['label' => 'E-mail', 'max' => 255, 'autocomplete' => 'email'],
            'department_id' => ['label' => 'Departamento', 'max' => 20, 'autocomplete' => 'off'],
            'ext' => ['label' => 'Telefone', 'max' => 20, 'autocomplete' => 'tel'],
        ];
        foreach ($fields as $field => $options):
            $error = form_error($field);
            $attrs = [
                'name' => $field, 'id' => $field,
                'type' => $field === 'email' ? 'email' : ($field === 'ext' ? 'tel' : 'text'),
                'maxlength' => $options['max'], 'autocomplete' => $options['autocomplete'],
                'class' => 'form-control' . ($error ? ' is-invalid' : ''),
                'value' => $field === 'department_id' ? ($department ?: '(Nenhum)') : set_value($field, $user->$field, FALSE),
            ];
            if ($field === 'department_id') { $attrs['disabled'] = 'disabled'; $attrs['aria-describedby'] = 'department-hint'; }
            if ($field === 'ext') { $attrs['onkeypress'] = "$(this).mask('(00) 0000-00000')"; }
            if ($error) { $attrs['aria-invalid'] = 'true'; $attrs['aria-describedby'] = $field . '-error'; }
        ?>
            <div class="profile-field">
                <?= form_label($options['label'], $field) ?>
                <?= form_input($attrs) ?>
                <?php if ($field === 'department_id'): ?><small id="department-hint">Para alterar o departamento, entre em contato com a administração.</small><?php endif; ?>
                <?php if ($error): ?><div id="<?= $field ?>-error" class="profile-error"><?= $error ?></div><?php endif; ?>
            </div>
        <?php endforeach; ?>
        </div>
    </fieldset>
    <fieldset>
        <legend>Alterar senha</legend>
        <p class="profile-password-hint" id="password-hint">Deixe os dois campos em branco para manter a senha atual. A nova senha deve ter pelo menos 6 caracteres.</p>
        <div class="profile-fields">
        <?php foreach (['password1' => 'Nova senha', 'password2' => 'Confirmar nova senha'] as $field => $label): ?>
            <div class="profile-field">
                <?= form_label($label, $field) ?>
                <?php
                $error = form_error($field);
                echo form_password([
                    'name' => $field, 'id' => $field, 'value' => '', 'maxlength' => 40,
                    'autocomplete' => 'new-password',
                    'class' => 'form-control' . ($error ? ' is-invalid' : ''),
                    'aria-describedby' => 'password-hint' . ($error ? ' ' . $field . '-error' : ''),
                    'aria-invalid' => $error ? 'true' : 'false',
                ]);
                ?>
                <?php if ($error): ?><div id="<?= $field ?>-error" class="profile-error"><?= $error ?></div><?php endif; ?>
            </div>
        <?php endforeach; ?>
        </div>
    </fieldset>
    <div class="profile-actions">
        <button type="submit" class="btn btn-primary">Salvar alterações</button>
        <a href="<?= site_url('/') ?>">Cancelar</a>
    </div>
    <?= form_close() ?>
</section>

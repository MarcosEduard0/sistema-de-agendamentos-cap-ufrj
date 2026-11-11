<div class="limiter">
	<div class="container-login100">
		<div class="wrap-login100 login-card">
			<?php
			if (isset($message) && strlen($message)) {
				echo '<div class="login-message-container">';
				echo "<div class='login_message' role='note'>" . nl2br($message) . "</div>";
				echo '</div>';
			}
			?>
			<div class="login100-pic js-tilt" data-tilt>
				<?= img($logo, FALSE) ?>
			</div>
			<?php
			echo form_open('login/submit', array('id' => 'login', 'class' => 'login100-form validate-form'), array('page' => $this->uri->uri_string()));
			?>
			<div class="login-form-content">
				<div class="row">
					<div class="col-sm-12">
						<h1 class="title-login">Sistema de Agendamentos<strong>CAp UFRJ</strong></h1>
						<?= validation_errors() ?>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12" style="padding-bottom: 15px;">
						<?php echo $this->session->flashdata('auth') ?>
					</div>
				</div>

				<div class="wrap-input100 validate-input" data-validate="Usuário é obrigatorio">
					<?php
					$value = set_value('username', '', FALSE);
					echo form_input(array(
						'name' => 'username',
						'id' => 'username',
						'size' => '20',
						'maxlength' => '20',
						'placeholder' => " Usuário",
						'class' => "input100",
						'tabindex' => tab_index(),
						'value' => $value,
					));
					?>
					<span class="focus-input100"></span>
					<span class="symbol-input100">
						<i class="fa fa-user" aria-hidden="true"></i>
					</span>
				</div>

				<div class="wrap-input100 validate-input" data-validate="Senha é obrigatoria">
					<?php
					echo form_password(array(
						'name' => 'password',
						'id' => 'password',
						'size' => '20',
						'placeholder' => 'Senha',
						'aria-label' => 'Senha',
						'autocomplete' => 'current-password',
						'class' => "input100",
						'tabindex' => tab_index(),
						'maxlength' => '20',
					));
					?>
					<span class=" focus-input100"></span>
					<span class="symbol-input100">
						<i class="fa fa-lock" aria-hidden="true"></i>
					</span>
				</div>

				<div class="container-login100-form-btn">
					<button type="submit" class="login100-form-btn">
						Entrar
					</button>


                    <div class="login-help">
                        <a tabindex="0" class="txt2" role="button" data-toggle="popover" data-trigger="focus" title="Cadastro ou redefinição de senha" data-content="Para realizar um cadastro ou redefinir sua senha, procure um bolsista do LiG. E-mail: capufrj.lig@gmail.com">Não possui Usuário?</a>
                    </div>
				</div>

			</div>
			<?= form_close() ?>
			<footer class="login-footer">
				<div class="row align-items-center">
					<div class="col-sm-2"></div>
					<div class="col-sm-8">
						Adaptado de <a href="https://www.classroombookings.com/" target="_blank" rel="noopener noreferrer">classroombookings</a> por
						<span class="login-contact" data-toggle="tooltip" data-placement="top" title="marcos.eduardo22@gmail.com">Marcos Eduardo de Souza</span>.
						<br />Versão <?= VERSION ?> &copy; <?= COPYRIGHT_YEAR ?>
					</div>
					<div class="col-sm-2"></div>
				</div>
			</footer>
		</div>
	</div>
</div>
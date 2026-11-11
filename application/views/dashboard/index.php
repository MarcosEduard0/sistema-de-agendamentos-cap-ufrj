<?php echo $this->session->flashdata('saved'); ?>
<div class="teacher-home">
    <h1 class="teacher-home-title">Início</h1>
    <nav class="teacher-shortcuts" aria-label="Acessos principais">
        <a class="teacher-shortcut" href="<?= site_url('bookings') ?>">
            <img src="<?= base_url('assets/images/ui/bookings.png') ?>" alt="" width="64" height="64">
            <h2>Agendamentos</h2>
            <p>Consulte horários e reserve uma sala.</p>
        </a>
        <a class="teacher-shortcut" href="<?= site_url('profile/edit') ?>">
            <img src="<?= base_url('assets/images/ui/profile.png') ?>" alt="" width="64" height="64">
            <h2>Meu perfil</h2>
            <p>Atualize seus dados e sua senha.</p>
        </a>
    </nav>
    <section class="teacher-summary" aria-labelledby="teacher-summary-title">
        <h2 id="teacher-summary-title">Seus agendamentos</h2>
        <?php $this->load->view('dashboard/stats'); ?>
    </section>
    <div class="block-group has-spacing teacher-bookings">
        <?php $this->load->view('dashboard/user_bookings'); ?>
        <?php $this->load->view('dashboard/room_bookings'); ?>
    </div>
</div>

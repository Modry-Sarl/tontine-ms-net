<?php $this->extend('\App\Views\errors\layout') ?>
<?php $this->title(lang('Errors.badRequest')) ?>
<?php $this->section('code', '404') ?>
<?php $this->section('message', ! on_prod() ? nl2br(esc($message)) : lang('Errors.sorryBadRequest')) ?>

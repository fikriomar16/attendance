<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/sb-admin-2.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/app.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/bootstrap-select.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/flatpickr/dist/themes/material_blue.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/sweetalert2/themes/material-ui/material-ui.min.css') ?>">
	<?php $p = uri_string(); ?>
	<?php if ($p=='attendance/employee'): ?>
		<link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/flatpickr/dist/plugins/monthSelect/style.css') ?>">
	<?php endif ?>
	<?php if ($p=='attendance/office'): ?>
		<link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/flatpickr/dist/plugins/monthSelect/style.css') ?>">
	<?php endif ?>
	<?php if ($p=='attendance/visitor'): ?>
		<link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/flatpickr/dist/plugins/monthSelect/style.css') ?>">
	<?php endif ?>
	<link rel="icon" href="<?= base_url('assets/img/undraw_posting_photo.svg') ?>">
	<title><?= $title ?? '' ?></title>
</head>
<body id="page-top" data-home="<?= base_url() ?>">
	<div id="wrapper">
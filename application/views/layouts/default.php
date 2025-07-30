<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
    	<meta charset="utf-8">
    	<base href="<?= base_url(); ?>">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<?= $template['metadata'] ?>
    	<link rel="icon" href="<?= base_url('assets/images/logo.ico'); ?>">
    	
    	<title><?= $page_title; ?></title>
    	<meta name="title" content="<?= $meta_title; ?>" />
    	<meta name="description" content="<?= $meta_description; ?>" /> 
    	<meta name="keywords" content="<?= $meta_keywords; ?>">
    	
    	<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        
        <!-- jquery -->
        <script src="<?= base_url('assets/sbadmin/') ?>vendor/jquery/jquery.min.js"></script>
        <script src="<?= base_url('assets/sbadmin/') ?>vendor/jquery-easing/jquery.easing.min.js"></script>
        
        <!-- datatable -->
        <link href="<?= base_url('assets/sbadmin/') ?>vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
        <script src="<?= base_url('assets/sbadmin/') ?>vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="<?= base_url('assets/sbadmin/') ?>vendor/datatables/dataTables.bootstrap4.min.js"></script>
        
        <!-- Sweetalert -->
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        
        <!-- swiper -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        
    </head>
    <body class="bg-black">
        <div class="wrapper bg-main">
            <?= $template['body'] ?>
        </div>
        
        <footer class="mt-5 text-white-50 text-center">
            <p>Cover template for <a href="https://getbootstrap.com/" class="text-white">Bootstrap</a>, by <a href="" class="text-white">dhias | adhiasta</a>.</p>
        </footer>
    </body>
</html>
<?= $this->extend('layout/frontend'); ?>
<?= $this->section('content'); ?>

<?php
$locale = $locale ?? 'id';
$sections = $sections ?? [];
$builderSections = $builderSections ?? [];
$homepageItems = $homepageItems ?? [];
$agenda = $agenda ?? [];
$news = $news ?? [];
$writingArticles = $writingArticles ?? [];
$programs = $programs ?? [];
$board = $board ?? [];
$partners = $partners ?? [];
$aboutValues = $aboutValues ?? [];
$heroSlides = $heroSlides ?? [];
?>

<?= view('frontend/homepage_builder', get_defined_vars()); ?>

<?= $this->endSection(); ?>

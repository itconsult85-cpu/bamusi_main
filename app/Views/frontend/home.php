<?= $this->extend('layout/frontend'); ?>

<!-- Menyuntikkan meta tag spesifik ke layout -->
<?= $this->section('og_meta'); ?>
<?php if (isset($meta)): ?>
    <meta property="og:title" content="<?= esc($meta['title']) ?>">
    <meta property="og:description" content="<?= esc(strip_tags($meta['description'])) ?>">
    <meta property="og:image" content="<?= esc($meta['image']) ?>">
    <meta property="og:url" content="<?= esc($meta['url']) ?>">
    <meta property="og:type" content="<?= esc($meta['type']) ?>">
<?php endif; ?>
<?= $this->endSection(); ?>

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
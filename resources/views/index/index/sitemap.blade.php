<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<url><loc><?php echo route('home'); ?></loc><changefreq>daily</changefreq><priority>1.0</priority></url>
<url><loc><?php echo route('index_page_detail', array('id'=>'contact')); ?></loc></url>
<?php if ($list) { foreach ($list as $row) { ?><url><loc><?php echo route('index_article_detail', array('id'=>$row->id)); ?></loc><lastmod><?php echo date("Y-m-d", $row->update_time); ?></lastmod><changefreq>monthly</changefreq></url>
<?php } } ?>
</urlset>
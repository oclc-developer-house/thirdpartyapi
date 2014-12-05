<!doctype html>
<html>
	<head>

		<title>DAPI Diem</title>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>components/bootstrap/css/bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>components/bootstrap/css/bootstrap-theme.min.css">
		<!--responsive design-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>

	<body>
  <div class="page-header">
    <h1>DAPI Diem     <small>Have a DAPI day!</small></h1>
  </div>
	<div class="container-fluid">
    <?php foreach ($entities as $e): ?>
    <?php $holdings = $e->get_holdings(); ?>
    <?php $count = 1; ?>
    <?php if ($count % 2 != 0): ?>
    <div class="row">
    <?php endif; ?>
      <div class="col-sm-12 col-md-12" style="border:1px solid #ccc; margin: 5px;">
        <div class="row">
          <div class="col-md-2" style="margin: 10px;">
          <?php if ($e->get_image()): ?>
            <img src="<?php echo $e->get_image(); ?>">
          <?php else: ?>
            <img src="http://placehold.it/150x200">
          <?php endif; ?>
          </div>
          <div class="col-md-8" style="margin: 10px;">
            <h3><?php echo $e->get_label(); ?></h3>
          </div>
        </div>
        <div class="row">
          <?php foreach($holdings as $holding) { ?>
          <div class="col-sm-2 col-md-2">
            <div class="thumbnail">
              <img src="http://placehold.it/80x100">
              <div class="caption">
                <h3><?php echo $holding['name']; ?></h3>
                <div><?php echo $holding['author']; ?></div>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>

    <?php if ($count % 2 != 0): ?>
    </div>
    <?php endif; ?>
    <?php $count++; ?>
    <?php endforeach; ?>


	</div>
	<!-- Latest compiled and minified JavaScript -->
  <script src="<?php echo base_url(); ?>components/jquery/jquery.min.js"></script>
	<script src="<?php echo base_url(); ?>components/bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>

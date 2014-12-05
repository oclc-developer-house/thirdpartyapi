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
      <div class="col-sm-6 col-md-6">
        <div>
          <img data-src="holder.js/300x300" alt="...">
          <h3><?php echo $e->get_label(); ?></h3>
        </div>
        <div class="row">
          <div class="col-sm-4 col-md-4">
            <?php echo $holdings[0]['name']; ?>
          </div>
          <div class="col-sm-4 col-md-4">
            <?php echo $holdings[1]['name']; ?>
          </div>
          <div class="col-sm-4 col-md-4">
            <?php echo $holdings[2]['name']; ?>
          </div>
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

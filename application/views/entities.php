<!doctype html>
<html>
	<head>

		<title>DAPI Diem</title>
		<div class="page-header">
  <h1>DAPI Diem     <small>Have a DAPI day!</small></h1>
</div>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
		<!--responsive design-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>

	<body>
	<div class="container-fluid">
    <?php foreach ($entities as $e): ?>
    <?php $holdings = $e->get_holdings(); ?>
    <?php $count = 1; ?>
    <?php if ($count % 2 != 0): ?>
    <div class="row-fluid">
    <?php endif; ?>
      <div class="col-sm-6 col-md-6">
        <div class="thumbnail">
          <img data-src="holder.js/300x300" alt="...">
          <div class="caption">
            <h3><?php echo $e->get_label(); ?></h3>
          </div>
        </div>
        <div class="col-sm-2 col-md-2">
          <?php echo $holdings[0]; ?>
        </div>
        <div class="col-sm-2 col-md-2">
          <?php echo $holdings[1]; ?>
        </div>
        <div class="col-sm-2 col-md-2">
          <?php echo $holdings[2]; ?>
        </div>
      </div>

    <?php if ($count % 2 != 0): ?>
    </div>
    <?php endif; ?>
    <?php $count++; ?>
    <?php endforeach; ?>


	</div>
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	</body>
</html>

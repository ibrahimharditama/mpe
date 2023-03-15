<!DOCTYPE HTML>
<html>
<head>
	<title>Makmur Permai Elektronik</title>
	
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="robots" content="noindex, nofollow">
	<meta name="googlebot" content="noindex, nofollow">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="icon" href="<?php echo base_url(); ?>assets/img/ico.png" type="image/png">
	
	<!-- GOOGLE FONTS -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap"> 
	
	<!-- THEMIFY ICONS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
	
	<!-- BOOTSTRAP & APP CSS -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/app.css">
	
	<!-- JQUERY & BOOTSTRAP JS -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
	
	<!-- DATATABLES -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/DataTables/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/DataTables/datatables.custom.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/DataTables/datatables.min.js"></script>
	
	<!-- JQUERY NUMBER -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/jquery.number.min.js"></script>
	
	<!-- ZEBRA DATEPICKER -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/zebra_datepicker/css/bootstrap/zebra_datepicker.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/plugins/zebra_datepicker/css/zebra_datepicker.custom.css">
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/zebra_datepicker/zebra_datepicker.min.js"></script>
	
	<!-- SELECT2 -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/select2.custom.css">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
	
	<script type="text/javascript">var site_url = '<?php echo site_url(); ?>';</script>
	
	<!-- APP.JS -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/app.js"></script>
	
	<!-- TABLE-CELL.JS -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/table-cell.js"></script>

	<!-- MOMENT.JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

	<script> var base_url = "<?= base_url(); ?>"; </script>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-info fixed-top">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNavDropdown">
			
			<?php $menu = menu(user_session('id_pengguna_grup')); ?>
			
			<ul class="navbar-nav">
				<?php foreach ($menu as $id => $m): ?>
				<?php if (isset($m['sub'])): ?>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="<?php echo site_url($m['uri']); ?>" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="ti ti-<?php echo $m['ikon']; ?>"></i> <?php echo $m['teks']; ?>
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
						<?php foreach ($m['sub'] as $id_sub => $sm): ?>
						<a class="dropdown-item" href="<?php echo site_url($sm['uri']); ?>"><?php echo $sm['teks']; ?></a>
						<?php endforeach; ?>
					</div>
				</li>
				<?php else: ?>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo site_url($m['uri']); ?>">
						<i class="ti ti-<?php echo $m['ikon']; ?>"></i> <?php echo $m['teks']; ?>
					</a>
				</li>
				<?php endif; ?>
				<?php endforeach; ?>
			</ul>
			
			<ul class="nav navbar-nav ml-auto">
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="ti ti-home"></i> <?php echo user_session('nama'); ?>
					</a>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="accountDropdown">
						<a class="dropdown-item" href="<?php echo site_url('pengguna/profil'); ?>"><i class="ti ti-id-badge"></i> Profil Saya</a>
						<a class="dropdown-item" href="<?php echo site_url('pengguna/ubah-password'); ?>"><i class="ti ti-key"></i> Ubah Password</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="<?php echo site_url('pengguna/autentikasi/logout'); ?>"><i class="ti ti-power-off"></i> Logout</a>
					</div>
				</li>
			</ul>
		</div>
	</nav>
	<main class="container-fluid">
		<?php if (isset($content)) $this->load->view($content); ?>
	</main>
</body>
</html>
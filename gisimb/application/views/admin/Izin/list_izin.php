<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view("admin/_partials/head.php") ?>
</head>

<body id="page-top">


	<?php $this->load->view("admin/_partials/navbar.php") ?>
	<div id="wrapper">

		<?php $this->load->view("admin/_partials/sidebar.php") ?>

		<div id="content-wrapper">

			<div class="container-fluid">

				<?php $this->load->view("admin/_partials/breadcrumb.php") ?>
<div>
<?php echo $this->session->flashdata('hasil'); ?>
</div>
				<!-- DataTables -->
				<div class="card mb-3">
					<div class="card-header">
						<a href="<?php echo site_url('admin/products/add') ?>"><i class="fas fa-plus"></i> Add New</a>
					</div>
					<div class="card-body">

						<div class="table-responsive">
							<table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th>Nomor Pendaftaran</th>
										<th>Nama Pemohon</th>
										<th>Jenis Izin</th>
										<th>Nomor Surat</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($izin_terbit as $izin): ?>
									<tr>
										<td class="small" width="150">
											<?php echo $izin->pendaftaran_id ?>
										</td>
										<td class="small">
											<?php echo $izin->n_pemohon ?>
										</td>
										<td class="small">
                                        <?php echo $izin->n_perizinan ?>
											
										</td>
										<td class="small">
                                            <?php echo $izin->no_surat ?>
                                        </td>
										<td width="250">
											<a href="#"
											    class="btn btn-small"><i class="fas fa-edit"></i>Geocode</a>
                                            <a href="#!" 
                                            c   lass="btn btn-small text-danger"><i class="fas fa-trash"></i> Hapus</a>
										</td>
									</tr>
									<?php endforeach; ?>

								</tbody>
							</table>
						</div>
					</div>
				</div>

			</div>
			<!-- /.container-fluid -->

			<!-- Sticky Footer -->
			<?php $this->load->view("admin/_partials/footer.php") ?>

		</div>
		<!-- /.content-wrapper -->

	</div>
	<!-- /#wrapper -->


	<?php $this->load->view("admin/_partials/scrolltop.php") ?>
	<?php $this->load->view("admin/_partials/modal.php") ?>

	<?php $this->load->view("admin/_partials/js.php") ?>

	<script>
	function deleteConfirm(url){
		$('#btn-delete').attr('href', url);
		$('#deleteModal').modal();
	}
	</script>
</body>

</html>

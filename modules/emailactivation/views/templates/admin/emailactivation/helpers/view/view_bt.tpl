<div class="row">
	<div class="col-lg-12">

	<div class="leadin"></div>
		<div id="container-customer">
			<div class="row">

				<div class="col-lg-6">
					<div class="panel clearfix">
						<div class="panel-heading">
							<i class="icon-eye-close"></i> Data Bank Pelanggan
						</div>
						<div class="form-horizontal">
							<div class="row">
								<label class="control-label col-lg-3">Order No</label>
								<div class="col-lg-9">
									<p class="form-control-static">{$order}</p>
								</div>
							</div>
							
							<div class="row">
								<label class="control-label col-lg-3">Nama Bank</label>
								<div class="col-lg-9">
									<p class="form-control-static">{$nama_bank}</p>
								</div>
							</div>

							<div class="row">
								<label class="control-label col-lg-3">Atas Nama</label>
								<div class="col-lg-9">
									<p class="form-control-static">{$pemilik_rek}</p>
								</div>
							</div>
						</div>
					</div>
					<div class="panel clearfix">
						<div class="panel-heading">
							<i class="icon-eye-close"></i> Data Pembayaran
						</div>
						<div class="form-horizontal">
							<div class="row">
								<label class="control-label col-lg-3">Rekening Tujuan</label>
								<div class="col-lg-9">
									<p class="form-control-static">{$rekening_tujuan}</p>
								</div>
							</div>
							
							<div class="row">
								<label class="control-label col-lg-3">Jumlah Pembayaran</label>
								<div class="col-lg-9">
									<p class="form-control-static">{$payment}</p>
								</div>
							</div>

							<div class="row">
								<label class="control-label col-lg-3">Tanggal Pembayaran</label>
								<div class="col-lg-9">
									<p class="form-control-static">{$payment_date}</p>
								</div>
							</div>

							<div class="row">
								<label class="control-label col-lg-3">Status Pembayaran</label>
								<div class="col-lg-9">
									<p class="form-control-static">
										{if $state=="WAITING"}
										<span class="label label-warning">
											<i class="icon-check"></i> {$state}
										</span>
										{else if $state=="PAID"}
										<span class="label label-success">
											<i class="icon-check"></i> {$state}
										</span>
										{else if $state=="CANCEL"}
										<span class="label label-danger">
											<i class="icon-check"></i> {$state}
										</span>
										{else if $state=="POSTPONE"}
										<span class="label label-danger">
											<i class="icon-check"></i> {$state}
										</span>
										{/if}
									</p>
								</div>
							</div>

							<div class="row">
								<label class="control-label col-lg-3">Notes</label>
								<div class="col-lg-9">
									<p class="form-control-static">{$notes}</p>
								</div>
							</div>
						</div>
					</div>
				</div>



				<div class="col-lg-6">
					<div class="panel clearfix">
						<div class="panel-heading">
							<i class="icon-user"></i>
							{$firstname} {$lastname}
						</div>

					<div class="form-horizontal">
						<div class="row">
							<label class="control-label col-lg-3">Social Title</label>
						<div class="col-lg-9">
							<p class="form-control-static">{$gender}</p>
						</div>
						</div>

						<div class="row">
						<label class="control-label col-lg-3">First Name</label>
							<div class="col-lg-9">
								<p class="form-control-static">{$firstname}</p>
							</div>
						</div>

						<div class="row">
						<label class="control-label col-lg-3">Last Name</label>
							<div class="col-lg-9">
								<p class="form-control-static">{$lastname}</p>
							</div>
						</div>
						
						<div class="row">
						<label class="control-label col-lg-3">Email</label>
							<div class="col-lg-9">
								<p class="form-control-static">{$email}</p>
							</div>
						</div>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$sql = "SELECT * FROM ops_user";
$result = mysqli_query($connect, $sql);
$rowcount = mysqli_num_rows($result);
?>
<div class="d-flex align-items-center justify-content-between p-3 bg-blue-gra">
	<div class="d-flex align-items-center">
		<span class="material-symbols-outlined text-white me-2">account_circle</span>
		<p class="text-white fw-bold my-3 text-20">Tài khoản</p>
	</div>
	<div class="d-grid">
		<?php if ($in_id == 2 || $in_id == 5) { ?>
			<a href="/systemaccount/?view=add" class="btn btn-light btn-sm">
				<p class="">+ Tạo mới</p>
			</a>
		<?php } ?>
	</div>
</div>
<div class="bg-white">
	<div class="row mx-0">
		<div class="col-12 col-xl-6 offset-xl-3 mt-3" style="padding-bottom: 80px;">
			<!-- Table -->
			<?php if ($rowcount == 0) { ?>
				<div class="text-center">
					<img src="../img/notfound.svg" width="300">
					<h5 class="text-main">Chưa có User nào!</h5>
				</div>
			<?php } else { ?>
				<p class="text-12 mb-2 text-primary text-center"><?= $rowcount; ?> tài khoản</p>
				<div class="table-responsive vh-100">
					<table class="table table-sm table-bordered table-hovered bg-white">
						<thead class="table-secondary">
							<th>
								<p class="text-12">ID</p>
							</th>
							<th>
								<p class="text-12 fw-bold">Tên</p>
							</th>
							<th>
								<p class="text-12">Gmail</p>
							</th>
							<th>
							</th>
						</thead>
						<tbody>
							<?php while ($row = mysqli_fetch_array($result)) { ?>
								<tr style="cursor: pointer;" onclick="location.href='/systemaccount/?view=detail&id=<?= $row['id']; ?>'">
									<td>
										<p class="text-center fw-bold">#<?= $row['id']; ?></p>
									</td>
									<td>
										<p class="fw-bold"><?= $row['name']; ?></p>
										<p class="text-sub"><?= $row['phone']; ?></p>
									</td>
									<td>
										<p class=""><?= $row['gmail']; ?></p>
									</td>
									<td>
										<a title="Sửa thông tin" href="/systemaccount/?view=edit-info&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-success"><span class="material-symbols-outlined text-14">create</span></a>
										<a title="Đặt lại mật khẩu" href="/systemaccount/?view=reset-pass&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-dark"><span class="material-symbols-outlined text-14">vpn_key</span></a>
										<a title="Xóa user" href="/systemaccount/?view=delete&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger"><span class="material-symbols-outlined text-14">delete</span></a>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
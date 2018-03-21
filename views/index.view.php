<div class="container">
	<h1>To Do</h1>
	<div class="content">
		<form method="post" enctype="multipart/form-data">
			<input type="file" name="profile">
			<br>
			<input type="submit" name="add">
		</form>
		<div class="avatar">
			<img src="<?php echo getProfilePicture($_COOKIE['user_key']); ?>">
			<span class="badge"><?php echo getCountOfAllToDos($_COOKIE['user_key']) ?></span>
		</div>
		<ul class="list">
		<?php if ($data) : ?>
		<?php foreach($data as $item): ?>
			<li>
                <span class="item <?= ($item['done'] == 1) ? 'done' : ''?>">
                    <?= $item['name'] ?>
                </span>

                <?php if ((int) $item['done'] === 0): ?>
                <a class="button-done" href="index.php?tid_done=<?= $item['id']; ?>">Mark as done</a>
                <?php else: ?>
                <a class="button-done" href="index.php?tid_undone=<?= $item['id']; ?>">Undone</a>
                <?php endif; ?>

			</li>
		<?php endforeach; ?>
		<?php endif; ?>
		</ul>
		<form action="" method="POST">
			<ul class="form-list">
				<li>
					<input type="text" maxlength="47" name="name" placeholder="" required>
					<label>Type here..</label>
				</li>
				<input type="submit" value="Add">
			</ul>
		</form>
	</div>
</div>

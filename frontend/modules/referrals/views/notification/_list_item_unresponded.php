<?php
// _list_item.php
use yii\helpers\Html;
//use yii\helpers\Url;
?>

<ul>
	<?php if($count_notice > 0) : ?>
		<?= "<a href='/referrals/referral/view?id=".$model['referral_id']."&notice_id=".$model['notice_id']."'>" ?>
			<li>
				<?= $model['notice_sent'] ?><br>
				<span class="notification-date"><?= date("d-M-Y g:i A", strtotime($model['notification_date'])) ?></span>
			</li>
		</a>
	<?php else: ?>
		<li>No notifications to be displayed.</li>
	<?php endif; ?>
</ul>
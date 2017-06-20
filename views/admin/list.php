<?php 
	use yii\helpers\Url;
	use yii\helpers\html;
?>

<div class="row clearfix">
		<div class="col-md-12 column">
			<ul class="nav nav-tabs">
				<li <?php echo $active?>>
					<?php echo Html::a('列表页', Url::toRoute(['admin/list'])) ?>
				</li>
				<li>
					<?php echo Html::a('上传页', Url::toRoute(['admin/index'])) ?>
				</li>
			</ul>
		</div>
	</div>


<div class="row clearfix">
	<div class="col-md-12 column">
		<table class="table">
			<thead>
				<tr>
					<th>
						编号
					</th>
					<th>
						标题
					</th>
					<th>
						下载链接
					</th>
					<th>
						状态
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($list as $k=>$v):?>
				<tr>
					<td>
						<?php echo $v['id']?>
					</td>
					<td>
						<?php echo $v['title']?>
					</td>
					<td>
						<a href="<?php echo Url::toRoute(['admin/download','url'=>"http://upload.yiistart.me/{$v['url']}",'filename'=>basename($v['url']), 'old_url'=>"{$v['url']}"]) ?>">点我下载</a>
					</td>
					<td>
						<a href="<?php echo Url::toRoute(['admin/delete','id' => $v['id']]) ?>">删除</a>
					</td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
</div>







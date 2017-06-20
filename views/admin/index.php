<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;


$form = ActiveForm::begin([
    'id' => 'Admin',
	'action'=>Yii::$app->urlManager->createUrl('admin/ajax-save'),
    'options' =>
	['enctype' => 'multipart/form-data'],
]) ?>
 
	<div class="row clearfix">
		<div class="col-md-12 column">
			<ul class="nav nav-tabs">
				<li>
					<?php echo Html::a('列表页', Url::toRoute(['admin/list'])) ?>
				</li>
				<li <?php echo $active?>>
					<?php echo Html::a('上传页', Url::toRoute(['admin/index'])) ?>
				</li>
			</ul>
		</div>
	</div>


	<div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
			标题：<?= $form->field($model, 'title',['inputOptions'=>['style'=>'width:200px']])->textInput(['autofocus' => true]) ?>
        </div>
    </div>

	<div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
			<?= $form->field($model, 'file')->fileInput() ?>
        </div>
    </div>
   
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('save', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
<?php ActiveForm::end() ?>



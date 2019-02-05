<?php
declare(strict_types = 1);

/**
 * Шаблон страницы группового изменения пользователя
 * @var View $this
 * @var UsersMassUpdate $massUpdateModel
 * @var ArrayDataProvider|null $statistics Статистика предыдущей операции
 * @var array $attributesData
 * @var false|Groups $group
 */

use app\models\groups\Groups;
use app\models\users\UsersMassUpdate;
use kartik\select2\Select2;
use app\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;

$this->title = 'Групповое изменение пользователей';
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];

if ($group) {
	$this->params['breadcrumbs'][] = ['label' => 'Группы', 'url' => ['/admin/groups']];
	$this->params['breadcrumbs'][] = ['label' => $group->name, 'url' => ['/admin/groups/update', 'id' => $group->id]];
	$usersLabel = "Пользователи из группы {$group->name}, всего ".count($massUpdateModel->users);
} else {
	$this->params['breadcrumbs'][] = ['label' => 'Люди', 'url' => ['/admin/users']];
	$usersLabel = "Пользователи: ".count($massUpdateModel->users). " всего";
}
$this->params['breadcrumbs'][] = $this->title;
?>

	<div class="row">
		<div class="col-xs-12">
			<?php $form = ActiveForm::begin(); ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><?= $this->title; ?></h3>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<div class="row">
							<div class="col-md-12">
								<?= $form->field($massUpdateModel, 'usersIdSelected')->widget(Select2::class, [
									'data' => ArrayHelper::map($massUpdateModel->users, 'id', 'username'),
									'pluginOptions' => [
										'allowClear' => false,
										'multiple' => true
									]
								])->label($usersLabel); ?>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-6">
								<?= Select2::widget([
									'model' => $massUpdateModel->virtualUser,
									'attribute' => 'relGroups',
									'name' => 'relGroups',
									'data' => ArrayHelper::map(Groups::find()->all(), 'id', 'name'),
									'options' => [
										'multiple' => true,
										'placeholder' => 'Добавить в группы'
									]
								]); ?>

							</div>
							<div class="col-md-6">
								<?= Select2::widget([
									'model' => $massUpdateModel->virtualUser,
									'attribute' => 'dropGroups',
									'name' => 'dropGroups',
									'data' => ArrayHelper::map(Groups::find()->all(), 'id', 'name'),
									'options' => [
										'multiple' => true,
										'placeholder' => 'Убрать из групп'
									]
								]); ?>

							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-6">
								<?= Select2::widget([
									'model' => $massUpdateModel->virtualUser,
									'attribute' => 'relDynamicAttributes',
									'name' => 'relDynamicAttributes',
									'data' => $attributesData,
									'options' => [
										'multiple' => true,
										'placeholder' => 'Добавить атрибут'
									]
								]) ?>
							</div>
							<div class="col-md-6">
								<?= Select2::widget([
									'model' => $massUpdateModel->virtualUser,
									'attribute' => 'dropUsersAttributes',
									'name' => 'dropUsersAttributes',
									'data' => $attributesData,
									'options' => [
										'multiple' => true,
										'placeholder' => 'Удалить атрибуты'
									]
								]) ?>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<?= Html::submitButton('Применить', ['class' => 'btn btn-success']); ?>
				</div>
			</div>
		</div>
		<?php ActiveForm::end(); ?>
	</div>

<?php if (null !== $statistics): ?>
	<div class="row">
		<div class="col-xs-12">
			<div class="panel">
				<div class="panel-heading">
					<h3 class="panel-title">Результат обработки</h3>
				</div>
				<div class="panel-body">
					<?= GridView::widget([
						'dataProvider' => $statistics,
						'columns' => [
							[
								'attribute' => 'id',
								'label' => 'id пользователя',
								'value' => function($array) {
									return Html::a(ArrayHelper::getValue($array, 'username'), ['/admin/users/update', 'id' => ArrayHelper::getValue($array, 'id')]);
								},
								'format' => 'raw'
							],
							[
								'attribute' => 'status',
								'label' => 'Статус'
							],
							[
								'attribute' => 'error',
								'label' => 'Ошибка',
								'format' => 'boolean'
							]
						],
						'rowOptions' => function($array) {
							return ['class' => ArrayHelper::getValue($array, 'error')?'danger':'success'];
						}
					]); ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $group
 * @var array $options -- 'showChildGroups':bool -- показывать дочерние группы; 'col-md' -- значение для модификатора колонк
 */

use app\modules\groups\GroupsModule;
use app\modules\groups\models\Groups;
use app\modules\groups\models\references\RefGroupTypes;
use app\modules\groups\widgets\group_leaders\GroupLeadersWidget;
use app\modules\groups\widgets\group_users\GroupUsersWidget;
use app\components\pozitronik\widgets\BadgeWidget;
use app\components\pozitronik\helpers\ArrayHelper;
use yii\web\View;

//$this->registerJs("var MsnrySmall = new Masonry('.panel-card-small',{columnWidth: '.grid-sizer-small', itemSelector: '.panel-card-small', percentPosition: true, fitWidth: true}); ", View::POS_END);
//$this->registerJs("MsnrySmall.layout();", View::POS_LOAD);

$showSubitems = (ArrayHelper::getValue($options, 'showChildGroups', true) && $group->getChildGroupsCount() > 0);
?>
<div class="grid-sizer-small" style="width: 33%"></div>
<div class="panel panel-card-small col-md-<?= ArrayHelper::getValue($options, 'col-md', 4) ?>" data-filter='<?= BadgeWidget::widget(['models' => $group->relGroupTypes, 'useBadges' => false, 'attribute' => 'id']) ?>'>
	<div class="panel-heading">
		<?php if (ArrayHelper::getValue($options, 'showChildGroups', true) && $group->getChildGroupsCount() > 0): ?>
			<div class="panel-control">
				<?= $this->render('control_block', ['targetId' => "childGroups-{$group->id}", 'expanded' => false]) ?>
			</div>
		<?php endif; ?>
		<div class="panel-title">
			<?= BadgeWidget::widget([
				'models' => $group,
				'attribute' => 'name',
				'prefix' => BadgeWidget::widget([
					'models' => $group->relGroupTypes,
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => 3,
					'itemsSeparator' => false,
					"optionsMap" => RefGroupTypes::colorStyleOptions(),
					"badgeOptions" => [
						'class' => 'badge group-type-name'
					],
					'iconify' => true,
					'tooltip' => static function(RefGroupTypes $model) {
						return $model->name;
					},
					'linkScheme' => [GroupsModule::to(), 'GroupsSearch[type]' => 'id']
				]),
				"badgeOptions" => [
					'class' => "badge badge-info"
				],
				"optionsMap" => RefGroupTypes::colorStyleOptions(),
				"optionsMapAttribute" => 'type',
				'linkScheme' => [GroupsModule::to(['groups/profile', 'id' => $group->id])]

			]) ?>
		</div>
	</div>

	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<?= GroupUsersWidget::widget(['group' => $group, 'options' => ['column_view' => true, 'showChildStats' => $showSubitems]]) ?>
			</div>
		</div>
		<?php if ($showSubitems): ?>
			<div id="childGroups-<?= $group->id ?>" class="collapse" aria-expanded="false" style="height: 0px;">
				<div class="list-divider"></div>
				<div class="row child-groups">
					<div class="child-groups-summary">
						<?= BadgeWidget::widget([
							'models' => ArrayHelper::cmap(Groups::getGroupScopeTypesData(array_diff($group->collectRecursiveIds(), [$group->id])), 'id', ['name', 'count'], ': '),//Как я круто придумал
							"optionsMap" => RefGroupTypes::colorStyleOptions(),
							"optionsMapAttribute" => 'id',
							'itemsSeparator' => false
						]) ?>
					</div>
					<div class="col-md-12">
						<?php foreach ($group->relChildGroups as $childGroup): ?>
							<?= $this->render('group_small', ['group' => $childGroup, 'options' => ['col-md' => 12]]) ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>


		<?php endif; ?>
	</div>


	<div class="panel-footer">
		<?= GroupLeadersWidget::widget(['group' => $group, 'showImportant' => true]) ?>
	</div>
</div>

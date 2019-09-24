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
use app\widgets\badge\BadgeWidget;
use pozitronik\helpers\ArrayHelper;
use yii\web\View;

?>

<div class="panel panel-card-small col-md-<?= ArrayHelper::getValue($options, 'col-md', 4) ?>" data-filter='<?= BadgeWidget::widget(['models' => $group->relGroupTypes, 'useBadges' => false, 'attribute' => 'id']) ?>'>
	<div class="panel-heading">
		<?php if (ArrayHelper::getValue($options, 'showChildGroups', true) && $group->getChildGroupsCount() > 0): ?>
			<div class="panel-control">
				<?= $this->render('control_block', ['target' => "childGroups-{$group->id}", 'expanded' => false]) ?>
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
				<?= GroupUsersWidget::widget(['group' => $group, 'options' => ['column_view' => true]]) ?>
			</div>
		</div>
		<?php if (ArrayHelper::getValue($options, 'showChildGroups', true) && $group->getChildGroupsCount() > 0): ?>
			<div id="childGroups-<?= $group->id ?>" class="collapse" aria-expanded="false" style="height: 0px;">
				<div class="list-divider"></div>
				<div class="row child-groups">
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
		<?= GroupLeadersWidget::widget(['group' => $group]) ?>
	</div>
</div>

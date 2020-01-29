<?php

namespace app\modules\targets\models\import\activerecord;

use Yii;

/**
 * This is the model class for table "import_targets_decomposed".
 *
 * @property int $id
 * @property int|null $cluster_id
 * @property int|null $command_id
 * @property int|null $initiative_id
 * @property int|null $milestone_id
 * @property int|null $target_id
 * @property int $domain
 */
class ImportTargetsDecomposed extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'import_targets_decomposed';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cluster_id', 'command_id', 'initiative_id', 'milestone_id', 'target_id', 'domain'], 'integer'],
            [['domain'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cluster_id' => 'Cluster ID',
            'command_id' => 'Command ID',
            'initiative_id' => 'Initiative ID',
            'milestone_id' => 'Milestone ID',
            'target_id' => 'Target ID',
            'domain' => 'Domain',
        ];
    }
}

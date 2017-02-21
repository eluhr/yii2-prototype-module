<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace dmstr\modules\prototype\models\base;

use Yii;

/**
 * This is the base-model class for table "app_twig".
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 * @property string $aliasModel
 */
abstract class Twig extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%twig}}';
    }

    /**
     * @inheritdoc
     * @return \dmstr\modules\prototype\models\query\TwigQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \dmstr\modules\prototype\models\query\TwigQuery(get_called_class());
    }

    /**
     * Alias name of table for crud viewsLists all Area models.
     * Change the alias name manual if needed later
     * @return string
     */
    public function getAliasModel($plural = false)
    {
        if ($plural) {
            return Yii::t('prototype', 'Twigs');
        } else {
            return Yii::t('prototype', 'Twig');
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'value'], 'required'],
            [['value'], 'string'],
            [['key'], 'string', 'max' => 255],
            [['key'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('prototype', 'ID'),
            'key' => Yii::t('prototype', 'Key'),
            'value' => Yii::t('prototype', 'Value'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(
            parent::attributeHints(),
            [
                'id' => Yii::t('prototype', 'ID'),
                'key' => Yii::t('prototype', 'Key'),
                'value' => Yii::t('prototype', 'Value'),
            ]);
    }


}

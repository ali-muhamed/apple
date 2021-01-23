<?php


namespace common\models;


use yii\db\ActiveRecord;


/**
 * AppleModel model
 *
 * @property integer $id
 * @property string $color
 * @property integer $created_at
 * @property integer $fallen_at
 * @property integer $status
 * @property float $eaten
 */
class AppleModel extends ActiveRecord
{

    const
        STATUS_ON_TREE = 0,
        STATUS_FALLEN = 1,
        STATUS_ROTTEN = 2;

    public static $statusName = [
        self::STATUS_ON_TREE => 'Висит на дереве',
        self::STATUS_FALLEN => 'Упало/лежит на земле',
        self::STATUS_ROTTEN => 'Гнилое яблоко'
    ];

    const
        COLOR_RED = 'Red',
        COLOR_GREEN = 'Green',
        COLOR_YELLOW = 'Yellow',
        COLOR_BROWN = 'Brown';

    public static $colors = [
        self::COLOR_RED,
        self::COLOR_GREEN,
        self::COLOR_YELLOW,
        self::COLOR_BROWN,
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'apple';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['color', 'created_at', 'status', 'eaten'], 'required'],
            [['created_at', 'fallen_at', 'status'], 'integer'],
            [['eaten'], 'number', 'min' => 0, 'max' => 100],
            [['color'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color' => 'Color',
            'created_at' => 'Created at',
            'fallen_at' => 'Fallen at',
            'status' => 'Status',
            'eaten' => 'Eaten %',
        ];
    }

    static function removeEaten() {
        self::deleteAll(
            [
                'eaten' => 100,
            ]
        );
    }

    static function updateRotten() {
        $applesRotten =  self::find()->andWhere(['is not', 'fallen_at', new \yii\db\Expression('null')])->all();

        foreach ($applesRotten as $appleRotten) {
            if (($appleRotten->fallen_at + 60 * 60 * 1000 * 5) < time()) {
                $appleRotten->status = self::STATUS_ROTTEN;
                $appleRotten->update(false, array('status'));
            }
        }
    }

}
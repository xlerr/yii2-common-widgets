公共小部件和辅助类
===========


### 自定义格式化方法

##### 注册
```php
'components' => [
    'formatter' => [
        'as CustomFormatter' => \xlerr\common\behaviors\FormatterBehavior::class,
    ],
];
```

##### 用法
```php
$formatter = \Yii::$app->getFormatter(); 
$formatter->format(123456, ['f2y', true]); // string 1,234.56
$formatter->format(123456, 'f2y'); // float 1234.56
$formatter->format(1234.56, 'y2f'); // int 123456
```

##### 用法 - \yii\grid\GridView
```php
'columns' => [
    [
        'format' => ['f2y', true],
        'attribute' => 'amount',
    ],
],
```

### 金额输入框
```php
$form->field($model, 'amount')->widget(\xlerr\common\widgets\MoneyInput::class, [
    'displayOptions' => [
        'placeholder' => '金额输入框',
    ],
]);
```

or

```php
\xlerr\common\widgets\MoneyInput::widget([
    'name' => 'amount',
    'displayOptions' => [
        'placeholder' => '金额输入框',
    ],
]);
```
公共小部件和辅助类
===========


## 自定义格式化方法

### 注册

```php
'components' => [
    'formatter' => [
        'as CustomFormatter' => \xlerr\common\behaviors\FormatterBehavior::class,
    ],
];
```

### 金额格式化

- f2y|y2f 用法

    ```php
    $formatter = \Yii::$app->getFormatter();
    $formatter->format(123456, ['f2y', true]); // string 1,234.56
    $formatter->format(123456, 'f2y'); // float 1234.56
    $formatter->format(1234.56, 'y2f'); // int 123456
    ```

- f2y|y2f 用法 - \yii\grid\GridView

    ```php
    'columns' => [
        [
            'format' => ['f2y', true],
            'attribute' => 'amount',
        ],
    ],
    ```
    `amount`为`647283`, 输出字符串`6,472.83`

- in 用法

    ```php
    'columns' => [
        [
            'format' => ['in', ['open' => '打开', 'close' => '关闭'], 'defaultValue'],
            'attribute' => 'status', // print 打开|关闭|defaultValue
        ],
    ],
    ```

### 金额输入框

![amount_input]

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





[amount_input]:data:image/png;base64,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
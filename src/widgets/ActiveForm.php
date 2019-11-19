<?php

namespace xlerr\common\widgets;

class ActiveForm extends \kartik\widgets\ActiveForm
{
    public $submitWaiting = false;
    public $waitingPrompt = '<i class="fa fa-spin fa-spinner"></i> 处理中...';

    public function registerAssets()
    {
        parent::registerAssets();

        if ($this->submitWaiting) {
            $view   = $this->getView();
            $id     = $this->options['id'];
            $prompt = $this->waitingPrompt;
            $js     = <<<JAVASCRIPT
jQuery('#$id').on('beforeSubmit', function (e) {
    const submitBtn = $(this).find('[type=submit]');
    if (submitBtn) {
        submitBtn.html('$prompt');
        submitBtn.attr('disabled', true);
    }
});
JAVASCRIPT;

            $view->registerJs($js);
        }
    }
}

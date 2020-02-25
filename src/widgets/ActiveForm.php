<?php

namespace xlerr\common\widgets;

class ActiveForm extends \kartik\widgets\ActiveForm
{
    const WAITING_PROMPT_SEARCH = '<i class="fa fa-spin fa-spinner"></i> 搜索';

    public $submitWaiting = true;
    public $waitingPrompt = '<i class="fa fa-spin fa-spinner"></i> 处理中...';

    public function registerAssets()
    {
        parent::registerAssets();

        if ($this->submitWaiting) {
            $view   = $this->getView();
            $id     = $this->options['id'];
            $prompt = $this->waitingPrompt;
            $js     = <<<JAVASCRIPT
jQuery('#$id').on('beforeValidate', function (e) {
    const submitBtn = $(this).find('[type=submit]');
    if (submitBtn) {
        submitBtn.attr('_old_html', submitBtn.html()).html('$prompt').attr('disabled', true);
    }
}).on('afterValidate', function (e, err) {
    for (k in err) {
        if (!$.isEmptyObject(err[k])) {
            const submitBtn = $(this).find('[type=submit]');
            submitBtn && submitBtn.html(submitBtn.attr('_old_html')).removeAttr('disabled');
            break;
        }
    }
});
JAVASCRIPT;

            $view->registerJs($js);
        }
    }
}

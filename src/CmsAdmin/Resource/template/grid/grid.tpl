{headScript()->appendFile('/resource/cmsAdmin/js/grid.js')}
<table id="{$_grid->getClass()}" class="table table-striped">
    {$_renderer->renderHeader()}
    {$_renderer->renderBody()}
    {$_renderer->renderFooter()}
</table>
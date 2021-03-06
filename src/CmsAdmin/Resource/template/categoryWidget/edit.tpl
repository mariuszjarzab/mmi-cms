<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <strong>{if $request->id > 0}{#Edycja#}{else}{#Dodawanie#}{/if} {#konfiguracji widgeta#}</strong>
                    </div>
                    <div class="card-body">
                        {$widgetForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{if $relationGrid}
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <strong>{if !$request->relationId}{#Dodaj atrybut#}{else}{#Edytuj opcje atrybutu#}{/if}</strong>
                    </div>
                    <div class="card-body">
                        {$relationForm}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{#Atrybuty#}</strong>
                    </div>
                    <div class="card-body">
                        {$relationGrid}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/if}
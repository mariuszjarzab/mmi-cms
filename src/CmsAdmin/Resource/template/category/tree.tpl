{headScript()->appendFile('/resource/cmsAdmin/js/category.js')}
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{#Zarządzanie treścią#}</strong>
                        <div id="categoryMessageContainer" style="float: left; margin-left: 150px;"></div>
                    </div>
                    <div class="card-body">
                       <div id="categoryTreeContainer">
                            <div id="jstree">
                                {jsTree([], $baseUrl . '/resource/cmsAdmin/js/tree.js')}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

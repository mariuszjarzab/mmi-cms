/**
 * Obsługa drzewka stron CMS
 */

var request = request || {};
//konfiguracja
var CATEGORYCONF = CATEGORYCONF || {};
//klucz do stanu drzewka
CATEGORYCONF.stateKey = 'cms-category-jstree';
//po jakim czasie ukryć message
CATEGORYCONF.msgDelay = 2500;
//czy przeładować
CATEGORYCONF.reload = false;
//czy otwarto menu kontekstowe
CATEGORYCONF.contextMenu = false;

//zarządzanie stronami
$(document).ready(function () {
    //przeniesienie messengera
    $('ul#messenger').appendTo('#categoryMessageContainer').show();

    //odpalenie drzewka
    $('#jstree').jstree({
        'core': {
            'data': {
                'url': request.baseUrl + '/cmsAdmin/category/node',
                'data': function (node) {
                    return {'parentId': node.id};
                }
            },
            'themes': {
                'name': 'default',
                'variant': 'small',
                'responsive': false,
                'stripes': true
            },
            'strings': {
                'New node': 'Nowa strona',
                'Loading ...': 'Ładowanie ...'
            },
            'multiple': false,
            'expand_selected_onload': true,
            'force_text': true,
            'check_callback': function (op) {
                if (op === 'delete_node') {
                    return confirm("Czy na pewno usunąć stronę?");
                }
                return true;
            }
        },
        'state': {
            'key': CATEGORYCONF.stateKey,
            'filter': function (state) {
                if (request.originalId) {
                    state.core.selected = [request.originalId];
                }
                return state;
            }
        },
        'unique': {
            'duplicate': function (name, counter) {
                return name + ' ' + counter;
            }
        },
        'types': {
            '#': {'valid_children': ["root"]},
            'root': {
                'valid_children': ["default", "leaf"],
                'icon': request.baseUrl + '/resource/cmsAdmin/images/tree.png'
            },
            'default': {'valid_children': ["default", "leaf"]},
            'leaf': {'valid_children': ["default", "leaf"]}
        },
        'contextmenu': {
            'items': function (node) {
                if (node.state.disabled) {
                    return;
                }
                CATEGORYCONF.contextMenu = true;
                var tmp = $.jstree.defaults.contextmenu.items();
                var menu = {};
                delete tmp.ccp;
                delete tmp.rename;
                if (this.get_type(node) !== "root") {
                    menu.preview = {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Podgląd",
                        "action": function (data) {
                            var inst = $.jstree.reference(data.reference);
                            var node = inst.get_node(data.reference);
                            CATEGORYCONF.loadPreviewUrl(node.id);

                        }
                    };
                };
                tmp.create.label = "Utwórz podstronę";
                tmp.remove.label = "Usuń";
                //kopia artykułu z menu kontekstowego
                tmp.copy = {
                    "label": "Kopiuj",
                    "action": function (data) {
                        var inst = $.jstree.reference(data.reference);
                        var node = inst.get_node(data.reference);
                        CATEGORYCONF.hideMessage();
                        $.post(request.baseUrl + '/cmsAdmin/category/copy', {'id': node.id})
                                .done(function (d) {
                                    if (d.status) {
                                        CATEGORYCONF.reload = true;
                                        inst.set_id(node, d.id);
                                        $('#jstree').jstree('deselect_all');
                                        $('#jstree').jstree('select_node', d.id);
                                    }
                                    inst.refresh();
                                    CATEGORYCONF.showMessage(d);
                                })
                                .fail(function () {
                                    inst.refresh();
                                    CATEGORYCONF.showMessage({'error': 'Nie udało się skopiować strony'});
                                });
                    }
                };
                if (this.get_type(node) === "root") {
                    tmp.create.label = "Utwórz nową stronę";
                    tmp.create.separator_after = false;
                } else {
                    tmp.edit = {
                        "separator_before": true,
                        "separator_after": false,
                        "label": "Edytuj",
                        "action": function (data) {
                            var inst = $.jstree.reference(data.reference);
                            var node = inst.get_node(data.reference);
                            CATEGORYCONF.loadEditUrl(node.id);
                        }
                    };
                }
                menu.edit = tmp.edit;
                menu.create = tmp.create;
                if (this.get_type(node) !== "root") {
                    menu.copy = tmp.copy;
                }
                if (this.get_type(node) == "leaf") {
                    menu.remove = tmp.remove;
                }
                return menu;
            }
        },
        'plugins': ["state", "unique", "types", "contextmenu", "dnd", "wholerow"]
    })
            .on('delete_node.jstree', function (e, data) {
                CATEGORYCONF.hideMessage();
                $.post(request.baseUrl + '/cmsAdmin/category/delete', {'id': data.node.id})
                        .done(function (d) {
                            if (d.status) {
                                CATEGORYCONF.reload = true;
                                $('#jstree').jstree('deselect_all');
                                $('#jstree').jstree('select_node', data.parent);
                            } else {
                                data.instance.refresh();
                            }
                            CATEGORYCONF.showMessage(d);
                        })
                        .fail(function () {
                            data.instance.refresh();
                            CATEGORYCONF.showMessage({'error': 'Nie udało się usunąć strony'});
                        });
            })
            .on('create_node.jstree', function (e, data) {
                CATEGORYCONF.hideMessage();
                $.post(request.baseUrl + '/cmsAdmin/category/create', {'parentId': data.node.parent, 'order': data.position, 'name': data.node.text})
                        .done(function (d) {
                            if (d.status) {
                                data.instance.set_id(data.node, d.id);
                                data.instance.set_icon(data.node, d.icon);
                                if (d.disabled) {
                                    data.instance.disable_node(data.node);
                                }
                                $('#jstree').jstree('deselect_all');
                                $('#jstree').jstree('select_node', d.id);
                            } else {
                                data.instance.refresh();
                            }
                            CATEGORYCONF.showMessage(d);
                        })
                        .fail(function () {
                            data.instance.refresh();
                            CATEGORYCONF.showMessage({'error': 'Nie udało się utworzyć strony'});
                        });
            })
            .on('rename_node.jstree', function (e, data) {
                CATEGORYCONF.hideMessage();
                $.post(request.baseUrl + '/cmsAdmin/category/rename', {'id': data.node.id, 'name': data.text})
                        .done(function (d) {
                            if (d.status) {
                                CATEGORYCONF.reload = true;
                                data.node.text = d.name;
                                $('#jstree').jstree('deselect_all');
                                $('#jstree').jstree('select_node', d.id);
                            } else {
                                data.instance.refresh();
                            }
                            CATEGORYCONF.showMessage(d);
                        })
                        .fail(function () {
                            data.instance.refresh();
                            CATEGORYCONF.showMessage({'error': 'Nie udało się zmienić nazwy strony'});
                        });
            })
            .on('move_node.jstree', function (e, data) {
                CATEGORYCONF.hideMessage();
                var params = {'id': data.node.id, 'parentId': data.parent, 'oldParentId': data.old_parent, 'order': data.position, 'oldOrder': data.old_position};
                $.post(request.baseUrl + '/cmsAdmin/category/move', params)
                        .done(function (d) {
                            if (d.status) {
                                CATEGORYCONF.reload = true;
                                $('#jstree').jstree('deselect_all');
                                $('#jstree').jstree('select_node', d.id);
                            } else {
                                data.instance.refresh();
                            }
                            CATEGORYCONF.showMessage(d);
                        })
                        .fail(function () {
                            data.instance.refresh();
                            CATEGORYCONF.showMessage({'error': 'Nie udało się przenieść strony'});
                        });
            })
            .on('changed.jstree', function (e, data) {
                if (!data || !data.selected || !data.selected.length || !(0 in data.selected)) {
                    return;
                }
                //jeśli nie jest to zaznaczenie, wychodzimy
                if (data.action !== "select_node") {
                    return;
                }
                //jeśli aktualny url nie pochodzi z drzewka, wychodzimy
                if (window.location.search.indexOf("from=tree") === -1 && window.location.search.indexOf("id=") !== -1 && !CATEGORYCONF.reload) {
                    if (parseFloat(request.originalId) === parseFloat(data.selected[0])) {
                        return;
                    }
                }
            })
            .on('state_ready.jstree', function (e, data) {
                //jeśli aktualny url nie pochodzi z drzewka
                if (window.location.search.indexOf("from=tree") === -1) {
                    resExp = window.location.search.match(/originalId=(\d+)/);
                    if (resExp !== null && parseFloat(resExp[1])) {
                        $('#jstree').jstree('deselect_all');
                        var selRes = $('#jstree').jstree('select_node', resExp[1]);
                        if (selRes === false) {
                            CATEGORYCONF.reload = true;
                        }
                        return;
                    }
                    resExp = window.location.search.match(/id=(\d+)/);
                    if (resExp !== null && parseFloat(resExp[1])) {
                        $('#jstree').jstree('deselect_all');
                        var selRes = $('#jstree').jstree('select_node', resExp[1]);
                        if (selRes === false) {
                            CATEGORYCONF.reload = true;
                        }
                    }
                }
            });
});

//przeładowanie strony
CATEGORYCONF.loadEditUrl = function (nodeId) {
    window.location.assign(request.baseUrl + '/cmsAdmin/category/edit?id=' + nodeId);
};

//przeładowanie strony
CATEGORYCONF.loadPreviewUrl = function (nodeId) {
    window.open(request.baseUrl + '/cms-content-preview?versionId=' + nodeId, '_blank');
};

CATEGORYCONF.showMessage = function (data) {
    $('#categoryMessageContainer').empty();
    var msg = null;
    if (typeof data.error === 'string') {
        msg = data.error;
    } else if (typeof data.message === 'string') {
        msg = data.message;
    }
    if (msg === null) {
        return;
    }
    $('#categoryMessageContainer').html('<p style="position: absolute;">' + msg + '</p>');
    $('#categoryMessageContainer > p').delay(1500).fadeOut(500);

};

CATEGORYCONF.hideMessage = function () {
    $('#categoryMessageContainer').empty();
};

define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'users/index',
                    add_url: 'users/add',
                    edit_url: 'users/edit',
                    del_url: 'users/del',
                    multi_url: 'users/multi',
                    table: 'users',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'uid',
                sortName: 'uid',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'uid', title: __('Uid')},
                        {field: 'appid', title: __('Appid')},
                        {field: 'open_id', title: __('Open_id')},
                        {field: 'name', title: __('Name')},
                        {field: 'head_image', title: __('Head_image'), formatter: Table.api.formatter.image},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
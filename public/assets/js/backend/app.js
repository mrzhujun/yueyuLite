define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'app/index',
                    add_url: 'app/add',
                    edit_url: 'app/edit',
                    del_url: 'app/del',
                    multi_url: 'app/multi',
                    table: 'app',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'appid', title: __('Appid')},
                        {field: 'appsecret', title: __('Appsecret')},
                        {field: 'name', title: __('Name')},
                        {field: 'column_switch', title: __('Column_switch'), visible:false, searchList: {"0":__('Column_switch 0'),"1":__('Column_switch 1')}},
                        {field: 'column_switch_text', title: __('Column_switch'), operate:false},
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
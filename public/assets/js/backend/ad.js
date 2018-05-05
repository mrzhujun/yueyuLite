define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ad/index',
                    add_url: 'ad/add',
                    edit_url: 'ad/edit',
                    del_url: 'ad/del',
                    multi_url: 'ad/multi',
                    table: 'ad',
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
                        {field: 'code', title: __('Code')},
                        {field: 'type_id', title: __('Type_id')},
                        {field: 'where', title: __('Where'), visible:false, searchList: {"0":__('Where 0'),"1":__('Where 1'),"2":__('Where 2'),"3":__('Where 3'),"4":__('Where 4'),"5":__('Where 5'),"6":__('Where 6')}},
                        {field: 'where_text', title: __('Where'), operate:false},
                        {field: 'is_open', title: __('Is_open'), visible:false, searchList: {"0":__('Is_open 0'),"1":__('Is_open 1')}},
                        {field: 'is_open_text', title: __('Is_open'), operate:false},
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
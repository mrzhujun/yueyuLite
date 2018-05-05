define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'banner/index',
                    add_url: 'banner/add',
                    edit_url: 'banner/edit',
                    del_url: 'banner/del',
                    multi_url: 'banner/multi',
                    table: 'banner',
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
                        {field: 'app_id_from', title: __('Appidfrom')},
                        {field: 'image', title: __('Image'), formatter: Table.api.formatter.image},
                        {field: 'type', title: __('Type'), visible:false, searchList: {"0":__('Type 0'),"1":__('Type 1'),"2":__('Type 2'),"3":__('Type 3')}},
                        {field: 'type_text', title: __('Type'), operate:false},
                        {field: 'intro', title: __('Intro')},
                        {field: 'status_data', title: __('Status_data'), visible:false, searchList: {"0":__('Status_data 0'),"1":__('Status_data 1')}},

                        {field: 'content_id', title: __('Content_id')},
                        {field: 'status_data_text', title: __('Status_data'), operate:false},
                        // {field: 'appid', title: __('Appid')},
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
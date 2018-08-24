define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'donation/index',
                    add_url: 'donation/add',
                    edit_url: 'donation/edit',
                    del_url: 'donation/del',
                    multi_url: 'donation/multi',
                    table: 'donation',
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
                        {field: 'user.nickname', title: __('User_id')},
                        {field: 'student.name', title: __('Student_id')},
                        {field: 'year', title: __('Year')},
                        {field: 'money', title: __('Money'), operate:'BETWEEN'},
                        {field: 'paytime', title: __('Paytime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'paystatus', title: __('Paystatus'), visible:false, searchList: {"0":__('Paystatus 0'),"1":__('Paystatus 1'),"2":__('Paystatus 2'),"3":__('Paystatus 3')}},
                        {field: 'paystatus_text', title: __('Paystatus'), operate:false},
                        {field: 'expirytime', title: __('Expirytime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'fullname', title: __('Fullname')},
                        {field: 'sex', title: __('Sex'), visible:false, searchList: {"1":__('Sex 1'),"2":__('Sex 2')}},
                        {field: 'sex_text', title: __('Sex'), operate:false},
                        {field: 'email', title: __('Email')},
                        {field: 'birthday', title: __('Birthday'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'profession', title: __('Profession')},
                        {field: 'address', title: __('Address')},
                        {field: 'address_detail', title: __('Address_detail')},
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
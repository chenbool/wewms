// 表格接口
var Table = {
    urls : { 
        "index":'',
        'create':'',
        'edit':'',
        'delete':''
    },
    formatter : { },
    table: '',
    methods: {}
};

// 添加和修改的弹窗大小
var layer_area = {
    create: [],
    edit:[]
};

// formatter
Table.formatter = {
    time: function(value, row, index){
        if( value != null ){
            value *= 1000;
            return moment(value).format('YYYY-MM-DD HH:mm');  
        }else{
            return '-';
        }
    },
    operate: function(value, row, index){
        return '<button class="btn btn-sm btn-success mg-r-xs" type="button" onclick="Table.methods.edit(' + row.id + ')" ><i class="fa fa-edit" > </i><span class="hidden-xs hidden-sm">编辑</span></button><button class="btn btn-sm btn-danger" type="button" onclick="Table.methods.del(' + row.id + ')"><i class="fa fa-trash" > </i><span class="hidden-xs hidden-sm">删除</span></button>';
        // return '<button class="btn btn-sm btn-success" type="button" onclick="Table.methods.edit('+row.id+')"> <i class="fa fa-edit"></i> 编辑</button> <button class="btn btn-sm btn-danger" type="button" onclick="Table.methods.del('+row.id+')">  <i class="fa fa-trash"></i>删除 </button> ';
    },
    img: function(value, row, index){
        return ' <a href="'+value+'" target="_blank"> <img src="'+value+'" alt="" width="20" > </a> ';
    },
    file: function(value, row, index){
        return '  <a href="'+value+'">查看</a>';
    },
    head_img: function(value, row, index){
        if( value != null ){
            return ' <a href="'+value+'" target="_blank"> <img src="'+value+'" alt="" width="20" > </a> ';
        }else{
            return ' <a href="" target="_blank"> <img src="/static/admin/img/about.png" alt="" width="20" > </a> ';
        }
    },
    checkbox: function(value, row, index){
        return '<label class="cr-styled"><input type="checkbox"><i class="fa"></i> </label>';
    },
    status: function (value, row, index){
        return value == 0 ? '正常' : '禁用';
    },
    icon: function (value, row, index) {
        return '<i class="fa ' + value + '"></i>'
    }

};

// 表格数据
var tableData = {
    url: Table.urls.index,             //请求后台的URL（*）
    method: 'GET',                      //请求方式（*）
    toolbar: '#toolbar',                //工具按钮用哪个容器
    striped: true,                      //是否显示行间隔色
    cache: false,                       //是否使用缓存，默认为true，所以一般情况下需要设置一下这个属性（*）
    sortable: true,                     //是否启用排序
    sortOrder: "asc",                   //排序方式
    pagination: true,                   //是否显示分页（*）
    sidePagination: "server",           //分页方式：client 客户端分页，server 服务端分页（*）
    pageNumber: 1,                      //初始化加载第一页，默认第一页,并记录
    pageSize: 10,                        //每页的记录行数（*）
    pageList: [5, 10, 25, 50, 100],     //可供选择的每页的行数（*）
    search: false, //是否显示表格搜索
    strictSearch: false,
    searchOnEnterKey: true,

    showPrint: true,    //打印
    showJumpto: true, //分页跳转
    
    // copyBtn: true,  //复制

    showExport: true, 
    exportDataType: "basic",              //basic', 'all', 'selected'
    // 'json', 'xml', 'png', 'csv', 'txt', 'sql', 'doc', 'excel', 'xlsx', 'pdf','powerpoint'
    exportTypes: ['csv', 'xlsx', 'excel', 'doc', 'png', 'sql', 'json', 'xml', 'txt' ],
    exportOptions:{
        ignoreColumn: [0,-1],  //忽略某一列的索引
        // ignoreRow: [0,1],
        fileName: '文件名',  //文件名称设置
        worksheetName: 'sheet1',  //表格工作区名称
        tableName: '文件名',
        excelstyles: ['background-color', 'color', 'font-size', 'font-weight'],
        jsonScope: 'all',
        csvEnclosure: '"',
        csvSeparator: ',',
        csvUseBOM: true,
        displayTableName: false,
        escape: false,
        exportHiddenCells: false,
    },

    showColumns: true,                  //是否显示所有的列（选择显示的列）
    showRefresh: true,                  //是否显示刷新按钮
    minimumCountColumns: 2,             //最少允许的列数
    clickToSelect: true,                //是否启用点击选中行
    // uniqueId: "ID",                     //每一行的唯一标识，一般为主键列
    showToggle: true,                   //是否显示详细视图和列表视图的切换按钮
    cardView: false,                    //是否显示详细视图
    detailView: false,                  //是否显示父子表
    checkboxHeader: true,               //设置 false 将在列头隐藏全选复选框
    buttonsClass: 'default',
    iconSize: 'md',
    iconsPrefix: 'fa',

    icons: {
        refresh: 'fa-refresh',
        export: 'fa-download',
        toggle: 'fa-columns',
        columns: 'fa-list-ul',
        print: 'fa-print',
    },

    //得到查询的参数
    queryParams : function (params) {
        //这里的键的名字和控制器的变量名必须一致，这边改动，控制器也需要改成一样的
        var data = $(".form-search").serializeArray();
        var temp = {   
            rows: params.limit,                         //页面大小
            page: (params.offset / params.limit) + 1,   //页码
            sort: params.sort,      //排序列名  
            sortOrder: params.order, //排位命令（desc，asc）
            data: arrayToJson(data),
            keyword: params.search
        };
        return temp;
    },
    columns: [
        {
            checkbox: true,  
            visible: true,
            printIgnore: true
        }, 
        {
            field: 'id',
            title: 'ID',
            sortable: true,
            printIgnore: true
        },
        {
            field: 'add_time',
            title: '操作',
            formatter: Table.formatter.operate,
            printIgnore: true
        }
     ],
    onLoadSuccess: function () {
        // console.log("数据加载完毕！");
    },
    onLoadError: function () {
        toastr.error( "数据加载失败！" );
    },
    onDblClickRow: function (row, $element) {
        // console.log(row);
        // 双击编辑
        Table.methods.edit(row.id);
    },
};


// 表格方法
Table.methods = {
    add: function(){
        $.get(Table.urls.create, function(data) {
            layer.open({
                type: 1,
            　　title: "添加",
            　　shadeClose: true,
            　　shade: 0.4,
                anim: 5,
            // 　　area: layer_area.create,
            　　content: data
            });
        });
    },
    edit: function(id){
        $.get(Table.urls.edit+"?id="+id, function(data) {
            layer.open({
                type: 1,
            　　title: "编辑",
            　　shadeClose: true,
            　　shade: 0.4,
                anim: 5,
            // 　　area: layer_area.edit,
            　　content: data
            });
        });
    },
    del: function(id){
        layer.confirm('你确定要删除数据吗？', function(index){
            $.post(Table.urls.delete, {id: id}, function(data) {
                layer.msg(data.msg);
                if( data.error == 0 ){
                    Table.methods.refresh();
                    layer.close(index);
                }
            });  

        });
    },
    dels: function(){
        var res= Table.table.bootstrapTable('getSelections');
        // 判断是否有选中的行数
        if( res.length > 0 ){
            var ids = res.map(function(v) {
                return v.id;
            });
            
            // 弹出确认框
            layer.confirm('你确定要删除'+res.length+'条数据吗？', function(index){
                $.post(Table.urls.delete, {id: ids}, function(data) {
                    layer.msg(data.msg);
                    if( data.error == 0 ){
                        Table.methods.refresh();
                        layer.close(index);
                    }
                });  
            });

        }else{
            layer.msg('请选中一行');
        }
    },
    refresh: function(){
        Table.table.bootstrapTable('refresh');
    },
};

// 重复输出字符串
function repeat(src, n) {
    return (n > 0) ? src.concat(repeat(src, --n)):"";
}

// 数组转json
function arrayToJson(data){
    var tempObject = {};
    data.forEach(function(val,index,array){
        tempObject[val.name] = val.value;
        // console.log(val.value);
    });
    return tempObject;
}
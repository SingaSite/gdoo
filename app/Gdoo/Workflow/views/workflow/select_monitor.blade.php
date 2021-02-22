<form id="search-form" class="form-inline" name="mysearch" action="{{url()}}" method="get">

    <div class="input-group">
        <button type="button" class="btn btn-sm btn-default" data-toggle="dropdown">
            批量操作
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu text-xs">
            @if(isset($access['delete']))
            <li><a href="javascript:optionDelete('#myform','{{url('end')}}', '流程将被强行结束吗？');"><i class="icon icon-stop"></i> 结束</a></li>
            <li class="divider"></li>
            <li><a href="javascript:optionDelete('#myform','{{url('delete',['status'=>'0'])}}','确定要删除流程吗？');"><i class="icon icon-trash"></i> 删除</a></li>
            @endif
        </ul>
    </div>

    @include('searchForm')
</form>
<script type="text/javascript">

$(function() {
    $('#search-form').searchForm({
        data: {{json_encode($search['forms'])}},
        init: function(e) {

            var self     = this;

            var category = null;
            var work     = null;
            var step     = null;
            var category_id = 0;
            var work_id     = 0;
            var step_id     = 0;

            e.category = function(i) {

                var element = self.element[i];

                category = self.attr(i, 0);
                work     = self.attr(i, 1);
                step     = self.attr(i, 2);

                category_id = category.value;
                work_id     = work.value;
                step_id     = step.value;

                element.value.append('<select name="'+category.name+'" id="'+category.id+'" class="form-control input-sm"></select>&nbsp;<select name="'+work.name+'" id="'+work.id+'" class="form-control input-sm"></select>&nbsp;<select name="'+step.name+'" id="'+step.id+'" class="form-control input-sm"></select>');

                var res = {{json_encode($categorys)}};
                var option = '';
                $.map(res, function(row) {
                    option += '<option value="'+row.id+'">'+row.title+'</option>';
                });

                var e = $('#'+category.id).html(option);
                if(category_id) {
                    e.val(category_id);
                }
                
                _work(i);
                _step(i);

                self.on('change', '#' + category.id, function() {
                    category_id = this.value;
                    work_id     = 0;
                    step_id     = 0;
                    _work(i);
                    _step(i);
                });
                
                self.on('change', '#' + work.id, function() {
                    work_id = this.value;
                    step_id = 0;
                    _step(i);
                });
            }

            function _work(i) {
                $.get(app.url('workflow/workflow/dialog', {category_id: category_id}), function(res) {
                    var option = '<option value=""> - </option>';
                    $.map(res.data, function(row) {
                        option += '<option value="'+row.id+'">'+row.title+'</option>';
                    });
                    var e = $('#'+work.id).html(option);
                    if(work_id) {
                        e.val(work_id);
                    }
                });
            }

            function _step(i) {
                $.get(app.url('workflow/step/dialog', {work_id:work_id}), function(res) {
                    var option = '<option value=""> - </option>';
                    $.map(res.data, function(row) {
                        option += '<option value="'+row.id+'">'+row.title+'</option>';
                    });
                    var e = $('#'+step.id).html(option);
                    if(step_id) {
                        e.val(step_id);
                    }
                });
            }
        }
    });
});
</script>
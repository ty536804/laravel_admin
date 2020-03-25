<div class="chart tab-pane active" id="dept_edit">
    <div class="box-body">
        <form class="form-horizontal m-t" id="info_form">
            <div class="form-group">
                <label class="col-sm-3 control-label">职位名称：</label>
                <div class="col-sm-8">
                    <input id="" name="position_name"  type="text" class="form-control" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">归属部门：</label>
                <div class="col-sm-8">
                    <select class="form-control" name="department_id" id="department_id">
                        <option value="">--请选择--</option>
                        @foreach($all as $k=>$v)
                            <option  value="{{$v->id}}">{{$v->dp_name}}</option>
                            @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">职位说明：</label>
                <div class="col-sm-8">
                    <textarea id="desc" name="desc" class="form-control" required="" aria-required="true"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">状态：</label>
                <div class="col-sm-8 ">
                    <label>
                        <input type="radio" value="1" name="status" id="status">正常</label>
                    <label>
                        <input type="radio" value="0" name="status" id="status"> 禁用</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-3">
                    <button class="btn btn-primary" type="button" id="save_info" name="save_info">提交</button>
                </div>
            </div>
        </form>
    </div>
</div>

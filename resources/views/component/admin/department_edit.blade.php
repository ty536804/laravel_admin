<div class="chart tab-pane active" id="dept_edit">
    <div class="box-body">
        <form class="form-horizontal m-t" id="info_form">
            <div class="form-group">
                <label class="col-sm-3 control-label">部门名称：</label>
                <div class="col-sm-8">
                    <input id="dp_name" name="dp_name"  type="text" class="form-control" value="">
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

<div class="chart tab-pane" id="dept_power">
    <div class="box-body">
        <form class="form-horizontal m-t" id="edit_power">
            <button type="button" class="btn btn-primary btn-sm" id="all">所有</button>
            <button type="button" class="btn btn-primary btn-sm" id="save_power">保存修改</button>
            <input type="hidden" id="power_id" name="power_id" value="">
            <input type="hidden" id="id" name="id" value="">
            <hr>
            @foreach($power as $v)
                <label><input type="checkbox" data-id="{{$v->id}}"  data-parent_id ="{{$v->parent_id}}"  value="{{$v->id}}"> {{$v->pname}}</label>
                @if(!empty($v->allchild))
                    <p style="padding-left: 20px">
                        @foreach($v->allchild as $c)
                            <label><input type="checkbox" data-id="{{$c->id}}"   data-parent_id="{{$c->parent_id}}"  value="{{$c->id}}"> {{$c->pname}}</label>
                        @endforeach
                    </p>
                @endif
                <hr>
            @endforeach
        </form>
    </div>
</div>
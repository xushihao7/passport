<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登录</title>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link rel="stylesheet" href="{{URL::asset('/bootstrap/css/bootstrap.min.css')}}">
    <script src="{{URL::asset('/js/jquery-1.12.4.min.js')}}"></script>
    <script src="{{URL::asset('/bootstrap/js/bootstrap.min.js')}}"></script>
</head>
<body>
<form class="form-horizontal"  style="margin-top: 30px" >
    <h3 style="text-align: center">登录</h3>
    {{csrf_field()}}
    <div class="form-group" >
        <label for="inputEmail3" class="col-sm-2 control-label">账号</label>
        <div class="col-sm-10">
            <input type="eamil" class="form-control" name="name" id="uname">
        </div>
    </div>
    <div class="form-group" >
        <label for="inputPassword3" class="col-sm-2 control-label">密码</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" name="pwd" id="upwd">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="button" class="btn btn-info" id="add_cart_btn">登陆</button>
        </div>
    </div>
</form>
</body>
<script>
    $("#add_cart_btn").click(function(e){
        e.preventDefault();
        var name=$("#uname").val();
        var pwd=$("#upwd").val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url     :   "/login",
            type    :   'post',
            data    :   {name:name,pwd:pwd},
            dataType:   'json',
            success :   function(d){
               alert(d.msg)
               if(d.error==0){
                   location.href="{{$redirect}}"
               }
            }
        });
    })
</script>
</html>

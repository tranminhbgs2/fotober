@if (isset($success) && $success)
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">
<!--        <i class="ace-icon fa fa-times"></i>-->
    </button>
    {{ $success }}
</div>
@endif
@if (isset($warning) && $warning)
<div class="alert alert-warning">
    <button type="button" class="close" data-dismiss="alert">
<!--        <i class="ace-icon fa fa-times"></i>-->
    </button>
    {{ $warning }}
</div>
@endif

@if (isset($danger) && $danger)
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">
<!--            <i class="ace-icon fa fa-times"></i>-->
        </button>
        {{ $danger }}
    </div>
@endif
@if (session('success'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">
<!--        <i class="ace-icon fa fa-times"></i>-->
    </button>
    {{ session('success') }}
</div>
@endif
@if (session('warning'))
<div class="alert alert-warning">
    <button type="button" class="close" data-dismiss="alert">
<!--        <i class="ace-icon fa fa-times"></i>-->
    </button>
    {{ session('warning') }}
</div>
@endif
@if (session('danger'))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">
<!--            <i class="ace-icon fa fa-times"></i>-->
        </button>
        {{ session('danger') }}
    </div>
@endif
@if (isset($message) && $message)
    <div class="alert alert-warning">
        <button type="button" class="close" data-dismiss="alert">
<!--            <i class="ace-icon fa fa-times"></i>-->
        </button>
        {{ $message }}
    </div>
@endif

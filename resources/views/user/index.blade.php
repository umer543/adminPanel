@extends('layouts.adminPanelLayout')

@section('content')

    <style>
        .alternate-btn{
            display: none;
        }
    </style>

    <script type="text/javascript">


        function setId(x){
            document.getElementById('user_id').value = x;
        }

        function setDeleteId(x){
            document.getElementById('del_user_id').value = x;

        }

        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#deleteRoleForm').submit(function (e) {
                event.preventDefault(e);

                $.ajax({
                    type: 'post',
                    url: 'deleteRole',
                    data: $('#deleteRoleForm').serialize(),
                    dataType: 'json',
                    success: function (data) {

                        // alert('Omer');
                        $('#'+ data.id).text('');
                        // toggle buttons on if else based on each user row only
                        $('#btn-'+ data.id +' #whenDelete').removeClass('alternate-btn');
                        $('#btn-'+ data.id +' #mainDelete').addClass('alternate-btn');
                        $('#btn-'+ data.id +' #whenAssign').addClass('alternate-btn');
                        $('#btn-'+ data.id +' #mainAssign').removeClass('alternate-btn');

                        $('#deletemodal .close').click();

                    },
                });

            });


            $('#assignRoleForm').submit(function (e) {
                event.preventDefault(e);

                $.ajax({
                    type: 'post',
                    url: 'assignRole',
                    data: $('#assignRoleForm').serialize(),
                    dataType: 'json',
                    success: function (data) {
                        let id= data.id;
                        // alert('Omer');
                        $('#'+ data.id ).append(data.role);

                        // toggle buttons on if else based on each user row only
                        $('#btn-'+ data.id +' #whenAssign').removeClass('alternate-btn');
                        $('#btn-'+ data.id +' #mainAssign').addClass('alternate-btn');
                        $('#btn-'+ data.id +' #whenDelete').addClass('alternate-btn');
                        $('#btn-'+ data.id +' #mainDelete').removeClass('alternate-btn');

                        $('#assignmodal .close').click();


                    },
                });
            });

        });



    </script>


<!-- USER DATA-->
<div class="user-data m-b-30">
    <h3 class="title-3 m-b-30">
        <i class="zmdi zmdi-account-calendar"></i>user data</h3>
    <div class="table-responsive table-data">
        <table class="table">
            <thead>
            <tr>
                <td>name</td>
                <td>role</td>
                <td>Operation</td>
                <td></td>
            </tr>
            </thead>
            <tbody>

            @foreach($users as $user)
            <tr>
                <td>
                    <div class="table-data__info">
                        <h6>{{$user->name}}</h6>
                        <span>
                            <a href="#">{{$user->email}}</a>
                        </span>
                    </div>
                </td>
                <td>
                    <span id="{{$user->id}}">
                       @foreach($user->getRoleNames() as $role) {{ $role }} @endforeach
                    </span>
                </td>
{{--                 to identify row for changing buttons--}}
                <td id="btn-{{ $user->id }}">
                    @if($user->hasRole('super_admin'))
                        N/A
                        @elseif( $user->roles()->count() > 0 )
{{--                        for alternating buttons --}}
{{--                        <button> <span class="role user">Edit</span> </button>--}}
                        <button id="whenDelete" class="alternate-btn" type="button" data-toggle="modal" data-target="#assignmodal" onclick="setId({{$user->id}})"> <span class="role user" >Assign Role</span> </button>

                        <button id="mainDelete" type="button" data-toggle="modal" data-target="#deletemodal" onclick="setDeleteId({{$user->id}})"> <span class="role admin">Delete</span> </button>
                    @else
                        <button id="mainAssign" type="button" data-toggle="modal" data-target="#assignmodal" onclick="setId({{$user->id}})"> <span class="role user" >Assign Role</span> </button>

                        <button id="whenAssign" class="alternate-btn" type="button" data-toggle="modal" data-target="#deletemodal" onclick="setDeleteId({{$user->id}})"> <span class="role admin">Delete</span> </button>
                    @endif

                </td>
{{--                <td>--}}
{{--                    <span class="more">--}}
{{--                        <i class="zmdi zmdi-more"></i>--}}
{{--                    </span>--}}
{{--                </td>--}}
            </tr>

            @endforeach

            </tbody>
        </table>
    </div>
</div>
<!-- END USER DATA-->


{{--     assign form modal--}}
<!-- modal small -->
<div class="modal fade" id="assignmodal" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="smallmodalLabel">Small Modal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" method="post" id="assignRoleForm">
                @csrf
            <div class="modal-body">

                <div class="form-group">
                    @foreach($roles as $role)
                        <input type="radio" value="{{ $role->name }}" name="roleName"> {{$role->name}} <br>
                        @endforeach

                <input type="hidden" value="" name="user_id" id="user_id">

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <input class="btn btn-primary" type="submit" title="Assign Role" id="assignRoleSubmit">
            </div>
            </form>
        </div>
    </div>
</div>
<!-- end modal small -->


{{--    delete modal--}}
    <!-- modal small -->
    <div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="smallmodalLabel">Small Modal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal" method="post" id="deleteRoleForm">
                    @csrf
                    <div class="modal-body">
                        <h5>Are you sure?</h5>

                        <div class="form-group">
                            <input type="hidden" value="" id="del_user_id" name="delete_user_id">
                            <input style="display: none" type="text" value="user" name="user">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <input class="btn btn-primary" type="submit" title="Delete Role" id="deleteRoleSubmit">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal small -->


    @endsection

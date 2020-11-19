@extends('layouts.app')

@section('content-header', __('Account List'))

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-striped mb30">
            @if (session('status'))
              <p class="alert alert-success">{{ session('status') }}</p>
            @endif
            @if (session('success'))
              <p class="alert alert-success">{{ session('success') }}</p>
            @endif
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th class="table-column"></th>
                <th class="table-column"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data as $admin)
              <tr>
                <td>{{ $admin->name }}</td>
                <td>{{ $admin->email }}</td>
                <td><a role="button" class="btn btn-primary btn-sm"
                        href="{{ route('AdminController@updateAdminAccount',$admin->user_id) }}">Update</a>
                </td>
                <td>
                  @if (Auth::guard('admin')->user()->user_id != $admin->user_id)
                  <a role="button" class="btn btn-danger btn-sm"
                    data-toggle="modal" onclick="deleteData({{ $admin->user_id }})" 
                    data-target="#DeleteModal">
                    Delete
                  </a>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="DeleteModal" class="modal fade text-danger" role="dialog">
  <div class="modal-dialog ">
    <!-- Modal content-->
    <form action="" id="deleteForm" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title text-center">DELETE CONFIRMATION</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          {{ csrf_field() }}
          {{ method_field('DELETE') }}
          <p class="text-center">Are You Sure Want To Delete ?</p>
        </div>
        <div class="modal-footer">
          <center>
            <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
            <button type="submit" name="" class="btn btn-danger" data-dismiss="modal" onclick="formSubmit()">Yes, Delete</button>
          </center>
        </div>
        </div>
    </form>
  </div>
</div>
@endsection
@section('js')
<script>
  var url = '{{ route("AdminController#deleteAdminAccount", ":user_id") }}';
</script>
<script src="/js/admin/adminlist.js"></script>
@endsection

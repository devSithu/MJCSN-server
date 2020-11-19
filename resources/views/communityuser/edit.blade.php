@extends('layouts.app')
@section('content-header', __('Community User Edit'))
@section('css')
<link rel="stylesheet" href="{{ asset('css/communityuser/edit.css') }}">
@endsection
@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="row justify-content-center">
          <div class="col-sm-8">
            <button role="button" class="btn btn-danger m-2 float-right"
            data-toggle="modal" onclick="deleteData({{ $communityuser->user_number }})" 
            data-target="#DeleteModal">Delete</button>
          </div>
        </div>
        <form action="{{ route('CommunityUser#updateCommunityUserStatus', $communityuser->user_number) }}" method="post">
          @csrf
          <div class="row justify-content-center">
            <div class="col-sm-8">
              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <td><label for="status" class="control-label">User Name</label></td>
                    <td>
                      <label for="status" class="control-label">{{ $communityuser->user_name }}</label>
                    </td>
                  </tr>
                  <tr>
                    <td><label for="status" class="control-label">Status</label></td>
                    <td>
                      <select name="status" class="form-control">
                        <option value="0" {{ empty($communityuser->status) ? '' : old('status', $communityuser->status) == 0 ? 'selected' : '' }}>Active</option>
                        <option value="1" {{ empty($communityuser->status) ? '' : old('status', $communityuser->status) == 1 ? 'selected' : '' }}>Deactive</option>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="text-center">
                      <button type="button" class="btn btn-secondary" onclick="location.href='{{ route('CommunityUser#showList') }}'">Back</button>
                      <button type="submit" class="btn btn-danger">Update</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </form>
        <div id="DeleteModal" class="modal fade text-danger" role="dialog">
          <div class="modal-dialog ">
            <!-- Modal content-->
            <form action="" id="deleteForm" method="post">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title text-center">DELETE CONFIRMATION FOR COMMUNITY USER</h5>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                  {{ csrf_field() }}
                  {{ method_field('DELETE') }}
                  <p class="text-center">Are You Sure Want To Delete this user ?</p>
                  <p class="text-center"><b>User Name : {{ $communityuser->user_name }}</b></p>
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
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
  <script>
    var url = '{{ route("CommunityUser#deleteCommunityUser", ":user_number") }}';
  </script>
  <script src="/js/communityuser/edit.js"></script>
@endsection

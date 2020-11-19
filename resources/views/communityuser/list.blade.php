@extends('layouts.app')
@section('content-header', __('Community User List'))
@section('css')
<link rel="stylesheet" href="{{ asset('css/jquery.datetimepicker.css') }}">
<link rel="stylesheet" href="{{ asset('css/communityuser/list.css') }}">
@endsection
@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <form action="{{ route('CommunityUser#searchCommunityUsers') }}" method="post">
          {!! csrf_field() !!}
          <div class="row">
            <div class="col-sm-12">
              <table class="table">
                <tbody>
                  <tr>
                    <td><label for="user_name" class="control-label">User Name</label></td>
                    <td>
                      <input type="text" class="form-control" name="user_name" value="{{ empty($search->user_name) ? null : old('user_name', $search->user_name) }}">
                    </td>
                    <td><label for="user_name" class="control-label">Gender</label></td>
                    <td>
                      <input type="radio" name="gender" value="M" {{ empty($search->gender) ? '' : old('gender', $search->gender) == 'M' ? 'checked': '' }}>Male
                      <input type="radio" name="gender" value="F" {{ empty($search->gender) ? '' : old('gender', $search->gender) == 'F' ? 'checked': '' }}>Female
                    </td>
                  </tr>
                  <tr>
                    <td><label for="date_of_birth" class="control-label">Date of birth</label></td>
                    <td>
                      <input type="text" class="form-control app-date app-date-picker" name="fromDate" value="{{ empty($search->fromDate) ? null : old('fromDate', $search->fromDate) }}">
                    </td>
                    <td class="text-center">
                      <span class="text-center">~</span>
                    </td>
                    <td>
                      <input type="text" class="form-control app-date app-date-picker" name="toDate" value="{{ empty($search->toDate) ? null : old('toDate', $search->toDate) }}">
                    </td>
                  </tr>
                  <tr>
                    <td><label for="register_date" class="control-label">Registered Date</label></td>
                    <td>
                      <input type="text" class="form-control app-date app-date-picker" name="registerFromDate" value="{{ empty($search->registerFromDate) ? null : old('registerFromDate', $search->registerFromDate) }}">
                    </td>
                    <td class="text-center">
                      <span class="text-center">~</span>
                    </td>
                    <td>
                      <input type="text" class="form-control app-date app-date-picker" name="registerToDate" value="{{ empty($search->registerToDate) ? null : old('registerToDate', $search->registerToDate) }}">
                    </td>
                  </tr>
                  <tr>
                    <td><label for="graduated_from" class="control-label">Graduated From</label></td>
                    <td>
                      <input type="text" class="form-control" name="graduated_from" value="{{ empty($search->graduated_from) ? null : old('graduated_from', $search->graduated_from) }}">
                    </td>
                    <td><label for="graduated_dep" class="control-label">Graduated Dep</label></td>
                    <td>
                      <input type="text" class="form-control" name="graduated_dep" value="{{ empty($search->graduated_dep) ? null : old('graduated_dep', $search->graduated_dep) }}">
                    </td>
                  </tr>
                  <tr>
                    <td><label for="graduated_year" class="control-label">Graduated year</label></td>
                    <td>
                      <input type="text" class="form-control" name="graduated_year" value="{{ empty($search->graduated_year) ? null : old('graduated_year', $search->graduated_year) }}">
                    </td>
                    <td><label for="phone_number" class="control-label">Phone Number</label></td>
                    <td>
                      <input type="text" class="form-control" name="phone_number" value="{{ empty($search->phone_number) ? null : old('phone_number', $search->phone_number) }}">
                    </td>
                  </tr>
                  <tr>    
                    <td><label for="email" class="control-label">Email</label></td>
                    <td>
                      <input type="text" class="form-control" name="email" value="{{ empty($search->email) ? null : old('email', $search->email) }}">
                    </td>
                    <td><label for="status" class="control-label">Status</label></td>
                    <td>
                      <select name="status" class="form-control">
                        <option value="" selected>Please select</option>
                        <option value="0" {{ empty($search->status) ? '' : old('status', $search->status) == 0 ? 'selected' : '' }}>Active</option>
                        <option value="1" {{ empty($search->status) ? '' : old('status', $search->status) == 1 ? 'selected' : '' }}>Deactive</option>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="6" class="text-center">
                      <button type="button" class="btn btn-secondary" onclick="location.href='{{ route('CommunityUser#showList') }}'">Clear</button>
                      <button type="submit" class="btn btn-danger">Search</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </form>
        @if(isset($result) && $result->total() >= 1)
          <div class="row text-right">
            <div class="col-lg-12">
              <a href="{{ route('CommunityUser#communityUserDownloadCsv') }}" class="btn btn-success m-2">CSV Download</a>
            </div>
          </div>
        @endif

        <div class="table-top__wrapper">
          <p class="table-top__txt">Search results: All {{ empty($search) ? '0' : $result->total() }} cases</p>
        </div>
        @if(session()->has('USER_SEARCH'))
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
              <thead>
                <tr>
                  <th>User Number</th>
                  <th>User Name</th>
                  <th>Gender</th>
                  <th>Date of birth</th>
                  <th>Registered Date</th>
                  <th>NRC Number</th>
                  <th>Graduated From</th>
                  <th>Graduated Dep</th>
                  <th>Graduated Year</th>
                  <th>Address</th>
                  <th>Phone Number</th>
                  <th>Email</th>
                  <th>Status</th>
                 
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @forelse($result as $communityuser)
                  <tr>
                    <td>{{ $communityuser->user_number }}</td>
                    <td>{{ $communityuser->user_name }}</td>
                    <td>
                      @if($communityuser->gender == 'M')
                        Male
                      @else
                        Female
                      @endif
                    </td>
                    <td>{{ $communityuser->date_of_birth }}</td>
                    <td>{{ $communityuser->created_at }}</td>
                    <td>{{ $communityuser->nrc_number }}</td>
                    <td>{{ $communityuser->graduated_from }}</td>
                    <td>{{ $communityuser->graduated_dep }}</td>
                    <td>{{ $communityuser->graduated_year }}</td>
                    <td>{{ $communityuser->address }}</td>
                    <td>{{ $communityuser->phone_number }}</td>
                    <td>{{ $communityuser->email }}</td>
                    <td>
                      @if($communityuser->status == 0) 
                        Active
                      @else
                        Deactive
                      @endif
                    </td>
                    
                    <td>
                      <a href="{{ route('CommunityUser#communityUserEdit', $communityuser->user_number) }}" class="btn btn-danger">Edit</a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="12" class="text-center">{{ config('constants.NO_DATA_FOUND') }}</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          {{ $result->render() }}
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
  <script src="{{ asset('/js/jquery.datetimepicker.js') }}"></script>
  <script src="{{ asset('/js/communityuser/list.js') }}"></script>
@endsection

@extends('layouts.app')
@section('content-header', __('Introduce User List'))
@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-striped">
            <thead>
              <tr>
                <th>Connect Person Name</th>
                <th>Email</th>
                <th>Operator Type</th>
                <th>Phone Number</th>
                <th>Number of People</th>
                <th class="table-column"></th>
              </tr>
            </thead>
            <tbody>
              @foreach($data as $result)
                <tr>
                  <td>{{ $result->user_name }}</td>
                  <td>{{ $result->email }}</td>
                  <td>{{ $result->career }}</td>
                  <td>{{ $result->phone_number }}</td>
                  <td>{{ $result->count_number }}</td>
                  <td><a role="button" class="btn btn-danger btn-sm" href="{{ route('BillPayController#payperson', $result->login_id) }}">View</a></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer clearfix">
        <ul class="pagination m-0 float-right">
          <li class="page-item">{{ $data->links() }}</li>
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection




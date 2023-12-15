@extends('layouts.app')

@section('content')
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <div class="container">
    <x-dashboard />
      <div id="app">
              <welcome/>
      </div>
  </div>
@endsection

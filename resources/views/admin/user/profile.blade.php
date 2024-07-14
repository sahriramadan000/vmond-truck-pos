@extends('admin.layouts.app')

@push('style-link')
<link href="{{ asset('src/assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('src/assets/css/light/components/tabs.css') }}" rel="stylesheet" type="text/css">

<link href="{{ asset('src/assets/css/dark/components/modal.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('src/assets/css/dark/components/tabs.css') }}" rel="stylesheet" type="text/css">

<link href="{{ asset('src/assets/css/light/dashboard/dash_1.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('src/assets/css/dark/dashboard/dash_1.css') }}" rel="stylesheet" type="text/css" />

<style>
    .btn-custom {
        border-radius: 15px !important;
    }
</style>
@endpush

@section('breadcumbs')
<nav class="breadcrumb-style-one" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">{{ $page_title }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="col-lg-6 col-sm-12  layout-spacing">
    <div class="widget widget-card-one">
        <div class="widget-content">

            <div class="media">
                <div class="w-img">
                    <img src="{{ asset('images/users/'.($user->avatar ?? 'default.png')) }}" alt="avatar">
                </div>
                <div class="media-body d-flex justify-content-between align-items-center ms-2">
                    <div class="">
                        <h5 class="fw-bold mb-0 pb-0">{{ Auth::user()->fullname }}</h5>
                        <p class="meta-date-time">{{ str_replace(['-', '_'], ' ',Auth::user()->getRoleNames()[0]) }}</p>

                        <ul class="list-group mt-3">
                            <li class="list-group-item bg-transparent border-0 p-0">
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="flex-1">
                                        <svg class="text-white" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mg-b-0 text-white opacity-75">{{ Auth::user()->phone ?? 'Nomer telephone belum di cantumkan' }}</p>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item bg-transparent border-0 p-0">
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="flex-1">
                                        <svg class="text-white" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mg-b-0 text-white opacity-75">{{ Auth::user()->email ?? 'Email belum di cantumkan' }}</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-sm-12 layout-spacing bg-transparent px-4 pb-0">
                @include('admin.components.alert')
                <form class="mt-0" action="{{ route('users.update.profile') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="simple-pill">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-profile-icon-tab" data-bs-toggle="pill" data-bs-target="#pills-profile-icon" type="button" role="tab" aria-controls="pills-profile-icon" aria-selected="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    Profile
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-change-password-icon-tab" data-bs-toggle="pill" data-bs-target="#pills-change-password-icon" type="button" role="tab" aria-controls="pills-change-password-icon" aria-selected="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                    Change Password
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-profile-icon" role="tabpanel" aria-labelledby="pills-profile-icon-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group mb-3 text-left">
                                                    <label for="fullname">Fullname <span class="text-danger">*</span></label>
                                                    <input type="text" name="fullname" class="form-control form-control-sm" placeholder="Ex:franky" aria-label="fullname" id="fullname" value="{{ $user->fullname ?? old('fullname') }}">
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group mb-3 text-left">
                                                    <label for="username">Username <span class="text-danger">*</span></label>
                                                    <input type="text" name="username" class="form-control form-control-sm" placeholder="Ex:franky" aria-label="username" id="username" value="{{ $user->username ?? old('username') }}">
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="email">Email <span class="text-danger">*</span></label>
                                                    <input type="email" name="email" class="form-control form-control-sm" placeholder="Ex:example@gmail.com" aria-label="email" value="{{ $user->email ?? old('email') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group mb-3 text-left">
                                                    <label for="avatar">Avatar</label>
                                                    <img src="{{ $user->avatar ? asset('images/users/'.$user->avatar) : 'https://ui-avatars.com/api/?name=No+Image' }}" alt="" class="d-block mx-auto p-2 bg-black mb-2" style="width: 73px !important; border-radius:50%;">
                                                    <input type="file" class="form-control file-upload-input" name="avatar" aria-label="avatar" id="avatar">
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group mb-3">
                                                    <label for="phone">Phone</label>
                                                    <input type="text" name="phone" id="phone" class="form-control form-control-sm" placeholder="Ex:089999999999" aria-label="phone" value="{{ $user->phone ?? old('phone') }}" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <textarea name="address" id="address" class="form-control" cols="30" rows="5">{{ $user->address ?? old('address') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-change-password-icon" role="tabpanel" aria-labelledby="pills-change-password-icon-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label for="old_password">Old Password</label>
                                            <input type="password" name="old_password" id="old_password" class="form-control form-control-sm" placeholder="Ex:********" aria-label="old_password">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label for="new_password">New Password</label>
                                            <input type="password" name="new_password" id="new_password" class="form-control form-control-sm" placeholder="Ex:********" aria-label="new_password">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary w-100 py-2">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modalContainer"></div>

@endsection

@push('js')
<script>
    $(document).ready(function() {

    });
</script>
@endpush

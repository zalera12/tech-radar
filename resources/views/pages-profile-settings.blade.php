@extends('layouts.master')
@section('title')
    @lang('translation.settings')
@endsection
@section('content')
    <form action="/auth-profile/edit/{{ $user->id }}" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="old_photo" value="{{ $user->photo }}">
        <input type="hidden" name="id" value="{{ $user->id }}">
        @csrf
        <div class="position-relative mx-n4 mt-n4">
            <div class="profile-wid-bg profile-setting-img">
                <img src="{{ URL::asset('build/images/profile-bg.jpg') }}" class="profile-wid-img" alt="">
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-3">
                <div class="card mt-n5">
                    <div class="card-body p-4">
                        <div class="text-center">
                            <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                                <img src="{{ asset($user->photo ? 'storage/'.$user->photo : '/build/images/users/user-dummy-img.jpg') }}"
                                    class="rounded-circle avatar-xl img-thumbnail user-profile-image"
                                    alt="user-profile-image">
                                <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                    <input id="profile-img-file-input" type="file" class="profile-img-file-input"
                                        name="photo">
                                    <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                        <span class="avatar-title rounded-circle bg-light text-body">
                                            <i class="ri-camera-fill"></i>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <h5 class="fs-16 mb-1">{{ $user->name }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
            <div class="col-xxl-9">
                <div class="card mt-xxl-n5">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-4">
                                    <label for="name" class="form-label text-secondary mb-1">Nama Lengkap
                                        <span style="color:var(--error)">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" placeholder="Nama Lengkap" name="name"
                                        value="{{ old('name', $user->name) }}">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="submit" class="btn btn-secondary">Update</button>
                                    <a href="/auth-profile" type="button" class="btn btn-soft-danger">Cancel</a>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </form>
@endsection
@section('script')
    <script src="{{ URL::asset('build/js/pages/profile-setting.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        // Script JavaScript untuk menggabungkan input file dengan form saat submit
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah submit default

            let formData = new FormData(this); // Ambil data dari form
            let fileInput = document.getElementById('profile-foreground-img-file-input');
            if (fileInput.files.length > 0) {
                formData.append('photo', fileInput.files[0]); // Tambahkan file input dari bagian atas
            }

            // Kirim data form dengan Fetch API atau AJAX
            fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                }).then(response => response.json())
                .then(data => {
                    // Handle response dari server
                    console.log(data);
                }).catch(error => {
                    console.error('Error:', error);
                });
        });
    </script>
@endsection

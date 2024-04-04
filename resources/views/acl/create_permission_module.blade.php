@extends('layout.app')
@section('styles')
<style>
    .image-preview {
        position: relative;
        display: inline-block;
        vertical-align: bottom;
        top: 5px;
        margin-bottom: 5px;
    }

    .color-box {
        width: 107px;
        height: 107px;
        position: relative;
        top: 0;
        left: 0;
        border: 1px solid lightgray;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .color-box img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .save-button {
        overflow: hidden;
        /* Hide overflowing content */
        transition: width 0.5s ease;
        /* Add transition for width change */
    }

    .save-button.d-flex {
        width: auto !important;
        /* Remove width constraint when loader is visible */
    }

    .loader {
        border: 2px solid #f3f3f3;
        /* Light grey */
        border-top: 2px solid #3498db;
        /* Blue */
        border-radius: 50%;
        width: 15px;
        height: 15px;
        animation: spin 2s linear infinite;
        margin: 3px 0px 0px 4px;
    }

    #selected-country-flag {
        width: 18px;
        height: 18px;
    }

    #country-dropdown {
        max-width: 200px !important;
        max-height: 200px;
        overflow-y: scroll;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
    /* Add your custom CSS styles here */
    .toast {
        position: fixed;
        top: 80px;
        right: 0px;
        color: #fff;
        padding: 10px;
        display: none;
        border-radius: 5px;
    }
</style>
@endsection

@section('content')
<main class="content">
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3">Create Permission Module</h1>
        <div class="card">
            <div class="card-body">
                <form action="{{route('permission_modules.store')}}" method="POST" id="permission-module-form" enctype="multipart/form-data">
                    @csrf
                    @include('acl.permission_module_form')
                </form>
            </div>
        </div>
        <div id="toast" class="toast">
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#image').on('change', function() {
            var input = this;
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#previewImage').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        });
        $(document).on('submit', '#permission-module-form', function(e) {
            e.preventDefault();
            $('.loader').removeClass('d-none');
            var form = $(this);
            var formData = new FormData(form.get(0));
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('.loader').addClass('d-none');
                    if (response.status == true) {
                        $('#toast').html(response.message);
                        $('#toast').css('background-color', 'green');
                        $('#toast').fadeIn().delay(3000).fadeOut();
                    } else {
                        $('#toast').html(response.message);
                        $('#toast').css('background-color', 'red');
                        $('#toast').fadeIn().delay(3000).fadeOut();
                    }
                },
                error: function(xhr, status, error) {
                    $('.loader').addClass('d-none');
                    $('#toast').html("Something went wrong");
                    $('#toast').css('background-color', 'red');
                    $('#toast').fadeIn().delay(3000).fadeOut();
                }
            });
        });
    });
</script>
@endsection
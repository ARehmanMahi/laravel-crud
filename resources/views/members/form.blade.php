<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <style>
        i.required {
            color: tomato;
        }

        span.notice {
            font-size: 0.5em;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="mt-3">
        <a href="{{ route('members.index') }}">Listing</a> > <i class="text-gray-200">Member Form</i>
    </div>

    <div class="d-flex justify-content-center">
        <form method="post" action="{{ $formAction }}" enctype="multipart/form-data" id="myform">
            @csrf
            @if($member->id)
                @method('patch')
            @else
                @method('post')
            @endif

            <input type="hidden" name="id" id="id" value="{{ $member->id }}"/>

            <div class="row">
                <div class="col">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(Session('success'))
                        <div class="alert alert-success success">
                            {{ Session('success') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <h2 class="mb-4">Create <span class="notice">(All fields with <i class="required">*</i> are required.)</span>
                    </h2>
                </div>
            </div>

            <div class="row">
                <div class="col-auto">
                    <label for="first_name"><i class="required">*</i> First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name"
                           value="{{ old('first_name', $member->first_name) }}" placeholder="First Name" required>
                </div>
                <div class="col-auto">
                    <label for="last_name"><i class="required">*</i> Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name"
                           value="{{ old('last_name', $member->last_name) }}" placeholder="Last Name" required>
                </div>
                <div class="col-auto">
                    <label for="email"><i class="required">*</i> Email</label>
                    <input type="text" class="form-control" id="email" name="email"
                           value="{{ old('email', $member->email) }}" placeholder="email@example.com" required>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-auto">
                    <label for="info">Info</label>
                    <textarea cols="47" class="form-control" id="info" name="info"
                              placeholder="Description goes here">{{ old('info', $member->info) }}</textarea>
                </div>
                <div class="col-auto">
                    <label for="image">Profile Pic</label>
                    <input type="file" class="form-control" name="member_image" id="member_image">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary mb-3" id="add">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("form").submit(function (event) {
            event.preventDefault();

            let myform = document.getElementById("myform");
            let formData = new FormData(myform);
            let url = $(myform).prop('action');

            axios.post(url, formData)
                .then(function (response) {
                    alert(response.data);
                    if (!id) {
                        $("form")[0].reset();
                    }
                })
                .catch(function (error) {
                    alert(error.responseJSON)
                })
                .then(function () {
                    // always executed
                });
        });
    });
</script>

</body>

</html>
</x-app-layout>

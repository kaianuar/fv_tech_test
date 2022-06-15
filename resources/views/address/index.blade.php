<html>
    <head>
        <title>Customer Address Form</title>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/js/bootstrap.min.js" integrity="sha512-EKWWs1ZcA2ZY9lbLISPz8aGR2+L7JVYqBAYTq5AXgBkSjRSuQEGqWx8R1zAX16KdXPaCjOCaKE8MCpU0wcHlHA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            $(document).ready(function() {
                $('#addressSubmit').submit(function (event) {
                    event.preventDefault();

                    $.post('{{ route('verifyAddress') }}', $( "#addressSubmit" ).serialize())
                        .done(function (response) {
                            let formattedaddress = '';

                            $.each(response, function (key, value) {
                                console.log(key + value);
                                formattedaddress += value + '<br />';
                            });

                            $('#formSuccess')
                                .removeClass('d-none')
                                .addClass('text-success');
                            $('#successMessage').html(formattedaddress);
                        })
                        .fail(function (response) {
                            $('#formErrors')
                                .removeClass('d-none')
                                .addClass('text-danger');

                            $('#errorMessage').html(response.responseJSON.message);
                        });
                    });
            });
        </script>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" integrity="sha512-Ez0cGzNzHR1tYAv56860NLspgUGuQw16GiOOp/I2LuTmpSK9xDXlgJz3XN4cnpXWDmkNBKXR/VDMTCnAaEooxA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    </head>
    <body>
        <div class="container-fluid">
            <div class="col-md-7 mx-auto mt-2">
                <div class="card d-none" id="formSuccess">
                    <div class="card-body">
                        <h5 class="card-title">Address verified</h5>
                        <div class="card-text">
                            You're address will be saved as:
                            <br />
                            <span id="successMessage"></span>
                        </div>
                    </div>
                </div>
                <div class="card d-none" id="formErrors">
                    <div class="card-body">
                        <h5 class="card-title">There was an error verifying your address</h5>
                        <div class="card-text">
                            <span class="text-success" id="errorMessage"></span>
                        </div>
                    </div>
                </div>
                <h3>Add New Address</h3>
                <form method="post" action="{{ route('verifyAddress') }}" id="addressSubmit">
                    @csrf
                    <div class="form-group row">
                        <label for="address1" class="col-sm-2 col-form-label">Address Line 1</label>
                        <div class="col-sm-10 mb-2">
                            <input type="text" class="form-control" id="address1" name="address1" placeholder="Enter the first line of your address" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="address2" class="col-sm-2 col-form-label">Address Line 2</label>
                        <div class="col-sm-10 mb-2">
                            <input type="text" class="form-control" id="address2" name="address2" placeholder="Enter the second line of your address" />
                        </div>

                    </div>
                    <div class="form-group row">
                        <label for="address3" class="col-sm-2 col-form-label">Address Line 3</label>
                        <div class="col-sm-10 mb-2">
                            <input type="text" class="form-control" id="address3" name="address3" placeholder="Enter the third line of your address" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-10 center-screen">
            <div class="card animated fadeIn w-100 p-3">
                <div class="card-body">
                    <h4>Sign Up</h4>
                    <hr />
                    <div class="container-fluid m-0 p-0">
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <label>Email Address</label>
                                <input id="email" placeholder="User Email" class="form-control" type="email" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Name</label>
                                <input id="name" placeholder="Full Name" class="form-control" type="text" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Phone Number</label>
                                <input id="phone" placeholder="Phone Number" class="form-control" type="text" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Password</label>
                                <input id="password" placeholder="User Password" class="form-control" type="password" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Confirm Password</label>
                                <input id="passwordConfirmation" placeholder="Retype Password" class="form-control" type="password" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Address</label>
                                <textarea id="address" placeholder="Your Address" class="form-control"></textarea>
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Avatar</label>
                                <input id="avatar" placeholder="User Password" class="form-control" type="file" />
                            </div>
                            <input type="hidden" id="role" value="customer">
                        </div>
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <button onclick="onRegistration()" class="btn mt-3 w-100  bg-gradient-primary">Complete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    async function onRegistration() {
        let email = document.getElementById('email').value;
        let fullName = document.getElementById('name').value;
        let phone = document.getElementById('phone').value;
        let role = document.getElementById('role').value;
        let password = document.getElementById('password').value;
        let passwordConfirmation = document.getElementById('passwordConfirmation').value;
        let address = document.getElementById('address').value;
        let avatarFile = document.getElementById('avatar').files[0];


        // --- VALIDATIONS ---
        if (email.length === 0) return errorToast('Email is required');
        if (fullName.length === 0) return errorToast('Name is required');
        if (phone.length === 0) return errorToast('Phone Number is required');
        if (password.length === 0) return errorToast('Password is required');
        if (passwordConfirmation.length === 0) return errorToast('Confirm password is required');
        if (password !== passwordConfirmation) return errorToast('Password mismatch');
        if (address.length === 0) return errorToast('Address is required');
        if (role.length === 0) return errorToast('Suspicious activity detected!');
        if (!avatarFile) return errorToast('Avatar is required');


        // making object to gather all data of the registration form
        let formData = new FormData();
        formData.append('email', email);
        formData.append('name', fullName);
        formData.append('phone', phone);
        formData.append('password', password);
        formData.append('password_confirmation', passwordConfirmation);
        formData.append('address', address);
        formData.append('role', role);
        formData.append('avatar', avatarFile); // FIXED


        showLoader();

        try {
            let res = await axios.post("/backend/register", formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            hideLoader();

            if (res.status === 201 && res.data.status === true) {
                successToast(res.data.message);
                setTimeout(() => {
                    window.location.href = "/login";
                }, 2000);
            }

        } catch (err) {
            hideLoader();

            // Backend validation errors (Laravel 422)
            if (err.response && err.response.status === 422) {
                const allErrors = err.response.data.errors;

                Object.values(allErrors).forEach(errorArray => {
                    if (Array.isArray(errorArray)) {
                        errorArray.forEach(msg => errorToast(msg));
                    } else {
                        errorToast(errorArray);
                    }
                });

            } else if (err.response && err.response.status === 500) {
                errorToast("Server Error! Please try again later");
            } else {
                errorToast("Something went wrong");
            }
        }
    }
</script>
@endpush
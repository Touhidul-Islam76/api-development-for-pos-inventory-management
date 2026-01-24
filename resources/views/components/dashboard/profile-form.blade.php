<div class="container">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card animated fadeIn w-100 p-3">
                <div class="card-body">
                    <h4>User Profile</h4>
                    <hr />
                    <div class="container-fluid m-0 p-0">
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <label>Role</label>
                                <input id="role" readonly class="form-control" type="text" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Email Address</label>
                                <input id="email" placeholder="User Email" class="form-control" readonly type="email" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Full Name</label>
                                <input id="name" placeholder="Full Name" class="form-control" type="text" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Phone Number</label>
                                <input id="phone" placeholder="Phone Number" class="form-control" type="text" />
                            </div>
                            <div class="col-md-4 p-2">
                                <label>Address</label>
                                <textarea id="address" placeholder="Address" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <button onclick="onUpdate()" class="btn mt-3 w-100  bg-gradient-primary">Update</button>
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
    getProfile()
    async function getProfile() {
        showLoader();
        let res = await axios.get("/backend/profile")
        hideLoader();

        if (res.status === 200) {
            // console.log(res.data.data);
            let data = res.data.data;
            document.getElementById('role').value = data.role;
            document.getElementById('email').value = data.email;
            document.getElementById('name').value = data.name;
            document.getElementById('phone').value = data.phone;
            document.getElementById('address').value = data.address;
        } else {
            errorToast(res.data['message'])
        }
    }


    async function onUpdate() {
        let name = document.getElementById('name').value;
        let phone = document.getElementById('phone').value;
        let address = document.getElementById('address').value;

        if (name.length === 0) {
            errorToast('First Name is required')
        } else if (phone.length === 0) {
            errorToast('Phone is required')
        } else if (address.length === 0) {
            errorToast('Address is required')
        }

        showLoader();
        try {
            // let token = localStorage.getItem('jwtToken');
            let res = await axios.post(
                "/backend/profile-update", {
                    name: name,
                    phone: phone,
                    address: address
                }, {
                    withCredentials: true
                }
            );
            hideLoader();
            if (res.status === 200 && res.data.status === true) {
                successToast(res.data.message);

                setTimeout(() => {
                    window.location.href = '/profile';
                }, 1000)
            }


        } catch (err) {
            hideLoader();

            // Backend validation errors (Laravel 422)
            if (err.response && err.response.status === 422) {
                console.log("ERR RESPONSE ---> ", err.response);
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
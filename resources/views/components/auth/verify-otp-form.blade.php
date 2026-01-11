<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90  p-4">
                <div class="card-body">
                    <h4>ENTER OTP CODE</h4>
                    <br />
                    <label>6 Digit Code Here</label>
                    <input id="otp" placeholder="Code" class="form-control" type="text" />
                    <br />
                    <button onclick="verifyOtp()" class="btn w-100 float-end bg-gradient-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    async function verifyOtp() {
        let otp = document.getElementById('otp').value;

        if (otp.length !== 6) {
            errorToast('OTP must be 6 digits long');
        } else {
            showLoader();
            try {
                let res = await axios.post('/backend/verifyOtp', {
                    otp: otp,
                    email: sessionStorage.getItem('email')
                });

                hideLoader();

                if (res.status === 200 && res.data.status === true) {
                    successToast(res.data.message);
                    sessionStorage.clear();
                    setTimeout(() => {
                        window.location.href = '/reset-password';
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
                    errorToast(err.response.data.message);
                } else {
                    errorToast("Please check your Internet connection");
                }
            }
        }
    }
</script>
@endpush
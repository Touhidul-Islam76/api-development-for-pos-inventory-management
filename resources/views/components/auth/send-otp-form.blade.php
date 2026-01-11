<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6 center-screen">
            <div class="card animated fadeIn w-90  p-4">
                <div class="card-body">
                    <h4>EMAIL ADDRESS</h4>
                    <br />
                    <label>Your email address</label>
                    <input id="email" placeholder="User Email" class="form-control" type="email" />
                    <br />
                    <button onclick="verifyEmail()" class="btn w-100 float-end bg-gradient-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    async function verifyEmail() {
    let email = document.getElementById('email').value;

    if (!email) return errorToast('Please enter your email');

    showLoader();

    try {
        let res = await axios.post('/backend/resetPasswordReq', { email });
        hideLoader();

        console.log("Response:", res);

        if (res.status === 200 && res.data.status === true) {
            successToast(res.data.message);
            sessionStorage.setItem('email', email);
            window.location.href = '/verify-otp';
        }

    } catch (err) {
    hideLoader();

    if (err.response) {

        // 422 validation error
        if (err.response.status === 422) {
            const allErrors = err.response.data.errors || {};

            if (Object.keys(allErrors).length > 0) {
                Object.values(allErrors).forEach(errorArray => {
                    if (Array.isArray(errorArray)) {
                        errorArray.forEach(msg => errorToast(msg));
                    } else {
                        errorToast(errorArray);
                    }
                });
            } else {
                errorToast("Validation failed");
            }

        } else if (err.response.status === 500) {
            errorToast(err.response.data.message);
        } else if (err.response.status === 401) {
            errorToast(err.response.data.message || 'Unauthorized access');
        }

    } else {
        errorToast("Network error");
    }
}

}

</script>
@endpush
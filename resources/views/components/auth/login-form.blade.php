<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 animated fadeIn col-lg-6 center-screen">
            <div class="card w-90  p-4">
                <div class="card-body">
                    <h4>SIGN IN</h4>
                    <br />
                    <input id="email" placeholder="User Email" class="form-control" type="email" />
                    <br />
                    <input id="password" placeholder="User Password" class="form-control" type="password" />
                    <br />
                    <button onclick="submitLogin()" class="btn w-100 bg-gradient-primary">Next</button>
                    <hr />
                    <div class="float-end mt-3">
                        <span>
                            <a class="text-center ms-3 h6" href="{{ route('register') }}">Sign Up </a>
                            <span class="ms-1">|</span>
                            <a class="text-center ms-3 h6" href="{{ route('forgot-password.send-otp') }}">Forget Password</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    async function submitLogin() {
        // Get input values
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;

        // Simple validation
        if (!email) return errorToast("Email is required");
        if (!password) return errorToast("Password is required");

        try {
            showLoader();

            // Axios POST request
            const res = await axios.post("/backend/login", {
                email:email,
                password:password
            });

            hideLoader();

            if (res.data.success) {
                successToast(res.data.message);

                // Redirect after short delay
                setTimeout(() => {
                    window.location.href = "/dashboard";
                }, 1500);
            } else {
                // Handle unlikely case if success=false
                errorToast(res.data.message || "Login failed");
            }

        } catch (err) {
            hideLoader();

            // Handle HTTP errors
            if (err.response) { //err.response means server responded with a status code outside like 401,422,500 etc
                const status = err.response.status;

                if (status === 401) {
                    // Invalid credentials
                    errorToast(err.response.data.message || "Invalid credentials");
                } else if (status === 422) {
                    // Validation errors from LoginReq
                    const errors = err.response.data.errors || {};
                    Object.values(errors).forEach(msgArray => {
                        if (Array.isArray(msgArray)) msgArray.forEach(msg => errorToast(msg));
                        else errorToast(msgArray);
                    });
                } else {
                    // Other server errors
                    errorToast(err.response.data.message || "Server error. Please try again later.");
                }
            } else {
                // Network or unexpected errors
                errorToast("Network error. Please check your connection.");
            }
        }
    }
</script>
@endpush
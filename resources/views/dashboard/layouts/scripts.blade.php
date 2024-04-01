<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="{{ URL::asset('assets/libs/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- App js -->
<script src="{{ URL::asset('assets/js/app.js') }}"></script>
<script>
    function togglePasswordVisibility() {
        let passwordInput = document.getElementById('password');
        let eyeIcon = document.getElementById('eyeIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('bi-eye');
            eyeIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('bi-eye-slash');
            eyeIcon.classList.add('bi-eye');
        }
    }
    function toggleConfirmPasswordVisibility() {
        let passwordConfirmInput = document.getElementById('password_confirmation');
        let eyeConfirmIcon = document.getElementById('eyeConfirmIcon');

        if (passwordConfirmInput.type === 'password') {
            passwordConfirmInput.type = 'text';
            eyeConfirmIcon.classList.remove('bi-eye');
            eyeConfirmIcon.classList.add('bi-eye-slash');
        } else {
            passwordConfirmInput.type = 'password';
            eyeConfirmIcon.classList.remove('bi-eye-slash');
            eyeConfirmIcon.classList.add('bi-eye');
        }
    }
    function toggleOldPasswordVisibility() {
        let oldPasswordInput = document.getElementById('old_password');
        let eyeOldIcon = document.getElementById('eyeOldIcon');

        if (oldPasswordInput.type === 'password') {
            oldPasswordInput.type = 'text';
            eyeOldIcon.classList.remove('bi-eye');
            eyeOldIcon.classList.add('bi-eye-slash');
        } else {
            oldPasswordInput.type = 'password';
            eyeOldIcon.classList.remove('bi-eye-slash');
            eyeOldIcon.classList.add('bi-eye');
        }
    }

    function setDiscountValueAttributes() {
        const discountTypeInput = document.getElementById('discount_type');
        const discountAmountInput = document.getElementById('discount_amount');

        if (discountTypeInput.value === '1') {
            discountAmountInput.max = '100';
        } else {
            discountAmountInput.max = '';
        }
    }

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    function hideModal() {
        $('.detailsModal').modal('hide');
    }
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckboxes = document.querySelectorAll('.select-all-checkbox');
        selectAllCheckboxes.forEach(function (checkbox) {
            checkbox.addEventListener('click', function () {
                const permissionsCheckboxes = this.closest('.collapse').querySelectorAll('.permission-checkbox');
                permissionsCheckboxes.forEach(function (permissionCheckbox) {
                    permissionCheckbox.checked = checkbox.checked;
                });
            });
        });

        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
        permissionCheckboxes.forEach(function (permissionCheckbox) {
            permissionCheckbox.addEventListener('click', function () {
                const allPermissionCheckboxes = this.closest('.collapse').querySelectorAll('.permission-checkbox');
                const selectAllCheckbox = this.closest('.collapse').querySelector('.select-all-checkbox');
                let allChecked = true;
                allPermissionCheckboxes.forEach(function (checkbox) {
                    if (!checkbox.checked) {
                        allChecked = false;
                    }
                });
                selectAllCheckbox.checked = allChecked;
            });
        });
    });
    $(document).ready(function() {
        $('.form-select').select2({
            placeholder: "{{__('messages.select')}}"
        });
    });
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
</script>
@stack('scripts')

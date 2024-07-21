// ============================== My Scripts For (User) ==============================
/*

1. Show / Hidden Password (Class .password-toggle)
2. copy URL
3. Jquery Validation Form (Register)
4. Jquery Validation Form (Login)
5. Confirm Logout
*/

// ============================== 1. Show / Hidden Password (Class .password-toggle) ==============================
document.addEventListener('DOMContentLoaded', function () {
    const passwordToggles = document.querySelectorAll('.password-toggle');

    passwordToggles.forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            const passwordField = this.previousElementSibling;
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye-slash', type === 'password');
            icon.classList.toggle('fa-eye', type !== 'password');
        });
    });
});

// ============================== 2. copy URL ==============================
function copyURL(event) {
    event.preventDefault(); // ป้องกันไม่ให้หน้ารีเฟรช
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(function() {
        Swal.fire({
            icon: 'success',
            title: 'สำเร็จ',
            text: 'คัดลอก URL สินค้าเรียบร้อยแล้ว',
            confirmButtonText: 'ตกลง'
        });
    }, function(err) {
        Swal.fire({
            icon: 'error',
            title: 'ข้อผิดพลาด',
            text: 'ไม่สามารถคัดลอก URL: ' + err,
            confirmButtonText: 'ตกลง'
        });
    });
}

// ============================== 3. Jquery Validation Form (Register) ==============================
$(document).ready(function () {
    $("#formRegister").validate({
        rules: {
            username_email: {
                required: true,
            },
            password: {
                required: true,
                nowhitespace: true,
                pattern: /^[^\u0E00-\u0E7F]+$/,
                minlength: 8,
                maxlength: 255,
            },
        },
        messages: {
            username_email: {
                required: "กรุณาระบุ ชื่อผู้ใช้งาน หรือ อีเมล",
            },
            password: {
                required: "กรุณาระบุ รหัสผ่าน",
                nowhitespace: "ห้ามมีเว้นวรรค",
                pattern: "ห้ามมีภาษาไทย",
                minlength: "ต้องมี อย่างน้อย 8 ตัวอักษร",
                maxlength: "ต้องไม่เกิน 255 ตัวอักษร",
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.mb-3').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        // ปรับแต่งสีของข้อความ error
        errorClass: 'text-danger'
    });
});

// ============================== 4. Jquery Validation Form (Login) ==============================
$(document).ready(function () {
    $("#formLogin").validate({
        rules: {
            username_email: {
                required: true,
                maxlength: 50,
            },
            lname: {
                required: true,
                pattern: /^[a-zA-Zก-๙\s]+$/,
                maxlength: 50,
            },
            username: {
                required: true,
                pattern: /^[a-zA-Z][a-zA-Z0-9_]*$/,
                minlength: 6,
                maxlength: 50,

            },
            password: {
                required: true,
                nowhitespace: true,
                pattern: /^[^\u0E00-\u0E7F]+$/,
                minlength: 8,
                maxlength: 255,
            },
            confirmPassword: {
                required: true,
                equalTo: "[name='password']"
            },
            email: {
                required: true,
                email: true,
                minlength: 10,
                maxlength: 100,
            },
            newProfile: {
                accept: "image/png,image/jpg,image/jpeg",
            },
            username_email: {
                required: true,
            }
        },
        messages: {
            fname: {
                required: "กรุณาระบุ ชื่อ",
                pattern: "ต้องเป็นตัวอักษรภาษาไทย หรือ ภาษาอังกฤษ เท่านั้น",
                minlength: 50,
            },
            lname: {
                required: "กรุณาระบุ นามสกุล",
                pattern: "ต้องเป็นตัวอักษรภาษาไทย หรือ ภาษาอังกฤษ เท่านั้น",
                minlength: 50,
            },
            username: {
                required: "กรุณาระบุ ชื่อผู้ใช้งาน",
                pattern: "ต้องเป็น a-z, A-Z, 0-9 และ _ เท่านั้น และตัวแรกต้องเริ่มต้นด้วย a-z, A-Z",
                minlength: "ต้องมี 6 ตัวอักษรขึ้นไป",
                maxlength: "ต้องไม่เกิน 50 ตัวอักษร",
            },
            password: {
                required: "กรุณาระบุ รหัสผ่าน",
                nowhitespace: "ห้ามมีเว้นวรรค",
                pattern: "ห้ามมีภาษาไทย",
                minlength: "ต้องมี อย่างน้อย 8 ตัวอักษร",
                maxlength: "ต้องไม่เกิน 255 ตัวอักษร",
            },
            confirmPassword: {
                required: "กรุณาระบุ รหัสผ่าน อีกครั้ง",
                equalTo: "ยืนยันรหัสผ่าน ไม่ถูกต้อง",
            },
            email: {
                required: "กรุณาระบุ อีเมล",
                email: "รูปแบบอีเมล ไม่ถูกต้อง",
                minlength: "ต้องมี อย่างน้อย 10 ตัวอักษร",
                maxlength: "ต้องไม่เกิน 255 ตัวอักษร",
            },
            newProfile: {
                accept: "ต้องเป็นไฟล์ประเภท .png .jpg หรือ .jpeg เท่านั้น",
            },
            username_email: {
                required: "กรุณาระบุ ชื่อผู้ใช้งาน หรือ อีเมล",
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.mb-3').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        // ปรับแต่งสีของข้อความ error
        errorClass: 'text-danger'
    });
});

// ============================== 5. Confirm Logout ==============================
function confirmLogout(event) {
    event.preventDefault(); // ป้องกันการ redirect ทันที

    Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: "คุณต้องการออกจากระบบหรือไม่?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, ออกจากระบบ!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'logout'; // เปลี่ยนไปยังหน้า logout
        }
    })
}

// ============================== My Scripts ==============================
/*

1. Data Table Default
2. Data Table Export File
3. Show / Hiden Password (Class .password-toggle)
3. Jquery Validation Form (Employee)

*/



// ============================== 1. Data Table Default ==============================
new DataTable('#MyTable', {
    responsive: true
})

// ============================== 2. Data Table Export File ==============================
new DataTable('#MyTableExport', {
    layout: {
        topStart: {
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        },
        responsive: true
    }
});

// ============================== 3. Show / Hiden Password (Class .password-toggle) ==============================
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

// ============================== 4. Jquery Validation Form (Employee) ==============================
$(document).ready(function () {
    $("#formEmployee").validate({
        rules: {
            fname: {
                required: true,
                pattern: /^[a-zA-Zก-๙\s]+$/,
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



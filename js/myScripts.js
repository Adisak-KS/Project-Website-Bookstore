// ============================== My Scripts ==============================
/*

1. Data Table Default
2. Data Table Export File
3. Show / Hiden Password (Class .password-toggle)
3. Jquery Validation Form (Employee)

*/



// ============================== 1. Data Table Default ==============================
$(document).ready(function() {
    $('#MyTable').DataTable({
        responsive: true
    });
});

// ============================== 2. Data Table Export File ==============================
pdfMake.fonts = {
    THSarabun: {
        normal: 'THSarabun.ttf',
        bold: 'THSarabun-Bold.ttf',
        italics: 'THSarabun-Italic.ttf',
        bolditalics: 'THSarabun-BoldItalic.ttf'
    }
};
$(document).ready(function () {
    new DataTable('#MyTableExport', {
        layout: {
            topStart: {
                buttons: [
                    'copy',
                    {
                        extend: 'csv',
                        charset: 'UTF-8',
                        bom: true,
                        fieldSeparator: ',',
                        text: 'CSV',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    'excel',
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        pageSize: 'A4',
                        customize: function (doc) {
                            doc.content[1].layout = {
                                hLineWidth: function (i, node) {
                                    return 1; // เส้นขอบแนวนอน
                                },
                                vLineWidth: function (i, node) {
                                    return 1; // เส้นขอบแนวตั้ง
                                },
                                hLineColor: function (i, node) {
                                    return '#AAA'; // สีขอบแนวนอน
                                },
                                vLineColor: function (i, node) {
                                    return '#AAA'; // สีขอบแนวตั้ง
                                }
                            };

                            doc.defaultStyle = {
                                font: 'THSarabun',
                                fontSize: 16
                            };
                            doc.styles.tableHeader.fontSize = 16;

                            doc.content[1].table.widths = ['*', '*', '*', '*'];

                            const rowCount = doc.content[1].table.body.length;
                            for (let i = 1; i < rowCount; i++) {
                                doc.content[1].table.body[i][0] = { text: doc.content[1].table.body[i][0], alignment: 'center' };
                                doc.content[1].table.body[i][1] = { text: doc.content[1].table.body[i][1], alignment: 'center' };
                                doc.content[1].table.body[i][2] = { text: doc.content[1].table.body[i][2], alignment: 'center' };
                                doc.content[1].table.body[i][3] = { text: doc.content[1].table.body[i][3], alignment: 'center' };
                            }

                            const now = new Date();
                            const dateString = now.toLocaleString('en-US', { timeZone: 'Asia/Bangkok' });
                            doc.content.splice(1, 0, {
                                alignment: 'right',
                                margin: [0, 0, 20, 0],
                                text: 'Exported on : ' + dateString
                            });
                        }
                    },
                    'print'
                ],
            },
        },
        responsive: true
    });
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
    $("#formUser").validate({
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

// ============================== 4. Jquery Validation Form Account(Employee) ==============================
$(document).ready(function () {
    $("#formEmployeeAccount").validate({
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
            newProfile: {
                accept: "image/png,image/jpg,image/jpeg",
            },
            username: {
                pattern: /^[a-zA-Z][a-zA-Z0-9_]*$/,
                minlength: 6,
                notEqualTo: "#old_username",
                maxlength: 50,

            },
            email: {
                email: true,
                minlength: 10,
                notEqualTo: "#old_email",
                maxlength: 100,
            },

            password: {
                required: true,
                nowhitespace: true,
                pattern: /^[^\u0E00-\u0E7F]+$/,
                minlength: 8,
                maxlength: 255,
            },
            newPassword: {
                required: true,
                nowhitespace: true,
                pattern: /^[^\u0E00-\u0E7F]+$/,
                minlength: 8,
                notEqualTo: "#oldPassword",
                maxlength: 255,
            },
            confirmNewPassword: {
                required: true,
                equalTo: "#newPassword",
                maxlength: 255,
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
                pattern: "ต้องเป็น a-z, A-Z, 0-9 และ _ เท่านั้น และตัวแรกต้องเริ่มต้นด้วย a-z, A-Z",
                minlength: "ต้องมี 6 ตัวอักษรขึ้นไป",
                notEqualTo: "ชื่อผู้ใช้เดิม กรุณาตั้งชื่อผู้ใช้ใหม่",
                maxlength: "ต้องไม่เกิน 50 ตัวอักษร",
            },
            password: {
                required: "กรุณาระบุ รหัสผ่าน",
                nowhitespace: "ห้ามมีเว้นวรรค",
                pattern: "ห้ามมีภาษาไทย",
                minlength: "ต้องมี อย่างน้อย 8 ตัวอักษร",
                maxlength: "ต้องไม่เกิน 255 ตัวอักษร",
            },
            newPassword: {
                required: "กรุณาระบุ รหัสผ่าน",
                nowhitespace: "ห้ามมีเว้นวรรค",
                pattern: "ห้ามมีภาษาไทย",
                minlength: "ต้องมี อย่างน้อย 8 ตัวอักษร",
                notEqualTo: "กรุณาตั้งรหัสผ่านใหม่",
                maxlength: "ต้องไม่เกิน 255 ตัวอักษร",
            },
            confirmNewPassword: {
                required: "กรุณาระบุ รหัสผ่านใหม่ อีกครั้ง",
                equalTo: "ยืนยันรหัสผ่านใหม่ ไม่ถูกต้อง",
            },
            email: {
                email: "รูปแบบอีเมล ไม่ถูกต้อง",
                minlength: "ต้องมี อย่างน้อย 10 ตัวอักษร",
                notEqualTo: "อีเมลเดิม กรุณาใช้อีเมลใหม่",
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

// ============================== 4. Jquery Validation Form Account(Employee) ==============================
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

// ============================== 5. Jquery Validation Form (Publishere) ==============================
$(document).ready(function () {
    $("#formPublisher").validate({
        rules: {
            pub_name: {
                required: true,
                maxlength: 100,
            },
            pub_detail: {
                required: true,
                maxlength: 100,
            },
            pub_newImg: {
                accept: "image/png,image/jpg,image/jpeg",
            },
        },
        messages: {
            pub_name: {
                required: "กรุณาระบุ ชื่อสำนักพิมพ์",
                maxlength: "ชื่อสำนักพิมพ์ต้องไม่เกิน 100 ตัวอักษร",
            },
            pub_detail: {
                required: "กรุณาระบุ รายละเอียดสำนักพิมพ์",
                maxlength: "รายละเอียดสำนักพิมพ์ ต้องไม่เกิน 100 ตัวอักษร",
            },
            pub_newImg: {
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

// ============================== 5. Jquery Validation Form (Product Type) ==============================
$(document).ready(function () {
    $("#formProductType").validate({
        rules: {
            pty_name: {
                required: true,
                maxlength: 100,
            },
            pty_detail: {
                required: true,
                maxlength: 100,
            },
            pty_newCover: {
                accept: "image/png,image/jpg,image/jpeg",
            },
        },
        messages: {
            pty_name: {
                required: "กรุณาระบุ ชื่อประเภทสินค้า",
                maxlength: "ชื่อประเภทสินค้าต้องไม่เกิน 100 ตัวอักษร",
            },
            pty_detail: {
                required: "กรุณาระบุ รายละเอียดประเภทสินค้า",
                maxlength: "รายละเอียดประเภทสินค้าต้องไม่เกิน 100 ตัวอักษร",
            },
            pty_newCover: {
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

// ============================== 5. Jquery Validation Form (Product Type) ==============================
$(document).ready(function () {
    $("#formAuthor").validate({
        rules: {
            auth_name: {
                required: true,
                maxlength: 100,
            },
            auth_detail: {
                required: true,
                maxlength: 100,
            },
            auth_newImg: {
                accept: "image/png,image/jpg,image/jpeg",
            },
        },
        messages: {
            auth_name: {
                required: "กรุณาระบุ ชื่อผู้แต่ง",
                maxlength: "ชื่อผู้แต่งต้องไม่เกิน 100 ตัวอักษร",
            },
            auth_detail: {
                required: "กรุณาระบุ รายละเอียดผู้แต่ง",
                maxlength: "รายละเอียดผู้แต่งต้องไม่เกิน 100 ตัวอักษร",
            },
            auth_newImg: {
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

// ============================== 5. Jquery Validation Form (Product Type) ==============================
$(document).ready(function () {
    $("#formPromotion").validate({
        rules: {
            pro_name: {
                required: true,
                maxlength: 100,
            },
            pro_percent_discount: {
                required: true,
                number: true,
                pattern: /^(?:100|[1-9]?[0-9])$/
            },
            pro_time_start: {
                required: true,
            },
            pro_time_end: {
                required: true,
                greaterThan: "#pro_time_start"
            },
            pro_detail: {
                required: true,
                maxlength: 100,
            },
            pro_newImg: {
                accept: "image/png,image/jpg,image/jpeg",
            },
        },
        messages: {
            pro_name: {
                required: "กรุณาระบุ ชื่อโปรโมชั่น",
                maxlength: "ชื่อโปรโมชั่นต้องไม่เกิน 100 ตัวอักษร",
            },
            pro_percent_discount: {
                required: "กรุณาระบุ เปอร์เซ็นต์ส่วนลด",
                number: "เปอร์เซ็นต์ส่วนลด ต้องเป็นตัวเลข",
                pattern: "ต้องเป็นตัวเลข 0 - 100 เท่านั้น"
            },
            pro_time_start: {
                required: "กรุณาระบุ วันเริ่มโปรโมชั่น",
            },
            pro_time_end: {
                required: "กรุณาระบุ วันสิ้นสุดโปรโมชั่น",
                greaterThan: "วันสิ้นสุดโปรโมชั่น ต้องมากกว่า วันเริ่มโปรโมชั่น"
            },
            pro_detail: {
                required: "กรุณาระบุ รายละเอียดโปรโมชั่น",
                maxlength: "รายละเอียดโปรโมชั่น ไม่เกิน 100 ตัวอักษร",
            },
            pro_newImg: {
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

// ============================== 5. Jquery Validation Form (Product Type) ==============================
$(document).ready(function () {
    // Custom validation method to check for length of 10 or 13
    $.validator.addMethod("isbnLength", function (value, element) {
        return this.optional(element) || value.length === 10 || value.length === 13;
    }, "รหัส ISBN ต้องมี 10 หรือ 13 ตัวเท่านั้น");
    $("#formProduct").validate({
        rules: {
            prd_name: {
                required: true,
                maxlength: 100,
            },
            prd_isbn: {
                required: true,
                digits: true,
                isbnLength: true
            },
            prd_coin: {
                required: true,
                digits: true,
                maxlength: 5,
            },
            prd_quantity: {
                required: true,
                digits: true,
                maxlength: 7,
            },
            prd_number_pages: {
                required: true,
                digits: true,
                maxlength: 7,
            },
            prd_price: {
                required: true,
                number: true,
                min: 0,
                max: 9999999,
            },
            prd_percent_discount: {
                required: true,
                digits: true,
                max: 100
            },
            pty_id: {
                required: true,
                digits: true,
                min: 1,
            },
            pub_id: {
                required: true,
                digits: true,
                min: 1,
            },
            auth_id: {
                required: true,
                digits: true,
                min: 1,
            },
            prd_preorder: {
                required: true,
                digits: true,
                min: 0,
                max: 1,
            },
            prd_status: {
                required: true,
                digits: true,
                min: 0,
                max: 1,
            },
            prd_detail: {
                required: true,
            },
            prd_newImg1: {
                accept: "image/png,image/jpg,image/jpeg",
            },
            prd_newImg2: {
                accept: "image/png,image/jpg,image/jpeg",
            },
        },
        messages: {
            prd_name: {
                required: "กรุณาระบุ ชื่อสินค้า",
                maxlength: "ชื่อสินค้าต้องไม่เกิน 100 ตัวอักษร",
            },
            prd_isbn: {
                required: "กรุณาระบุ รหัส ISBN",
                digits: "รหัส ISBN ต้องเป็นตัวเลข จำนวนเต็มบวก",
                isbnLength: "รหัส ISBN ต้องมี 10 หรือ 13 ตัว เท่านั้น"
            },
            prd_coin: {
                required: "กรุณาระบุ จำนวนเหรียญที่จะได้",
                digits: "ต้องเป็นตัวเลขจำนวนเต็มวก เท่านั้น",
                maxlength: "ต้องไม่เกิน 99,999 เหรียญ",
            },
            prd_quantity: {
                required: "กรุณาระบุ จำนวนสินค้าที่มีในคลัง",
                digits: "ต้องเป็นตัวเลขจำนวนเต็มบวก เท่านั้น",
                maxlength: "ต้องไม่เกิน 9,999,999",
            },
            prd_number_pages: {
                required: "กรุณาระบุ จำนวนหน้าหนังสือ",
                digits: "ต้องเป็นตัวเลขจำนวนเต็มบวก เท่านั้น",
                maxlength: "ต้องไม่เกิน 9,999,999",
            },
            prd_price: {
                required: "กรุณาระบุ ราคาสินค้า",
                number: "ต้องเป็นตัวเลข เท่านั้น",
                min: "ราคาน้อยที่สุดคือ 0 บาท",
                max: "ต้องไม่เกิน 9,999,999.00 บาท",
            },
            prd_percent_discount: {
                required: "กรุณาระบุ ส่วนลดสินค้า",
                digits: "ต้องเป็นตัวเลขจำนวนเต็มบวก เท่านั้น",
                max: "ส่วนลดสินค้า ต้องไม่เกิน 100%",
            },
            pty_id: {
                required: "กรุณาระบุ ประเภทสินค้า",
                digits: "ต้องเป็นตัวเลขจำนวนเต็มบวก เท่านั้น",
                min: "ค่าต่ำสุด คือ 1",
            },
            pub_id: {
                required: "กรุณาระบุ สำนักพิมพิ์",
                digits: "ต้องเป็นตัวเลขจำนวนเต็มบวก เท่านั้น",
                min: "ค่าต่ำสุด คือ 1",
            },
            auth_id: {
                required: "กรุณาระบุ ชื่อผู้แต่ง",
                digits: "ต้องเป็นตัวเลขจำนวนเต็มบวก เท่านั้น",
                min: "ค่าต่ำสุด คือ 1",
            },
            prd_preorder: {
                required: "กรุณาระบุ ชนิดสินค้า",
                digits: "ต้องเป็นตัวเลขจำนวนเต็มบวก เท่านั้น",
                min: "ค่าต่ำสุด คือ 0",
                max: "ค่าสูงสุด คือ 1",
            },
            prd_status: {
                required: "กรุณาระบุ สถานะการแสดง",
                digits: "ต้องเป็นตัวเลขจำนวนเต็มบวก เท่านั้น",
                min: "ค่าต่ำสุด คือ 0",
                max: "ค่าสูงสุด คือ 1",
            },
            prd_detail: {
                required: "กรุณาระบุ รายละเอียดสินค้า",
            },
            prd_newImg1: {
                accept: "ต้องเป็นไฟล์ประเภท .png .jpg หรือ .jpeg เท่านั้น",
            },
            prd_newImg2: {
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

// ============================== 5. Jquery Validation Form (Product Type) ==============================
$(document).ready(function () {
    $("#formSettingPercentDiscount").validate({
        rules: {
            prd_percent_discount: {
                required: true,
                digits: true,
                min: 0,
                max: 100,
            },
        },
        messages: {
            prd_percent_discount: {
                required: "กรุณาระบุ ชื่อธนาคาร",
                digits: "ต้องเป็นตัวเลขจำนวนเต็ม",
                min: "ค่าต่ำสุดคือ 0",
                max: "ค่าสูงสุดคือ 100",
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

// ============================== 5. Jquery Validation Form (Product Type) ==============================
$(document).ready(function () {
    $("#formPayment").validate({
        rules: {
            pmt_bank: {
                required: true,
                maxlength: 100,
            },
            pmt_name: {
                required: true,
                maxlength: 100,
            },
            pmt_number: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10,
            },
            pmt_detail: {
                required: true,
                maxlength: 255,
            },
            pmt_status: {
                required: true,
                digits: true,
                min: 0,
                max: 1,
            },
            prd_newImg1: {
                accept: "image/png,image/jpg,image/jpeg",
            },
            prd_newImg2: {
                accept: "image/png,image/jpg,image/jpeg",
            },
        },
        messages: {
            pmt_bank: {
                required: "กรุณาระบุ ชื่อธนาคาร",
                maxlength: "ชื่อธนาคาร มีตัวอักษรได้ไม่เกิน 100 ตัวอักษร",
            },
            pmt_name: {
                required: "กรุณาระบุ ชื่อบัญชีธนาคาร",
                maxlength: "ชื่อบัญชีธนาคาร มีตัวอักษรได้ไม่เกิน 100 ตัวอักษร",
            },
            pmt_number: {
                required: "กรุณาระบุ หมายเลขบัญชีธนาคาร",
                digits: "หมายเลขบัญชีธนาคาร ต้องเป็นตัวเลขจำนวนเต็ม",
                minlength: "หมายเลขบัญชีธนาคาร ต้องมี 10 หมายเลข",
                maxlength: "หมายเลขบัญชีธนาคาร ต้องมี 10 หมายเลข",
            },
            pmt_detail: {
                required: "กรุณาระบุ รายละเอียดช่องทางชำระเงิน",
                maxlength: "มีตัวอักษรได้ไม่เกิน 255 ตัวอักษร",
            },
            pmt_status: {
                required: "กรุณาระบุ สถานะการแสดง",
                digits: "สถานะการแสดง ต้องเป็นตัวเลขจำนวนเต็ม",
                min: "ค่าต่ำสุดคือ 0",
                max: "ค่าสูงสุดคือ 1",
            },
            prd_newImg1: {
                accept: "ต้องเป็นไฟล์ประเภท .png .jpg หรือ .jpeg เท่านั้น",
            },
            prd_newImg2: {
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

// ============================== 5. Jquery Validation Form (Product Type) ==============================
$(document).ready(function () {
    $("#formShipping").validate({
        rules: {
            shp_name: {
                required: true,
                maxlength: 100,
            },
            shp_price: {
                required: true,
                number: true,
                min: 0,
                max: 99999,
            },
            shp_detail: {
                required: true,
                maxlength: 255,
            },
            shp_status: {
                required: true,
                digits: true,
                min: 0,
                max: 1,
            },
            shp_newLogo: {
                accept: "image/png,image/jpg,image/jpeg",
            },
        },
        messages: {
            shp_name: {
                required: "กรุณาระบุ ชื่อขนส่ง",
                maxlength: "ชื่อขนส่ง มีตัวอักษรได้ไม่เกิน 100 ตัวอักษร",
            },
            shp_price: {
                required: "กรุณาระบุ ราคาขนส่ง",
                number: "ต้องเป็นตัวเลข เท่านั้น",
                min: "ราคา ต่ำสุดคือ 0 บาท",
                max: "ราคา สูงสุดคือ 99999 บาท",
            },
            shp_detail: {
                required: "กรุณาระบุ รายละเอียดขนส่ง",
                maxlength: "รายละเอียดขนส่ง มีได้ไม่เกิน 255 ตัวอักษร",
            },
            shp_status: {
                required: "กรุณาระบุ สถานะการแสดง",
                digits: "ต้องเป็นตัวเลข เท่านั้น",
                min: "ค่าต่ำสุด คือ 0",
                max: "ค่าสูงสุด คือ 1",
            },
            shp_newLogo: {
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

// ============================== 5. Jquery Validation Form (Product Type) ==============================
$(document).ready(function () {
    $.validator.addMethod("after", function (value, element, params) {
        var start_time = $(params).val();
        var end_time = value;

        if (!start_time || !end_time) {
            return true;
        }

        return Date.parse(end_time) >= Date.parse(start_time);
    });

    $("#formSearchView").validate({
        rules: {
            time_start: {
                after: "#time_end"
            },
            time_end: {
                after: "#time_start"
            },
            prd_name: {
                maxlength: 100
            },
            pty_name: {
                maxlength: 100
            }
        },
        messages: {
            time_start: {
                after: "วันสิ้นสุด ต้องมากกว่า วันเริ่ม"
            },
            time_end: {
                after: "วันสิ้นสุด ต้องมากกว่า วันเริ่ม"
            },
            productName: {
                maxlength: "ชื่อสินค้า มีได้ไม่เกิน 100 ตัวอักษร"
            },
            pty_name: {
                maxlength: "ชื่อประเภทสินค้า มีได้ไม่เกิน 100 ตัวอักษร"
            }
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


// ============================== 5. Jquery Validation Form (Product Type) ==============================
$(document).ready(function () {
    $.validator.addMethod("googleMapEmbed", function (value, element) {
        return this.optional(element) || /^<iframe.*?src="https:\/\/www\.google\.com\/maps\/embed\?.*?".*?><\/iframe>$/.test(value);
    }, "Please enter a valid Google Maps embed code.");

    $("#formContact").validate({

        rules: {
            ct_detail: {
                required: true,
                url: true
            },
            ct_name_link: {
                required: true,
                maxlength: 100,
            },
            ct_email: {
                required: true,
                email: true,
                maxlength: 100,
            },
            ct_phone_number: {
                required: true,
                pattern: "^[0-9]{10}$", // ตรวจสอบว่ามีเฉพาะตัวเลขและความยาว 10 หลัก
            },
            ct_address: {
                required: true,
                maxlength: 255
            },
            ct_location: {
                required: true,
                googleMapEmbed: true
            },
            ct_status: {
                required: true,
                digits: true,
                min: 0,
                max: 1,
            },
        },
        messages: {
            ct_detail: {
                required: "กรุณาระบุลิงค์ช่องทางติดต่อ",
                url: "กรุณาระบุ URL ที่ถูกต้อง เช่น https://www.facebook.com/"
            },
            ct_name_link: {
                required: "กรุณาระบุ ข้อความแสดงแทนลิงค์",
                maxlength: "ข้อความแสดงแทนลิงค์ มีได้ไม่เกิน 100 ตัวอักษร",
            },
            ct_email: {
                required: "กรุณาระบุ อีเมล",
                email: "รูปแบบอีเมลไม่ถูกต้อง",
                maxlength: "อีเมล มีได้ไม่เกิน 100 ตัวอักษร",
            },
            ct_phone_number: {
                required: "กรุณาระบุ เบอร์โทรศัพท์",
                pattern: "เบอร์โทรศัพท์ต้องเป็นตัวเลข 10 หลัก",
            },
            ct_address: {
                required: "กรุณาระบุ ที่อยู่",
                maxlength: "ที่อยู่ มีได้ไม่เกิน 255 ตัวอักษร",
            },
            ct_location: {
                required: "กรุณาระบุ ตำแหน่งสถานที่",
                googleMapEmbed: "ตำแหน่งสถานที่ ต้องเป็น Embed จาก Google Map โดยไปที่ Google Map > ค้นหาสถานที่ > แชร์ (share) > ฝังแผนที่ (Embed a map) > คัดลอก HTML (Copy HTML)"
            },
            ct_status: {
                required: "กรุณาระบุ สถานะการแสดง",
                digits: "ต้องเป็นตัวเลข เท่านั้น",
                min: "ค่าต่ำสุด คือ 0",
                max: "ค่าสูงสุด คือ 1",
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

// ============================== 5. Jquery Validation Form (Product Type) ==============================
$(document).ready(function () {
    $("#formProductStockLow").validate({

        rules: {
            prd_number_low: {
                required: true,
                number: true,
                digits: true,
                min: 0,
                max: 9999999
            },
        },
        messages: {
            prd_number_low: {
                required: "กรุณาระบุ จำนวนสินค้า",
                number: "ต้องเป็นตัวเลข เท่านั้น",
                digits: "ต้องเป็นตัวเลขจำนวนเต็ม เท่านั้น",
                min: "ค่าต่ำสุดคือ 0",
                max: "ค่าสูงสุดคือ 9,999,999",
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

// ============================== 5. Jquery Validation Form (Product Type) ==============================
$(document).ready(function () {
    $("#formBanner").validate({

        rules: {
            bn_name: {
                required: true,
                maxlength: 100,
            },
            bn_link: {
                url: true,
            },
            bn_img: {
                required: true,
                accept: "image/png,image/jpg,image/jpeg",
            }
        },
        messages: {
            bn_name: {
                required: "กรุณาระบุ ชื่อแบนเนอร์",
                maxlength: "ชื่อแบนเนอร์ต้องไม่เกิน 100 ตัวอักษร",
            },
            bn_link: {
                url: "ลิงค์ URL ไม่ถูกต้อง",
            },
            bn_img: {
                required: "กรุณาระบุ รูปภาพแบนเนอร์",
                accept: "ต้องเป็นไฟล์ประเภท .png .jpg หรือ .jpeg เท่านั้น",
            }
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

// ============================== 5. Jquery Validation Form (Product Type) ==============================
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


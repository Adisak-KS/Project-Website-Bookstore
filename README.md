<p align="center">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap">
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JavaScript">
  <img src="https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white" alt="jQuery">
</p>

<h1 align="center">Project Bookshop</h1>
<p align="center"><strong>เว็บไซต์ซื้อขายหนังสือออนไลน์</strong></p>

<p align="center">
  <a href="#-รายละเอียดโครงการ">รายละเอียด</a> •
  <a href="#-ระบบภายในเว็บไซต์">ระบบภายใน</a> •
  <a href="#-เทคโนโลยีที่ใช้">เทคโนโลยี</a> •
  <a href="#-วิธีติดตั้ง">ติดตั้ง</a> •
  <a href="#-ตัวอย่างเว็บไซต์">ตัวอย่าง</a>
</p>

---

## รายละเอียดโครงการ

> โปรเจ็คจบปริญญาตรีที่พัฒนาต่อยอดจาก PHP (MySQLi) เป็น **PHP (PDO)** พร้อมปรับปรุงประสิทธิภาพและแก้ไขบั๊คต่างๆ

### การปรับปรุง
- เปลี่ยนจาก **MySQLi** เป็น **PDO** เพื่อความปลอดภัยและประสิทธิภาพ
- เปลี่ยนการแจ้งเตือนจาก JavaScript ธรรมดาเป็น **SweetAlert2**
- ปรับปรุงประสิทธิภาพ SQL Query
- แก้ไขบั๊คได้เกือบทั้งหมด
- ลดขนาดพื้นที่ใช้งานของเว็บไซต์

### ลิงก์ที่เกี่ยวข้อง
| รายการ | ลิงก์ |
|--------|------|
| ตัวอย่างเว็บไซต์ | [คลิกที่นี่](https://github.com/Adisak-KS/Project-Website-Bookstore/tree/main/previews/admin) |
| ไฟล์รายงาน PDF | [คลิกที่นี่](https://github.com/Adisak-KS/Project-Website-Bookstore/blob/main/previews/Documentation/%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%9E%E0%B8%B1%E0%B8%92%E0%B8%99%E0%B8%B2%E0%B9%80%E0%B8%A7%E0%B9%87%E0%B8%9A%E0%B9%84%E0%B8%8B%E0%B8%95%E0%B9%8C%E0%B8%82%E0%B8%B2%E0%B8%A2%E0%B8%AB%E0%B8%99%E0%B8%B1%E0%B8%87%E0%B8%AA%E0%B8%B7%E0%B8%AD%E0%B8%AD%E0%B8%AD%E0%B8%99%E0%B9%84%E0%B8%A5%E0%B8%99%E0%B9%8C.pdf) |

---

## ระบบภายในเว็บไซต์

<details>
<summary><strong>1. ผู้ดูแลระบบสูงสุด (Super Admin)</strong></summary>

| หมวด | ความสามารถ |
|------|-----------|
| **บัญชีผู้ใช้** | Login, Logout, แก้ไขข้อมูลส่วนตัว, แก้ไข Username/Email/Password |
| **จัดการผู้ใช้** | เพิ่ม/ลบ/แก้ไข เจ้าของร้าน, ผู้ดูแลระบบ, พนักงาน, สมาชิก |
| **จัดการสินค้า** | เพิ่ม/ลบ/แก้ไข ประเภทสินค้า, สำนักพิมพ์, ผู้แต่ง, โปรโมชัน, สินค้า |
| **จัดการระบบ** | ช่องทางชำระเงิน, ช่องทางขนส่ง, แบนเนอร์, ช่องทางติดต่อ, ตั้งค่าเว็บไซต์ |
| **รายงาน** | ตรวจสอบยอดขาย/ยอดเข้าชม, Export CSV/Excel/PDF |
| **จัดการคำสั่งซื้อ** | รอตรวจสอบชำระเงิน, รอจัดส่ง, หาหนังสือ, ความคิดเห็น, สินค้าคงคลัง |

</details>

<details>
<summary><strong>2. ผู้ดูแลระบบ (Admin)</strong></summary>

| หมวด | ความสามารถ |
|------|-----------|
| **บัญชีผู้ใช้** | Login, Logout, แก้ไขข้อมูลส่วนตัว, แก้ไข Username/Email/Password |
| **จัดการผู้ใช้** | เพิ่ม/แก้ไข ผู้ดูแลระบบ, เพิ่ม/ลบ/แก้ไข พนักงาน/สมาชิก, กำหนดสิทธิ์/สถานะ |
| **จัดการสินค้า** | เพิ่ม/ลบ/แก้ไข ประเภทสินค้า, สำนักพิมพ์, ผู้แต่ง, โปรโมชัน, สินค้า |
| **จัดการระบบ** | ช่องทางชำระเงิน, ช่องทางขนส่ง, แบนเนอร์, ช่องทางติดต่อ, ตั้งค่าเว็บไซต์ |
| **รายงาน** | ตรวจสอบยอดขาย/ยอดเข้าชม, Export CSV/Excel/PDF |
| **จัดการคำสั่งซื้อ** | รอตรวจสอบชำระเงิน, รอจัดส่ง, หาหนังสือ, ความคิดเห็น, สินค้าคงคลัง |

</details>

<details>
<summary><strong>3. ฝ่ายการเงิน (Accounting)</strong></summary>

| หมวด | ความสามารถ |
|------|-----------|
| **บัญชีผู้ใช้** | Login, Logout, แก้ไขข้อมูลส่วนตัว, แก้ไข Username/Email/Password |
| **จัดการผู้ใช้** | เพิ่ม/แก้ไข/ลบ ผู้ดูแลระบบ, อนุญาต/ระงับสถานะ |
| **จัดการระบบ** | ช่องทางชำระเงิน, ช่องทางขนส่ง |
| **จัดการคำสั่งซื้อ** | รอตรวจสอบชำระเงิน, รอจัดส่ง, หาหนังสือ, ความคิดเห็น, สินค้าคงคลัง |

</details>

<details>
<summary><strong>4. ฝ่ายขาย (Sale)</strong></summary>

| หมวด | ความสามารถ |
|------|-----------|
| **บัญชีผู้ใช้** | Login, Logout, แก้ไขข้อมูลส่วนตัว, แก้ไข Username/Email/Password |
| **จัดการสินค้า** | เพิ่ม/ลบ/แก้ไข ประเภทสินค้า, สำนักพิมพ์, ผู้แต่ง, โปรโมชัน, สินค้า |
| **จัดการคำสั่งซื้อ** | รอตรวจสอบชำระเงิน, รอจัดส่ง, หาหนังสือ, ความคิดเห็น, สินค้าคงคลัง |

</details>

<details>
<summary><strong>5. พนักงาน (Employee)</strong></summary>

| หมวด | ความสามารถ |
|------|-----------|
| **บัญชีผู้ใช้** | Login, Logout, แก้ไขข้อมูลส่วนตัว, แก้ไข Username/Email/Password |
| **จัดการระบบ** | แบนเนอร์, ช่องทางติดต่อ, ตั้งค่าเว็บไซต์ |
| **จัดการคำสั่งซื้อ** | รอตรวจสอบชำระเงิน, รอจัดส่ง, หาหนังสือ, ความคิดเห็น, สินค้าคงคลัง |

</details>

<details>
<summary><strong>6. สมาชิก (Member)</strong></summary>

| หมวด | ความสามารถ |
|------|-----------|
| **บัญชีผู้ใช้** | Login, Logout, แก้ไขข้อมูลส่วนตัว, แก้ไข Username/Email/Password |
| **สินค้า** | ค้นหา, ดูรายละเอียด, ตะกร้าสินค้า, รายการสิ่งที่อยากได้, พรีออเดอร์ |
| **คำสั่งซื้อ** | สั่งซื้อ, ยกเลิก, แจ้งชำระเงิน, ตรวจสอบประวัติ, สั่งซื้อซ้ำ |
| **จัดส่ง** | เลือกที่อยู่จัดส่ง, ตรวจสอบพัสดุ, ยืนยันรับสินค้า |
| **อื่นๆ** | ใช้เหรียญส่วนลด, โอนเหรียญ, หาหนังสือตามสั่ง, ติดต่อผู้ดูแล |

</details>

<details>
<summary><strong>7. ผู้ใช้ทั่วไป (User)</strong></summary>

| หมวด | ความสามารถ |
|------|-----------|
| **ทั่วไป** | สมัครสมาชิก, ค้นหาสินค้า, ดูรายละเอียดสินค้า, ติดต่อผู้ดูแลระบบ |

</details>

---

## เทคโนโลยีที่ใช้

### ภาษาและ Framework

| Frontend | Backend | Database |
|----------|---------|----------|
| HTML | PHP (PDO) | MySQL |
| CSS | | |
| JavaScript | | |
| jQuery | | |
| Bootstrap 5 | | |
| AJAX | | |

### Libraries และ Plugins

| Library | การใช้งาน |
|---------|----------|
| DataTables | จัดการตารางข้อมูล |
| jQuery Validation | ตรวจสอบฟอร์ม |
| Chart.js | แสดงกราฟรายงาน |
| SweetAlert2 | แจ้งเตือนสวยงาม |

### เครื่องมือพัฒนา

| เครื่องมือ | การใช้งาน |
|-----------|----------|
| Visual Studio Code | Code Editor |
| XAMPP | Local Server |

---

## วิธีติดตั้ง

### ขั้นตอนการติดตั้ง

```
1. ดาวน์โหลดและติดตั้ง XAMPP หรือ WAMP

2. นำไฟล์ Database ไปติดตั้ง
   - ไฟล์อยู่ที่: preview/database/bookshop.sql

3. นำโฟลเดอร์ bookshop ไปวางใน htdocs

4. หากมี Error เรื่อง Database
   - ตรวจสอบไฟล์ connection.php
   - แก้ไข Username และ Password ให้ตรง
```

### URL เข้าใช้งาน

| ระดับผู้ใช้ | URL |
|------------|-----|
| สมาชิก / ผู้ใช้ทั่วไป | `http://localhost/bookshop/` |
| ผู้ดูแลระบบ | `http://localhost/bookshop/admin/login_form.php` |

### ข้อมูล Login เริ่มต้น

| ระดับ | Username | Password |
|-------|----------|----------|
| Super Admin | `superAdmin` | `superAdmin` |
| Admin | `Admin1` | `Admin111` |
| Member | `member1` | `member11` |

---

## ตัวอย่างเว็บไซต์

### หน้าสมาชิก (Member)

| หน้าแรก | ตะกร้าสินค้า |
|--------|-------------|
| ![หน้าแรก](https://github.com/Adisak-KS/Project-Website-Bookstore/blob/main/previews/member/01_index.png) | ![ตะกร้า](https://github.com/Adisak-KS/Project-Website-Bookstore/blob/main/previews/member/07_cart.png) |

| บัญชีของฉัน |
|------------|
| ![บัญชี](https://github.com/Adisak-KS/Project-Website-Bookstore/blob/main/previews/member/12_account_show.png) |

### หน้าผู้ดูแลระบบ (Admin)

| หน้าแรก Admin | รายงานยอดขาย |
|--------------|--------------|
| ![Admin](https://github.com/Adisak-KS/Project-Website-Bookstore/blob/main/previews/admin/02_index.png) | ![รายงาน](https://github.com/Adisak-KS/Project-Website-Bookstore/blob/main/previews/admin/53_report_product_sale_show.png) |

---

<p align="center">
  <strong>แก้ไขเมื่อ:</strong> 22/08/2567 | <strong>ผู้จัดทำ:</strong> Adisak Khongsuk
</p>

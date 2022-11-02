![Shape1](RackMultipart20221102-1-8mesqe_html_5f647d927b22b92d.gif) J2School

![](RackMultipart20221102-1-8mesqe_html_bb756ba1752ca69a.png)

# **ĐỒ ÁN J2TEAMSHOOL**

## **Hệ thống lớp bổ túc**

| **Lớp thầy Nong - 21** |
| --- |
| **Thành viên** | Prox (Nhóm trưởng) |
| **Giảng viên** | Nguyễn Nam Long |

**Mục lục**

[1.Mở đầu 3](#_Toc96115198)

[1.1.Đưa ra vấn đề 4](#_Toc96115199)

[1.2.Hệ thống hiện tại 4](#_Toc96115200)

[1.3.Hệ thống đề nghị 4](#_Toc96115201)

[1.4.Công nghệ 4](#_Toc96115202)

[2.Phân tích yêu cầu người dùng 5](#_Toc96115203)

[2.1.Yêu cầu phi chức năng 5](#_Toc96115204)

[2.2.Yêu cầu chức năng 5](#_Toc96115205)

[3.2.1.Nhóm người dùng của hệ thống 5](#_Toc96115206)

[3.2.2.Phân tích chức năng 6](#_Toc96115207)

[4.Thiết kế hệ thống 29](#_Toc96115208)

[a.Sơ đồ quan hệ thực thể 29](#_Toc96115209)

[b.Sơ đồ cơ sở dữ liệu 30](#_Toc96115210)

[c.Sơ đồ trang web 31](#_Toc96115211)

[Kết luận 33](#_Toc96115212)

[5.Phân công công việc 34](#_Toc96115213)

#


1.
#
### Mở đầu

Sử dụng công nghệ trong các lĩnh vực, hỗ trợ cho công việc trở thành một nhu cầu thiết yếu, ứng dụng phổ biến bởi những giá trị, những lợi ích mà nó mang lại. Với phần mềm quản lý lớp học khi lựa chọn thích hợp hỗ trợ cho công tác giảng dạy, đào tạo được thực hiện tốt. Việc quản lý, kiểm soát học sinh được thực hiện tốt hỗ trợ cho công việc của từng giáo viên được hoàn thành tốt.

Mỗi phần mềm được phát triển và ứng dụng cho những lĩnh vực khác nhau mang tới những lợi ích, những giá trị riêng biệt. Hoàn thiện phần mềm đạt tiêu chuẩn, thích hợp đảm bảo giúp quá trình sử dụng phát huy được tối đa lợi ích. Trong đó, một phần mềm quản lý trường học hay cụ thể hơn ở đây là phần mềm quản lý cho từng lớp học khi được đưa vào sử dụng sẽ được những hiệu quả cao trong công việc giáo dục.

Hệ thống được tạo ra nhằm mục đích tăng khả năng quản lý những trung tâm giáo dục hoặc những thành phần giáo dục nhỏ, giúp quá trình đào tạo, giảng dạy có được hỗ trợ tốt, diễn ra thuận lợi. Giúp mỗi giáo viên giảm thiểu lượng công việc, mọi việc được hoàn thành tốt, chi tiết và hiệu quả với khả năng hỗ trợ toàn diện cho yêu cầu của từng người. Lúc đó việc sử dụng có được kết quả cao như mỗi người mong muốn. Khả năng tiết kiệm thời gian song nâng cao hiệu quả quản lý lớp học.

Xin cảm ơn!

### Giới thiệu

  1.
## Đưa ra vấn đề

Các trung tâm hiện tại đã có những ứng dụng công nghệ thông tin vào quản lý. Tuy nhiên vẫn chưa thực sự hiệu quả. Hiện tại đã có sẵn dữ liệu trên excel, csv,….Tuy nhiên lượng dữ liệu lớn khó xử lý, gây nhiều sai sót.

  1.
## Hệ thống hiện tại

Thực hiện thủ công trên giấy tờ hoặc excel, csv

  1.
## Hệ thống đề nghị

Hệ thống mới chính xác hơn, lưu trữ dữ liệu đơn giản hơn. Dễ dàng quản lý dinh viên, giáo viên. Tạo lịch học đơn giản, ít gây sai sót.

  1.
## Công nghệ

1. PHP 7.2.0
2. Framework Laravel 8
3. Blade
4. JavaScript
5. jQuery 3
6. HTML 5
7. CSS
8. Bootstrap 5
9. MySQL

#
### Phân tích yêu cầu người dùng

  1.
## Yêu cầu phi chức năng

- Dễ nhìn.

- Đơn giản.

- Dễ thao tác

- Bảo mật

- Màu sắc bắt mắt.

- Dùng được trên nhiều thiết bị, trình duyệt.

  1.
## Yêu cầu chức năng

1.
### Nhóm người dùng của hệ thống

- Sinh viên

- Giảng viên

- Giáo vụ

- Quản trị viên

1. **Sinh viên:**
  1. Đăng nhập
  2. Xem tổng quan tình hình học tập
  3. Xem lịch học
  4. Xem lịch sử điểm danh
  5. Nhận thông báo
  6. Lịch cá nhân
  7. Xem thông tin liên hệ giảng viên
  8. Đăng xuất
2. **Giảng viên:**
  1. Đăng nhập
  2. Xem tổng quan tình hình giảng dạy
  3. Xem lịch giảng dạy
  4. Điểm danh cho sinh viên
  5. Nhận thông báo
  6. Lịch cá nhân
  7. Xem thông tin liên hệ giáo vụ
  8. Đăng xuất
3. **Giáo vụ**
  1. Đăng nhập
  2. Xem tổng quan tình hình giảng dạy
  3. Quản lý tài khoản từ sinh viên đến giảng viên
  4. Quản lý lớp học
  5. Quản lý lịch học chung
  6. Quản lý điểm danh
  7. Nhận thông báo
  8. Lịch cá nhân
  9. Đăng xuất
4. **Quản trị viên**
  1. Tương tự giáo vụ nhưng có thể quản lý tài khoản giáo vụ

1.
### Phân tích chức năng

- **Đăng nhập**

| _ **Các tác nhân** _ | Sinh viên, Giảng viên, Giáo vụ, Quản trị viên |
| --- | --- |
| _ **Mô tả** _ | Đăng nhập. |
| _ **Kích hoạt** _ | Người dùng vào truy cập vào domain name. Nếu chưa đăng nhập thì trang đăng nhập sẽ hiển thị ra |
| _ **Đầu vào** _ |
  - Tên đăng nhập.
  - Mật khẩu.
  - Ô ghi nhớ đăng nhập
 |
| _ **Trình tự xử lý** _ |
  1. Hiện thị form đăng nhập.
  2. Kiểm tra thông tin hợp lệ khi bấm nút đăng nhập.
    1. Không hợp lệ: hiện thị thông báo thông tin không hợp lệ (không để trống, email hợp lệ, ...).
    2. Hợp lệ: lấy thông tin từ form, thực hiện bước 3.
  3. Xác thực tài khoản.
    1. Đúng:
      1. Tạo phiên đăng nhập, phân quyền, lưu tên, token, mã tài khoản.
      2. Đưa vào trang chính.
    2. Sai: Hiện thị thông báo "Tài khoản hoặc mật khẩu không chính xác".
 |
| _ **Đầu ra** _ |
  1. Đúng: Hiển thị trang chủ và thông báo thành công.
  2. Sai: Hiển thị form đăng nhập và thông báo thất bại.
 |
| _ **Lưu ý** _ |
  - Kiểm tra thông tin hợp lệ bằng jQuery
  - Kiểm tra thông tin ở phía backend.
 |

- **Xem tổng quan**

| _ **Các tác nhân** _ | Sinh viên, Giảng viên, Giáo vụ, Quản trị viên |
| --- | --- |
| _ **Mô tả** _ | Xem tổng quan |
| _ **Kích hoạt** _ | Người dùng nhấn vào mục Dashboard trên menu |
| _ **Đầu vào** _ |
1. Người dùng đã đăng nhập
 |
| _ **Trình tự xử lý** _ | 1. Phân quyền, nếu:
- **Người dùng là Sinh viên:**
+ Lấy dữ liệu từ backend và hiển thị số lớp đã và đang học, thông tin chi tiết+ Lấy dữ liệu từ backend và hiển thị số buổi đã học, thông tin chi tiết
- **Người dùng là Giảng viên:**
+ Lấy dữ liệu từ backend và hiển thị các lớp học đang giảng dạy, thông tin chi tiết
- **Người dùng là Giáo vụ:**
+ Lấy dữ liệu từ backend và hiển thị số lượng sinh viên + Lấy dữ liệu từ backend và hiển thị số lượng giảng viên+ Lấy dữ liệu từ backend và hiển thị số lớp+ Lấy dữ liệu từ backend và hiển thị số môn học
- **Người dùng là Quản trị viên:**
+Tương tự Giáo vụ |
| _ **Đầu ra** _ |
1. Đúng: Hiển thị các thông tin nêu trên
2. Sai: Thoát phiên đăng nhập lại
 |
| _ **Lưu ý** _ |
- Truy xuất thông tin phía backend.
- Xử lí hiển thị bằng JavaScript
 |

- **Xem lịch học / giảng dạy**

| _ **Các tác nhân** _ | Sinh viên, Giảng viên |
| --- | --- |
| _ **Mô tả** _ | Xem lịch học / giảng dạy |
| _ **Kích hoạt** _ | Người dùng nhấn vào mục Schedule trên menu |
| _ **Đầu vào** _ |
1. Người dùng đã đăng nhập, người dùng là sinh viên hoặc giảng viên
 |
| _ **Trình tự xử lý** _ |
- Xác thực quyền người dùng
- Lấy dữ liệu từ backend,
- gửi về phía frontend
- hiển thị ra lịch biểu: Tên lớp, giờ bắt đầu kết thúc,…
 |
| _ **Đầu ra** _ |
- Đúng: Hiển thị các thông tin nêu trên
- Sai: Thoát phiên đăng nhập lại
 |
| _ **Lưu ý** _ |
1. Truy xuất thông tin phía backend.
2. Xử lí hiển thị bằng JavaScript, dùng thư viện full calendar
 |

- **Quản lí lịch học**

| _ **Các tác nhân** _ | Giáo vụ, Quản trị viên |
| --- | --- |
| _ **Mô tả** _ | Xem, chỉnh sửa, xoá lịch học, dời lịch học |
| _ **Kích hoạt** _ | Người dùng nhấn vào mục Schedule trên menu |
| _ **Đầu vào** _ |
- Người dùng đã đăng nhập, người dùng là giáo vụ hoặc quản trị viên
 |
| _ **Trình tự xử lý** _ |
1. Xác thực quyền người dùng
2. Hiển thị ra danh sách các lớp

- Nếu lớp chưa có lịch học thì hiển thị nút "Tạo lịch"

1. Ngược lại, ẩn nút tạo lịch, hiển thị "Đã có lịch học"

- Khi người dùng nhấn vào nút "Edit":

1. Hiển thị lịch học của lớp đó ra để chỉnh sửa
2. Nếu người dùng nhấn vào biểu tượng dời lịch học:
+ Dời lịch học sau ngày cuối cùng của lịch học
- Khi người dùng nhấn vào nút "Delete":

- Kiểm tra lớp ấy đã hoạt động buổi nào chưa?
+ Xác nhận xem người dùng có chắc chắn muốn xoá hay không ? + Nếu đã hoạt động thì ngăn người dùng xoá và hiển thị thông báo thất bại+ Nếu chưa thì xoá lịch và hiển thị thông báo thành công
- Nếu người dùng ấn nút "Tạo lịch":

1. Căn cứ vào thông tin lớp học và tạo lịch học phù hợp
2. Hiển thị thông báo thành công nếu không xảy ra lỗi
3. Hiển thị thông báo thất bại nếu xảy ra sai sót
 |
| _ **Đầu ra** _ |
- Đúng: Hiển thị các thông tin nêu trên
- Sai: Thoát phiên đăng nhập lại
 |
| _ **Lưu ý** _ |
1. Truy xuất thông tin phía backend.
2. Xử lí hiển thị bằng JavaScript
 |

- **Xem điểm danh**

| _ **Các tác nhân** _ | Sinh viên |
| --- | --- |
| _ **Mô tả** _ | Xem điểm danh |
| _ **Kích hoạt** _ | Người dùng nhấn vào mục Attendance trên menu |
| _ **Đầu vào** _ |
- Người dùng đã đăng nhập, người dùng là sinh viên
 |
| _ **Trình tự xử lý** _ |
- Xác thực quyền người dùng
- Hiển thị ra lịch biểu:

1. Nếu đi học thì nền sẽ là màu xanh đậm
2. Nếu nghỉ phép thì nền sẽ là xanh lá
3. Nếu vắng học thì nền sẽ là màu đỏ
4. Nếu chưa được điểm danh thì nền sẽ là màu xanh da trời
 |
| _ **Đầu ra** _ |
- Đúng: Hiển thị các thông tin nêu trên
- Sai: Thoát phiên đăng nhập lại
 |
| _ **Lưu ý** _ |
1. Truy xuất thông tin phía backend.
2. Xử lí hiển thị bằng JavaScript, dùng thư viện full calendar
 |

- **Điểm danh cho sinh viên**

| _ **Các tác nhân** _ | Giảng viên, Giáo vụ, Quản trị viên |
| --- | --- |
| _ **Mô tả** _ | Điểm danh cho sinh viên |
| _ **Kích hoạt** _ | Người dùng nhấn vào mục Attendance trên menu |
| _ **Đầu vào** _ |
- Người dùng đã đăng nhập, người dùng là giảng viên, giáo vụ hoặc quản trị viên
 |
| _ **Trình tự xử lý** _ |
1. Xác thực quyền người dùng
2. Nếu là giảng viên thì hiển thị ra toàn bộ các lớp mà giảng viên ấy dạy
3. Nếu là giáo vụ hoặc quản trị viên thì hiển thị toàn bộ các lớp
4. Khi người dùng nhấn vào nút "History"

1. Hiển thị thông tin:
+ Tổng số học sinh của lớp+ Số học sinh đi học đầy đủ+Số học sinh vắng học quá 3 buổi+Biểu đồ thống kê đi học của lớp
1. Hiển thị lịch biểu của lớp:
+ Người dùng nhấn vào một buổi cụ thể sẽ liên kết đến trang điểm danh của lớp+ Ở trang điểm danh, người dùng có thể đánh vắng hoặc phép cho sinh viên vào buổi học đó+ Ở trang điểm danh người dùng có thể xem tổng quan buổi đó có bao nhiêu sinh viên vắng học hoặc phép trên tổng số sinh viên+ Người dùng xác nhận điểm danh sẽ nhấn vào ô "Hoàn thành" để lưu kết quả điểm danh. |
| _ **Đầu ra** _ |
- Đúng: Hiển thị các thông tin nêu trên
- Sai: Thoát phiên đăng nhập lại
 |
| _ **Lưu ý** _ |
1. Truy xuất thông tin phía backend.
2. Để giảng viên chủ động trong việc điểm danh, có vẫn cho phép sửa điểm danh trong quá trình học
3. Xử lí hiển thị bằng JavaScript, dùng thư viện full calendar
 |

- **Nhận thông báo**

| _ **Các tác nhân** _ | Sinh viên, Giảng viên, Giáo vụ, Quản trị viên |
| --- | --- |
| _ **Mô tả** _ | Nhận thông báo |
| _ **Kích hoạt** _ | Người dùng nhấn vào mục biểu tượng thông báo trên menu |
| _ **Đầu vào** _ |
- Người dùng đã đăng nhập
 |
| _ **Trình tự xử lý** _ |
1. Backend lấy mọi thông báo của người dùng gửi về frontend, dùng frontend hiển thị ra thông báo cho người dùng
 |
| _ **Đầu ra** _ |
- Đúng: Hiển thị các thông tin nêu trên
- Sai: Thoát phiên đăng nhập lại
 |
| _ **Lưu ý** _ |
1. Truy xuất thông tin phía backend.
2. Xử lí hiển thị bằng HTML, Blade
 |

1. **Lịch các nhân**

| _ **Các tác nhân** _ | Sinh viên, Giảng viên, Giáo vụ, Quản trị viên |
| --- | --- |
| _ **Mô tả** _ | Lịch các nhân |
| _ **Kích hoạt** _ | Người dùng nhấn vào mục "Dashboard" -\> "Lịch cá nhân" trên menu |
| _ **Đầu vào** _ |
- Người dùng đã đăng nhập
 |
| _ **Trình tự xử lý** _ |
1. Hiển thị giao diện lịch tháng
2. Hiển thị các sự kiện đã thêm
3. Nếu người dùng nhấn vào nút thêm lịch:

1. Tạo form để người dùng thêm sự kiện vào ngày đã chọn
2. Nếu người dùng nhấn "OK" trong form:
+ Lưu sự kiện vào máy người dùng
1. Nếu người dùng nhấn vào nút "Cancel" trong form thì sẽ ẩn form và hiển thị các sự kiện trong ngày
 |
| _ **Đầu ra** _ |
- Đúng: Hiển thị các thông tin nêu trên
- Sai: Thoát phiên đăng nhập lại
 |
| _ **Lưu ý** _ |
1. Lịch sử dụng JavaScript để bắt sự kiện
2. Kết hợp dùng jQuery
 |

1. **Liên hệ Giảng viên**

| _ **Các tác nhân** _ | Sinh viên |
| --- | --- |
| _ **Mô tả** _ | Liên hệ Giảng viên |
| _ **Kích hoạt** _ | Người dùng nhấn vào mục "Dashboard" -\> "Liên hệ giảng viên" trên menu |
| _ **Đầu vào** _ |
- Người dùng đã đăng nhập
- Người dùng là Sinh viên
 |
| _ **Trình tự xử lý** _ |
1. Xác thực người dùng
2. Backend lấy dữ liệu, thông tin của Giảng viên các môn học
3. Khi người dùng nhấn vào "Liên hệ":

1. Đưa người dùng đến địa chỉ email của Giảng viên
 |
| _ **Đầu ra** _ |
- Đúng: Hiển thị các thông tin nêu trên
- Sai: Thoát phiên đăng nhập lại
 |
| _ **Lưu ý** _ |
1. Truy xuất thông tin phía backend.
2. Xử lí hiển thị bằng HTML, Blade
 |

1. **Liên hệ Giáo vụ**

| _ **Các tác nhân** _ | Giảng viên |
| --- | --- |
| _ **Mô tả** _ | Liên hệ Giảng viên |
| _ **Kích hoạt** _ | Người dùng nhấn vào mục "Dashboard" -\> "Liên hệ giáo vụ" |
| _ **Đầu vào** _ |
- Người dùng đã đăng nhập
- Người dùng là Giảng viên
 |
| _ **Trình tự xử lý** _ |
1. Xác thực người dùng
2. Backend lấy dữ liệu, thông tin ngẫu nhiên của 3 Giáo vụ
3. Khi người dùng nhấn vào "Liên hệ":

1. Đưa người dùng đến địa chỉ email của Giáo vụ
 |
| _ **Đầu ra** _ |
- Đúng: Hiển thị các thông tin nêu trên
- Sai: Thoát phiên đăng nhập lại
 |
| _ **Lưu ý** _ |
1. Truy xuất thông tin phía backend.
2. Xử lí hiển thị bằng HTML, Blade
 |

1. **Quản lí tài khoản**

| _ **Các tác nhân** _ | Giáo vụ, Quản trị viên |
| --- | --- |
| _ **Mô tả** _ | Quản lý tài khoản:
1. Xem thông tin người dùng
2. Thêm sinh viên bằng file excel
3. Thêm thủ công sinh viên
4. Sửa thông tin sinh viên
5. Xoá sinh viên
 |
| _ **Kích hoạt** _ | Người dùng nhấn vào mục "Users" trên menu |
| _ **Đầu vào** _ |
- Người dùng đã đăng nhập
- Người dùng là Giáo vụ hoặc Quản trị viên
 |
| _ **Trình tự xử lý** _ |
1. Xác thực người dùng
2. Nếu người dùng là Giáo vụ:

1. Người dùng chỉ có thể thêm, chỉnh sửa và xoá tài khoản của sinh viên và giảng viên

- Nếu người dùng là quản trị viên:

1. Người dùng chỉ có thể thêm, chỉnh sửa và xoá tài khoản của sinh viên, giảng viên và giáo vụ

- Nếu người dùng bấm vào nút "Tải lên sinh viên":

1. Hiển thị file mẫu cho người dùng
2. Khi người dùng tải file lên:
+ Kiểm tra dữ liệu file: + Nếu đúng: lưu dữ liệu vào cơ sở dữ liệu và hiển thị thông báo thành công+ Nếu sai hiển thị thông báo thất bại
- Nếu người dùng nhấn vào nút "Thêm người dùng"

1. Hiển thị form tạo người dùng

- Nếu người dùng nhấn vào nút "Xoá"

1. Kiểm tra người dùng đã đi vào hoạt động hay chưa
+ Nếu người dùng đã đi vào hoạt động thì gửi thông báo người dùng đã hoạt động+ Nếu người chưa từng hoạt động thì xoá người dùng và thông báo thành công |
| _ **Đầu ra** _ |
- Đúng: Hiển thị các thông tin nêu trên
- Sai: Thoát phiên đăng nhập lại
 |
| _ **Lưu ý** _ |
1. Truy xuất thông tin phía backend.
2. Kiểm tra thông tin người dùng ở cả frontend và backend
3. Xử lí hiển thị bằng HTML, Blade
 |

1. **Quản lí lớp**

| _ **Các tác nhân** _ | Giáo vụ, Quản trị viên |
| --- | --- |
| _ **Mô tả** _ | Quản lý lớp học:
1. Xem thông tin lớp học
2. Thêm lớp học bằng file excel
3. Thêm lớp học thủ công
4. Thêm sinh viên vào lớp học bằng excel
5. Thêm thủ công sinh viên vào lớp học
6. Thêm giảng viên
7. Sửa thông tin lớp học
8. Phê duyệt lớp học
9. Xoá lớp học
 |
| _ **Kích hoạt** _ | Người dùng nhấn vào mục "Classes" trên menu |
| _ **Đầu vào** _ |
- Người dùng đã đăng nhập
- Người dùng là Giáo vụ hoặc Quản trị viên
 |
| _ **Trình tự xử lý** _ |
1. Xác thực người dùng

- Nếu người dùng bấm vào nút "Tải lên lớp học":

1. Hiển thị file mẫu cho người dùng
2. Khi người dùng tải file lên:
+ Kiểm tra dữ liệu file: + Nếu đúng: Phân lớp, lưu dữ liệu vào cơ sở dữ liệu và hiển thị thông báo thành công+ Nếu sai hiển thị thông báo thất bại
- Nếu người dùng nhấn vào nút "Thêm lớp học"

1. Hiển thị form tạo lớp học
2. Sau khi người dùng chọn môn học, dùng ajax lấy tên lớp học (để tên lớp theo logic)
3. Khi người dùng ấn vào nút "Submit":
+ Kiểm tra dữ liệu bằng JavaScript và tiếp tục kiểm tra dữ liệu bằng backend nếu đúng thì lưu vào CSDL
- Nếu người dùng nhấn vào biểu tượng xoá:

1. Kiểm tra lớp học đã hoạt động hay chưa:
+ Nếu đã đi vào hoạt động thì đưa về thông báo không thể xoá+ Nếu chưa đi vào hoạt động thì cho phép người dùng xoá
- Nếu người dùng ấn vào nút "Thêm giảng viên":

1. Backend lấy những giáo viên không trùng lịch và gửi về frontend hiển thị ra
2. Người dùng chọn giảng viên và nhấn "Hoàn thành để lưu lại"

- Nếu người dùng ấn vào biểu tượng chỉnh sửa:

1. Chuyển hướng trang đến trang chỉnh sửa lớp học
2. Hiển thị những thông tin chưa được điền để người dùng chú ý hơn

- Nếu người dùng bấm vào nút "Tải lên sinh viên":

1. Hiển thị file mẫu cho người dùng
2. Khi người dùng tải file lên:
+ Kiểm tra dữ liệu file: + Nếu đúng: Lưu dữ liệu vào cơ sở dữ liệu và hiển thị thông báo thành công+ Nếu sai hiển thị thông báo thất bại
- Nếu người dùng bấm vào nút "Thêm sinh viên":

1. Hiển thị danh sách 20 các sinh viên không trùng lịch
2. Nếu người dùng filter thì chỉ lấy 20 sinh viên giống với kết quả
3. Người dùng chọn sinh viên và nhấn nút "Thêm" để thêm sinh viên

- Hiển thị nút phê duyệt nếu lớp chưa được phê duyệt
- Kiểm tra xem lớp đã được phê duyệt chưa:

1. Nếu lớp đầy đủ thông tin và số lượng học sinh đã đủ thì cho phép mở lớp
+ Người dùng nhấn vào biểu tượng phê duyệt để mở lớp
- Người dùng chỉ có thể sửa thông tin lớp nếu chưa được phê duyệt
- Nếu người dùng bấm vào nút kết thúc lớp học:

1. Kiểm tra số buổi học đã trên 50% chưa:
+ Nếu chưa thông báo không thể kết thúc+ Nếu đã trên 50% thì thông báo kết thúc thành công và tổng kết điểm |
| _ **Đầu ra** _ |
- Đúng: Hiển thị các thông tin nêu trên
- Sai: Thoát phiên đăng nhập lại
 |
| _ **Lưu ý** _ |
1. Truy xuất thông tin phía backend.
2. Kiểm tra thông tin người dùng ở cả frontend và backend
3. Xử lí hiển thị bằng HTML, Blade, JavaScript, jQuery
4. Sử dung thư viện choose.js để làm multi select
5. Sử dụng thư viện Datatables
 |

1. **Quản lí môn học**

| _ **Các tác nhân** _ | Giáo vụ, Quản trị viên |
| --- | --- |
| _ **Mô tả** _ | Quản lý môn học:
1. Xem thông tin môn học
2. Thêm môn học
3. Chỉnh sửa môn học
4. Xoá môn học
 |
| _ **Kích hoạt** _ | Người dùng nhấn vào mục "Subjects" trên menu |
| _ **Đầu vào** _ |
- Người dùng đã đăng nhập
- Người dùng là Giáo vụ hoặc Quản trị viên
 |
| _ **Trình tự xử lý** _ |
1. Xác thực người dùng
2. Hiển thị thông tin môn học

- Nếu người dùng nhấn vào nút "Thêm môn học"

1. Hiển thị form tạo môn học
2. Khi người dùng ấn vào nút "Submit":
+ Kiểm tra dữ liệu bằng JavaScript và tiếp tục kiểm tra dữ liệu bằng backend nếu đúng thì lưu vào CSDL
- Nếu người dùng nhấn vào nút "Delete":

1. Kiểm tra môn học đã hoạt động hay chưa:
+ Nếu đã đi vào hoạt động thì đưa về thông báo không thể xoá+ Nếu chưa đi vào hoạt động thì cho phép người dùng xoá
- Nếu người dùng ấn vào nút "Edit":

1. Chuyển hướng trang đến trang chỉnh sửa lớp học
+Sau khi người dùng ấn nút "Submit" kiểm tra dữ liệu bằng frontend và backend |
| _ **Đầu ra** _ |
- Đúng: Hiển thị các thông tin nêu trên
- Sai: Thoát phiên đăng nhập lại
 |
| _ **Lưu ý** _ |
1. Truy xuất thông tin phía backend.
2. Kiểm tra thông tin người dùng ở cả frontend và backend
3. Xử lí hiển thị bằng HTML, Blade, JavaScript
4. Sử dụng thư viện Datatables
 |

- **Đăng xuất**

| _ **Các tác nhân** _ | Sinh viên, Giảng viên, Giáo vụ, Quản trị viên |
| --- | --- |
| _ **Mô tả** _ | Đăng xuất. |
| _ **Kích hoạt** _ | Người dùng bấm vào biểu avatar trên thanh trạng thái -\> "Logout" |
| _ **Đầu vào** _ |
 |
| _ **Trình tự xử lý** _ | Đăng xuất và xoá phiên đăng nhập của người dùng |
| _ **Đầu ra** _ |
1. Đúng: Chuyển hướng đến trang đăng nhập.
 |
| _ **Lưu ý** _ |
- Kiểm tra thông tin ở phía backend.
 |

- **Sửa thông tin cá nhân**

| _ **Các tác nhân** _ | Sinh viên, Giảng viên, Giáo vụ, Quản trị viên |
| --- | --- |
| _ **Mô tả** _ | Sửa thông tin cá nhân |
| _ **Kích hoạt** _ | Người dùng nhấn vào avatar trên thanh trạng thái -\> "My profile" |
| _ **Đầu vào** _ |
1. Người dùng đã đăng nhập
 |
| _ **Trình tự xử lý** _ |
1. Backend lấy thông tin người dùng hiển thị ra
2. Nếu người dùng nhấn vào nút edit-\> mở chỉnh sửa thông tin cá nhân
3. Nếu người dùng nhấn vào nút "Thay đổi mật khẩu":
 - Hiển thị form thay đổi mật khẩu
4. Nếu người dùng ấn vào nút "Submit":

1. Kiểm tra thông tin người dùng hợp lệ bằng JavaScript
2. Kiểm tra dữ liệu người dùng bằng backend
+ Nếu đúng thì cập nhật thông tin người dùng, sau đó thông báo thành công+ Nếu sai thì chuyển hướng về lại và thông báo thông tin sai |
| _ **Đầu ra** _ |
1. Đúng: Thông báo thành công hoặc Thất bại và thông tin sai
 |
| _ **Lưu ý** _ |
- Kiểm tra thông tin ở phía backend.
- Kiểm tra thông tin phía frontend
 |

#


-
#
### Thiết kế hệ thống

  -
## Sơ đồ quan hệ thực thể

![](RackMultipart20221102-1-8mesqe_html_1fd7edce3e874dd9.png)

  -
## Sơ đồ cơ sở dữ liệu

![](RackMultipart20221102-1-8mesqe_html_e0a0a1e7e6761dd.png)

  -
## Sơ đồ trang web

Sinh viên:

![](RackMultipart20221102-1-8mesqe_html_fcb8b3ad169887d3.png)

Giảng viên:

![](RackMultipart20221102-1-8mesqe_html_785445718281b67a.png)

Giáo vụ, Quản trị viên

![](RackMultipart20221102-1-8mesqe_html_d6c72cf52f975fd6.png)

#
### Kết luận

- Đã làm được:

Đầy đủ các tính năng cần có của một trang web quản lí lớp bổ túc:

CRUD Các đối tượng

Giao diện theo Flat UI design khá dễ nhìn

- Hướng mở rộng:

1. Thêm quản lý điểm
2. Tối ưu hiệu suất
3. Hỗ trợ Chatbot
4. Chat realtime
5. Mở rộng quy mô:
+ Thêm khoa học, khoá học
+ Thêm ngành học

Chân thành cảm ơn !


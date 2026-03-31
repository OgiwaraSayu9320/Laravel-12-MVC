# FLOW CODE — Tài liệu kỹ thuật dự án Laravel CMS Đa Ngôn Ngữ

Cập nhật lần cuối: 31/03/2026
Framework: Laravel 12 | PHP 8.2+ | MySQL | Bootstrap 5 | CKEditor 5
Tác giả: Antigravity AI + Nguyễn Trí

---

## MỤC LỤC

1. Kiến trúc tổng quan
2. Database — Cấu trúc bảng đã tồn tại
3. Sprint 1 — Hoàn thành (Nền tảng Admin)
4. Sprint 2 — Hoàn thành (Hoàn thiện Admin)
5. Sprint 3 — Hoàn thành (Frontend Website)
6. Sprint 4 — Cần làm tiếp (Contact Form)
7. Quy ước code chuẩn của dự án
8. Kiến trúc Service Layer

---

## PHẦN 1: KIẾN TRÚC TỔNG QUAN

Dự án có 2 phân hệ độc lập:

### Phân hệ 1 — Admin (Backend)

- URL prefix: `/admin/`
- Guard: `admin` (bảng `admins`)
- Controllers: `App\Http\Controllers\Admin\`
- Views: `resources/views/admin/`
- Layout: `resources/views/admin/layouts/app.blade.php`
- Auth: Middleware `auth:admin` + `AdminRoleMiddleware`

### Phân hệ 2 — Frontend (Public Website)

- URL prefix: `/{lang}/` — ví dụ `/vi/san-pham`, `/en/products`
- Middleware: `SetLocale` tự nhận ngôn ngữ từ segment đầu URL
- Controllers: `App\Http\Controllers\Frontend\`
- Services: `App\Services\` — dùng chung cho cả Web và API sau này
- Views: `resources/views/pages/` + layout `resources/views/layouts/main.blade.php`
- Route mặc định: `/` redirect về `/vi`

### Luồng xử lý chính

```
Request → Route → Middleware → Controller → Service → Model → Database
                                          ↓
                                     View (Blade)
```

Đặc biệt: Controller chỉ nhận request và gọi Service. Service chứa toàn bộ logic nghiệp vụ. API Controller sau này sẽ gọi chính Service đó để trả về JSON.

---

## PHẦN 2: DATABASE — CẤU TRÚC BẢNG

### Quy ước chung cho mọi bảng đa ngôn ngữ

```
tenvi          — Tên tiếng Việt (bắt buộc)
tenen          — Tên tiếng Anh (optional)
tenkhongdauvi  — Slug VI, unique, dùng thay ID trong URL
tenkhongdauen  — Slug EN, unique
motavi         — Mô tả ngắn VI
motaen         — Mô tả ngắn EN
noidungvi      — Nội dung chi tiết VI (longText, CKEditor)
noidungen      — Nội dung chi tiết EN (longText, CKEditor)
titlevi        — SEO title VI
keywordsvi     — SEO keywords VI
descriptionvi  — SEO description VI
titleen        — SEO title EN
keywordsen     — SEO keywords EN
descriptionen  — SEO description EN
stt            — Số thứ tự (integer, default 0)
hienthi        — Hiển thị (boolean, default true)
noibat         — Nổi bật (boolean, default false)
```

### Sơ đồ quan hệ đầy đủ

```
product_lists (Danh mục sản phẩm cấp 1)
    id | tenvi | ten... | tenkhongdau... | photo | SEO fields | stt | hienthi | noibat
    |
    +-- hasMany --> product_cats (Danh mục sản phẩm cấp 2)
    |               id | id_list | tenvi | ten... | tenkhongdau... | photo | SEO | stt | hienthi | noibat
    |               |
    |               +-- hasMany --> products
    |
    +-- hasMany --> products
                    id | id_list | id_cat | id_brand
                    masp | photo
                    tenvi | tenen | tenkhongdauvi | tenkhongdauen
                    motavi | motaen | noidungvi | noidungen
                    gia | giamoi | giakm
                    stt | hienthi | noibat | banchay | luotxem
                    SEO fields
                    |
                    +-- hasMany --> galleries (type = 'san-pham')

news_lists (Danh mục tin tức cấp 1)
    id | tenvi | ten... | tenkhongdau... | photo | SEO fields | stt | hienthi | noibat
    |
    +-- hasMany --> news_cats (Danh mục tin tức cấp 2)
    |               id | id_list | tenvi | ten... | tenkhongdau... | photo | SEO | stt | hienthi | noibat
    |               |
    |               +-- hasMany --> news
    |
    +-- hasMany --> news (Bài viết / Tin tức)
                    id | id_list | id_cat | photo
                    tenvi | tenen | tenkhongdauvi | tenkhongdauen
                    motavi | motaen | noidungvi | noidungen
                    stt | hienthi | noibat | luotxem
                    SEO fields
                    |
                    +-- hasMany --> galleries (type = 'bai-viet')

brands (Thương hiệu)
    id | tenvi | tenen | tenkhongdauvi | tenkhongdauen
    motavi | motaen | photo | stt | hienthi | noibat

galleries (Bảng gallery dùng chung cho nhiều module)
    id | photo | id_parent | type | tenvi | tenen | stt | hienthi
    type phân biệt: 'san-pham' | 'bai-viet' | 'slider' | 'doi-tac'

admins
    id | name | email | password | role ('admin' | 'super_admin') | remember_token

seo_pages (SEO cho các trang tĩnh)
    id | slug (unique) | name
    titlevi | keywordsvi | descriptionvi
    titleen | keywordsen | descriptionen
    image_seo

contacts (CHƯA TẠO — Sprint 4)
    id | type | ho_ten | email | phone | noi_dung | da_doc | created_at | updated_at
    type phân biệt: 'lien-he' | 'tu-van' | 'hop-tac' | ...
```

---

## PHẦN 3: SPRINT 1 — HOÀN THÀNH

Sprint 1 thiết lập nền tảng toàn bộ phân hệ Admin.

### Những gì đã làm

**Middleware & Auth**

- `AdminRoleMiddleware` — phân quyền theo role, đăng ký alias `role` trong `bootstrap/app.php`
- Route Admin được load qua `bootstrap/app.php` → `routes/admin.php`, prefix `admin`, middleware `web`
- Redirect unauthenticated về `admin.login`

**Trait HasImageUpload** — `app/Traits/HasImageUpload.php`

- Method `uploadImage(UploadedFile $file, string $folder): string`
- Method `deleteImage(?string $path): void`
- Dùng disk `public`, trả về đường dẫn tương đối
- Được dùng trong tất cả Controller có upload ảnh

**Trait HasMultilingual** — `app/Traits/HasMultilingual.php`

- Method `getLangs(): array` — đọc từ `config('lang.langs')`
- Method `processLangData(array $data, ?int $id): array` — tự tạo slug từ tên nếu trống, đảm bảo unique
- Method `makeLangRules(array $baseRules, ?int $id): array` — sinh validation rules cho cả VI và EN
- Method `makeLangAttributes(array $baseAttrs): array` — sinh tên trường humanreadable

**Module Sản phẩm (product) — Full CRUD**

- Migrations: `products`, `product_lists`, `product_cats`
- Models: `Product`, `ProductList`, `ProductCat` với đầy đủ relationship, booted() cascade
- Controllers: `ProductController`, `ProductListController`, `ProductCatController`
- Views: `admin/products/{index,create,edit}`, `admin/product_lists/{index,create,edit}`, `admin/product_cats/{index,create,edit}`
- Gallery: upload nhiều ảnh, xoá từng ảnh trong form edit
- Ajax: `GET /admin/products/get-cats/{id_list}` trả JSON để load danh mục cấp 2 theo cấp 1

**CKEditor Upload**

- `UploadController@upload` — nhận file, lưu vào `storage/app/public/editor/`, trả JSON url về cho CKEditor

**Module SEO Pages**

- Migration + Model `SeoPage` với đầy đủ fillable
- `SeoPageController` — chỉ có `index` và `edit` (data được seed sẵn, không tạo mới)
- Seeder `SeoPageSeeder`: 5 bản ghi mặc định (home, about, contact, products, news)
- Views: `admin/seo_pages/{index,edit}.blade.php`

**Auth Admin**

- `LoginController`: `showLoginForm`, `login`, `logout`
- Views: `admin/auth/login.blade.php`

**Cấu hình đa ngôn ngữ**

- `config/lang.php`: `langs` = `['vi' => 'Tiếng Việt', 'en' => 'English']`, `default_lang` = `'vi'`

---

## PHẦN 4: SPRINT 2 — HOÀN THÀNH

Sprint 2 hoàn thiện toàn bộ chức năng Admin còn thiếu.

### Module Tin tức (News) — 2 cấp danh mục

**Bảng DB đã tạo:** `news_lists`, `news_cats`, `news`
News KHÔNG có: `gia`, `giamoi`, `giakm`, `masp`, `id_brand`, `banchay`
News CÓ: `luotxem`, gallery (type = 'bai-viet')

**Models đã tạo:**

- `NewsList` — booted() cascade khi xoá (set id_list của news_cats và news về null)
- `NewsCat` — belongsTo NewsList, hasMany News
- `News` — belongsTo NewsList, NewsCat; hasMany galleries (where type='bai-viet')

**Controllers đã tạo:**

- `NewsListController` — full CRUD, pattern giống ProductListController
- `NewsCatController` — full CRUD, load danh mục cấp 1 để chọn
- `NewsController` — full CRUD + Gallery + CKEditor, không có các trường thương mại

**Views đã tạo:**

- `admin/news_lists/{index,create,edit}.blade.php`
- `admin/news_cats/{index,create,edit}.blade.php`
- `admin/news/{index,create,edit}.blade.php`

**Routes đã thêm vào `routes/admin.php`:**

```
GET  /admin/news/get-cats/{id_list}  NewsController@getCats  admin.news.get-cats
Resource /admin/news-lists           NewsListController
Resource /admin/news-cats            NewsCatController
Resource /admin/news                 NewsController
```

### Module Thương hiệu (Brands)

**Bảng DB đã tạo:** `brands`

- Các cột: `id`, `tenvi`, `tenen`, `tenkhongdauvi`, `tenkhongdauen`, `motavi`, `motaen`, `photo`, `stt`, `hienthi`, `noibat`

**Đã tạo:**

- Model `Brand` — relationship `hasMany Product`
- `BrandController` — full CRUD
- Views: `admin/brands/{index,create,edit}.blade.php`
- `ProductController` đã được cập nhật: load `$brands` vào form create/edit để chọn thương hiệu
- Model `Product` đã có relationship `brand()`

### Dashboard thống kê

`DashboardController` trả ra view:

- Thẻ thống kê: tổng sản phẩm, thương hiệu, bài viết, danh mục
- Bảng 5 sản phẩm mới nhất (với danh mục)
- Bảng 5 bài viết mới nhất (với danh mục)

### Module Profile Admin

- `ProfileController` — `index`, `update` (cập nhật tên/email), `changePassword` (xác nhận mật khẩu cũ)
- View: `admin/profile/index.blade.php` — 2 form trên 1 trang (thông tin + đổi mật khẩu)
- Routes đã thêm vào `routes/admin.php`:
    ```
    GET  /admin/profile          ProfileController@index           admin.profile
    PUT  /admin/profile          ProfileController@update          admin.profile.update
    PUT  /admin/profile/password ProfileController@changePassword  admin.profile.password
    ```

### Sidebar Admin đã cập nhật

File: `resources/views/admin/layouts/sidebar.blade.php`

- Logic active class dùng `request()->routeIs('admin.xxx.*')`
- Có menu: Dashboard, Quản lý User, Quản lý Sản phẩm (dropdown), Quản lý Tin tức (dropdown), Quản lý SEO, Thương hiệu, Thông tin cá nhân, Đăng xuất
- Sản phẩm dropdown: Danh mục cấp 1, Danh mục cấp 2, Danh sách sản phẩm
- Tin tức dropdown: Danh mục cấp 1, Danh mục cấp 2, Danh sách bài viết

---

## PHẦN 5: SPRINT 3 — HOÀN THÀNH

Sprint 3 xây dựng giao diện Frontend theo kiến trúc Service Layer.

### Middleware SetLocale

File: `app/Http/Middleware/SetLocale.php`

- Đọc `segment(1)` của URL (ví dụ `vi`, `en`)
- So sánh với `config('lang.langs')`, nếu khớp thì `App::setLocale($lang)`
- Đăng ký alias `setlocale` trong `bootstrap/app.php`

### Service Layer

#### HomeService — `app/Services/HomeService.php`

- Inject: `ProductService`, `NewsService`
- `getHomeData()`: gọi ProductService lấy SP nổi bật, NewsService lấy tin nổi bật, SeoPage lấy meta
- `getSeoData(string $slug)`: truy vấn bảng `seo_pages` theo slug

#### ProductService — `app/Services/ProductService.php`

- `getListing(array $filters, int $perPage = 12)`: filter theo id_list, id_cat, id_brand, search; paginate
- `getDetail(string $slug)`: tìm SP theo `tenkhongdauvi` hoặc `tenkhongdauen` tùy locale
- `getRelated(Product $product, int $limit = 8)`: SP cùng id_list, khác id
- `incrementView(Product $product)`: tăng luotxem
- `getAllLists()`: toàn bộ danh mục cấp 1 (cho menu/filter)
- `getCatsByList(int $listId)`: danh mục cấp 2 theo cấp 1

#### NewsService — `app/Services/NewsService.php`

- `getListing(array $filters, int $perPage = 12)`: filter theo id_list, id_cat, search, noibat; paginate
- `getDetail(string $slug)`: tìm bài viết theo slug tùy locale
- `getRelated(News $news, int $limit = 8)`: bài viết cùng id_list, khác id
- `incrementView(News $news)`: tăng luotxem
- `getAllLists()`: toàn bộ danh mục cấp 1 tin tức

### Frontend Controllers

Tất cả nằm trong `app/Http/Controllers/Frontend/`:

| Controller                | Route xử lý                   | Gọi Service       |
| ------------------------- | ----------------------------- | ----------------- |
| `HomeController@index`    | `GET /{lang}/`                | HomeService       |
| `ProductController@index` | `GET /{lang}/san-pham`        | ProductService    |
| `ProductController@show`  | `GET /{lang}/san-pham/{slug}` | ProductService    |
| `NewsController@index`    | `GET /{lang}/tin-tuc`         | NewsService       |
| `NewsController@show`     | `GET /{lang}/tin-tuc/{slug}`  | NewsService       |
| `ContactController@index` | `GET /{lang}/lien-he`         | HomeService (SEO) |

### Routing Frontend — `routes/web.php`

```
GET  /                                   redirect về /vi
GET  /login                              redirect về admin.login (đặt tên login để Auth hoạt động)

Middleware group [setlocale]:
  Prefix {lang}:
    GET  /                 HomeController@index       home
    GET  /san-pham         ProductController@index    products.index
    GET  /san-pham/{slug}  ProductController@show     products.show
    GET  /tin-tuc          NewsController@index        news.index
    GET  /tin-tuc/{slug}   NewsController@show         news.show
    GET  /lien-he          ContactController@index    contact
```

### Layout & CSS Frontend

**Layout chính:** `resources/views/layouts/main.blade.php`

- Load Google Fonts Inter từ fonts.googleapis.com
- Load `public/css/app.css` qua `asset('css/app.css')`
- Include `layouts/header.blade.php` và `layouts/footer.blade.php`
- Stack: `@stack('styles')` trong head, `@stack('scripts')` trước `</body>`

**CSS:** `resources/css/app.css` + copy ra `public/css/app.css`

- Quy ước: mỗi class CSS trên 1 dòng (compact style)
- CSS Variables: `--primary-color`, `--secondary-color`, `--text-color`, `--bg-color`, `--white`, `--border-color`
- Classes chính: `.container`, `.header`, `.nav-menu`, `.lang-switcher`, `.footer`, `.product-card`, `.product-grid`, `.news-card`, `.news-grid`, `.btn`, `.btn-primary`, `.section-title`

**Header:** `resources/views/layouts/header.blade.php`

- Hiển thị logo, nav menu với active class
- Nút chuyển ngôn ngữ VI/EN — tự gán sang route cùng tên nhưng đổi `lang` parameter

**Footer:** `resources/views/layouts/footer.blade.php`

- 3 cột: logo + mô tả, liên kết nhanh, thông tin liên hệ
- Copyright động theo năm hiện tại

### Views Frontend

| File                             | Mô tả                                                                                  |
| -------------------------------- | -------------------------------------------------------------------------------------- |
| `pages/home/index.blade.php`     | Hero banner + SP nổi bật + Tin tức mới nhất                                            |
| `pages/products/index.blade.php` | Danh sách SP + Sidebar filter danh mục + Search + Pagination                           |
| `pages/products/show.blade.php`  | Chi tiết SP: Gallery ảnh chính + thumbnail, thông tin, nội dung CKEditor, SP liên quan |
| `pages/news/index.blade.php`     | Danh sách bài viết dạng list (ảnh trái, nội dung phải) + Pagination                    |
| `pages/news/show.blade.php`      | Chi tiết bài viết: Header, ảnh đại diện, nội dung, Gallery ảnh, Bài viết liên quan     |
| `pages/contact/index.blade.php`  | Hero section, thông tin liên hệ 3 cột, Form (tạm thời chỉ UI), Google Maps embed       |

**Xử lý slug trống:** Tất cả view đã có fallback khi slug trống:

```php
$slug = $lang == 'vi' ? $product->tenkhongdauvi : $product->tenkhongdauen;
$slug = $slug ?: 'product-' . $product->id;
```

---

## PHẦN 6: SPRINT 4 — CẦN LÀM TIẾP

### 6.1 Contact Form — Lưu Database + Gửi Email

**Yêu cầu:** Form liên hệ trang `/lien-he` phải đồng thời:

1. Lưu thông tin vào database bảng `contacts`
2. Gửi email thông báo cho admin

**Tại sao cần cột `type`:** Sau này website có thể có nhiều loại liên hệ khác nhau (tư vấn, hợp tác, khiếu nại...). Dùng cột `type` để phân biệt mà không cần tạo nhiều bảng.

#### Migration: contacts

File: `database/migrations/xxxx_create_contacts_table.php`

```sql
Schema::create('contacts', function (Blueprint $table) {
    $table->id();
    $table->string('type')->default('lien-he');
    -- Giá trị type: 'lien-he', 'tu-van', 'hop-tac', 'khieu-nai'
    -- Thêm sau nếu cần loại mới

    $table->string('ho_ten');
    $table->string('email');
    $table->string('phone')->nullable();
    $table->text('noi_dung');
    $table->boolean('da_doc')->default(false);
    -- da_doc: Admin đánh dấu đã xử lý liên hệ này

    $table->timestamps();
});
```

#### Model: Contact

File: `app/Models/Contact.php`

```php
protected $fillable = ['type', 'ho_ten', 'email', 'phone', 'noi_dung', 'da_doc'];
protected $casts = ['da_doc' => 'boolean'];
```

#### ContactService — `app/Services/ContactService.php`

```php
class ContactService
{
    // Lưu liên hệ vào DB và gửi email
    public function submit(array $data): Contact
    {
        // 1. Lưu vào DB
        $contact = Contact::create([
            'type'     => $data['type'] ?? 'lien-he',
            'ho_ten'   => $data['ho_ten'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'noi_dung' => $data['noi_dung'],
        ]);

        // 2. Gửi email thông báo cho admin
        Mail::to(config('mail.admin_email'))->send(new ContactMail($contact));

        return $contact;
    }

    // Lấy danh sách liên hệ cho trang Admin
    public function getListing(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Contact::latest();
        if (!empty($filters['type']))   $query->where('type', $filters['type']);
        if (!empty($filters['da_doc'])) $query->where('da_doc', $filters['da_doc'] == '1');
        return $query->paginate($perPage);
    }

    // Đánh dấu đã đọc
    public function markAsRead(Contact $contact): void
    {
        $contact->update(['da_doc' => true]);
    }
}
```

#### Mailable: ContactMail

File: `app/Mail/ContactMail.php`

```php
class ContactMail extends Mailable
{
    public function __construct(public Contact $contact) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Liên hệ mới từ website: ' . $this->contact->ho_ten);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact');
    }
}
```

Email template: `resources/views/emails/contact.blade.php`

- Hiển thị: họ tên, email, phone, loại liên hệ, nội dung, thời gian gửi

#### ContactController cập nhật — `app/Http/Controllers/Frontend/ContactController.php`

```php
class ContactController extends Controller
{
    public function __construct(
        private HomeService $homeService,
        private ContactService $contactService
    ) {}

    public function index(Request $request, $lang) { ... }  // Hiển thị form

    public function store(Request $request, $lang)
    {
        $request->validate([
            'ho_ten'   => 'required|string|max:255',
            'email'    => 'required|email',
            'phone'    => 'nullable|string|max:20',
            'noi_dung' => 'required|string|min:10',
        ]);

        $this->contactService->submit($request->only(['ho_ten', 'email', 'phone', 'noi_dung']));

        return back()->with('success', 'Cảm ơn! Chúng tôi sẽ liên hệ lại sớm nhất.');
    }
}
```

Route bổ sung vào `routes/web.php`:

```
POST /{lang}/lien-he  ContactController@store  contact.store
```

#### Admin Module Contacts (quản lý liên hệ)

File: `app/Http/Controllers/Admin/ContactController.php`

- `index()` — danh sách liên hệ, filter theo type và da_doc, paginate
- `show($id)` — xem chi tiết, tự động đánh dấu da_doc = true
- `destroy($id)` — xoá liên hệ

Views:

- `admin/contacts/index.blade.php` — bảng danh sách + filter + nút "Chưa đọc / Đã đọc"
- `admin/contacts/show.blade.php` — hiển thị chi tiết nội dung liên hệ

Route trong `routes/admin.php`:

```
Resource /admin/contacts ContactController (only: index, show, destroy)
```

Sidebar Admin: thêm menu "Quản lý Liên hệ" → `admin.contacts.index`

#### Cấu hình email — `.env`

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="Antigravity CMS"
MAIL_ADMIN_EMAIL=admin@example.com
```

Thêm vào `config/mail.php`:

```php
'admin_email' => env('MAIL_ADMIN_EMAIL', 'admin@example.com'),
```

---

## PHẦN 7: QUY ƯỚC CODE CHUẨN

### 7.1 Cấu trúc thư mục đầy đủ hiện tại

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── Auth/LoginController.php        [XONG]
│   │   │   ├── DashboardController.php         [XONG]
│   │   │   ├── ProductController.php           [XONG]
│   │   │   ├── ProductListController.php       [XONG]
│   │   │   ├── ProductCatController.php        [XONG]
│   │   │   ├── BrandController.php             [XONG]
│   │   │   ├── NewsController.php              [XONG]
│   │   │   ├── NewsListController.php          [XONG]
│   │   │   ├── NewsCatController.php           [XONG]
│   │   │   ├── SeoPageController.php           [XONG]
│   │   │   ├── ProfileController.php           [XONG]
│   │   │   ├── UploadController.php            [XONG]
│   │   │   └── ContactController.php           [CẦN TẠO — Sprint 4]
│   │   ├── Frontend/
│   │   │   ├── HomeController.php              [XONG]
│   │   │   ├── ProductController.php           [XONG]
│   │   │   ├── NewsController.php              [XONG]
│   │   │   └── ContactController.php           [CẦN CẬP NHẬT — Sprint 4]
│   │   └── Controller.php
│   └── Middleware/
│       ├── AdminRoleMiddleware.php              [XONG]
│       └── SetLocale.php                       [XONG]
├── Mail/
│   └── ContactMail.php                         [CẦN TẠO — Sprint 4]
├── Models/
│   ├── Admin.php                               [XONG]
│   ├── Brand.php                               [XONG]
│   ├── Gallery.php                             [XONG]
│   ├── News.php                                [XONG]
│   ├── NewsCat.php                             [XONG]
│   ├── NewsList.php                            [XONG]
│   ├── Product.php                             [XONG]
│   ├── ProductCat.php                          [XONG]
│   ├── ProductList.php                         [XONG]
│   ├── SeoPage.php                             [XONG]
│   ├── Contact.php                             [CẦN TẠO — Sprint 4]
│   └── User.php                               (chưa dùng)
├── Services/
│   ├── HomeService.php                         [XONG]
│   ├── ProductService.php                      [XONG]
│   ├── NewsService.php                         [XONG]
│   └── ContactService.php                      [CẦN TẠO — Sprint 4]
└── Traits/
    ├── HasMultilingual.php                     [XONG]
    └── HasImageUpload.php                      [XONG]

resources/views/
├── admin/
│   ├── layouts/ (app, head, header, sidebar, footer)   [XONG]
│   ├── auth/login.blade.php                    [XONG]
│   ├── dashboard.blade.php                     [XONG]
│   ├── brands/{index,create,edit}.blade.php    [XONG]
│   ├── news/{index,create,edit}.blade.php      [XONG]
│   ├── news_cats/{index,create,edit}.blade.php [XONG]
│   ├── news_lists/{index,create,edit}.blade.php[XONG]
│   ├── products/{index,create,edit}.blade.php  [XONG]
│   ├── product_cats/{index,create,edit}.blade.php[XONG]
│   ├── product_lists/{index,create,edit}.blade.php[XONG]
│   ├── profile/index.blade.php                 [XONG]
│   ├── seo_pages/{index,edit}.blade.php        [XONG]
│   └── contacts/{index,show}.blade.php         [CẦN TẠO — Sprint 4]
├── layouts/
│   ├── main.blade.php                          [XONG]
│   ├── header.blade.php                        [XONG]
│   └── footer.blade.php                        [XONG]
├── pages/
│   ├── home/index.blade.php                    [XONG]
│   ├── products/{index,show}.blade.php         [XONG]
│   ├── news/{index,show}.blade.php             [XONG]
│   └── contact/index.blade.php                 [XONG — cần cập nhật form action]
└── emails/
    └── contact.blade.php                       [CẦN TẠO — Sprint 4]

routes/
├── web.php                                     [XONG — cần thêm POST lien-he]
└── admin.php                                   [XONG — cần thêm contacts resource]

database/
├── migrations/
│   ├── admins, products, product_lists, product_cats  [XONG]
│   ├── news, news_lists, news_cats             [XONG]
│   ├── brands, galleries, seo_pages            [XONG]
│   └── contacts                                [CẦN TẠO — Sprint 4]
└── seeders/
    ├── AdminSeeder.php                          [XONG] email: nguyentri9320@gmail.com / 123qwe
    ├── SeoPageSeeder.php                       [XONG]
    └── DatabaseSeeder.php                      [XONG]
```

### 7.2 Pattern chuẩn cho Admin Controller mới

```php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\XxxModel;
use App\Traits\HasMultilingual;
use App\Traits\HasImageUpload;

class XxxController extends Controller
{
    use HasMultilingual, HasImageUpload;

    private string $view  = 'admin.xxx.';
    private string $route = 'admin.xxx.';

    public function index(Request $request)
    {
        // query()->latest()
        // search đa ngôn ngữ dùng getLangs()
        // filter theo các cột phù hợp
        // paginate(10)->appends(request()->query())
        return view($this->view . 'index', compact('items'));
    }

    public function store(Request $request)
    {
        $rules = $this->makeLangRules(['tenvi' => 'required']);
        $request->validate($rules);
        $data = $this->processLangData($request->all());
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->uploadImage($request->file('photo'), 'xxx');
        }
        XxxModel::create($data);
        return redirect()->route($this->route . 'index')->with('success', 'Đã thêm!');
    }

    public function update(Request $request, $id)
    {
        $item  = XxxModel::findOrFail($id);
        $rules = $this->makeLangRules(['tenvi' => 'required'], $id);
        // truyền $id vào makeLangRules để bỏ qua unique chính nó
        $request->validate($rules);
        $data = $this->processLangData($request->all(), $id);
        if ($request->hasFile('photo')) {
            $this->deleteImage($item->photo);
            $data['photo'] = $this->uploadImage($request->file('photo'), 'xxx');
        }
        $item->update($data);
        return back()->with('success', 'Đã cập nhật!');
    }

    public function destroy($id)
    {
        $item = XxxModel::findOrFail($id);
        $this->deleteImage($item->photo);
        $item->delete();
        return redirect()->route($this->route . 'index')->with('success', 'Đã xoá!');
    }
}
```

### 7.3 Pattern chuẩn cho Frontend Controller

```php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\XxxService;
use Illuminate\Http\Request;

class XxxController extends Controller
{
    public function __construct(private XxxService $xxxService) {}

    public function index(Request $request, $lang)
    {
        $filters = $request->only(['id_list', 'id_cat', 'search']);
        $items   = $this->xxxService->getListing($filters);
        return view('pages.xxx.index', compact('items'));
    }

    public function show(Request $request, $lang, $slug)
    {
        $item = $this->xxxService->getDetail($slug);
        $this->xxxService->incrementView($item);
        $related = $this->xxxService->getRelated($item);
        return view('pages.xxx.show', compact('item', 'related'));
    }
}
```

### 7.4 Quy ước tên Route

| Loại            | Prefix     | Ví dụ                                            |
| --------------- | ---------- | ------------------------------------------------ |
| Admin           | `admin.`   | `admin.news.index`, `admin.news-lists.create`    |
| Frontend        | `{lang}`   | `home`, `products.index`, `news.show`, `contact` |
| API (tương lai) | `/api/v1/` | `api.products.index`, `api.news.show`            |

### 7.5 Quy ước Upload ảnh

- Disk: `public`
- Thư mục gốc: `storage/app/public/`
- Thư mục theo module: `products/`, `news/`, `brands/`, `news_lists/`, `news_cats/`, `product_lists/`, `product_cats/`
- Tên file: `time() . '_' . uniqid() . '.' . extension`
- Hiển thị: `asset('storage/' . $path)`
- CKEditor: route `admin.upload.media` → `UploadController@upload` → lưu vào `editor/`

### 7.6 Quy ước Gallery

Bảng `galleries` dùng chung, phân biệt bằng cột `type`:

| Module   | Giá trị type                   |
| -------- | ------------------------------ |
| Sản phẩm | `'san-pham'`                   |
| Bài viết | `'bai-viet'`                   |
| Slider   | `'slider'` (thêm sau nếu cần)  |
| Đối tác  | `'doi-tac'` (thêm sau nếu cần) |

Relationship luôn có `.where('type', 'xxx')`:

```php
public function galleries() {
    return $this->hasMany(Gallery::class, 'id_parent')
                ->where('type', 'san-pham')
                ->orderBy('stt')->orderBy('id', 'desc');
}
```

### 7.7 Quy ước Slug

- Slug (tenkhongdauvi, tenkhongdauen) phải unique trong bảng của module
- Tự động tạo từ tên nếu người dùng để trống (xử lý trong Trait HasMultilingual)
- Trên Frontend dùng slug thay cho ID trong URL: `/san-pham/{slug}`, `/tin-tuc/{slug}`
- Fallback khi slug trống trong View: `$slug = $slug ?: 'product-' . $product->id`

### 7.8 Quy ước CSS Frontend

- Mỗi class CSS viết trên 1 dòng (compact style)
- Dùng CSS Variables (`--primary-color`, `--bg-color`...) cho màu sắc
- File: `resources/css/app.css` — sau khi sửa phải copy ra `public/css/app.css`
- Không dùng Tailwind, không dùng inline style nếu đã có class (trừ trường hợp cần override nhanh)

---

## PHẦN 8: KIẾN TRÚC SERVICE LAYER — NGUYÊN TẮC

### Tại sao dùng Service Layer?

Dự án sẽ cần cả Web và API. Nếu logic nghiệp vụ đặt thẳng trong Controller thì khi viết API phải viết lại. Service Layer giải quyết điều này:

```
Web request  → Frontend Controller → Service → Model
API request  → API Controller      → Service → Model (cùng Service, khác response format)
```

### Nguyên tắc phân chia trách nhiệm

| Lớp          | Làm gì                                                                |
| ------------ | --------------------------------------------------------------------- |
| Controller   | Nhận request, validate, gọi Service, trả về View hoặc JSON            |
| Service      | Chứa logic nghiệp vụ, query phức tạp, gọi các Model                   |
| Model        | Định nghĩa bảng, relationship, cast, scope — KHÔNG có logic nghiệp vụ |
| View (Blade) | Hiển thị dữ liệu, KHÔNG có logic PHP phức tạp                         |

### Dependency Injection — Cách Laravel tự inject Service

```php
// Laravel tự tạo instance ProductService khi Controller được khởi tạo
class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}
    // Không cần new ProductService(), Laravel làm tự động
}
```

---

## TRẠNG THÁI TỔNG KẾT

| Sprint   | Mô tả                                                     | Trạng thái |
| -------- | --------------------------------------------------------- | ---------- |
| Sprint 1 | Nền tảng Admin (Auth, Products, SEO, Traits)              | XONG       |
| Sprint 2 | Hoàn thiện Admin (News, Brands, Dashboard, Profile)       | XONG       |
| Sprint 3 | Frontend Website đa ngôn ngữ + Service Layer              | XONG       |
| Sprint 4 | Contact Form (lưu DB + gửi email) + Admin quản lý liên hệ | CẦN LÀM    |

Tài khoản Admin mặc định (từ AdminSeeder):

- Email: `nguyentri9320@gmail.com`
- Mật khẩu: `123qwe`

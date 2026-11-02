#!/usr/bin/env bash
# =============================================================================
# SKENA – Scaffold Generator (compatible dengan bash 3.x / macOS default)
# Buat semua file stub Controllers, Models, Migrations, Views, Tests
# Jalankan dari root folder skena/: bash scripts/scaffold.sh
# =============================================================================
set -e

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

GREEN='\033[0;32m'; YELLOW='\033[1;33m'; NC='\033[0m'
info() { echo -e "${GREEN}[SCAFFOLD]${NC} $1"; }
stub() { echo -e "${YELLOW}  ↳ Created:${NC} $1"; }

make_stub() {
    local file="$1"
    local content="$2"
    mkdir -p "$(dirname "$file")"
    if [ ! -f "$file" ]; then
        printf '%s\n' "$content" > "$file"
        stub "$file"
    else
        echo "  [skip] $file (sudah ada)"
    fi
}

make_controller() {
    local path="$1"; local class="$2"; local ns="$3"; local pbi="$4"; local pic="$5"
    make_stub "$path" "<?php

namespace ${ns};

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * ${class} – ${pbi}
 * PIC: ${pic}
 */
class ${class} extends Controller
{
    /** ${pbi} – daftar */
    public function index() {}

    /** ${pbi} – form tambah */
    public function create() {}

    /** ${pbi} – simpan */
    public function store(Request \$request) {}

    /** ${pbi} – detail */
    public function show(\$id) {}

    /** ${pbi} – form edit */
    public function edit(\$id) {}

    /** ${pbi} – update */
    public function update(Request \$request, \$id) {}

    /** ${pbi} – hapus */
    public function destroy(\$id) {}
}"
}

make_model() {
    local class="$1"; local table="$2"
    make_stub "app/Models/${class}.php" "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ${class} extends Model
{
    use HasFactory;

    protected \$table = '${table}';

    protected \$fillable = [
        // TODO: definisikan kolom yang dapat diisi
    ];

    // TODO: tambahkan relasi Eloquent sesuai ERD
}"
}

make_migration() {
    local filename="$1"; local table="$2"
    make_stub "database/migrations/${filename}.php" "<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('${table}', function (Blueprint \$table) {
            \$table->id();
            // TODO: definisikan kolom tabel '${table}' sesuai ERD
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('${table}');
    }
};"
}

make_seeder() {
    local class="$1"
    make_stub "database/seeders/${class}.php" "<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ${class} extends Seeder
{
    public function run(): void
    {
        // TODO: isi data awal untuk ${class}
    }
}"
}

make_view() {
    local path="$1"
    local title
    title=$(basename "$path" .blade.php)
    make_stub "$path" "{{-- ${title} – TODO: implementasi tampilan --}}
@extends('layouts.app')

@section('title', '${title} | SKENA')

@section('content')
<div class=\"container mx-auto px-4 py-6\">
    <h1 class=\"text-2xl font-bold\">{{ '${title}' }}</h1>
    {{-- TODO: implementasi konten --}}
</div>
@endsection"
}

make_test() {
    local class="$1"; local pbi="$2"
    make_stub "tests/Feature/${class}.php" "<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * ${class} – ${pbi}
 */
class ${class} extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_works(): void
    {
        // TODO: implementasi pengujian ${pbi}
        \$this->assertTrue(true);
    }
}"
}

# ─────────────────────────────────────────────────────────────────────────────
# ADMIN CONTROLLERS
# ─────────────────────────────────────────────────────────────────────────────
info "Membuat Admin Controllers..."
make_controller "app/Http/Controllers/Admin/UserController.php" "UserController" "App\Http\Controllers\Admin" "FR-05" "Tim"
make_controller "app/Http/Controllers/Admin/RoleController.php" "RoleController" "App\Http\Controllers\Admin" "FR-06" "Tim"
make_controller "app/Http/Controllers/Admin/AdminLogController.php" "AdminLogController" "App\Http\Controllers\Admin" "PBI-03" "Earliyanza"
make_controller "app/Http/Controllers/Admin/ModerasiKontenController.php" "ModerasiKontenController" "App\Http\Controllers\Admin" "PBI-03" "Earliyanza"
make_controller "app/Http/Controllers/Admin/ServiceCategoryController.php" "ServiceCategoryController" "App\Http\Controllers\Admin" "PBI-05" "Anindya"
make_controller "app/Http/Controllers/Admin/VoucherController.php" "VoucherController" "App\Http\Controllers\Admin" "PBI-09" "Rizal"

# ─────────────────────────────────────────────────────────────────────────────
# CONTROLLERS UMUM
# ─────────────────────────────────────────────────────────────────────────────
info "Membuat Controllers umum..."
NS="App\Http\Controllers"
make_controller "app/Http/Controllers/DashboardController.php"       "DashboardController"       "$NS" "FR-04"  "Tim"
make_controller "app/Http/Controllers/ProfileController.php"         "ProfileController"         "$NS" "PBI-01" "Earliyanza"
make_controller "app/Http/Controllers/ExperienceController.php"      "ExperienceController"      "$NS" "PBI-01" "Earliyanza"
make_controller "app/Http/Controllers/SkillController.php"           "SkillController"           "$NS" "PBI-02" "Earliyanza"
make_controller "app/Http/Controllers/ServiceController.php"         "ServiceController"         "$NS" "PBI-04" "Anindya"
make_controller "app/Http/Controllers/PortfolioController.php"       "PortfolioController"       "$NS" "PBI-06" "Anindya"
make_controller "app/Http/Controllers/FavoriteController.php"        "FavoriteController"        "$NS" "PBI-07" "Rizal"
make_controller "app/Http/Controllers/SavedSearchController.php"     "SavedSearchController"     "$NS" "PBI-08" "Rizal"
make_controller "app/Http/Controllers/SearchController.php"          "SearchController"          "$NS" "FR-25"  "Rizal"
make_controller "app/Http/Controllers/ServiceDetailController.php"   "ServiceDetailController"   "$NS" "FR-26"  "Rizal"
make_controller "app/Http/Controllers/ServiceRequestController.php"  "ServiceRequestController"  "$NS" "PBI-10" "Mahendra"
make_controller "app/Http/Controllers/OrderController.php"           "OrderController"           "$NS" "PBI-11" "Mahendra"
make_controller "app/Http/Controllers/NegotiationController.php"     "NegotiationController"     "$NS" "PBI-12" "Mahendra"
make_controller "app/Http/Controllers/PaymentController.php"         "PaymentController"         "$NS" "PBI-13" "Firzatullah"
make_controller "app/Http/Controllers/WorkProgressController.php"    "WorkProgressController"    "$NS" "PBI-14" "Firzatullah"
make_controller "app/Http/Controllers/MessageController.php"         "MessageController"         "$NS" "PBI-15" "Firzatullah"
make_controller "app/Http/Controllers/ReviewController.php"          "ReviewController"          "$NS" "PBI-16" "Aprilia"
make_controller "app/Http/Controllers/NotificationController.php"    "NotificationController"    "$NS" "PBI-17" "Aprilia"
make_controller "app/Http/Controllers/ReportController.php"          "ReportController"          "$NS" "PBI-18" "Aprilia"

# ─────────────────────────────────────────────────────────────────────────────
# MODELS
# ─────────────────────────────────────────────────────────────────────────────
info "Membuat Models..."
make_model "User"            "users"
make_model "Role"            "roles"
make_model "Permission"      "permissions"
make_model "Profile"         "profiles"
make_model "Experience"      "experiences"
make_model "Skill"           "skills"
make_model "UserSkill"       "user_skills"
make_model "AdminLog"        "admin_logs"
make_model "ModerasiKonten"  "moderasi_konten"
make_model "Service"         "services"
make_model "ServiceCategory" "service_categories"
make_model "Portfolio"       "portfolios"
make_model "Favorite"        "favorites"
make_model "SavedSearch"     "saved_searches"
make_model "Voucher"         "vouchers"
make_model "ServiceRequest"  "service_requests"
make_model "Order"           "orders"
make_model "Negotiation"     "negotiations"
make_model "Payment"         "payments"
make_model "WorkProgress"    "work_progress"
make_model "Message"         "messages"
make_model "Review"          "reviews"
make_model "Notification"    "notifications"
make_model "Report"          "reports"

# ─────────────────────────────────────────────────────────────────────────────
# MIGRATIONS
# ─────────────────────────────────────────────────────────────────────────────
info "Membuat Migrations..."
make_migration "2024_01_01_000001_create_roles_table"              "roles"
make_migration "2024_01_01_000002_create_permissions_table"        "permissions"
make_migration "2024_01_01_000003_create_role_permissions_table"   "role_permissions"
make_migration "2024_01_01_000004_create_users_table"              "users"
make_migration "2024_01_01_000005_create_profiles_table"           "profiles"
make_migration "2024_01_01_000006_create_experiences_table"        "experiences"
make_migration "2024_01_01_000007_create_skills_table"             "skills"
make_migration "2024_01_01_000008_create_user_skills_table"        "user_skills"
make_migration "2024_01_01_000009_create_service_categories_table" "service_categories"
make_migration "2024_01_01_000010_create_services_table"           "services"
make_migration "2024_01_01_000011_create_portfolios_table"         "portfolios"
make_migration "2024_01_01_000012_create_favorites_table"          "favorites"
make_migration "2024_01_01_000013_create_saved_searches_table"     "saved_searches"
make_migration "2024_01_01_000014_create_vouchers_table"           "vouchers"
make_migration "2024_01_01_000015_create_service_requests_table"   "service_requests"
make_migration "2024_01_01_000016_create_orders_table"             "orders"
make_migration "2024_01_01_000017_create_negotiations_table"       "negotiations"
make_migration "2024_01_01_000018_create_payments_table"           "payments"
make_migration "2024_01_01_000019_create_work_progress_table"      "work_progress"
make_migration "2024_01_01_000020_create_messages_table"           "messages"
make_migration "2024_01_01_000021_create_reviews_table"            "reviews"
make_migration "2024_01_01_000022_create_notifications_table"      "notifications"
make_migration "2024_01_01_000023_create_reports_table"            "reports"
make_migration "2024_01_01_000024_create_moderasi_konten_table"    "moderasi_konten"
make_migration "2024_01_01_000025_create_admin_logs_table"         "admin_logs"

# ─────────────────────────────────────────────────────────────────────────────
# SEEDERS
# ─────────────────────────────────────────────────────────────────────────────
info "Membuat Seeders..."
make_seeder "RolePermissionSeeder"
make_seeder "UserSeeder"
make_seeder "ServiceCategorySeeder"
make_seeder "SkillSeeder"
make_seeder "ServiceSeeder"
make_seeder "VoucherSeeder"

make_stub "database/seeders/DatabaseSeeder.php" "<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        \$this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            ServiceCategorySeeder::class,
            SkillSeeder::class,
            ServiceSeeder::class,
            VoucherSeeder::class,
        ]);
    }
}"

# ─────────────────────────────────────────────────────────────────────────────
# VIEWS
# ─────────────────────────────────────────────────────────────────────────────
info "Membuat Views..."
make_view "resources/views/layouts/app.blade.php"
make_view "resources/views/layouts/admin.blade.php"
make_view "resources/views/layouts/partials/navbar.blade.php"
make_view "resources/views/layouts/partials/sidebar.blade.php"
make_view "resources/views/layouts/partials/footer.blade.php"
make_view "resources/views/auth/register.blade.php"
make_view "resources/views/auth/login.blade.php"
make_view "resources/views/home/index.blade.php"
make_view "resources/views/services/index.blade.php"
make_view "resources/views/services/create.blade.php"
make_view "resources/views/services/edit.blade.php"
make_view "resources/views/services/show.blade.php"
make_view "resources/views/profile/index.blade.php"
make_view "resources/views/profile/edit.blade.php"
make_view "resources/views/skills/index.blade.php"
make_view "resources/views/portfolios/index.blade.php"
make_view "resources/views/favorites/index.blade.php"
make_view "resources/views/saved-searches/index.blade.php"
make_view "resources/views/requests/index.blade.php"
make_view "resources/views/orders/index.blade.php"
make_view "resources/views/orders/show.blade.php"
make_view "resources/views/payments/index.blade.php"
make_view "resources/views/work-progress/index.blade.php"
make_view "resources/views/reviews/index.blade.php"
make_view "resources/views/notifications/index.blade.php"
make_view "resources/views/reports/index.blade.php"
make_view "resources/views/dashboard/index.blade.php"
make_view "resources/views/admin/dashboard.blade.php"
make_view "resources/views/admin/users/index.blade.php"
make_view "resources/views/admin/roles/index.blade.php"
make_view "resources/views/admin/categories/index.blade.php"
make_view "resources/views/admin/vouchers/index.blade.php"
make_view "resources/views/admin/moderasi/index.blade.php"
make_view "resources/views/admin/logs/index.blade.php"
make_view "resources/views/admin/reports/index.blade.php"

# ─────────────────────────────────────────────────────────────────────────────
# TESTS
# ─────────────────────────────────────────────────────────────────────────────
info "Membuat Tests..."
make_test "AuthTest"              "FR-01, FR-02"
make_test "ProfileTest"           "PBI-01"
make_test "SkillTest"             "PBI-02"
make_test "AdminLogTest"          "PBI-03"
make_test "ServiceTest"           "PBI-04"
make_test "ServiceCategoryTest"   "PBI-05"
make_test "PortfolioTest"         "PBI-06"
make_test "FavoriteTest"          "PBI-07"
make_test "SavedSearchTest"       "PBI-08"
make_test "VoucherTest"           "PBI-09"
make_test "ServiceRequestTest"    "PBI-10"
make_test "OrderTest"             "PBI-11"
make_test "NegotiationTest"       "PBI-12"
make_test "PaymentTest"           "PBI-13"
make_test "WorkProgressTest"      "PBI-14"
make_test "MessageTest"           "PBI-15"
make_test "ReviewTest"            "PBI-16"
make_test "NotificationTest"      "PBI-17"
make_test "ReportTest"            "PBI-18"

# ─────────────────────────────────────────────────────────────────────────────
# MIDDLEWARE
# ─────────────────────────────────────────────────────────────────────────────
info "Membuat Middleware..."
make_stub "app/Http/Middleware/CheckRole.php" "<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request \$request, Closure \$next, string ...\$roles): Response
    {
        // TODO: cek role user (admin, freelancer, client)
        if (!\$request->user() || !in_array(\$request->user()->role?->name, \$roles)) {
            abort(403, 'Akses ditolak.');
        }
        return \$next(\$request);
    }
}"

make_stub "app/Http/Middleware/CheckPermission.php" "<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request \$request, Closure \$next, string \$permission): Response
    {
        // TODO: cek permission spesifik user
        return \$next(\$request);
    }
}"

# ─────────────────────────────────────────────────────────────────────────────
# SERVICES
# ─────────────────────────────────────────────────────────────────────────────
info "Membuat Service classes..."
make_stub "app/Services/NotificationService.php" "<?php

namespace App\Services;

use App\Models\Notification;

/**
 * NotificationService – PBI-17
 * Satu-satunya service lintas tabel untuk notifikasi otomatis.
 */
class NotificationService
{
    public function send(int \$userId, string \$type, string \$message, ?int \$relatedId = null): Notification
    {
        // TODO: kirim notifikasi (database / broadcast)
        return Notification::create([
            'user_id'    => \$userId,
            'type'       => \$type,
            'message'    => \$message,
            'related_id' => \$relatedId,
            'is_read'    => false,
        ]);
    }
}"

# ─────────────────────────────────────────────────────────────────────────────
# ROUTES
# ─────────────────────────────────────────────────────────────────────────────
info "Membuat Routes..."
make_stub "routes/web.php" "<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SavedSearchController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ServiceDetailController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NegotiationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WorkProgressController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminLogController;
use App\Http\Controllers\Admin\ModerasiKontenController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\VoucherController;

/*
|--------------------------------------------------------------------------
| SKENA – Web Routes
|--------------------------------------------------------------------------
*/

// ── Guest ──────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register'); // FR-01
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');                 // FR-02
    Route::post('/login', [LoginController::class, 'login']);
});

// ── Publik ─────────────────────────────────────────────────────────────────
Route::get('/', [SearchController::class, 'index'])->name('home');                                  // FR-25
Route::get('/services/{service}', [ServiceDetailController::class, 'show'])->name('services.show'); // FR-26
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Authenticated ──────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');             // FR-04
    Route::resource('profile', ProfileController::class)->only(['index', 'edit', 'update']);        // PBI-01
    Route::resource('experiences', ExperienceController::class);                                    // PBI-01
    Route::resource('skills', SkillController::class);                                              // PBI-02
    Route::resource('services', ServiceController::class)->except(['show']);                        // PBI-04
    Route::resource('portfolios', PortfolioController::class);                                      // PBI-06
    Route::resource('favorites', FavoriteController::class)->only(['index', 'store', 'destroy']);   // PBI-07
    Route::resource('saved-searches', SavedSearchController::class)->only(['index', 'store', 'destroy']); // PBI-08
    Route::resource('requests', ServiceRequestController::class);                                   // PBI-10
    Route::resource('orders', OrderController::class);                                              // PBI-11
    Route::resource('orders.negotiations', NegotiationController::class)->shallow();               // PBI-12
    Route::resource('payments', PaymentController::class)->only(['index', 'show', 'store']);       // PBI-13
    Route::resource('work-progress', WorkProgressController::class)->only(['index', 'show', 'update']); // PBI-14
    Route::resource('messages', MessageController::class)->only(['index', 'store']);               // PBI-15
    Route::resource('reviews', ReviewController::class)->only(['index', 'store']);                 // PBI-16
    Route::resource('notifications', NotificationController::class)->only(['index', 'update']);    // PBI-17
    Route::resource('reports', ReportController::class)->only(['index', 'create', 'store']);       // PBI-18
});

// ── Admin ──────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');       // FR-03
    Route::resource('users', UserController::class);                                               // FR-05
    Route::resource('roles', RoleController::class);                                               // FR-06
    Route::resource('logs', AdminLogController::class)->only(['index', 'show']);                   // PBI-03
    Route::resource('moderasi', ModerasiKontenController::class);                                  // PBI-03
    Route::resource('categories', ServiceCategoryController::class);                               // PBI-05
    Route::resource('vouchers', VoucherController::class);                                         // PBI-09
    Route::resource('reports', ReportController::class)->only(['index', 'update']);                // PBI-18
});"

# ─────────────────────────────────────────────────────────────────────────────
# STORAGE
# ─────────────────────────────────────────────────────────────────────────────
info "Membuat folder storage..."
for DIR in "storage/app/public/portfolios" "storage/app/public/proofs" \
           "storage/app/public/skills" "storage/app/public/avatars"; do
    mkdir -p "$DIR"
    touch "$DIR/.gitkeep"
done

info "✅ Scaffold selesai!"
echo ""
echo "┌─────────────────────────────────────────────┐"
echo "│         Langkah Selanjutnya                  │"
echo "├─────────────────────────────────────────────┤"
echo "│  1. Install PHP 8.2+ & Composer              │"
echo "│  2. composer install && npm install          │"
echo "│  3. cp .env.example .env                     │"
echo "│  4. php artisan key:generate                 │"
echo "│  5. php artisan migrate --seed               │"
echo "│  6. php artisan serve                        │"
echo "└─────────────────────────────────────────────┘"

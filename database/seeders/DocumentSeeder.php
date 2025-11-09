<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Documents\Models\Document;
use App\Domain\ImageIntros\Models\ImageIntro;
use App\Models\User;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->where('email', 'editor@example.com')->first();
        $userId = $user ? $user->id : null;

        // Get image intros for linking
        $danCaKhmer = ImageIntro::where('slug', 'di-san-van-hoa-dan-ca-khmer-nam-bo')->first();
        $danVuKhmer = ImageIntro::where('slug', 'nghe-thuat-dan-vu-khmer')->first();
        $danNhacKhmer = ImageIntro::where('slug', 'dan-nhac-ngu-am-pin-peat')->first();
        $leHoiKhmer = ImageIntro::where('slug', 'le-hoi-chol-chnam-thmay')->first();
        $danCaHoa = ImageIntro::where('slug', 'van-hoa-dan-ca-dan-nhac-nguoi-hoa')->first();
        $danVuHoa = ImageIntro::where('slug', 'mua-lan-su-rong-nguoi-hoa')->first();
        $leHoiHoa = ImageIntro::where('slug', 'tet-nguyen-dan-nguoi-hoa')->first();
        $baoTon = ImageIntro::where('slug', 'bao-ton-va-phat-huy-di-san-van-hoa')->first();

        $documents = [
            [
                'title' => 'Nghiên cứu về Dân ca Khmer Nam Bộ',
                'description' => 'Tài liệu nghiên cứu về các loại hình dân ca truyền thống của đồng bào Khmer ở Nam Bộ, bao gồm Hát Dù Kê, Hát Rô Băm, và các thể loại khác.',
                'status' => 'published',
                'published_at' => now()->subDays(15),
                'created_by' => $userId,
                'updated_by' => $userId,
                'image_intro_id' => $danCaKhmer?->id,
            ],
            [
                'title' => 'Bảo tồn và Phát huy Dân vũ Khmer',
                'description' => 'Tài liệu về các biện pháp bảo tồn và phát huy các điệu múa truyền thống của người Khmer như múa Lâm Thôn, múa Rom Vong, múa trống Chhay Dăm.',
                'status' => 'published',
                'published_at' => now()->subDays(14),
                'created_by' => $userId,
                'updated_by' => $userId,
                'image_intro_id' => $danVuKhmer?->id,
            ],
            [
                'title' => 'Nhạc cụ truyền thống Khmer - Dàn nhạc Ngũ âm',
                'description' => 'Tài liệu giới thiệu về các nhạc cụ trong dàn nhạc Ngũ âm Pin Peat của người Khmer, bao gồm Roneat, Skor Thom, Chhing, và các nhạc cụ khác.',
                'status' => 'published',
                'published_at' => now()->subDays(13),
                'created_by' => $userId,
                'updated_by' => $userId,
                'image_intro_id' => $danNhacKhmer?->id,
            ],
            [
                'title' => 'Lễ hội Chol Chnam Thmay - Văn hóa và Nghi lễ',
                'description' => 'Tài liệu chi tiết về lễ hội Chol Chnam Thmay, lễ hội năm mới của người Khmer, bao gồm các nghi lễ, phong tục, và hoạt động văn hóa.',
                'status' => 'published',
                'published_at' => now()->subDays(12),
                'created_by' => $userId,
                'updated_by' => $userId,
                'image_intro_id' => $leHoiKhmer?->id,
            ],
            [
                'title' => 'Dân ca Hoa - Hát Tiều và Hát Quảng',
                'description' => 'Tài liệu nghiên cứu về các loại hình dân ca truyền thống của người Hoa, đặc biệt là Hát Tiều và Hát Quảng.',
                'status' => 'published',
                'published_at' => now()->subDays(11),
                'created_by' => $userId,
                'updated_by' => $userId,
                'image_intro_id' => $danCaHoa?->id,
            ],
            [
                'title' => 'Múa Lân Sư Rồng - Nghệ thuật múa truyền thống Hoa',
                'description' => 'Tài liệu về nghệ thuật múa Lân Sư Rồng của người Hoa, bao gồm lịch sử, kỹ thuật biểu diễn, và ý nghĩa văn hóa.',
                'status' => 'published',
                'published_at' => now()->subDays(10),
                'created_by' => $userId,
                'updated_by' => $userId,
                'image_intro_id' => $danVuHoa?->id,
            ],
            [
                'title' => 'Nhạc cụ truyền thống Hoa - Đàn Nhị và Đàn Tranh',
                'description' => 'Tài liệu giới thiệu về các nhạc cụ truyền thống của người Hoa như Đàn Nhị, Đàn Tranh, và các nhạc cụ khác trong dàn nhạc dân tộc.',
                'status' => 'published',
                'published_at' => now()->subDays(9),
                'created_by' => $userId,
                'updated_by' => $userId,
                'image_intro_id' => $danCaHoa?->id,
            ],
            [
                'title' => 'Tết Nguyên Đán của người Hoa - Phong tục và Văn hóa',
                'description' => 'Tài liệu về Tết Nguyên Đán của người Hoa, bao gồm các phong tục, nghi lễ, và hoạt động văn hóa trong dịp lễ hội quan trọng nhất này.',
                'status' => 'published',
                'published_at' => now()->subDays(8),
                'created_by' => $userId,
                'updated_by' => $userId,
                'image_intro_id' => $leHoiHoa?->id,
            ],
            [
                'title' => 'Bảo tồn Di sản Văn hóa Dân tộc Thiểu số',
                'description' => 'Tài liệu về các biện pháp và chính sách bảo tồn, phát huy di sản văn hóa dân ca, dân vũ, dân nhạc truyền thống của các dân tộc thiểu số Khmer và Hoa.',
                'status' => 'published',
                'published_at' => now()->subDays(7),
                'created_by' => $userId,
                'updated_by' => $userId,
                'image_intro_id' => $baoTon?->id,
            ],
            [
                'title' => 'Nghệ nhân và Nghệ sĩ - Gìn giữ Di sản Văn hóa',
                'description' => 'Tài liệu về các nghệ nhân, nghệ sĩ biểu diễn và những người đang gìn giữ, truyền dạy di sản văn hóa truyền thống của các dân tộc Khmer và Hoa.',
                'status' => 'published',
                'published_at' => now()->subDays(6),
                'created_by' => $userId,
                'updated_by' => $userId,
                'image_intro_id' => $baoTon?->id,
            ],
        ];

        foreach ($documents as $documentData) {
            Document::query()->firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($documentData['title'])],
                $documentData
            );
        }
    }
}


<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Videos\Models\Video;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    public function run(): void
    {
        $videos = [
            // Videos về Dân ca Khmer
            [
                'title' => 'Biểu diễn Hát Dù Kê - Nghệ thuật sân khấu Khmer',
                'description' => 'Video biểu diễn Hát Dù Kê, loại hình nghệ thuật sân khấu truyền thống của người Khmer Nam Bộ.',
                'source_type' => 'external',
                'external_url' => 'https://www.youtube.com/watch?v=example_duke',
                'status' => 'published',
                'published_at' => now()->subDays(10),
                'sort_order' => 1,
            ],
            [
                'title' => 'Hát Rô Băm - Vở kịch múa cổ điển Khmer',
                'description' => 'Video biểu diễn Hát Rô Băm, loại hình nghệ thuật sân khấu cổ điển của người Khmer.',
                'source_type' => 'external',
                'external_url' => 'https://www.youtube.com/watch?v=example_robam',
                'status' => 'published',
                'published_at' => now()->subDays(9),
                'sort_order' => 2,
            ],
            [
                'title' => 'Chầm Riêng Chà Pây - Độc tấu đàn truyền thống',
                'description' => 'Video biểu diễn Chầm Riêng Chà Pây, nghệ sĩ vừa đàn vừa hát với đàn Chà Pây truyền thống.',
                'source_type' => 'external',
                'external_url' => 'https://www.youtube.com/watch?v=example_chapey',
                'status' => 'published',
                'published_at' => now()->subDays(8),
                'sort_order' => 3,
            ],
            // Videos về Dân vũ Khmer
            [
                'title' => 'Múa Lâm Thôn - Điệu múa truyền thống Khmer',
                'description' => 'Video biểu diễn điệu múa Lâm Thôn, một trong những điệu múa truyền thống nổi tiếng của người Khmer.',
                'source_type' => 'external',
                'external_url' => 'https://www.youtube.com/watch?v=example_lamthon',
                'status' => 'published',
                'published_at' => now()->subDays(7),
                'sort_order' => 4,
            ],
            [
                'title' => 'Múa Rom Vong - Điệu múa vòng tròn Khmer',
                'description' => 'Video biểu diễn điệu múa Rom Vong, điệu múa vòng tròn phổ biến của người Khmer trong lễ hội.',
                'source_type' => 'external',
                'external_url' => 'https://www.youtube.com/watch?v=example_romvong',
                'status' => 'published',
                'published_at' => now()->subDays(6),
                'sort_order' => 5,
            ],
            [
                'title' => 'Múa trống Chhay Dăm - Điệu múa nam Khmer',
                'description' => 'Video biểu diễn múa trống Chhay Dăm, điệu múa nam truyền thống thể hiện sức mạnh và tinh thần thượng võ.',
                'source_type' => 'external',
                'external_url' => 'https://www.youtube.com/watch?v=example_chhaydam',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'sort_order' => 6,
            ],
            [
                'title' => 'Múa Salavan - Điệu múa cổ điển Khmer',
                'description' => 'Video biểu diễn điệu múa Salavan, điệu múa cổ điển của người Khmer với các động tác uyển chuyển, tinh tế.',
                'source_type' => 'external',
                'external_url' => 'https://www.youtube.com/watch?v=example_salavan',
                'status' => 'published',
                'published_at' => now()->subDays(4),
                'sort_order' => 7,
            ],
            // Videos về Dân nhạc Khmer
            [
                'title' => 'Dàn nhạc Ngũ âm Pin Peat - Biểu diễn truyền thống',
                'description' => 'Video biểu diễn dàn nhạc Ngũ âm Pin Peat, dàn nhạc truyền thống quan trọng nhất của người Khmer.',
                'source_type' => 'external',
                'external_url' => 'https://www.youtube.com/watch?v=example_pinpeat',
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'sort_order' => 8,
            ],
            [
                'title' => 'Nhạc cụ Roneat - Đàn gõ tre Khmer',
                'description' => 'Video giới thiệu và biểu diễn nhạc cụ Roneat, đàn gõ bằng tre truyền thống của người Khmer.',
                'source_type' => 'external',
                'external_url' => 'https://www.youtube.com/watch?v=example_roneat',
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'sort_order' => 9,
            ],
            // Videos về Dân ca Hoa
            [
                'title' => 'Hát Tiều - Nghệ thuật ca hát truyền thống Hoa',
                'description' => 'Video biểu diễn Hát Tiều, loại hình nghệ thuật ca hát truyền thống của người Hoa.',
                'source_type' => 'external',
                'external_url' => 'https://www.youtube.com/watch?v=example_tieu',
                'status' => 'published',
                'published_at' => now()->subDays(11),
                'sort_order' => 10,
            ],
            [
                'title' => 'Hát Quảng - Dòng nhạc dân gian Hoa Quảng Đông',
                'description' => 'Video biểu diễn Hát Quảng, dòng nhạc dân gian đặc trưng của người Hoa Quảng Đông.',
                'source_type' => 'external',
                'external_url' => 'https://www.youtube.com/watch?v=example_quang',
                'status' => 'published',
                'published_at' => now()->subDays(10),
                'sort_order' => 11,
            ],
            // Videos về Dân vũ Hoa
            [
                'title' => 'Múa Lân Sư Rồng - Nghệ thuật múa truyền thống Hoa',
                'description' => 'Video biểu diễn Múa Lân Sư Rồng, loại hình nghệ thuật múa nổi tiếng của người Hoa trong lễ hội Tết.',
                'source_type' => 'external',
                'external_url' => 'https://www.youtube.com/watch?v=example_lansurong',
                'status' => 'published',
                'published_at' => now()->subDays(1),
                'sort_order' => 12,
            ],
            [
                'title' => 'Múa Rồng - Biểu tượng may mắn của người Hoa',
                'description' => 'Video biểu diễn Múa Rồng, điệu múa truyền thống biểu tượng cho sức mạnh và may mắn của người Hoa.',
                'source_type' => 'external',
                'external_url' => 'https://www.youtube.com/watch?v=example_muarong',
                'status' => 'published',
                'published_at' => now(),
                'sort_order' => 13,
            ],
            [
                'title' => 'Múa Sư Tử - Điệu múa linh vật của người Hoa',
                'description' => 'Video biểu diễn Múa Sư Tử, điệu múa linh vật truyền thống của người Hoa trong các dịp lễ hội.',
                'source_type' => 'external',
                'external_url' => 'https://www.youtube.com/watch?v=example_muasutu',
                'status' => 'published',
                'published_at' => now()->subHours(6),
                'sort_order' => 14,
            ],
            // Videos về Dân nhạc Hoa
            [
                'title' => 'Đàn Nhị - Biểu diễn nhạc cụ dây truyền thống Hoa',
                'description' => 'Video biểu diễn Đàn Nhị, nhạc cụ dây truyền thống quan trọng của người Hoa.',
                'source_type' => 'external',
                'external_url' => 'https://www.youtube.com/watch?v=example_dannhi',
                'status' => 'published',
                'published_at' => now()->subHours(12),
                'sort_order' => 15,
            ],
            [
                'title' => 'Đàn Tranh - Nhạc cụ gõ dây truyền thống Hoa',
                'description' => 'Video biểu diễn Đàn Tranh, nhạc cụ gõ dây truyền thống tạo ra âm thanh trong trẻo, thanh thoát.',
                'source_type' => 'external',
                'external_url' => 'https://www.youtube.com/watch?v=example_dantranh',
                'status' => 'published',
                'published_at' => now()->subHours(8),
                'sort_order' => 16,
            ],
            // Videos về Lễ hội
            [
                'title' => 'Lễ hội Chol Chnam Thmay - Năm mới Khmer',
                'description' => 'Video tài liệu về lễ hội Chol Chnam Thmay, lễ hội năm mới quan trọng nhất của người Khmer với các hoạt động văn hóa đặc sắc.',
                'source_type' => 'external',
                'external_url' => 'https://www.youtube.com/watch?v=example_cholchnam',
                'status' => 'published',
                'published_at' => now()->subDays(12),
                'sort_order' => 17,
            ],
            [
                'title' => 'Tết Nguyên Đán của người Hoa - Lễ hội truyền thống',
                'description' => 'Video tài liệu về Tết Nguyên Đán của người Hoa, lễ hội truyền thống lớn nhất với múa Lân Sư Rồng và các hoạt động văn hóa.',
                'source_type' => 'external',
                'external_url' => 'https://www.youtube.com/watch?v=example_tetnguyendan',
                'status' => 'published',
                'published_at' => now()->subDays(13),
                'sort_order' => 18,
            ],
        ];

        foreach ($videos as $videoData) {
            Video::query()->firstOrCreate(
                ['title' => $videoData['title']],
                $videoData
            );
        }
    }
}


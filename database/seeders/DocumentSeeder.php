<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Documents\Models\Document;
use App\Models\User;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->where('email', 'editor@example.com')->first();
        $userId = $user ? $user->id : null;

        $documents = [
            [
                'title' => 'Nghiên cứu về Dân ca Khmer Nam Bộ',
                'description' => 'Tài liệu nghiên cứu về các loại hình dân ca truyền thống của đồng bào Khmer ở Nam Bộ, bao gồm Hát Dù Kê, Hát Rô Băm, và các thể loại khác.',
                'status' => 'published',
                'published_at' => now()->subDays(15),
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Bảo tồn và Phát huy Dân vũ Khmer',
                'description' => 'Tài liệu về các biện pháp bảo tồn và phát huy các điệu múa truyền thống của người Khmer như múa Lâm Thôn, múa Rom Vong, múa trống Chhay Dăm.',
                'status' => 'published',
                'published_at' => now()->subDays(14),
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Nhạc cụ truyền thống Khmer - Dàn nhạc Ngũ âm',
                'description' => 'Tài liệu giới thiệu về các nhạc cụ trong dàn nhạc Ngũ âm Pin Peat của người Khmer, bao gồm Roneat, Skor Thom, Chhing, và các nhạc cụ khác.',
                'status' => 'published',
                'published_at' => now()->subDays(13),
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Lễ hội Chol Chnam Thmay - Văn hóa và Nghi lễ',
                'description' => 'Tài liệu chi tiết về lễ hội Chol Chnam Thmay, lễ hội năm mới của người Khmer, bao gồm các nghi lễ, phong tục, và hoạt động văn hóa.',
                'status' => 'published',
                'published_at' => now()->subDays(12),
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Dân ca Hoa - Hát Tiều và Hát Quảng',
                'description' => 'Tài liệu nghiên cứu về các loại hình dân ca truyền thống của người Hoa, đặc biệt là Hát Tiều và Hát Quảng.',
                'status' => 'published',
                'published_at' => now()->subDays(11),
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Múa Lân Sư Rồng - Nghệ thuật múa truyền thống Hoa',
                'description' => 'Tài liệu về nghệ thuật múa Lân Sư Rồng của người Hoa, bao gồm lịch sử, kỹ thuật biểu diễn, và ý nghĩa văn hóa.',
                'status' => 'published',
                'published_at' => now()->subDays(10),
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Nhạc cụ truyền thống Hoa - Đàn Nhị và Đàn Tranh',
                'description' => 'Tài liệu giới thiệu về các nhạc cụ truyền thống của người Hoa như Đàn Nhị, Đàn Tranh, và các nhạc cụ khác trong dàn nhạc dân tộc.',
                'status' => 'published',
                'published_at' => now()->subDays(9),
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Tết Nguyên Đán của người Hoa - Phong tục và Văn hóa',
                'description' => 'Tài liệu về Tết Nguyên Đán của người Hoa, bao gồm các phong tục, nghi lễ, và hoạt động văn hóa trong dịp lễ hội quan trọng nhất này.',
                'status' => 'published',
                'published_at' => now()->subDays(8),
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Bảo tồn Di sản Văn hóa Dân tộc Thiểu số',
                'description' => 'Tài liệu về các biện pháp và chính sách bảo tồn, phát huy di sản văn hóa dân ca, dân vũ, dân nhạc truyền thống của các dân tộc thiểu số Khmer và Hoa.',
                'status' => 'published',
                'published_at' => now()->subDays(7),
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Nghệ nhân và Nghệ sĩ - Gìn giữ Di sản Văn hóa',
                'description' => 'Tài liệu về các nghệ nhân, nghệ sĩ biểu diễn và những người đang gìn giữ, truyền dạy di sản văn hóa truyền thống của các dân tộc Khmer và Hoa.',
                'status' => 'published',
                'published_at' => now()->subDays(6),
                'created_by' => $userId,
                'updated_by' => $userId,
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


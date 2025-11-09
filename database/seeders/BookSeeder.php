<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Books\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->where('email', 'editor@example.com')->first();
        $userId = $user ? $user->id : null;

        $books = [
            [
                'title' => 'Văn hóa Khmer Nam Bộ - Dân ca, Dân vũ, Dân nhạc',
                'description' => 'Cuốn sách nghiên cứu toàn diện về văn hóa Khmer Nam Bộ, tập trung vào các loại hình nghệ thuật dân ca, dân vũ, và dân nhạc truyền thống.',
                'author' => 'PGS.TS. Nguyễn Văn Hòa',
                'publisher' => 'Nhà xuất bản Văn hóa Dân tộc',
                'published_year' => 2020,
                'isbn' => '978-604-123-456-7',
                'page_count' => 350,
                'status' => 'published',
                'published_at' => now()->subDays(20),
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Nghệ thuật Hát Dù Kê của người Khmer',
                'description' => 'Cuốn sách chuyên khảo về nghệ thuật Hát Dù Kê, loại hình sân khấu truyền thống độc đáo của người Khmer Nam Bộ.',
                'author' => 'TS. Lê Thị Minh',
                'publisher' => 'Nhà xuất bản Khoa học Xã hội',
                'published_year' => 2019,
                'isbn' => '978-604-234-567-8',
                'page_count' => 280,
                'status' => 'published',
                'published_at' => now()->subDays(19),
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Dàn nhạc Ngũ âm Pin Peat - Âm nhạc truyền thống Khmer',
                'description' => 'Cuốn sách giới thiệu chi tiết về dàn nhạc Ngũ âm Pin Peat, các nhạc cụ, kỹ thuật biểu diễn, và vai trò trong văn hóa Khmer.',
                'author' => 'ThS. Trần Văn Đức',
                'publisher' => 'Nhà xuất bản Âm nhạc',
                'published_year' => 2021,
                'isbn' => '978-604-345-678-9',
                'page_count' => 240,
                'status' => 'published',
                'published_at' => now()->subDays(18),
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Lễ hội Chol Chnam Thmay - Văn hóa và Nghi lễ',
                'description' => 'Cuốn sách nghiên cứu về lễ hội Chol Chnam Thmay, lễ hội năm mới của người Khmer, bao gồm các nghi lễ, phong tục, và hoạt động văn hóa.',
                'author' => 'PGS.TS. Phạm Thị Lan',
                'publisher' => 'Nhà xuất bản Tôn giáo',
                'published_year' => 2018,
                'isbn' => '978-604-456-789-0',
                'page_count' => 320,
                'status' => 'published',
                'published_at' => now()->subDays(17),
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Văn hóa Hoa ở Việt Nam - Dân ca và Dân nhạc',
                'description' => 'Cuốn sách nghiên cứu về văn hóa Hoa ở Việt Nam, tập trung vào các loại hình dân ca và dân nhạc truyền thống như Hát Tiều, Hát Quảng.',
                'author' => 'TS. Võ Thị Hương',
                'publisher' => 'Nhà xuất bản Văn hóa Dân tộc',
                'published_year' => 2020,
                'isbn' => '978-604-567-890-1',
                'page_count' => 300,
                'status' => 'published',
                'published_at' => now()->subDays(16),
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Múa Lân Sư Rồng - Nghệ thuật múa truyền thống Hoa',
                'description' => 'Cuốn sách chuyên khảo về nghệ thuật múa Lân Sư Rồng của người Hoa, bao gồm lịch sử, kỹ thuật biểu diễn, và ý nghĩa văn hóa.',
                'author' => 'ThS. Nguyễn Thị Mai',
                'publisher' => 'Nhà xuất bản Nghệ thuật',
                'published_year' => 2019,
                'isbn' => '978-604-678-901-2',
                'page_count' => 260,
                'status' => 'published',
                'published_at' => now()->subDays(15),
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Nhạc cụ truyền thống Hoa - Đàn Nhị, Đàn Tranh và các nhạc cụ khác',
                'description' => 'Cuốn sách giới thiệu về các nhạc cụ truyền thống của người Hoa như Đàn Nhị, Đàn Tranh, và các nhạc cụ trong dàn nhạc dân tộc.',
                'author' => 'PGS.TS. Lý Văn Tùng',
                'publisher' => 'Nhà xuất bản Âm nhạc',
                'published_year' => 2021,
                'isbn' => '978-604-789-012-3',
                'page_count' => 220,
                'status' => 'published',
                'published_at' => now()->subDays(14),
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Tết Nguyên Đán của người Hoa - Phong tục và Văn hóa',
                'description' => 'Cuốn sách nghiên cứu về Tết Nguyên Đán của người Hoa, bao gồm các phong tục, nghi lễ, và hoạt động văn hóa trong dịp lễ hội quan trọng nhất này.',
                'author' => 'TS. Trần Thị Hoa',
                'publisher' => 'Nhà xuất bản Văn hóa',
                'published_year' => 2018,
                'isbn' => '978-604-890-123-4',
                'page_count' => 290,
                'status' => 'published',
                'published_at' => now()->subDays(13),
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Bảo tồn Di sản Văn hóa Dân tộc Thiểu số - Kinh nghiệm và Giải pháp',
                'description' => 'Cuốn sách tổng hợp các kinh nghiệm và giải pháp bảo tồn, phát huy di sản văn hóa dân ca, dân vũ, dân nhạc truyền thống của các dân tộc thiểu số.',
                'author' => 'PGS.TS. Nguyễn Văn Nam',
                'publisher' => 'Nhà xuất bản Khoa học Xã hội',
                'published_year' => 2022,
                'isbn' => '978-604-901-234-5',
                'page_count' => 380,
                'status' => 'published',
                'published_at' => now()->subDays(12),
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'title' => 'Nghệ nhân Dân tộc Thiểu số - Gìn giữ và Truyền dạy Di sản Văn hóa',
                'description' => 'Cuốn sách giới thiệu về các nghệ nhân, nghệ sĩ biểu diễn và những người đang gìn giữ, truyền dạy di sản văn hóa truyền thống của các dân tộc Khmer và Hoa.',
                'author' => 'TS. Lê Văn Sơn',
                'publisher' => 'Nhà xuất bản Văn hóa Dân tộc',
                'published_year' => 2021,
                'isbn' => '978-604-012-345-6',
                'page_count' => 310,
                'status' => 'published',
                'published_at' => now()->subDays(11),
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
        ];

        foreach ($books as $bookData) {
            Book::query()->firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($bookData['title'])],
                $bookData
            );
        }
    }
}


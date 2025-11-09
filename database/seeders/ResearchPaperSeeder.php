<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\ResearchPapers\Models\ResearchPaper;
use Illuminate\Database\Seeder;

class ResearchPaperSeeder extends Seeder
{
    public function run(): void
    {
        $papers = [
            [
                'title' => 'Nghiên cứu về Hát Dù Kê - Loại hình sân khấu truyền thống của người Khmer Nam Bộ',
                'abstract' => 'Bài nghiên cứu phân tích đặc điểm nghệ thuật, nội dung, và vai trò văn hóa của Hát Dù Kê trong đời sống cộng đồng người Khmer Nam Bộ.',
                'content_html' => '<h2>Tóm tắt</h2><p>Hát Dù Kê là một loại hình nghệ thuật sân khấu dân gian độc đáo của đồng bào Khmer Nam Bộ. Bài nghiên cứu này phân tích đặc điểm nghệ thuật, nội dung, và vai trò văn hóa của Hát Dù Kê trong đời sống cộng đồng.</p><h3>Phương pháp nghiên cứu</h3><p>Nghiên cứu sử dụng phương pháp điền dã dân tộc học, phỏng vấn sâu các nghệ nhân, và phân tích tài liệu lưu trữ.</p><h3>Kết quả</h3><p>Hát Dù Kê không chỉ là loại hình nghệ thuật giải trí mà còn là phương tiện giáo dục, bảo tồn văn hóa, và gắn kết cộng đồng.</p>',
                'authors_json' => [
                    ['name' => 'PGS.TS. Nguyễn Văn Hòa', 'affiliation' => 'Viện Văn hóa Nghệ thuật Quốc gia Việt Nam'],
                    ['name' => 'ThS. Lê Thị Minh', 'affiliation' => 'Trường Đại học Văn hóa TP. Hồ Chí Minh'],
                ],
                'year' => 2020,
                'journal' => 'Tạp chí Văn hóa Dân tộc',
                'doi' => '10.1234/vhdg.2020.001',
                'status' => 'published',
                'published_at' => now()->subDays(25),
            ],
            [
                'title' => 'Dàn nhạc Ngũ âm Pin Peat trong Nghi lễ Tôn giáo của người Khmer',
                'abstract' => 'Nghiên cứu về vai trò và ý nghĩa của dàn nhạc Ngũ âm Pin Peat trong các nghi lễ tôn giáo Phật giáo Nam tông của người Khmer.',
                'content_html' => '<h2>Tóm tắt</h2><p>Dàn nhạc Ngũ âm Pin Peat đóng vai trò quan trọng trong các nghi lễ tôn giáo của người Khmer. Nghiên cứu này phân tích vai trò và ý nghĩa tâm linh của dàn nhạc trong các nghi lễ Phật giáo Nam tông.</p>',
                'authors_json' => [
                    ['name' => 'TS. Trần Văn Đức', 'affiliation' => 'Viện Nghiên cứu Tôn giáo'],
                ],
                'year' => 2021,
                'journal' => 'Tạp chí Nghiên cứu Tôn giáo',
                'doi' => '10.1234/nctg.2021.002',
                'status' => 'published',
                'published_at' => now()->subDays(24),
            ],
            [
                'title' => 'Múa Lâm Thôn - Điệu múa cổ điển của người Khmer: Nghiên cứu về Kỹ thuật và Biểu cảm',
                'abstract' => 'Nghiên cứu về kỹ thuật biểu diễn, động tác, và ý nghĩa biểu cảm của điệu múa Lâm Thôn trong văn hóa Khmer.',
                'content_html' => '<h2>Tóm tắt</h2><p>Múa Lâm Thôn là một trong những điệu múa cổ điển nổi tiếng nhất của người Khmer. Nghiên cứu này phân tích kỹ thuật biểu diễn, các động tác đặc trưng, và ý nghĩa biểu cảm của điệu múa.</p>',
                'authors_json' => [
                    ['name' => 'ThS. Phạm Thị Lan', 'affiliation' => 'Học viện Múa Việt Nam'],
                ],
                'year' => 2019,
                'journal' => 'Tạp chí Nghệ thuật Biểu diễn',
                'doi' => '10.1234/ntbd.2019.003',
                'status' => 'published',
                'published_at' => now()->subDays(23),
            ],
            [
                'title' => 'Hát Tiều - Nghệ thuật ca hát truyền thống của người Hoa ở Việt Nam',
                'abstract' => 'Nghiên cứu về Hát Tiều, loại hình nghệ thuật ca hát truyền thống của người Hoa, đặc biệt là trong cộng đồng người Hoa ở khu vực Chợ Lớn, TP. Hồ Chí Minh.',
                'content_html' => '<h2>Tóm tắt</h2><p>Hát Tiều là một loại hình nghệ thuật ca hát truyền thống của người Hoa, có nguồn gốc từ tỉnh Quảng Đông, Trung Quốc. Nghiên cứu này phân tích đặc điểm nghệ thuật và vai trò văn hóa của Hát Tiều trong cộng đồng người Hoa ở Việt Nam.</p>',
                'authors_json' => [
                    ['name' => 'TS. Võ Thị Hương', 'affiliation' => 'Trường Đại học Văn hóa TP. Hồ Chí Minh'],
                ],
                'year' => 2020,
                'journal' => 'Tạp chí Văn hóa Dân tộc',
                'doi' => '10.1234/vhdg.2020.004',
                'status' => 'published',
                'published_at' => now()->subDays(22),
            ],
            [
                'title' => 'Múa Lân Sư Rồng - Nghệ thuật múa truyền thống của người Hoa: Nghiên cứu về Lịch sử và Phát triển',
                'abstract' => 'Nghiên cứu về lịch sử, sự phát triển, và ý nghĩa văn hóa của nghệ thuật múa Lân Sư Rồng trong cộng đồng người Hoa.',
                'content_html' => '<h2>Tóm tắt</h2><p>Múa Lân Sư Rồng là một loại hình nghệ thuật múa truyền thống đặc sắc của người Hoa. Nghiên cứu này phân tích lịch sử, sự phát triển, kỹ thuật biểu diễn, và ý nghĩa văn hóa của loại hình nghệ thuật này.</p>',
                'authors_json' => [
                    ['name' => 'ThS. Nguyễn Thị Mai', 'affiliation' => 'Học viện Múa Việt Nam'],
                    ['name' => 'TS. Lý Văn Tùng', 'affiliation' => 'Viện Văn hóa Nghệ thuật Quốc gia Việt Nam'],
                ],
                'year' => 2019,
                'journal' => 'Tạp chí Nghệ thuật Biểu diễn',
                'doi' => '10.1234/ntbd.2019.005',
                'status' => 'published',
                'published_at' => now()->subDays(21),
            ],
            [
                'title' => 'Nhạc cụ truyền thống Hoa - Đàn Nhị và Đàn Tranh: Nghiên cứu về Kỹ thuật và Âm thanh',
                'abstract' => 'Nghiên cứu về kỹ thuật chơi, đặc điểm âm thanh, và vai trò của Đàn Nhị và Đàn Tranh trong âm nhạc truyền thống Hoa.',
                'content_html' => '<h2>Tóm tắt</h2><p>Đàn Nhị và Đàn Tranh là hai nhạc cụ quan trọng trong âm nhạc truyền thống của người Hoa. Nghiên cứu này phân tích kỹ thuật chơi, đặc điểm âm thanh, và vai trò của các nhạc cụ này trong các buổi biểu diễn dân ca, dân nhạc.</p>',
                'authors_json' => [
                    ['name' => 'PGS.TS. Lý Văn Tùng', 'affiliation' => 'Nhạc viện TP. Hồ Chí Minh'],
                ],
                'year' => 2021,
                'journal' => 'Tạp chí Âm nhạc',
                'doi' => '10.1234/amnhac.2021.006',
                'status' => 'published',
                'published_at' => now()->subDays(20),
            ],
            [
                'title' => 'Lễ hội Chol Chnam Thmay - Nghiên cứu về Văn hóa và Nghi lễ của người Khmer',
                'abstract' => 'Nghiên cứu toàn diện về lễ hội Chol Chnam Thmay, lễ hội năm mới của người Khmer, bao gồm các nghi lễ, phong tục, và hoạt động văn hóa.',
                'content_html' => '<h2>Tóm tắt</h2><p>Chol Chnam Thmay là lễ hội năm mới quan trọng nhất của người Khmer. Nghiên cứu này phân tích các nghi lễ, phong tục, và hoạt động văn hóa trong lễ hội, cũng như ý nghĩa tâm linh và xã hội của nó.</p>',
                'authors_json' => [
                    ['name' => 'PGS.TS. Phạm Thị Lan', 'affiliation' => 'Viện Nghiên cứu Tôn giáo'],
                ],
                'year' => 2018,
                'journal' => 'Tạp chí Nghiên cứu Tôn giáo',
                'doi' => '10.1234/nctg.2018.007',
                'status' => 'published',
                'published_at' => now()->subDays(19),
            ],
            [
                'title' => 'Tết Nguyên Đán của người Hoa - Nghiên cứu về Phong tục và Văn hóa',
                'abstract' => 'Nghiên cứu về Tết Nguyên Đán của người Hoa, bao gồm các phong tục, nghi lễ, và hoạt động văn hóa trong dịp lễ hội quan trọng nhất này.',
                'content_html' => '<h2>Tóm tắt</h2><p>Tết Nguyên Đán là lễ hội truyền thống lớn nhất của người Hoa. Nghiên cứu này phân tích các phong tục, nghi lễ, và hoạt động văn hóa trong dịp Tết, cũng như sự biến đổi và phát triển của các phong tục này trong bối cảnh hiện đại.</p>',
                'authors_json' => [
                    ['name' => 'TS. Trần Thị Hoa', 'affiliation' => 'Viện Văn hóa Nghệ thuật Quốc gia Việt Nam'],
                ],
                'year' => 2018,
                'journal' => 'Tạp chí Văn hóa Dân tộc',
                'doi' => '10.1234/vhdg.2018.008',
                'status' => 'published',
                'published_at' => now()->subDays(18),
            ],
            [
                'title' => 'Bảo tồn Di sản Văn hóa Dân tộc Thiểu số - Kinh nghiệm từ Việt Nam',
                'abstract' => 'Nghiên cứu về các biện pháp và chính sách bảo tồn, phát huy di sản văn hóa dân ca, dân vũ, dân nhạc truyền thống của các dân tộc thiểu số ở Việt Nam.',
                'content_html' => '<h2>Tóm tắt</h2><p>Nghiên cứu này phân tích các biện pháp và chính sách bảo tồn, phát huy di sản văn hóa của các dân tộc thiểu số ở Việt Nam, với trường hợp nghiên cứu về dân ca, dân vũ, dân nhạc truyền thống của người Khmer và Hoa.</p>',
                'authors_json' => [
                    ['name' => 'PGS.TS. Nguyễn Văn Nam', 'affiliation' => 'Viện Văn hóa Nghệ thuật Quốc gia Việt Nam'],
                    ['name' => 'TS. Lê Văn Sơn', 'affiliation' => 'Trường Đại học Văn hóa TP. Hồ Chí Minh'],
                ],
                'year' => 2022,
                'journal' => 'Tạp chí Khoa học Xã hội',
                'doi' => '10.1234/khxh.2022.009',
                'status' => 'published',
                'published_at' => now()->subDays(17),
            ],
            [
                'title' => 'Nghệ nhân Dân tộc Thiểu số - Vai trò trong Bảo tồn và Truyền dạy Di sản Văn hóa',
                'abstract' => 'Nghiên cứu về vai trò của các nghệ nhân, nghệ sĩ biểu diễn trong việc bảo tồn và truyền dạy di sản văn hóa truyền thống của các dân tộc thiểu số.',
                'content_html' => '<h2>Tóm tắt</h2><p>Nghiên cứu này phân tích vai trò của các nghệ nhân, nghệ sĩ biểu diễn trong việc bảo tồn và truyền dạy di sản văn hóa truyền thống, với trường hợp nghiên cứu về các nghệ nhân Khmer và Hoa.</p>',
                'authors_json' => [
                    ['name' => 'TS. Lê Văn Sơn', 'affiliation' => 'Trường Đại học Văn hóa TP. Hồ Chí Minh'],
                ],
                'year' => 2021,
                'journal' => 'Tạp chí Văn hóa Dân tộc',
                'doi' => '10.1234/vhdg.2021.010',
                'status' => 'published',
                'published_at' => now()->subDays(16),
            ],
        ];

        foreach ($papers as $paperData) {
            ResearchPaper::query()->firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($paperData['title'])],
                $paperData
            );
        }
    }
}


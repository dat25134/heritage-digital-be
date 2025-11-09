<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Posts\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::query()->where('email', 'editor@example.com')->first();
        $authorId = $author ? $author->id : null;

        $posts = [
            // Posts về Dân ca Khmer
            [
                'title' => 'Hát Dù Kê - Nghệ thuật sân khấu truyền thống của người Khmer',
                'excerpt' => 'Hát Dù Kê là một loại hình nghệ thuật sân khấu dân gian độc đáo của đồng bào Khmer Nam Bộ, kết hợp giữa hát, múa và diễn xuất.',
                'content_html' => '<h2>Giới thiệu về Hát Dù Kê</h2><p>Hát Dù Kê là một loại hình nghệ thuật sân khấu truyền thống của người Khmer ở Nam Bộ, đặc biệt phổ biến ở các tỉnh Sóc Trăng, Trà Vinh, Kiên Giang. Đây là một loại hình nghệ thuật tổng hợp, kết hợp giữa hát, múa, diễn xuất và âm nhạc.</p><h3>Đặc điểm nghệ thuật</h3><p>Hát Dù Kê thường được biểu diễn trong các dịp lễ hội, đám cưới, đám giỗ của người Khmer. Nội dung các vở diễn thường xoay quanh các câu chuyện dân gian, truyền thuyết, hoặc các tình huống đời thường mang tính giáo dục và giải trí.</p><h3>Nhạc cụ sử dụng</h3><p>Dàn nhạc Ngũ âm (Pin Peat) là nhạc cụ chính được sử dụng trong Hát Dù Kê, tạo nên âm thanh đặc trưng và sống động cho các vở diễn.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(10),
                'seo_title' => 'Hát Dù Kê - Nghệ thuật sân khấu truyền thống Khmer',
                'seo_description' => 'Tìm hiểu về Hát Dù Kê, loại hình nghệ thuật sân khấu dân gian độc đáo của đồng bào Khmer Nam Bộ.',
            ],
            [
                'title' => 'Hát Rô Băm - Vở kịch múa cổ điển của người Khmer',
                'excerpt' => 'Rô Băm là loại hình nghệ thuật sân khấu cổ điển của người Khmer, kết hợp giữa múa cổ điển, hát và diễn xuất.',
                'content_html' => '<h2>Rô Băm - Nghệ thuật sân khấu cổ điển</h2><p>Rô Băm là một loại hình nghệ thuật sân khấu cổ điển của người Khmer, có nguồn gốc từ Campuchia và được du nhập vào Việt Nam. Đây là loại hình nghệ thuật cao cấp, đòi hỏi kỹ thuật biểu diễn tinh tế và công phu.</p><h3>Đặc điểm</h3><p>Rô Băm thường được biểu diễn trong các dịp lễ hội lớn, đặc biệt là lễ hội Chol Chnam Thmay. Các vở diễn thường dựa trên các câu chuyện từ sử thi Ramayana hoặc các truyền thuyết dân gian.</p><h3>Trang phục và đạo cụ</h3><p>Trang phục trong Rô Băm rất công phu và đẹp mắt, với các màu sắc rực rỡ, kết hợp với mặt nạ và đạo cụ đặc trưng.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(8),
                'seo_title' => 'Hát Rô Băm - Vở kịch múa cổ điển Khmer',
                'seo_description' => 'Khám phá Rô Băm, loại hình nghệ thuật sân khấu cổ điển độc đáo của người Khmer.',
            ],
            [
                'title' => 'Hát Chầm Riêng Chà Pây - Độc tấu đàn Chà Pây của người Khmer',
                'excerpt' => 'Chầm Riêng Chà Pây là hình thức độc tấu đàn Chà Pây kết hợp với hát, một loại hình nghệ thuật độc đáo của người Khmer.',
                'content_html' => '<h2>Chầm Riêng Chà Pây</h2><p>Chầm Riêng Chà Pây là một loại hình nghệ thuật độc đáo của người Khmer, trong đó nghệ sĩ vừa đàn vừa hát. Đàn Chà Pây là một nhạc cụ dây truyền thống có 2-3 dây, được làm từ gỗ và da trăn.</p><h3>Kỹ thuật biểu diễn</h3><p>Nghệ sĩ Chầm Riêng Chà Pây phải có khả năng vừa đàn vừa hát, tạo nên một màn biểu diễn độc đáo và hấp dẫn. Các bài hát thường kể về cuộc sống, tình yêu, hoặc các câu chuyện dân gian.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(6),
                'seo_title' => 'Chầm Riêng Chà Pây - Độc tấu đàn truyền thống Khmer',
                'seo_description' => 'Tìm hiểu về Chầm Riêng Chà Pây, loại hình nghệ thuật độc tấu đàn độc đáo của người Khmer.',
            ],
            // Posts về Dân vũ Khmer
            [
                'title' => 'Múa Lâm Thôn - Điệu múa truyền thống của người Khmer',
                'excerpt' => 'Múa Lâm Thôn là một trong những điệu múa truyền thống nổi tiếng của người Khmer, thường được biểu diễn trong các lễ hội.',
                'content_html' => '<h2>Múa Lâm Thôn</h2><p>Múa Lâm Thôn là một điệu múa truyền thống của người Khmer, có nguồn gốc từ Campuchia. Điệu múa này thường được biểu diễn bởi các vũ công nữ, với các động tác uyển chuyển, mềm mại và đầy tính nghệ thuật.</p><h3>Đặc điểm</h3><p>Múa Lâm Thôn được đặc trưng bởi các động tác tay và chân rất tinh tế, kết hợp với trang phục truyền thống rực rỡ. Điệu múa thường được biểu diễn cùng với dàn nhạc Ngũ âm.</p><h3>Ý nghĩa văn hóa</h3><p>Múa Lâm Thôn không chỉ là một loại hình nghệ thuật mà còn mang ý nghĩa tâm linh, thường được biểu diễn trong các nghi lễ tôn giáo và lễ hội truyền thống.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'seo_title' => 'Múa Lâm Thôn - Điệu múa truyền thống Khmer',
                'seo_description' => 'Khám phá điệu múa Lâm Thôn, một trong những điệu múa truyền thống nổi tiếng của người Khmer.',
            ],
            [
                'title' => 'Múa Rom Vong - Điệu múa vòng tròn của người Khmer',
                'excerpt' => 'Rom Vong là điệu múa vòng tròn phổ biến của người Khmer, thường được biểu diễn trong các lễ hội và dịp vui.',
                'content_html' => '<h2>Múa Rom Vong</h2><p>Rom Vong là một điệu múa vòng tròn truyền thống của người Khmer, rất phổ biến trong cộng đồng. Điệu múa này thường được biểu diễn trong các lễ hội, đám cưới, và các dịp vui của người Khmer.</p><h3>Cách biểu diễn</h3><p>Người tham gia đứng thành vòng tròn, di chuyển theo nhịp nhạc với các động tác tay và chân nhẹ nhàng, uyển chuyển. Điệu múa tạo nên không khí vui tươi, đoàn kết trong cộng đồng.</p><h3>Nhạc cụ</h3><p>Rom Vong thường được biểu diễn cùng với dàn nhạc Ngũ âm hoặc các nhạc cụ truyền thống khác, tạo nên âm thanh sống động và vui tươi.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(4),
                'seo_title' => 'Múa Rom Vong - Điệu múa vòng tròn Khmer',
                'seo_description' => 'Tìm hiểu về điệu múa Rom Vong, điệu múa vòng tròn phổ biến của người Khmer.',
            ],
            [
                'title' => 'Múa trống Chhay Dăm - Điệu múa nam tính của người Khmer',
                'excerpt' => 'Múa trống Chhay Dăm là điệu múa nam truyền thống của người Khmer, thể hiện sức mạnh và tinh thần thượng võ.',
                'content_html' => '<h2>Múa trống Chhay Dăm</h2><p>Múa trống Chhay Dăm là một điệu múa nam truyền thống của người Khmer, thường được biểu diễn bởi các vũ công nam. Điệu múa này thể hiện sức mạnh, sự dũng cảm và tinh thần thượng võ.</p><h3>Đặc điểm</h3><p>Vũ công vừa múa vừa đánh trống, tạo nên nhịp điệu mạnh mẽ và hấp dẫn. Các động tác múa kết hợp với tiếng trống tạo nên một màn biểu diễn đầy năng lượng và ấn tượng.</p><h3>Ý nghĩa</h3><p>Múa trống Chhay Dăm thường được biểu diễn trong các lễ hội lớn, đặc biệt là lễ hội Chol Chnam Thmay, thể hiện tinh thần đoàn kết và sức mạnh của cộng đồng.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'seo_title' => 'Múa trống Chhay Dăm - Điệu múa nam Khmer',
                'seo_description' => 'Khám phá điệu múa trống Chhay Dăm, điệu múa nam truyền thống đầy sức mạnh của người Khmer.',
            ],
            // Posts về Dân nhạc Khmer
            [
                'title' => 'Dàn nhạc Ngũ âm (Pin Peat) - Linh hồn của âm nhạc Khmer',
                'excerpt' => 'Dàn nhạc Ngũ âm (Pin Peat) là dàn nhạc truyền thống quan trọng nhất của người Khmer, được sử dụng trong các nghi lễ và lễ hội.',
                'content_html' => '<h2>Dàn nhạc Ngũ âm (Pin Peat)</h2><p>Dàn nhạc Ngũ âm, hay còn gọi là Pin Peat, là dàn nhạc truyền thống quan trọng nhất của người Khmer. Dàn nhạc này được sử dụng trong các nghi lễ tôn giáo, lễ hội, và các buổi biểu diễn nghệ thuật.</p><h3>Thành phần dàn nhạc</h3><p>Dàn nhạc Ngũ âm bao gồm các nhạc cụ chính như: Roneat (đàn gõ bằng tre), Skor Thom (trống lớn), Chhing (chũm chọe), Sralai (kèn), và các nhạc cụ khác.</p><h3>Vai trò</h3><p>Dàn nhạc Ngũ âm không chỉ đơn thuần là nhạc cụ mà còn mang ý nghĩa tâm linh sâu sắc, là cầu nối giữa con người với thần linh trong các nghi lễ tôn giáo.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'seo_title' => 'Dàn nhạc Ngũ âm Pin Peat - Âm nhạc truyền thống Khmer',
                'seo_description' => 'Tìm hiểu về dàn nhạc Ngũ âm Pin Peat, dàn nhạc truyền thống quan trọng của người Khmer.',
            ],
            // Posts về Dân ca Hoa
            [
                'title' => 'Hát Tiều - Nghệ thuật ca hát truyền thống của người Hoa',
                'excerpt' => 'Hát Tiều là một loại hình nghệ thuật ca hát truyền thống của người Hoa, đặc biệt phổ biến trong cộng đồng người Hoa ở Việt Nam.',
                'content_html' => '<h2>Hát Tiều</h2><p>Hát Tiều là một loại hình nghệ thuật ca hát truyền thống của người Hoa, có nguồn gốc từ tỉnh Quảng Đông, Trung Quốc. Loại hình này rất phổ biến trong cộng đồng người Hoa ở Việt Nam, đặc biệt là ở khu vực Chợ Lớn, TP. Hồ Chí Minh.</p><h3>Đặc điểm</h3><p>Hát Tiều thường được biểu diễn trong các dịp lễ hội, đám cưới, và các buổi sinh hoạt văn hóa của cộng đồng người Hoa. Các bài hát thường kể về cuộc sống, tình yêu, lịch sử, hoặc các câu chuyện dân gian.</p><h3>Nhạc cụ</h3><p>Hát Tiều thường được đệm bằng các nhạc cụ truyền thống như đàn Nhị, đàn Tranh, sáo, và trống, tạo nên âm thanh đặc trưng và sống động.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(9),
                'seo_title' => 'Hát Tiều - Nghệ thuật ca hát truyền thống Hoa',
                'seo_description' => 'Khám phá Hát Tiều, loại hình nghệ thuật ca hát truyền thống của người Hoa.',
            ],
            [
                'title' => 'Hát Quảng - Dòng nhạc dân gian của người Hoa Quảng Đông',
                'excerpt' => 'Hát Quảng là dòng nhạc dân gian đặc trưng của người Hoa Quảng Đông, mang đậm bản sắc văn hóa truyền thống.',
                'content_html' => '<h2>Hát Quảng</h2><p>Hát Quảng là một dòng nhạc dân gian đặc trưng của người Hoa Quảng Đông, có lịch sử lâu đời và mang đậm bản sắc văn hóa truyền thống. Dòng nhạc này rất phổ biến trong cộng đồng người Hoa ở Việt Nam.</p><h3>Đặc điểm nghệ thuật</h3><p>Hát Quảng có giai điệu đa dạng, từ những bài hát vui tươi trong lễ hội đến những bài hát trữ tình, sâu lắng. Lời ca thường được viết bằng tiếng Quảng Đông, thể hiện tình cảm và tâm tư của người dân.</p><h3>Bảo tồn và phát triển</h3><p>Hiện nay, Hát Quảng đang được các nghệ nhân và cộng đồng người Hoa nỗ lực bảo tồn và phát huy, đặc biệt là trong các dịp lễ hội truyền thống như Tết Nguyên Đán, Tết Trung Thu.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(7),
                'seo_title' => 'Hát Quảng - Dòng nhạc dân gian Hoa Quảng Đông',
                'seo_description' => 'Tìm hiểu về Hát Quảng, dòng nhạc dân gian đặc trưng của người Hoa Quảng Đông.',
            ],
            // Posts về Dân vũ Hoa
            [
                'title' => 'Múa Lân Sư Rồng - Nghệ thuật múa truyền thống của người Hoa',
                'excerpt' => 'Múa Lân Sư Rồng là một trong những loại hình nghệ thuật múa nổi tiếng nhất của người Hoa, thường được biểu diễn trong các dịp lễ hội lớn.',
                'content_html' => '<h2>Múa Lân Sư Rồng</h2><p>Múa Lân Sư Rồng là một loại hình nghệ thuật múa truyền thống đặc sắc của người Hoa, thường được biểu diễn trong các dịp lễ hội lớn như Tết Nguyên Đán, khai trương, và các sự kiện quan trọng của cộng đồng.</p><h3>Ba loại hình múa</h3><p>Múa Lân Sư Rồng bao gồm ba loại hình chính: Múa Lân (múa con lân), Múa Sư Tử (múa sư tử), và Múa Rồng (múa rồng). Mỗi loại có ý nghĩa và cách biểu diễn riêng.</p><h3>Ý nghĩa văn hóa</h3><p>Múa Lân Sư Rồng không chỉ là một loại hình nghệ thuật mà còn mang ý nghĩa tâm linh sâu sắc, được cho là mang lại may mắn, tài lộc và xua đuổi tà ma. Điệu múa thường được biểu diễn cùng với tiếng trống, chiêng và pháo, tạo nên không khí sôi động và trang trọng.</p><h3>Kỹ thuật biểu diễn</h3><p>Múa Lân Sư Rồng đòi hỏi kỹ thuật cao, sự phối hợp nhịp nhàng giữa các vũ công, và sức mạnh thể chất. Các vũ công phải luyện tập công phu để có thể biểu diễn các động tác phức tạp và ấn tượng.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(1),
                'seo_title' => 'Múa Lân Sư Rồng - Nghệ thuật múa truyền thống Hoa',
                'seo_description' => 'Khám phá Múa Lân Sư Rồng, loại hình nghệ thuật múa nổi tiếng của người Hoa.',
            ],
            [
                'title' => 'Múa Rồng - Biểu tượng của sức mạnh và may mắn',
                'excerpt' => 'Múa Rồng là một trong những điệu múa quan trọng nhất của người Hoa, biểu tượng cho sức mạnh, quyền uy và may mắn.',
                'content_html' => '<h2>Múa Rồng</h2><p>Múa Rồng là một điệu múa truyền thống quan trọng của người Hoa, thường được biểu diễn trong các dịp lễ hội lớn, đặc biệt là Tết Nguyên Đán. Con rồng trong văn hóa Hoa là biểu tượng của sức mạnh, quyền uy, và may mắn.</p><h3>Cách biểu diễn</h3><p>Múa Rồng thường được biểu diễn bởi một nhóm vũ công, mỗi người cầm một phần của con rồng dài. Các vũ công phải phối hợp nhịp nhàng để tạo nên các động tác uyển chuyển, sống động như một con rồng thật đang bay lượn.</p><h3>Trang phục và đạo cụ</h3><p>Con rồng được làm từ vải, giấy, hoặc các vật liệu khác, với màu sắc rực rỡ, thường là màu vàng hoặc đỏ - những màu tượng trưng cho may mắn và thịnh vượng trong văn hóa Hoa.</p>',
                'status' => 'published',
                'published_at' => now(),
                'seo_title' => 'Múa Rồng - Biểu tượng may mắn của người Hoa',
                'seo_description' => 'Tìm hiểu về Múa Rồng, điệu múa truyền thống biểu tượng cho sức mạnh và may mắn của người Hoa.',
            ],
            // Posts về Dân nhạc Hoa
            [
                'title' => 'Đàn Nhị - Nhạc cụ dây truyền thống của người Hoa',
                'excerpt' => 'Đàn Nhị là một trong những nhạc cụ dây quan trọng nhất của người Hoa, được sử dụng rộng rãi trong các buổi biểu diễn dân ca và dân nhạc.',
                'content_html' => '<h2>Đàn Nhị</h2><p>Đàn Nhị, hay còn gọi là Erhu, là một nhạc cụ dây truyền thống của người Hoa, có lịch sử hơn 1000 năm. Đàn có 2 dây, được kéo bằng cung vĩ, tạo ra âm thanh trầm ấm, sâu lắng và đầy cảm xúc.</p><h3>Đặc điểm kỹ thuật</h3><p>Đàn Nhị có cấu tạo đơn giản nhưng kỹ thuật chơi rất tinh tế. Người chơi phải có kỹ thuật cao để có thể tạo ra các âm thanh biểu cảm, từ những nốt nhạc trầm buồn đến những nốt nhạc vui tươi, sống động.</p><h3>Vai trò trong âm nhạc</h3><p>Đàn Nhị được sử dụng rộng rãi trong các buổi biểu diễn dân ca, dân nhạc, và cả trong các dàn nhạc hiện đại. Đàn có thể đệm cho hát, độc tấu, hoặc hòa tấu với các nhạc cụ khác.</p>',
                'status' => 'published',
                'published_at' => now()->subHours(12),
                'seo_title' => 'Đàn Nhị - Nhạc cụ dây truyền thống Hoa',
                'seo_description' => 'Khám phá Đàn Nhị, nhạc cụ dây truyền thống quan trọng của người Hoa.',
            ],
            [
                'title' => 'Đàn Tranh - Nhạc cụ gõ dây của người Hoa',
                'excerpt' => 'Đàn Tranh là nhạc cụ gõ dây truyền thống của người Hoa, tạo ra âm thanh trong trẻo, thanh thoát.',
                'content_html' => '<h2>Đàn Tranh</h2><p>Đàn Tranh, hay còn gọi là Guzheng, là một nhạc cụ gõ dây truyền thống của người Hoa, có lịch sử hơn 2500 năm. Đàn có từ 16 đến 25 dây, được gõ bằng các ngón tay hoặc móng gảy.</p><h3>Âm thanh đặc trưng</h3><p>Đàn Tranh tạo ra âm thanh trong trẻo, thanh thoát, có thể diễn tả nhiều cung bậc cảm xúc khác nhau. Âm thanh của đàn thường được ví như tiếng suối chảy, tiếng gió thổi qua rừng tre.</p><h3>Sử dụng</h3><p>Đàn Tranh được sử dụng trong các buổi biểu diễn dân ca, dân nhạc, và cả trong các dàn nhạc hiện đại. Đàn có thể độc tấu, đệm cho hát, hoặc hòa tấu với các nhạc cụ khác.</p>',
                'status' => 'published',
                'published_at' => now()->subHours(6),
                'seo_title' => 'Đàn Tranh - Nhạc cụ gõ dây truyền thống Hoa',
                'seo_description' => 'Tìm hiểu về Đàn Tranh, nhạc cụ gõ dây truyền thống của người Hoa.',
            ],
            // Posts về Lễ hội
            [
                'title' => 'Lễ hội Chol Chnam Thmay - Năm mới của người Khmer',
                'excerpt' => 'Chol Chnam Thmay là lễ hội năm mới quan trọng nhất của người Khmer, kéo dài 3 ngày với nhiều hoạt động văn hóa đặc sắc.',
                'content_html' => '<h2>Lễ hội Chol Chnam Thmay</h2><p>Chol Chnam Thmay là lễ hội năm mới truyền thống của người Khmer, được tổ chức vào tháng 4 dương lịch hàng năm. Đây là lễ hội quan trọng nhất trong năm, kéo dài 3 ngày với nhiều hoạt động văn hóa, tôn giáo đặc sắc.</p><h3>Ngày thứ nhất - Sângkran</h3><p>Ngày đầu tiên, người Khmer dọn dẹp nhà cửa, chuẩn bị đồ cúng, và đi chùa để cầu nguyện, dâng lễ vật cho các nhà sư.</p><h3>Ngày thứ hai - Vanabat</h3><p>Ngày thứ hai, mọi người làm từ thiện, giúp đỡ người nghèo, và tổ chức các hoạt động văn hóa như múa, hát, biểu diễn nghệ thuật.</p><h3>Ngày thứ ba - Laeung Sak</h3><p>Ngày cuối cùng, người Khmer tắm tượng Phật, rửa tay cho các nhà sư, và cầu nguyện cho một năm mới tốt lành, may mắn.</p><h3>Hoạt động văn hóa</h3><p>Trong lễ hội, có nhiều hoạt động văn hóa như múa Lâm Thôn, múa Rom Vong, biểu diễn Hát Dù Kê, và các trò chơi dân gian truyền thống.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(11),
                'seo_title' => 'Lễ hội Chol Chnam Thmay - Năm mới Khmer',
                'seo_description' => 'Tìm hiểu về lễ hội Chol Chnam Thmay, lễ hội năm mới quan trọng nhất của người Khmer.',
            ],
            [
                'title' => 'Tết Nguyên Đán của người Hoa - Lễ hội truyền thống lớn nhất',
                'excerpt' => 'Tết Nguyên Đán là lễ hội truyền thống lớn nhất và quan trọng nhất của người Hoa, với nhiều phong tục và hoạt động văn hóa đặc sắc.',
                'content_html' => '<h2>Tết Nguyên Đán của người Hoa</h2><p>Tết Nguyên Đán, hay còn gọi là Tết Xuân, là lễ hội truyền thống lớn nhất và quan trọng nhất của người Hoa. Lễ hội này kéo dài 15 ngày, từ ngày mùng 1 đến ngày 15 tháng Giêng âm lịch, với nhiều phong tục và hoạt động văn hóa đặc sắc.</p><h3>Chuẩn bị Tết</h3><p>Trước Tết, người Hoa dọn dẹp nhà cửa, mua sắm đồ Tết, chuẩn bị các món ăn truyền thống, và trang trí nhà cửa bằng đèn lồng đỏ, câu đối, và các biểu tượng may mắn.</p><h3>Hoạt động trong Tết</h3><p>Trong những ngày Tết, người Hoa đi chùa cầu nguyện, thăm hỏi người thân, bạn bè, và tổ chức các hoạt động văn hóa như múa Lân Sư Rồng, biểu diễn Hát Tiều, và các trò chơi dân gian.</p><h3>Ý nghĩa</h3><p>Tết Nguyên Đán không chỉ là dịp để sum họp gia đình mà còn là thời điểm để cầu nguyện cho một năm mới tốt lành, thịnh vượng, và may mắn.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(12),
                'seo_title' => 'Tết Nguyên Đán - Lễ hội truyền thống của người Hoa',
                'seo_description' => 'Khám phá Tết Nguyên Đán, lễ hội truyền thống lớn nhất của người Hoa.',
            ],
        ];

        foreach ($posts as $postData) {
            $slug = Str::slug($postData['title']);
            Post::query()->firstOrCreate(
                ['slug' => $slug],
                array_merge($postData, ['author_id' => $authorId])
            );
        }
    }
}


<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\ImageIntros\Models\ImageIntro;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ImageIntroSeeder extends Seeder
{
    public function run(): void
    {
        $imageIntros = [
            [
                'title' => 'Di sản Văn hóa Dân ca Khmer Nam Bộ',
                'slug' => 'di-san-van-hoa-dan-ca-khmer-nam-bo',
                'summary' => 'Khám phá các loại hình dân ca truyền thống độc đáo của đồng bào Khmer ở Nam Bộ, từ Hát Dù Kê, Hát Rô Băm đến Chầm Riêng Chà Pây.',
                'content_html' => '<h2>Di sản Văn hóa Dân ca Khmer Nam Bộ</h2><p>Dân ca Khmer Nam Bộ là một phần quan trọng trong kho tàng văn hóa dân gian của Việt Nam, thể hiện đậm nét bản sắc văn hóa của đồng bào Khmer. Các loại hình dân ca này không chỉ là nghệ thuật biểu diễn mà còn là phương tiện lưu truyền văn hóa, giáo dục và gắn kết cộng đồng.</p><h3>Hát Dù Kê</h3><p>Hát Dù Kê là loại hình nghệ thuật sân khấu dân gian độc đáo, kết hợp giữa hát, múa và diễn xuất. Thường được biểu diễn trong các dịp lễ hội, đám cưới, đám giỗ của người Khmer.</p><h3>Hát Rô Băm</h3><p>Rô Băm là loại hình nghệ thuật sân khấu cổ điển, có nguồn gốc từ Campuchia. Các vở diễn thường dựa trên sử thi Ramayana hoặc truyền thuyết dân gian.</p><h3>Chầm Riêng Chà Pây</h3><p>Chầm Riêng Chà Pây là hình thức độc tấu đàn Chà Pây kết hợp với hát, một loại hình nghệ thuật độc đáo của người Khmer.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(20),
            ],
            [
                'title' => 'Nghệ thuật Dân vũ Khmer - Từ Lâm Thôn đến Rom Vong',
                'slug' => 'nghe-thuat-dan-vu-khmer',
                'summary' => 'Tìm hiểu về các điệu múa truyền thống của người Khmer như múa Lâm Thôn, múa Rom Vong, múa trống Chhay Dăm và ý nghĩa văn hóa của chúng.',
                'content_html' => '<h2>Nghệ thuật Dân vũ Khmer</h2><p>Dân vũ Khmer là một phần không thể thiếu trong đời sống văn hóa tinh thần của đồng bào Khmer Nam Bộ. Các điệu múa này không chỉ đẹp mắt mà còn mang ý nghĩa tâm linh sâu sắc.</p><h3>Múa Lâm Thôn</h3><p>Múa Lâm Thôn là điệu múa truyền thống nổi tiếng, thường được biểu diễn bởi các vũ công nữ với các động tác uyển chuyển, mềm mại và đầy tính nghệ thuật.</p><h3>Múa Rom Vong</h3><p>Rom Vong là điệu múa vòng tròn phổ biến, tạo nên không khí vui tươi, đoàn kết trong cộng đồng. Thường được biểu diễn trong các lễ hội và dịp vui.</p><h3>Múa trống Chhay Dăm</h3><p>Múa trống Chhay Dăm là điệu múa nam truyền thống, thể hiện sức mạnh, sự dũng cảm và tinh thần thượng võ của người Khmer.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(18),
            ],
            [
                'title' => 'Dàn nhạc Ngũ âm Pin Peat - Linh hồn của âm nhạc Khmer',
                'slug' => 'dan-nhac-ngu-am-pin-peat',
                'summary' => 'Khám phá dàn nhạc Ngũ âm Pin Peat, dàn nhạc truyền thống quan trọng nhất của người Khmer, được sử dụng trong các nghi lễ và lễ hội.',
                'content_html' => '<h2>Dàn nhạc Ngũ âm Pin Peat</h2><p>Dàn nhạc Ngũ âm, hay còn gọi là Pin Peat, là dàn nhạc truyền thống quan trọng nhất của người Khmer. Dàn nhạc này không chỉ đơn thuần là nhạc cụ mà còn mang ý nghĩa tâm linh sâu sắc, là cầu nối giữa con người với thần linh trong các nghi lễ tôn giáo.</p><h3>Thành phần dàn nhạc</h3><p>Dàn nhạc Ngũ âm bao gồm các nhạc cụ chính như: Roneat (đàn gõ bằng tre), Skor Thom (trống lớn), Chhing (chũm chọe), Sralai (kèn), và các nhạc cụ khác. Mỗi nhạc cụ có vai trò riêng trong việc tạo nên âm thanh đặc trưng của dàn nhạc.</p><h3>Vai trò trong đời sống văn hóa</h3><p>Dàn nhạc Ngũ âm được sử dụng trong các nghi lễ tôn giáo, lễ hội, và các buổi biểu diễn nghệ thuật. Âm thanh của dàn nhạc tạo nên không khí trang trọng, linh thiêng và sống động.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(16),
            ],
            [
                'title' => 'Văn hóa Dân ca và Dân nhạc của người Hoa',
                'slug' => 'van-hoa-dan-ca-dan-nhac-nguoi-hoa',
                'summary' => 'Tìm hiểu về các loại hình dân ca và dân nhạc truyền thống của người Hoa như Hát Tiều, Hát Quảng, và các nhạc cụ đặc trưng.',
                'content_html' => '<h2>Văn hóa Dân ca và Dân nhạc của người Hoa</h2><p>Người Hoa ở Việt Nam đã mang theo và bảo tồn nhiều loại hình dân ca, dân nhạc truyền thống từ quê hương, tạo nên một nét văn hóa đặc sắc trong cộng đồng đa dân tộc của Việt Nam.</p><h3>Hát Tiều</h3><p>Hát Tiều là loại hình nghệ thuật ca hát truyền thống của người Hoa, đặc biệt phổ biến trong cộng đồng người Hoa ở khu vực Chợ Lớn, TP. Hồ Chí Minh. Các bài hát thường kể về cuộc sống, tình yêu, lịch sử, hoặc các câu chuyện dân gian.</p><h3>Hát Quảng</h3><p>Hát Quảng là dòng nhạc dân gian đặc trưng của người Hoa Quảng Đông, có giai điệu đa dạng từ những bài hát vui tươi trong lễ hội đến những bài hát trữ tình, sâu lắng.</p><h3>Nhạc cụ truyền thống</h3><p>Các nhạc cụ như Đàn Nhị, Đàn Tranh, sáo, và trống được sử dụng rộng rãi trong các buổi biểu diễn dân ca và dân nhạc của người Hoa.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(14),
            ],
            [
                'title' => 'Múa Lân Sư Rồng - Nghệ thuật múa truyền thống của người Hoa',
                'slug' => 'mua-lan-su-rong-nguoi-hoa',
                'summary' => 'Khám phá nghệ thuật múa Lân Sư Rồng, một trong những loại hình nghệ thuật múa nổi tiếng nhất của người Hoa, thường được biểu diễn trong các dịp lễ hội lớn.',
                'content_html' => '<h2>Múa Lân Sư Rồng</h2><p>Múa Lân Sư Rồng là một loại hình nghệ thuật múa truyền thống đặc sắc của người Hoa, thường được biểu diễn trong các dịp lễ hội lớn như Tết Nguyên Đán, khai trương, và các sự kiện quan trọng của cộng đồng.</p><h3>Ba loại hình múa</h3><p>Múa Lân Sư Rồng bao gồm ba loại hình chính: Múa Lân (múa con lân), Múa Sư Tử (múa sư tử), và Múa Rồng (múa rồng). Mỗi loại có ý nghĩa và cách biểu diễn riêng, nhưng đều mang ý nghĩa tâm linh sâu sắc.</p><h3>Ý nghĩa văn hóa</h3><p>Múa Lân Sư Rồng được cho là mang lại may mắn, tài lộc và xua đuổi tà ma. Điệu múa thường được biểu diễn cùng với tiếng trống, chiêng và pháo, tạo nên không khí sôi động và trang trọng.</p><h3>Kỹ thuật biểu diễn</h3><p>Múa Lân Sư Rồng đòi hỏi kỹ thuật cao, sự phối hợp nhịp nhàng giữa các vũ công, và sức mạnh thể chất. Các vũ công phải luyện tập công phu để có thể biểu diễn các động tác phức tạp và ấn tượng.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(12),
            ],
            [
                'title' => 'Lễ hội Chol Chnam Thmay - Năm mới của người Khmer',
                'slug' => 'le-hoi-chol-chnam-thmay',
                'summary' => 'Tìm hiểu về lễ hội Chol Chnam Thmay, lễ hội năm mới quan trọng nhất của người Khmer, với nhiều hoạt động văn hóa và tôn giáo đặc sắc.',
                'content_html' => '<h2>Lễ hội Chol Chnam Thmay</h2><p>Chol Chnam Thmay là lễ hội năm mới truyền thống của người Khmer, được tổ chức vào tháng 4 dương lịch hàng năm. Đây là lễ hội quan trọng nhất trong năm, kéo dài 3 ngày với nhiều hoạt động văn hóa, tôn giáo đặc sắc.</p><h3>Ngày thứ nhất - Sângkran</h3><p>Ngày đầu tiên, người Khmer dọn dẹp nhà cửa, chuẩn bị đồ cúng, và đi chùa để cầu nguyện, dâng lễ vật cho các nhà sư. Đây là thời điểm để bắt đầu một năm mới với tâm hồn thanh tịnh.</p><h3>Ngày thứ hai - Vanabat</h3><p>Ngày thứ hai, mọi người làm từ thiện, giúp đỡ người nghèo, và tổ chức các hoạt động văn hóa như múa, hát, biểu diễn nghệ thuật. Đây là dịp để thể hiện tinh thần tương thân tương ái trong cộng đồng.</p><h3>Ngày thứ ba - Laeung Sak</h3><p>Ngày cuối cùng, người Khmer tắm tượng Phật, rửa tay cho các nhà sư, và cầu nguyện cho một năm mới tốt lành, may mắn. Đây là thời điểm để kết thúc lễ hội với những lời cầu nguyện tốt đẹp.</p><h3>Hoạt động văn hóa</h3><p>Trong lễ hội, có nhiều hoạt động văn hóa như múa Lâm Thôn, múa Rom Vong, biểu diễn Hát Dù Kê, và các trò chơi dân gian truyền thống, tạo nên không khí vui tươi, đoàn kết trong cộng đồng.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Tết Nguyên Đán của người Hoa - Lễ hội truyền thống lớn nhất',
                'slug' => 'tet-nguyen-dan-nguoi-hoa',
                'summary' => 'Khám phá Tết Nguyên Đán, lễ hội truyền thống lớn nhất và quan trọng nhất của người Hoa, với nhiều phong tục và hoạt động văn hóa đặc sắc.',
                'content_html' => '<h2>Tết Nguyên Đán của người Hoa</h2><p>Tết Nguyên Đán, hay còn gọi là Tết Xuân, là lễ hội truyền thống lớn nhất và quan trọng nhất của người Hoa. Lễ hội này kéo dài 15 ngày, từ ngày mùng 1 đến ngày 15 tháng Giêng âm lịch, với nhiều phong tục và hoạt động văn hóa đặc sắc.</p><h3>Chuẩn bị Tết</h3><p>Trước Tết, người Hoa dọn dẹp nhà cửa, mua sắm đồ Tết, chuẩn bị các món ăn truyền thống, và trang trí nhà cửa bằng đèn lồng đỏ, câu đối, và các biểu tượng may mắn. Màu đỏ là màu chủ đạo, tượng trưng cho may mắn và thịnh vượng.</p><h3>Hoạt động trong Tết</h3><p>Trong những ngày Tết, người Hoa đi chùa cầu nguyện, thăm hỏi người thân, bạn bè, và tổ chức các hoạt động văn hóa như múa Lân Sư Rồng, biểu diễn Hát Tiều, và các trò chơi dân gian. Đây là dịp để sum họp gia đình và gắn kết cộng đồng.</p><h3>Ý nghĩa</h3><p>Tết Nguyên Đán không chỉ là dịp để sum họp gia đình mà còn là thời điểm để cầu nguyện cho một năm mới tốt lành, thịnh vượng, và may mắn. Đây là dịp để mọi người quên đi những lo toan của năm cũ và đón chào một năm mới đầy hy vọng.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(8),
            ],
            [
                'title' => 'Bảo tồn và Phát huy Di sản Văn hóa Dân tộc',
                'slug' => 'bao-ton-va-phat-huy-di-san-van-hoa',
                'summary' => 'Tìm hiểu về các biện pháp bảo tồn và phát huy di sản văn hóa dân tộc, đặc biệt là các loại hình nghệ thuật dân gian của người Khmer và người Hoa.',
                'content_html' => '<h2>Bảo tồn và Phát huy Di sản Văn hóa Dân tộc</h2><p>Di sản văn hóa dân tộc là tài sản quý giá của mỗi dân tộc, cần được bảo tồn và phát huy để các thế hệ sau có thể tiếp tục thừa hưởng và phát triển. Đặc biệt, các loại hình nghệ thuật dân gian của người Khmer và người Hoa đang đối mặt với nhiều thách thức trong việc bảo tồn.</p><h3>Thách thức</h3><p>Việc bảo tồn các loại hình nghệ thuật dân gian đang gặp nhiều thách thức như: thiếu nghệ nhân kế thừa, giới trẻ ít quan tâm, thiếu kinh phí, và ảnh hưởng của văn hóa hiện đại. Cần có các biện pháp cụ thể để giải quyết những thách thức này.</p><h3>Giải pháp</h3><p>Các giải pháp bao gồm: đào tạo nghệ nhân trẻ, tổ chức các lớp học truyền thống, ghi chép và lưu trữ tài liệu, tổ chức các lễ hội và hoạt động văn hóa, và tăng cường giáo dục về văn hóa dân tộc trong trường học.</p><h3>Vai trò của cộng đồng</h3><p>Cộng đồng đóng vai trò quan trọng trong việc bảo tồn và phát huy di sản văn hóa. Cần có sự tham gia tích cực của các nghệ nhân, các tổ chức văn hóa, và toàn thể cộng đồng để đảm bảo các di sản văn hóa được bảo tồn và phát huy một cách hiệu quả.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(6),
            ],
        ];

        foreach ($imageIntros as $introData) {
            $slug = $introData['slug'] ?? Str::slug($introData['title']);
            ImageIntro::query()->firstOrCreate(
                ['slug' => $slug],
                $introData
            );
        }
    }
}


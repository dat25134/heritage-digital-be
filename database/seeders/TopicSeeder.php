<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Topics\Models\Topic;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            // Topics về dân tộc Khmer
            [
                'name' => 'Dân ca Khmer',
                'description' => 'Các bài hát dân ca truyền thống của đồng bào dân tộc Khmer, bao gồm các thể loại như hát Dù Kê, hát Rô Băm, hát Chầm Riêng Chà Pây, và các bài hát trong lễ hội Chol Chnam Thmay.',
            ],
            [
                'name' => 'Dân vũ Khmer',
                'description' => 'Các điệu múa truyền thống của người Khmer như múa Lâm Thôn, múa Salavan, múa Rom Vong, múa Rom Leo, múa Rom Kbach, múa trống Chhay Dăm, và các điệu múa trong nghi lễ tôn giáo.',
            ],
            [
                'name' => 'Dân nhạc Khmer',
                'description' => 'Nhạc cụ và âm nhạc truyền thống của người Khmer, bao gồm dàn nhạc Ngũ âm (Pin Peat), các nhạc cụ như Roneat, Skor Thom, Chhing, và nhạc cưới truyền thống.',
            ],
            [
                'name' => 'Lễ hội và Nghi lễ Khmer',
                'description' => 'Các lễ hội truyền thống của người Khmer như Chol Chnam Thmay (Năm mới), Ok Om Bok (Lễ cúng trăng), Sen Dolta (Lễ cúng ông bà), và các nghi lễ tôn giáo Phật giáo Nam tông.',
            ],
            // Topics về dân tộc Hoa
            [
                'name' => 'Dân ca Hoa',
                'description' => 'Các bài hát dân ca truyền thống của đồng bào dân tộc Hoa, bao gồm hát Tiều, hát Quảng, hát Triều Châu, và các bài hát trong lễ hội Tết Nguyên Đán, Tết Trung Thu.',
            ],
            [
                'name' => 'Dân vũ Hoa',
                'description' => 'Các điệu múa truyền thống của người Hoa như múa Lân Sư Rồng, múa Rồng, múa Sư Tử, múa Quạt, và các điệu múa trong lễ hội văn hóa.',
            ],
            [
                'name' => 'Dân nhạc Hoa',
                'description' => 'Nhạc cụ và âm nhạc truyền thống của người Hoa, bao gồm đàn Nhị, đàn Tranh, đàn Tỳ Bà, sáo, trống, và các dàn nhạc dân tộc trong các buổi biểu diễn.',
            ],
            [
                'name' => 'Lễ hội và Văn hóa Hoa',
                'description' => 'Các lễ hội truyền thống của người Hoa như Tết Nguyên Đán, Tết Trung Thu, Lễ hội Quan Công, và các phong tục tập quán văn hóa đặc trưng.',
            ],
            // Topics chung
            [
                'name' => 'Bảo tồn Di sản Văn hóa',
                'description' => 'Các hoạt động bảo tồn, phát huy và truyền dạy di sản văn hóa dân ca, dân vũ, dân nhạc truyền thống của các dân tộc thiểu số.',
            ],
            [
                'name' => 'Nghệ nhân và Nghệ sĩ',
                'description' => 'Thông tin về các nghệ nhân, nghệ sĩ biểu diễn và những người gìn giữ di sản văn hóa truyền thống của các dân tộc Khmer và Hoa.',
            ],
        ];

        foreach ($topics as $topicData) {
            Topic::query()->firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($topicData['name'])],
                $topicData
            );
        }
    }
}


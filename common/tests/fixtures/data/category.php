<?php

use common\models\constants\StatusConstant;

return [
    [
        'id' => '1',
        'name' => "Information technology",
        'short_description' => 'Information technology (IT) is the use of computers to create, process, store, retrieve and exchange all kinds of data and information.',
        'description' => 'Information technology (IT) is the use of computers to create, process, store, retrieve and exchange all kinds of data and information. IT forms part of information and communications technology (ICT). An information technology system (IT system) is generally an information system, a communications system, or, more specifically speaking, a computer system — including all hardware, software, and peripheral equipment — operated by a limited group of IT users.',
        'status' => StatusConstant::ACTIVE,
        'image' => 'D:/FGW/Final/Crush/Forum/backend/web/uploads/categories/category_no_image/no-image.png',
        'moderatorId' => null,
        'created_at' => new \yii\db\Expression('NOW()'),
        'created_by' => '1',
        'updated_at' => new \yii\db\Expression('NOW()'),
        'updated_by' => '1',
    ],
    [
        'id' => '2',
        'name' => "Graphic Design",
        'short_description' => 'Graphic design is a profession, academic discipline and applied art whose activity consists in projecting visual communications intended to transmit specific messages to social groups, with specific objectives.',
        'description' => 'Graphic design is a profession, academic discipline and applied art whose activity consists in projecting visual communications intended to transmit specific messages to social groups, with specific objectives. Graphic design is an interdisciplinary branch of design and of the fine arts. Its practice involves creativity, innovation and lateral thinking using manual or digital tools, where it is usual to use text and graphics to communicate visually.',
        'status' => StatusConstant::ACTIVE,
        'image' => 'D:/FGW/Final/Crush/Forum/backend/web/uploads/categories/category_no_image/no-image.png',
        'moderatorId' => null,
        'created_at' => new \yii\db\Expression('NOW()'),
        'created_by' => '1',
        'updated_at' => new \yii\db\Expression('NOW()'),
        'updated_by' => '1',
    ],
    [
        'id' => '3',
        'name' => "Business Administration",
        'short_description' => 'Business administration, also known as business management, is the administration of a commercial enterprise.',
        'description' => 'Business administration, also known as business management, is the administration of a commercial enterprise. It includes all aspects of overseeing and supervising the business operations of an organization. From the point of view of management and leadership, it also covers fields that include office building administration, accounting, finance, designing, development, quality assurance, data analysis, sales, project management, information-technology management, research and development, and marketing.',
        'status' => StatusConstant::ACTIVE,
        'image' => 'D:/FGW/Final/Crush/Forum/backend/web/uploads/categories/category_no_image/no-image.png',
        'moderatorId' => null,
        'created_at' => new \yii\db\Expression('NOW()'),
        'created_by' => '1',
        'updated_at' => new \yii\db\Expression('NOW()'),
        'updated_by' => '1',
    ],
    [
        'id' => '4',
        'name' => "Other",
        'short_description' => 'Other category is used to store posts about extracurricular activities.',
        'description' => 'Other category is used to store posts about extracurricular activities, likes: Games, Clubs, Outdoor activities, etc.',
        'status' => StatusConstant::ACTIVE,
        'image' => 'D:/FGW/Final/Crush/Forum/backend/web/uploads/categories/category_no_image/no-image.png',
        'moderatorId' => null,
        'created_at' => new \yii\db\Expression('NOW()'),
        'created_by' => '1',
        'updated_at' => new \yii\db\Expression('NOW()'),
        'updated_by' => '1',
    ],
];

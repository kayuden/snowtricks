<?php

namespace App\DataFixtures;

use App\Factory\TrickFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void //symfony console doctrine:fixtures:load
    {
        $now = new \DateTimeImmutable();

        $tricks = [
            [
                'name' => 'Method Grab',
                'type' => 'Grabs',
                'description' => 'The method grab is one of the most iconic tricks in snowboarding, often considered the ultimate expression of style. To perform it, the rider launches off a jump, grabs the heel edge of the board with the front hand (usually between the bindings), and simultaneously pulls the board upward while arching the back and tweaking the legs. The board is pushed out sideways, and the rider often twists their body to open up the chest toward the sky. Unlike more technical spins or flips, the method is all about amplitude, extension, and style—making it a timeless classic that’s still a favorite in competitions, photos, and videos.',
                'createdAt' => $now,
                'editedAt' => $now,
                'imagePaths' => ['method1.jpg','method2.jpg','method3.jpg'],
                'mainImage' => 'method1.jpg',
                'videoEmbeds' => ['<iframe width="560" height="315" src="https://www.youtube.com/embed/_hxLS2ErMiY?si=qdCJXP9OOyDqihAE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'],
            ],
            [
                'name' => 'Backflip',
                'type' => 'Flips',
                'description' => 'A classic snowboard trick where the rider launches off a jump and performs a full backward somersault in the air. Balance, speed, and commitment are essential, as the rider must fully rotate before spotting the landing. The backflip is often a crowd favorite because of its clean motion and high-risk visual impact.',
                'createdAt' => $now,
                'editedAt' => $now,
                'imagePaths' => ['backflip.jpg'],
                'mainImage' => 'backflip.jpg',
                'videoEmbeds' => ['<iframe width="560" height="315" src="https://www.youtube.com/embed/ITDirbEYCT8?si=DwQniXyJUoMhl5J_" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'],
            ],
            [
                'name' => 'Mute Grab',
                'type' => 'Grabs',
                'description' => 'This grab involves reaching the front hand across the body to hold the toe edge of the snowboard between the bindings. It’s often done during spins to add style and control, making the trick look smooth and compact. The mute grab has become a staple in freestyle snowboarding.',
                'createdAt' => $now,
                'editedAt' => $now,
                'imagePaths' => ['mutegrab.jpg'],
                'mainImage' => 'mutegrab.jpg',
                'videoEmbeds' => ['<iframe width="560" height="315" src="https://www.youtube.com/embed/k6aOWf0LDcQ?si=KRkPar8SFx0vCTDN" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'],
            ],
            [
                'name' => 'Tail Grab',
                'type' => 'Grabs',
                'description' => 'Performed by grabbing the very back end of the snowboard with the rear hand while airborne. The tail grab emphasizes style, especially if the rider tweaks their back leg to poke the board out for added flair. It’s simple but timeless, showcasing control and extension.',
                'createdAt' => $now,
                'editedAt' => $now,
                'imagePaths' => ['tailgrab.jpg'],
                'mainImage' => 'tailgrab.jpg',
                'videoEmbeds' => ['<iframe width="560" height="315" src="https://www.youtube.com/embed/id8VKl9RVQw?si=MdFXb1eKL3wqe1Xu" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'],
            ],
            [
                'name' => 'Frontside 360',
                'type' => 'Spins',
                'description' => 'A spin where the rider rotates a full 360 degrees in the air while leading with their front shoulder. Landing blindside requires precision and awareness. Riders often add grabs during the spin to increase both technical difficulty and style points.',
                'createdAt' => $now,
                'editedAt' => $now,
                'imagePaths' => ['frontside360.jpg'],
                'mainImage' => 'frontside360.jpg',
                'videoEmbeds' => ['<iframe width="560" height="315" src="https://www.youtube.com/embed/dLZwlLgohFw?si=8-jxj0ndctJK5M51" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'],
            ],
            [
                'name' => 'Cab 540',
                'type' => 'Spins',
                'description' => 'This trick begins switch (riding backwards) and spins one and a half rotations (540 degrees) frontside. It’s a step up in difficulty from a 360, demanding solid switch riding skills and the ability to stay oriented in mid-air. Adding a grab can make it look even smoother.',
                'createdAt' => $now,
                'editedAt' => $now,
                'imagePaths' => ['cab540.jpg'],
                'mainImage' => 'cab540.jpg',
                'videoEmbeds' => ['<iframe width="560" height="315" src="https://www.youtube.com/embed/UrJXDLT2_Bw?si=liZOah9qWvkS42Ph" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'],
            ],
            [
                'name' => 'Misty Flip 720',
                'type' => 'Flips',
                'description' => 'A corked trick that combines a front flip with two full rotations. The rider launches off-axis, blending a flip and spin into one continuous motion. It’s visually dynamic and highlights a rider’s ability to stay balanced despite being off the snowboard’s natural axis.',
                'createdAt' => $now,
                'editedAt' => $now,
                'imagePaths' => ['misty.jpg'],
                'mainImage' => 'misty.jpg',
                'videoEmbeds' => ['<iframe width="560" height="315" src="https://www.youtube.com/embed/HEzgU_A16Gs?si=6khGZBF4KaH5CKbd" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'],
            ],
            [
                'name' => 'Nose Grab',
                'type' => 'Grabs',
                'description' => 'Here the rider uses their front hand to grab the very tip (nose) of the board. Like the tail grab, tweaking the board adds style. This grab pairs nicely with spins and flips, making it versatile and stylish in any terrain park.',
                'createdAt' => $now,
                'editedAt' => $now,
                'imagePaths' => ['nosegrab.jpg'],
                'mainImage' => 'nosegrab.jpg',
                'videoEmbeds' => ['<iframe width="560" height="315" src="https://www.youtube.com/embed/gZFWW4Vus-Q?si=4scgxnOcmfnmNDmP" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'],
            ],
            [
                'name' => '50-50',
                'type' => 'Slides',
                'description' => 'A fundamental rail trick where the rider approaches straight on and locks the snowboard flat across the feature. Both feet remain parallel to the rail, sliding smoothly until the rider dismounts. It’s usually one of the first rail tricks snowboarders learn before progressing to more complex variations.',
                'createdAt' => $now,
                'editedAt' => $now,
                'imagePaths' => ['5050.jpg'],
                'mainImage' => '5050.jpg',
                'videoEmbeds' => ['<iframe width="560" height="315" src="https://www.youtube.com/embed/j1_sA4U6840?si=P8Urulmm-1MzOSR6" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'],
            ],
            [
                'name' => 'Boardslide',
                'type' => 'Slides',
                'description' => 'In this trick, the rider turns the board perpendicular to the rail or box, sliding with the middle of the board pressed against the feature. Boardslides are stylish, and riders often add variations, like spinning out, to increase the trick’s difficulty.',
                'createdAt' => $now,
                'editedAt' => $now,
                'imagePaths' => ['boardslide.jpg'],
                'mainImage' => 'boardslide.jpg',
                'videoEmbeds' => ['<iframe width="560" height="315" src="https://www.youtube.com/embed/oJ64UOu7Sn4?si=8r_tqeA9V2m-AEkR" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'],
            ],
            [
                'name' => 'Switch Backside 180',
                'type' => 'Spins',
                'description' => 'Ridden switch, the rider spins a half rotation backside, landing in their natural stance. Though relatively simple compared to higher rotations, doing it switch adds complexity, as it tests the rider’s ability to remain smooth while starting backwards.',
                'createdAt' => $now,
                'editedAt' => $now,
                'imagePaths' => ['backside.jpg'],
                'mainImage' => 'backside.jpg',
                'videoEmbeds' => ['<iframe width="560" height="315" src="https://www.youtube.com/embed/rH1cfVY4qgc?si=iM0IRWKcWt67w9eT" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'],
            ],
        ];

        foreach ($tricks as $data) {
            TrickFactory::createOne($data);
        }
    }
}

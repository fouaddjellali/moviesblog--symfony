<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Language;
use App\Entity\Media;
use App\Entity\Movie;
use App\Entity\Playlist;
use App\Entity\PlaylistMedia;
use App\Entity\PlaylistSubscription;
use App\Entity\Season;
use App\Entity\Serie;
use App\Entity\Subscription;
use App\Entity\SubscriptionHistory;
use App\Entity\User;
use App\Enum\CommentStatusEnum;
use App\Enum\UserAccountStatusEnum;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public const MAX_PERSONS = 12;
    public const MAX_CONTENT = 70;
    public const MAX_PACKAGES = 4;
    public const MAX_SERIES = 4;
    public const MAX_PARTS = 8;

    public const COLLECTIONS_PER_PERSON = 3;
    public const MAX_CONTENT_PER_COLLECTION = 4;
    public const MAX_LANGUAGES_PER_CONTENT = 3;
    public const MAX_TOPICS_PER_CONTENT = 3;

    public function __construct(
        protected UserPasswordHasherInterface $pwdHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $people = [];
        $contents = [];
        $collections = [];
        $topics = [];
        $langs = [];
        $packages = [];

        $this->createPersons($manager, $people);
        $this->createCollections($manager, $people, $collections);
        $this->createPackages($manager, $people, $packages);
        $this->createTopics($manager, $topics);
        $this->createLanguages($manager, $langs);
        $this->createContents($manager, $contents);
        $this->createNotes($manager, $contents, $people);

        $this->linkContentsToCollections($contents, $collections, $manager);
        $this->linkPackagesToPersons($people, $packages, $manager);
        $this->linkContentsToTopics($contents, $topics);
        $this->linkContentsToLanguages($contents, $langs);

        $manager->flush();
    }

    protected function createPackages(ObjectManager $manager, array $people, array &$packages): void
    {
        $options = [
            ['name' => 'Pack 1 mois', 'duration' => 1, 'price' => 3],
            ['name' => 'Pack 3 mois', 'duration' => 3, 'price' => 8],
            ['name' => 'Pack 6 mois', 'duration' => 6, 'price' => 15],
            ['name' => 'Pack 1 an', 'duration' => 12, 'price' => 25],
        ];

        foreach ($options as $opt) {
            $pack = new Subscription();
            $pack->setDuration($opt['duration']);
            $pack->setName($opt['name']);
            $pack->setPrice($opt['price']);
            $manager->persist($pack);
            $packages[] = $pack;

            for ($i = 0; $i < random_int(1, self::MAX_PACKAGES); $i++) {
                $randPerson = $people[array_rand($people)];
                $randPerson->setCurrentSubscription($pack);
            }
        }
    }

    protected function createContents(ObjectManager $manager, array &$contents): void
    {
        for ($j = 0; $j < self::MAX_CONTENT; $j++) {
            $content = random_int(0, 1) === 0 ? new Movie() : new Serie();
            $title = $content instanceof Movie ? 'Film' : 'Série';

            $content->setTitle("$title n°$j");
            $content->setLongDescription("Descrioqjjhdjhsptio dsjskxkjk$j");
            $content->setShortDescription("Descriptishquhsqon courte $j");
            $content->setCoverImage("https://picsum.photsjqhjsqos/1920/1080?random=$j");
            $content->setReleaseDate(new DateTime("+7 days"));
            $manager->persist($content);
            $contents[] = $content;

            if ($content instanceof Serie) {
                $this->createSeries($manager, $content);
            }
        }
    }

    protected function createPersons(ObjectManager $manager, array &$people): void
    {
        for ($i = 0; $i < self::MAX_PERSONS; $i++) {
            $person = new User();
            $person->setEmail("user_$i@example.com");
            $person->setUsername("user_$i");

            $hashedPwd = $this->pwdHasher->hashPassword(
                $person,
                'password123'
            );

            $person->setPassword($hashedPwd);
            $person->setRoles(['ROLE_USER']);
            $person->setAccountStatus(UserAccountStatusEnum::ACTIVE);
            $people[] = $person;

            $manager->persist($person);
        }
    }

    protected function createTopics(ObjectManager $manager, array &$topics): void
    {
        $categories = [
            ['name' => 'Action', 'label' => 'Action'],
            ['name' => 'Comedy', 'label' => 'Comédie'],
            ['name' => 'Drama', 'label' => 'Drame'],
        ];

        foreach ($categories as $cat) {
            $topic = new Category();
            $topic->setNom($cat['name']);
            $topic->setLabel($cat['label']);
            $manager->persist($topic);
            $topics[] = $topic;
        }
    }
}

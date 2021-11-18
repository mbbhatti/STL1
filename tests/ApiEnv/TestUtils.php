<?php

namespace App\Tests\ApiEnv;

use App\Entity\Form;
use App\Entity\Media;
use App\Entity\Suggestion;
use App\Service\Configuration;
use Aws\S3\S3Client;
use CRUDlex\EntityDefinitionFactory;
use CRUDlex\EntityDefinitionValidator;
use CRUDlex\MySQLDataFactory;
use DateTime;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Connection;
use CRUDlex\Service;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Adapter\NullAdapter;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Translation\Translator;
use Exception;

/**
 * Class TestUtils
 * @package App\Tests\ApiEnv
 */
class TestUtils
{
    /**
     * @return Connection
     * @throws DBALException
     */
    public static function getConnection()
    {
        $connectionParams = ['url' => $_ENV['DATABASE_URL']];
        return DriverManager::getConnection($connectionParams);
    }

    /**
     * @return array
     */
    public static function getHeader(): array
    {
        return ['headers' => ['Content-Type' => 'application/json']];
    }

    /**
     * @return array
     */
    public static function getAuth(): array
    {
        return ['PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'admin'];
    }

    /**
     * @return string
     */
    public static function getPostFormBodyMock(): string
    {
        $post = [
            'start_at' => '2016-01-01T00:00:00Z',
            'end_at' => '2016-01-31T00:00:00Z',
            'artist' => 'Laura Pausini',
            'action' => 'Christmas 2016',
            'placement' => '1. Etage',
            'type' => 'Aufsteller',
            'items_amount' => 40,
            'items_sold' => 15,
            'market_id' => 1,
        ];

        return json_encode($post);
    }

    /**
     * @return string
     */
    public static function getPostErrorFormBodyMock(): string
    {
        $post = [
            'start_at' => '2016-01-01T00:00:00Z',
            'end_at' => '2016-01-31T00:00:00Z',
            'artist' => 'Laura Pausini',
            'action' => 'Christmas 2016',
            'placement' => '1. Etage',
            'type' => 'Aufsteller',
            'items_amount' => 40,
            'items_sold' => '',
            'market_id' => 1,
        ];

        return json_encode($post);
    }

    /**
     * @return string
     */
    public static function getPostInvalidIdFormBodyMock(): string
    {
        $post = [
            'start_at' => '2016-01-01T00:00:00Z',
            'end_at' => '2016-01-31T00:00:00Z',
            'artist' => 'Laura Pausini',
            'action' => 'Christmas 2016',
            'placement' => '1. Etage',
            'type' => 'Aufsteller',
            'items_amount' => 40,
            'items_sold' => 15,
            'market_id' => 0,
        ];

        return json_encode($post);
    }

    /**
     * @return array
     */
    public static function getDBMockData(): array
    {
        return [
            ['id' => 1, 'type' => 'action'],
            ['id' => 2, 'type' => 'artist'],
            ['id' => 3, 'type' => 'department'],
            ['id' => 4, 'type' => 'type']
        ];
    }

    /**
     * @return Service
     * @throws DBALException
     */
    public static function getService(): Service
    {
        $crudFile = __DIR__ . '/../assets/crudTest.yml';
        $db = TestUtils::getConnection();
        $dataFactory = new MySQLDataFactory($db, false);
        $filesystem = new Filesystem(new NullAdapter());
        $validator = new EntityDefinitionValidator();
        $routes = new RouteCollection();
        $context = new RequestContext();
        $urlGenerator = new UrlGenerator($routes, $context);
        $translator = new Translator('en');
        $entityDefinitionFactory = new EntityDefinitionFactory();

        return new Service(
            $crudFile,
            null,
            $urlGenerator,
            $translator,
            $dataFactory,
            $entityDefinitionFactory,
            $filesystem,
            $validator
        );
    }

    /**
     * @param $awsStorage
     * @return Configuration
     */
    public static function getAwsConfiguration($awsStorage)
    {
        $client = new S3Client(array(
            'credentials' => array(
                'key'    => $_ENV['S3_ACCESS_KEY'],
                'secret' => $_ENV['S3_SECRET_ACCESS_KEY']
            ),
            'region' => $_ENV['S3_REGION'],
            'version' => 'latest',
        ));
        $awsAdapter = new AwsS3Adapter($client, $_ENV['S3_BUCKET']);
        $localAdapter = new Local($_ENV['FS_BASE_PATH']);

        return new Configuration($awsAdapter, $localAdapter, $awsStorage);
    }

    /**
     * @param $kernel
     * @return mixed
     */
    public function getMaxFormId($kernel)
    {
        $em = $kernel->getContainer()->get('doctrine')->getManager();

        return $em->createQueryBuilder('f')
            ->where('f.user = 1')
            ->select('MAX(f.id)')
            ->from(Form::class, 'f')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param $entityManager
     * @return mixed
     * @throws Exception
     */
    public function rollbackSuggestion($entityManager)
    {
        $date = new DateTime();
        $current = $date->format('Y-m-d');
        return $entityManager->getRepository(Suggestion::class)->createQueryBuilder('s')
            ->update()
            ->set('s.deletedAt', ':delete')
            ->setParameter('delete', NULL)
            ->where('DATE(s.deletedAt) = :date')
            ->setParameter('date', $current)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $entityManager
     * @return mixed
     */
    public function getMediaUpdatedAt($entityManager)
    {
        return $entityManager->getRepository(Media::class)->createQueryBuilder('m')
            ->select('max(m.updatedAt) AS etag')
            ->where('m.deletedAt IS NULL')
            ->andWhere('m.updatedAt < UTC_TIMESTAMP()')
            ->getQuery()
            ->getResult();
    }
}


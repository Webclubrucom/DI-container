<h2>Использование DI контейнера:</h2>
<p>Интерфейс</p>

interface WowInterface
{
    public function getWow(int $wow);
}

<p>Классы, имплементирующие интерфейс</p>

class BadClass implements WowInterface
{
    public function getWow(int $wow): int
    {
        return 100 + $wow;
    }
}


class BestClass implements WowInterface
{
    private BadInterface $wow;
    public function __construct(BadInterface $wow) {
        $this->wow = $wow;
    }
    public function getWow(int $wow): int
    {
        return $wow;
    }
}

<p>Класс, у которого в зависимости интерфейс</p>

class RealityService
{
    private WowInterface $reality;
    public function __construct(WowInterface $reality)
    {
        $this->reality = $reality;
    }
    public function getReality(int $wow): void
    {
        echo $this->reality->getWow($wow);
    }
}

<p>Реализация зависимостей</p>

$di = new DI();
$itemReality = $di->get(
    RealityService::class, [
        WowInterface::class => BadClass::class
    ]);


$itemReality->getReality(500);

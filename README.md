<h2>Использование DI контейнера:</h2>
<p>Интерфейс</p>
<code>
interface WowInterface
{
    public function getWow(int $wow);
}
</code>

<p>Классы, имплементирующие интерфейс</p>
<code>
class BadClass implements WowInterface
{
    public function getWow(int $wow): int
    {
        return 100 + $wow;
    }
}
</code>
<code>
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
</code>
<p>Класс, у которого в зависимости интерфейс</p>
<code>
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
</code>
<p>Реализация зависимостей</p>
<code>
$di = new DI();
$itemReality = $di->get(
    RealityService::class, [
        WowInterface::class => BadClass::class
    ]);
    </code>

$itemReality->getReality(500);

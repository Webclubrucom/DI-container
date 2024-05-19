<h2>Использование DI контейнера:</h2>

Интерфейс

<pre>
interface WowInterface
{
    public function getWow(int $wow);
}
</pre>

Классы, имплементирующие интерфейс

<pre>
class BadClass implements WowInterface
{
    public function getWow(int $wow): int
    {
        return 100 + $wow;
    }
}
</pre>

<pre>
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
</pre>

Класс, у которого в зависимости интерфейс

<pre>
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
</pre>

Реализация зависимостей

<pre>
$di = new DI();
$itemReality = $di->get(
    RealityService::class, [
        WowInterface::class => BadClass::class
    ]);
$itemReality->getReality(500);
</pre>
